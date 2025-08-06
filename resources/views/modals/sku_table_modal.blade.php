
@if(isset($variants) && count($variants) > 0)
                    <div class="table-responsive">
                        <table class="table table-bordered align-middle">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Variation</th>
                                    <th>SKU</th>
                                    <th>Price</th>
                                    <th>Total Order</th>
                                    <th>Running Stock</th>
                                    <th>Total Order Qty</th>
                                    <th>Total Stock</th>
                                    <th>Copy SKU</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($variants as $index => $variation)
                                    <tr>
                                        <td>{{ $index + 1 }}</td>
                                        <td>{{ $variation->variant ?? '-' }}</td>
                                        <td>
                                            <span class="sku-text">{{ $variation->sku ?? '-' }}</span>
                                        </td>
                                        <td>{{ $variation->price ?? '-' }}</td>
                                        <td>{{ \app\models\Product::getTotalOrders($variation->product_id,$variation->variant) ?? '-' }}</td>
                                        <td>{{ $variation->qty ?? '-' }}</td>
                                        <td>{{ \app\models\Product::getTotalOrderQty($variation->product_id,$variation->variant) ?? '-' }}</td>
                                        <td>{{ $variation->qty+ \app\models\Product::getTotalOrderQty($variation->product_id,$variation->variant)}}</td>
                                        <td>
                                            @if(!empty($variation->sku))
                                                <button type="button" class="btn btn-outline-primary btn-sm copy-sku-btn" data-sku="{{ $variation->sku }}">
                                                    Copy
                                                </button>
                                            @else
                                                -
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <p class="text-center mb-0">No product variations found.</p>
                @endif
