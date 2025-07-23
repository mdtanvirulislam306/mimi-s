@extends('backend.layouts.app')

@section('content')
<div class="container">
    <h2> Barcode Generate</h2>
    <form id="barcodeForm">
        <div class="row">
            <div class="col-md-4">
                <label>Select Product</label>
                <select name="stock_id" id="product_id" class="form-control aiz-selectpicker" data-live-search="true"  required>
                    <option value="">--Choose--</option>
                    @foreach($products as $product)
                        <option value="{{ $product->id }}">{{ $product->product->name }} 
                            @if ($product->variant)
                            ({{ $product->variant }}) 
                            @endif
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-2">
                <label>Quantity</label>
                <input type="number" name="quantity" id="quantity" class="form-control" value="5" required>
            </div>
            <div class="col-md-2" style="margin-top:30px;">
                <button type="submit" class="btn btn-primary">Generate</button>
            </div>
        </div>
    </form>

    <div id="samplePreview" class="mt-4" style="display:none;">
        <h4>Sample Preview:</h4>
        <div class="sticker border" style="width:50mm; height:25mm; text-align:center; padding:5px;">
            <b id="pName"></b><br>
            <img id="barcodeImg" style="height:5mm;" /><br>
            <b id="pBarcode"></b><br>
            <b id="pPrice"></b>
        </div>
        <form method="POST" action="{{ route('barcode.download') }}">
            @csrf
            <input type="hidden" name="stock_id" id="download_product_id">
            <input type="hidden" name="quantity" id="download_quantity">
            <button type="submit" class="btn custom-btn mt-3">Print</button>
        </form>
    </div>
</div>
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
            $('#samplePreview').show();
            $('#pName').text(response.product.name);
            $('#barcodeImg').attr('src','data:image/png;base64,'+response.barcodeImage);
            $('#pBarcode').text(response.stock.barcode);
            $('#pPrice').text(response.stock.price+' Taka');

            // Pass values for PDF download
            $('#download_product_id').val(response.stock.id);
            $('#download_quantity').val(response.quantity);
        }
    });
});
</script>
@endsection
