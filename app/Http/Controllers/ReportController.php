<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\CommissionHistory;
use App\Models\DailySalesExport;
use App\Models\InHouseSalesExport;
use App\Models\Order;
use App\Models\Wallet;
use App\Models\User;
use App\Models\Search;
use App\Models\Shop;
use Auth;
use DB;
use Carbon\Carbon;
use Maatwebsite\Excel\Facades\Excel;
use App\Models\StaffWiseSaleExport;
use Twilio\Rest\Api\V2010\Account\Usage\Record\DailyInstance;

class ReportController extends Controller
{
    public function __construct()
    {
        // Staff Permission Check
        $this->middleware(['permission:in_house_product_sale_report'])->only('in_house_sale_report');
        $this->middleware(['permission:seller_products_sale_report'])->only('seller_sale_report');
        $this->middleware(['permission:products_stock_report'])->only('stock_report');
        $this->middleware(['permission:product_wishlist_report'])->only('wish_report');
        $this->middleware(['permission:user_search_report'])->only('user_search_report');
        $this->middleware(['permission:commission_history_report'])->only('commission_history');
        $this->middleware(['permission:wallet_transaction_report'])->only('wallet_transaction_history');
    }

    public function stock_report(Request $request)
    {
        $sort_by = null;
        $products = Product::orderBy('created_at', 'desc');
        if ($request->has('category_id')) {
            $sort_by = $request->category_id;
            $products = $products->where('category_id', $sort_by);
        }
        $products = $products->paginate(15);
        return view('backend.reports.stock_report', compact('products', 'sort_by'));
    }

  public function in_house_sale_report(Request $request)
{
    // Validate incoming request
    $request->validate([
        'date'      => 'nullable|string',
        'branch_id' => 'nullable|integer',
        'sort'      => 'nullable|in:asc,desc',
    ]);

    $date     = $request->date;
    $sortBy   = $request->branch_id;
    $sort     = $request->sort;
                $sort = $sort == 'asc' ? 'asc' : 'desc';
      
    // Base query - only admin added products
    $products = Product::where('added_by', 'admin')->orderBy('num_of_sale', $sort);

    // Filter by branch if given
    if ($sortBy) {
        $products->where('branch_id', $sortBy);
    }

    // Filter by date range
    if (!empty($date)) {
        [$start, $end] = explode(" to ", $date);
        $products->whereBetween('created_at', [
            date('Y-m-d 00:00:00', strtotime($start)),
            date('Y-m-d 23:59:59', strtotime($end)),
        ]);
    } else {
        // Default: last 30 days if no date filter
        $products->whereBetween('created_at', [
            now()->subDays(30)->format('Y-m-d 00:00:00'),
            now()->format('Y-m-d 23:59:59'),
        ]);
    }

    // Paginate results
    $products = $products->paginate(15);

    return view('backend.reports.in_house_sale_report', [
        'products' => $products,
        'sort_by'  => $sortBy,
        'date'     => $date,
    ]);
}


  public function in_house_export(Request $request)
{
    // Validate incoming request
    $request->validate([
        'date'      => 'nullable|string',
        'branch_id' => 'nullable|integer',
        'sort'      => 'nullable|in:asc,desc',
    ]);

    $date     = $request->date;
    $sortBy   = $request->branch_id;
    $sort     = $request->sort;
                $sort = $sort == 'asc' ? 'asc' : 'desc';
      
    // Base query - only admin added products
    $products = Product::where('added_by', 'admin')->orderBy('num_of_sale', $sort);

    // Filter by branch if given
    if ($sortBy) {
        $products->where('branch_id', $sortBy);
    }

    // Filter by date range
    if (!empty($date)) {
        [$start, $end] = explode(" to ", $date);
        $products->whereBetween('created_at', [
            date('Y-m-d 00:00:00', strtotime($start)),
            date('Y-m-d 23:59:59', strtotime($end)),
        ]);
    } else {
        // Default: last 30 days if no date filter
        $products->whereBetween('created_at', [
            now()->subDays(30)->format('Y-m-d 00:00:00'),
            now()->format('Y-m-d 23:59:59'),
        ]);
    }

    // Paginate results
    $products = $products->get();
    $sales = $products->map(function ($product) {
        return [
            'name' => $product->name,   
            'branch' => $product->branch ? $product->branch->name : 'N/A',
            'sales_count' => $product->num_of_sale,
        ];
    });
         if(!empty($sales)) {
          return Excel::download(new InHouseSalesExport($sales), 'In House Sales Report.xlsx');
        }
        return back();
}
    public function daily_sale_report(Request $request)
    {
        $query = Order::query();

        // Filter by branch
        if ($request->filled('branch_id')) {
            $query->where('branch_id', $request->branch_id);
        }

        // Filter by staff
        if ($request->filled('staff_id')) {
            $query->where('sale_by', $request->staff_id);
        }
        if ($request->filled('payment_status')) {
            $query->where('orders.payment_status', $request->payment_status);
        }

        // Filter by date range
        if ($request->filled('date')) {
            [$start, $end] = explode(' to ', $request->date);
            $query->whereBetween('orders.created_at', [
                date('Y-m-d 00:00:00', strtotime($start)),
                date('Y-m-d 23:59:59', strtotime($end)),
            ]);
        }

        // Group by date and aggregate
       $sales = $query
    ->join('order_details', 'orders.id', '=', 'order_details.order_id')
    ->selectRaw("
        DATE(orders.created_at) as date,
        COUNT(DISTINCT orders.id) as total_sale,
        SUM(order_details.price) 
            - SUM(DISTINCT orders.coupon_discount) as grand_total,
        SUM(order_details.quantity) as total_quantity
    ")
    ->groupBy(DB::raw('DATE(orders.created_at)'))
    ->orderBy('date', 'desc')
    ->get();
 $staffs = User::where('user_type', 'staff')->get();
        return view('backend.reports.daily_sale_report', [
            'sales'     => $sales,
            'branch_id' => $request->branch_id,
            'staff_id'  => $request->staff_id,
            'date'      => $request->date,
            'staffs'    => $staffs,
        ]);
     
        
    }

        public function daily_sale_export(Request $request)
    {
        $query = Order::query();

        // Filter by branch
        if ($request->filled('branch_id')) {
            $query->where('branch_id', $request->branch_id);
        }

        // Filter by staff
        if ($request->filled('staff_id')) {
            $query->where('sale_by', $request->staff_id);
        }

        // Filter by date range
        if ($request->filled('date')) {
            [$start, $end] = explode(' to ', $request->date);
            $query->whereBetween('created_at', [
                date('Y-m-d 00:00:00', strtotime($start)),
                date('Y-m-d 23:59:59', strtotime($end)),
            ]);
        }

        // Group by date and aggregate
        $sales = $query->selectRaw('DATE(created_at) as date,
                COUNT(*) as total_sale,
                SUM(grand_total) as grand_total')
            ->groupBy(DB::raw('DATE(created_at)'))
            ->orderBy('date', 'desc')
            ->get();
//dd($sales);


         if(!empty($sales)) {
          return Excel::download(new DailySalesExport($sales), 'Daily Sales Report.xlsx');
        }
        return back();
    }
    public function staff_wise_sale_report(Request $request)
    {
        
        $date = $request->date;
        $staffs = User::where('user_type', 'staff')->get();

        $query = Order::query()->whereNotNull('sale_by');

        if ($date != null) {
            $orders = $query->where('created_at', '>=', date('Y-m-d', strtotime(explode(" to ", $date)[0])) . '  00:00:00')
                ->where('created_at', '<=', date('Y-m-d', strtotime(explode(" to ", $date)[1])) . '  23:59:59');
        }

        if ($request->filled('staff_id')) {
            $query->where('sale_by', $request->staff_id);
        }

        $salesData = $query->selectRaw('sale_by, COUNT(*) as total_sales, SUM(grand_total) as total_amount')
            ->groupBy('sale_by');
        
        if ($request->sort == 'desc') {
            $salesData->orderBy('total_sales', 'desc');
        } elseif ($request->sort == 'asc') {
            $salesData->orderBy('total_sales', 'asc');
        }

        $sales = $salesData->get()->map(function ($item) {
            $user = User::find($item->sale_by);
            return [
                'name' => $user ? $user->name : 'Unknown',
                'number' => $user ? $user->number : 'Unknown',
                'email' => $user ? $user->email : 'Unknown',
                'total_sales' => $item->total_sales,
                'total_amount' => $item->total_amount,
            ];
        });
        return view('backend.reports.staff_wise_sale_report', compact('staffs', 'sales', 'date'));
    }

    
    public function staff_wise_sale_export(Request $request)
    {
         $date = $request->date;
        $staffs = User::where('user_type', 'staff')->get();

        $query = Order::query()->whereNotNull('sale_by');

        if ($date != null) {
            $orders = $query->where('created_at', '>=', date('Y-m-d', strtotime(explode(" to ", $date)[0])) . '  00:00:00')
                ->where('created_at', '<=', date('Y-m-d', strtotime(explode(" to ", $date)[1])) . '  23:59:59');
        }

        if ($request->filled('staff_id')) {
            $query->where('sale_by', $request->staff_id);
        }

        $salesData = $query->selectRaw('sale_by, COUNT(*) as total_sales, SUM(grand_total) as total_amount')
            ->groupBy('sale_by');
        
        if ($request->sort == 'desc') {
            $salesData->orderBy('total_sales', 'desc');
        } elseif ($request->sort == 'asc') {
            $salesData->orderBy('total_sales', 'asc');
        }

        $sales = $salesData->get()->map(function ($item) {
            $user = User::find($item->sale_by);
            return [
                'name' => $user ? $user->name : 'Unknown',
                'number' => $user ? $user->number : 'Unknown',
                'email' => $user ? $user->email : 'Unknown',
                'total_sales' => $item->total_sales,
                'total_amount' => $item->total_amount,
            ];
        });
        if(!empty($sales)) {
          return Excel::download(new StaffWiseSaleExport($sales), 'Staff Wise Sales Report.xlsx');
        }
        return back();
    }

    public function seller_sale_report(Request $request)
    {
        $sort_by = null;
        // $sellers = User::where('user_type', 'seller')->orderBy('created_at', 'desc');
        $sellers = Shop::with('user')->orderBy('created_at', 'desc');
        if ($request->has('verification_status')) {
            $sort_by = $request->verification_status;
            $sellers = $sellers->where('verification_status', $sort_by);
        }
        $sellers = $sellers->paginate(10);
        return view('backend.reports.seller_sale_report', compact('sellers', 'sort_by'));
    }

    public function wish_report(Request $request)
    {
        $sort_by = null;
        $products = Product::orderBy('created_at', 'desc');
        if ($request->has('category_id')) {
            $sort_by = $request->category_id;
            $products = $products->where('category_id', $sort_by);
        }
        $products = $products->paginate(10);
        return view('backend.reports.wish_report', compact('products', 'sort_by'));
    }

    public function user_search_report(Request $request)
    {
        $searches = Search::orderBy('count', 'desc')->paginate(10);
        return view('backend.reports.user_search_report', compact('searches'));
    }

    public function commission_history(Request $request)
    {
        $seller_id = null;
        $date_range = null;

        if (Auth::user()->user_type == 'seller') {
            $seller_id = Auth::user()->id;
        }
        if ($request->seller_id) {
            $seller_id = $request->seller_id;
        }

        $commission_history = CommissionHistory::orderBy('created_at', 'desc');

        if ($request->date_range) {
            $date_range = $request->date_range;
            $date_range1 = explode(" / ", $request->date_range);
            $commission_history = $commission_history->where('created_at', '>=', $date_range1[0]);
            $commission_history = $commission_history->where('created_at', '<=', $date_range1[1]);
        }
        if ($seller_id) {

            $commission_history = $commission_history->where('seller_id', '=', $seller_id);
        }

        $commission_history = $commission_history->paginate(10);
        if (Auth::user()->user_type == 'seller') {
            return view('seller.reports.commission_history_report', compact('commission_history', 'seller_id', 'date_range'));
        }
        return view('backend.reports.commission_history_report', compact('commission_history', 'seller_id', 'date_range'));
    }

    public function wallet_transaction_history(Request $request)
    {
        $user_id = null;
        $date_range = null;

        if ($request->user_id) {
            $user_id = $request->user_id;
        }

        $users_with_wallet = User::whereIn('id', function ($query) {
            $query->select('user_id')->from(with(new Wallet)->getTable());
        })->get();

        $wallet_history = Wallet::orderBy('created_at', 'desc');

        if ($request->date_range) {
            $date_range = $request->date_range;
            $date_range1 = explode(" / ", $request->date_range);
            $wallet_history = $wallet_history->where('created_at', '>=', $date_range1[0]);
            $wallet_history = $wallet_history->where('created_at', '<=', $date_range1[1]);
        }
        if ($user_id) {
            $wallet_history = $wallet_history->where('user_id', '=', $user_id);
        }

        $wallets = $wallet_history->paginate(10);

        return view('backend.reports.wallet_history_report', compact('wallets', 'users_with_wallet', 'user_id', 'date_range'));
    }

}
