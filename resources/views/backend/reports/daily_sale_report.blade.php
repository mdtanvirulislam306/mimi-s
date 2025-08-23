@extends('backend.layouts.app')

@section('content')
<div class="container">
<div class="card">
    <div class="card-header">
        <h5 class="mb-0 h6">🧾 Daily Sales Report</h5>
    </div>
    <div class="card-body">
        <form method="GET" action="{{ route('daily_sale_report.index') }}" id="staff_sale_report" class="row g-3 mb-4">
        <div class="col-md-2">
            <label for="start_date" class="form-label">Date</label>
            <input type="text" class="aiz-date-range form-control" value="{{ $date }}"
                            name="date" placeholder="{{ translate('Filter by date') }}" data-format="DD-MM-Y"
                            data-separator=" to " data-advanced-range="true" autocomplete="off">
        </div>
        
        <div class="col-md-2">
            <label for="branch_id" class="form-label">Select Branch</label>
            <select name="branch_id" class="form-control aiz-selectpicker" data-live-search="true">
                <option value="">-- All Branch --</option>
                @foreach(all_branches() as $branch)
                    <option value="{{ $branch->id }}" {{ request('branch_id') == $branch->id ? 'selected' : '' }}>
                        {{ $branch->name }}
                    </option>
                @endforeach
            </select>
        </div>
        
       <div class="col-md-2">
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
            <label for="staff_id" class="form-label">Payment Status</label>
            <select name="payment_status" class="form-control aiz-selectpicker" data-live-search="true">
                <option value="">Select payment status</option>
                <option value="paid" {{ request('payment_status') == 'paid' ? 'selected' : '' }}>Paid</option>
                <option value="unpaid" {{ request('payment_status') == 'unpaid' ? 'selected' : '' }}>Unpaid</option>
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
                <th>Date</th>
                {{-- <th>Branch</th>
                <th>Staff</th> --}}
                <th>Total Sales</th>
                <th>Total Amount</th>
            </tr>
        </thead>
        <tbody>
            @php
                $total_sales = 0;
                $total_amount = 0;
            @endphp
            @forelse($sales as $sale)
                <tr>
                    <td>{{\Carbon\Carbon::parse($sale->date)->format('d M Y') }}</td>
                    {{-- <td>{{ get_branch_name($branch_id)??'N/A' }}</td>
                    <td>{{ get_staff_name($staff_id)??'N/A' }}</td> --}}
                    <td>{{ $sale->total_sale }}</td>
                    <td>{{ $sale->grand_total }}</td>
                </tr>
                @php
                    $total_sales += $sale->total_sale;
                    $total_amount += $sale->grand_total;
                @endphp
            @empty
                <tr>
                    <td colspan="3" class="text-center">No sales data found.</td>
                </tr>
            @endforelse
            <tr>
                <td><strong>Total</strong></td>
                <td><strong>{{$total_sales}}</strong></td>
                <td><strong>{{$total_amount}}</strong></td>
            </tr>
        </tbody>
    </table>
    </div>
    
</div>
@endsection
@section('script')
    <script>
        function order_bulk_export (){
            var url = '{{route('daily_sale_export')}}';
            $("#staff_sale_report").attr("action", url);
            $('#staff_sale_report').submit();
            $("#staff_sale_report").attr("action", '');
        }
    </script>
@endsection