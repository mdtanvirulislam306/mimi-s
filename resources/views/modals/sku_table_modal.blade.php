
@if(isset($variants) && count($variants) > 0)
                    <div class="table-responsive">
                        <table class="table table-bordered align-middle">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Variation</th>
                                    <th>SKU</th>
                                    <th>Price</th>
                                    <th>Stock</th>
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
                                        <td>{{ $variation->QTY ?? '-' }}</td>
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
