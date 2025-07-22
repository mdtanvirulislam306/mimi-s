@extends('backend.layouts.app')

@section('content')
<div class="container">
    <h2>Barcode Generator</h2>
    <form id="barcodeForm">
        <div class="row">
            <div class="col-md-4">
                <label>Select Product</label>
                <select name="product_id" class="form-control aiz-selectpicker" id="product_id" data-live-search="true" required>
                    <option value="">--Choose--</option>
                    @foreach($products as $product)
                        <option value="{{ $product->id }}">{{ $product->name }} ({{ $product->barcode }})</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-2">
                <label>Quantity</label>
                <input type="number" name="quantity" class="form-control" value="10" required>
            </div>
            <div class="col-md-2">
                <label>Per Row</label>
                <input type="number" name="per_row" class="form-control" value="4" required>
            </div>
            <div class="col-md-2" style="margin-top:30px;">
                <button type="submit" class="btn btn-primary">Generate</button>
            </div>
        </div>
    </form>

    <div id="barcodeResult" class="mt-4" style="display:none;">
        <button class="btn btn-success mb-3" onclick="printDiv()">Print</button>
        <div id="printArea" class="d-flex flex-wrap"></div>
    </div>
</div>

@endsection
@section('style')
    <style>
        #printArea {
        display: flex;
        flex-wrap: wrap;
    }
    .print-item {
        text-align: center;
        padding: 10px;
    }
    @media print {
        #printArea {
            display: flex;
            flex-wrap: wrap;
        }
        .print-item {
            box-sizing: border-box;
        }
    }
    </style>
@endsection
@section('script')
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
$('#barcodeForm').on('submit', function(e){
    e.preventDefault();
    $.ajax({
        url: "{{ route('barcode.generate') }}",
        method: "POST",
        data: $(this).serialize(),
        headers: {'X-CSRF-TOKEN': '{{ csrf_token() }}'},
        success: function(response){
            $('#barcodeResult').show();
            let html = '';
            for(let i = 0; i < response.quantity; i++){
                html += `<div class="print-item" style="width:${100/response.perRow}%; text-align:center; padding:10px;">
                    <b>${response.product.name}</b><br>
                    <img src="data:image/png;base64,${response.barcodeImage}" style="width:100%; height:50px;" /><br>
                    <b>Price: ${response.product.unit_price}</b><br>
                    <b>${response.product.barcode}</b>
                </div>`;
            }
            $('#printArea').html(html);
        }
    });
});
function printDiv() {
    var divContents = document.getElementById("printArea").innerHTML;
    var perRow = $('input[name="per_row"]').val();
    var width = 100 / perRow;

    var printWindow = window.open('', '', 'height=600,width=800');
    printWindow.document.write('<html><head><title>Print Barcode</title>');
    printWindow.document.write('<style>');
    printWindow.document.write(`
        @media print {
            .print-item {
                width: ${width}%;
                display: inline-block;
                text-align: center;
                padding: 10px;
                box-sizing: border-box;
            }
        }
    `);
    printWindow.document.write('</style>');
    printWindow.document.write('</head><body>');
    printWindow.document.write(divContents);
    printWindow.document.write('</body></html>');
    printWindow.document.close();
    printWindow.print();
}

</script>
    
@endsection