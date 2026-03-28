@extends('backend.layouts.app')

@section('content')

<div class="aiz-titlebar text-left mt-2 mb-3">
	<div class=" align-items-center">
       <h1 class="h3">{{translate('Inhouse Product sale report')}}</h1>
	</div>
</div>

<div class="row">
    <div class="col-md-8 mx-auto">
        <div class="card">
            <div class="card-body">

                <form method="GET" action="{{ route('in_house_sale_report.index') }}" id="staff_sale_report" class="row g-3 mb-4">
                    <div class="col-md-3">
                        <label for="start_date" class="form-label">Date</label>
                        <input type="text" class="aiz-date-range form-control" value="{{ $date }}"
                                        name="date" placeholder="{{ translate('Filter by date') }}" data-format="DD-MM-Y"
                                        data-separator=" to " data-advanced-range="true" autocomplete="off">
                    </div>
                    
                    <div class="col-md-3">
                       <label class="form-label">{{translate('Sort by Branch')}} :</label>
                        <select id="demo-ease" class="aiz-selectpicker"  name="branch_id">
                                <option value="">{{ translate('Choose branch') }}</option>
                                @foreach (\App\Models\BranchModel::all() as $key => $branch)
                                    <option value="{{ $branch->id }}" @if($branch->id == $sort_by) selected @endif >{{ $branch->name}}</option>
                                @endforeach
                            </select>
                    </div>
                    <div class="col-md-2">
                        <label for="sort" class="form-label">Sort By</label>
                        <select name="sort" class="form-control aiz-selectpicker">
                            <option value="">-- Default --</option>
                            <option value="desc" {{ request('sort') == 'desc' ? 'selected' : '' }}>High to Low</option>
                            <option value="asc" {{ request('sort') == 'asc' ? 'selected' : '' }}>Low to High</option>
                        </select>
                    </div>
                    <div class="d-flex align-items-end">
                        <button type="submit" class="btn btn-primary w-100">Filter</button>
                    </div>
                    <div class="col d-flex align-items-end">
                        <button type="button" class="btn btn-info w-100" onclick="order_bulk_export ()">Export</button>
                    </div>
                </form>

                <table class="table table-bordered aiz-table mb-0">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>{{ translate('Product Name') }}</th>
                            <th>{{ translate('Branch') }}</th>
                            <th>{{ translate('Num of Sale') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($products as $key => $product)
                            <tr>
                                <td>{{ ($key+1) + ($products->currentPage() - 1)*$products->perPage() }}</td>
                                <td>{{ $product->getTranslation('name') }}</td>
                                <td>{{ $product->branch->name }}</td>
                                <td>{{ $product->num_of_sale }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
                <div class="aiz-pagination mt-4">
                    {{ $products->appends(request()->input())->links() }}
                </div>
            </div>
        </div>
    </div>
</div>

@endsection
@section('script')
    <script>
        function order_bulk_export (){
            var url = '{{route('in_house_export')}}';
            $("#staff_sale_report").attr("action", url);
            $('#staff_sale_report').submit();
            $("#staff_sale_report").attr("action", '');
        }
    </script>
@endsection