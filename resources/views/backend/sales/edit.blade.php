<div class="modal-body">
    <form action="{{ route('orders.item.add', $order->id) }}" method="POST">
        @csrf
        <div class="form-group">
            <label>{{ translate('Select Product') }}</label>
            <select name="product_id" class="form-control aiz-selectpicker" data-live-search="true" required>
                @foreach(\App\Models\Product::all() as $product)
                    <option value="{{ $product->id }}">{{ $product->name }} ({{ single_price($product->unit_price) }})</option>
                @endforeach
            </select>
        </div>
        <div class="form-group">
            <label>{{ translate('Quantity') }}</label>
            <input type="number" name="quantity" class="form-control" value="1" min="1" required>
        </div>
        <button type="submit" class="btn btn-primary">{{ translate('Add Product') }}</button>
    </form>
</div>
  