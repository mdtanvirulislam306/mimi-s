@extends('backend.layouts.app')

@section('content')
<div class="container">
<div class="card">
    <div class="card-header">
        <h5 class="mb-0 h6">🧾 Staff-wise Sales Report</h5>
    </div>
    <div class="card-body">
        <form method="GET" action="{{ route('staff_wise_sale_report.index') }}" id="staff_sale_report" class="row g-3 mb-4">
        <div class="col-md-3">
            <label for="start_date" class="form-label">Start Date</label>
            <input type="text" class="aiz-date-range form-control" value="{{ $date }}"
                            name="date" placeholder="{{ translate('Filter by date') }}" data-format="DD-MM-Y"
                            data-separator=" to " data-advanced-range="true" autocomplete="off">
        </div>
        
        <div class="col-md-3">
            <label for="staff_id" class="form-label">Select Staff</label>
            <select name="staff_id" class="form-control aiz-selectpicker" data-live-search="true">
                <option value="">-- All Staff --</option>
                @foreach($staffs as $staff)
                    <option value="{{ $staff->id }}" {{ request('staff_id') == $staff->id ? 'selected' : '' }}>
                        {{ $staff->name }} - {{ $staff->number??$staff->email }}
                    </option>
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
        <div class="col-md-1 d-flex align-items-end">
            <button type="submit" class="btn btn-primary w-100">Filter</button>
        </div>
        <div class="col d-flex align-items-end">
            <button type="button" class="btn btn-info w-100" onclick="order_bulk_export ()">Export</button>
        </div>
    </form>

    <table class="table table-bordered  mb-0">
        <thead class="table-light">
            <tr>
                <th>Staff Name</th>
                <th>Staff Number/ Email</th>
                <th>Total Sales</th>
                <th>Total Amount</th>
            </tr>
        </thead>
        <tbody>
            @forelse($sales as $data)
                <tr>
                    <td>{{ $data['name'] }}</td>
                    <td>{{ $data['number']??$data['email'] }}</td>
                    <td>{{ $data['total_sales'] }}</td>
                    <td>{{ $data['total_amount'] }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="4" class="text-center">No sales data found.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
    </div>
    
</div>
@endsection
@section('script')
    <script>
        function order_bulk_export (){
            var url = '{{route('staff_wise_sale_export')}}';
            $("#staff_sale_report").attr("action", url);
            $('#staff_sale_report').submit();
            $("#staff_sale_report").attr("action", '');
        }
    </script>
@endsection