<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use niklasravnsborg\LaravelPdf\Facades\Pdf;
use Milon\Barcode\Facades\DNS1DFacade;
use App\Models\ProductStock;

class BarcodeController extends Controller
{
    public function index()
    {
        $products = ProductStock::with('product:id,name')
        ->select('id','product_id','variant','barcode','price')
        ->get();
        //dd($products);
        return view('backend.product.barcode.index', compact('products'));
    }

    public function generate(Request $request)
    {
        $request->validate([
            'stock_id' => 'required',
            'quantity' => 'required|integer|min:1',
        ]);

        $stock = ProductStock::with('product')->findOrFail($request->stock_id);

        return response()->json([
            'product' => $stock->product,
            'stock' => $stock,
            'quantity' => $request->quantity,
            'barcodeImage' => DNS1DFacade::getBarcodePNG($stock->barcode, 'C128')
        ]);
    }

    public function downloadPdf(Request $request)
    {
        //dd($request->all());
        $stock = ProductStock::with('product')->findOrFail($request->stock_id);
        $quantity = $request->quantity;
        $pdf = Pdf::loadView('backend.product.barcode.pdf',  compact('stock','quantity'), [], [
        'format' => [50, 30], // 50mm x 30mm Zebra Label
        'orientation' => 'P'
    ]);
        return $pdf->download('barcode_'.$stock->barcode.'.pdf');
    }
}

