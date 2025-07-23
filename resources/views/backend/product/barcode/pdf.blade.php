<style>
@page { size: 50mm 30mm; margin: 0; }
body { margin: 0; padding: 0; }
.sticker {
    width: 50mm;
    height: 30mm;
    text-align: center;
    font-size: 10px;
    padding: 3mm;
}
img { height: 12mm; }
</style>

@for($i = 0; $i < $quantity; $i++)
<div class="sticker">
    <b>{{ $stock->product->name }}</b><br><br>
    <img src="data:image/png;base64,{{ DNS1D::getBarcodePNG($stock->barcode, 'C128') }}" /><br>
    <b>{{ $stock->barcode }}</b><br>
    <b>{{$stock->price. " Taka"}}</b>
</div>
@if($i < $quantity-1)
<div style="page-break-after: always;"></div>
@endif
@endfor
