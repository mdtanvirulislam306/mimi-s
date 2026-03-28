@php
    $cart_added = [];
    $product_url = route($product->auction_product == 1 ? 'auction-product' : 'product', $product->slug);
@endphp
<div class="aiz-card-box h-auto bg-white hov-scale-img rounded overflow-hidden">
    <!-- Image Section -->
    <div class="position-relative h-140px h-md-170px img-fit overflow-hidden">
        <!-- Product Image -->
        <a href="{{ $product_url }}" class="d-block h-100">
            <img class="lazyload mx-auto img-fit has-transition"
                src="{{ get_image($product->thumbnail) }}"
                alt="{{ $product->getTranslation('name') }}"
                onerror="this.onerror=null;this.src='{{ static_asset('assets/img/placeholder.jpg') }}';">
        </a>

        <!-- Discount Badge -->
        @if (discount_in_percentage($product) > 0)
            <span class="absolute-top-left bg-primary ml-1 mt-1 fs-11 fw-700 text-white w-35px text-center"
                style="padding-top:2px;padding-bottom:2px;">
                -{{ discount_in_percentage($product) }}%
            </span>
        @endif

        <!-- Wholesale Tag -->
        @if ($product->wholesale_product)
            <span class="absolute-top-left fs-11 text-white fw-700 px-2 lh-1-8 ml-1 mt-1"
                style="background-color: #455a64; @if (discount_in_percentage($product) > 0) top:25px; @endif">
                {{ translate('Wholesale') }}
            </span>
        @endif

        <!-- Wishlist & Compare Icons -->
        @if ($product->auction_product == 0)
            <div class="absolute-top-right aiz-p-hov-icon">
                <!-- Wishlist -->
                <a href="javascript:void(0)" class="hov-svg-white"
                   onclick="addToWishList({{ $product->id }})"
                   data-toggle="tooltip" data-title="{{ translate('Add to wishlist') }}">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="14.4" viewBox="0 0 16 14.4">
                        <g transform="translate(-3.05 -4.178)">
                            <path d="M11.3,5.507l-.247.246L10.8,5.506A4.538,4.538,0,1,0,4.38,11.919l.247.247,6.422,6.412,6.422-6.412.247-.247A4.538,4.538,0,1,0,11.3,5.507Z"
                                  fill="#919199"/>
                        </g>
                    </svg>
                </a>

                <!-- Compare -->
                <a href="javascript:void(0)" class="hov-svg-white"
                   onclick="addToCompare({{ $product->id }})"
                   data-toggle="tooltip" data-title="{{ translate('Add to compare') }}">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 16 16">
                        <path d="M18.037,5.547v.8a.8.8,0,0,1-.8.8H7.221a.4.4,0,0,0-.4.4V9.216a.642.642,0,0,1-1.1.454L2.456,6.4a.643.643,0,0,1,0-.909L5.723,2.227a.642.642,0,0,1,1.1.454V4.342a.4.4,0,0,0,.4.4H17.234a.8.8,0,0,1,.8.8Zm-3.685,4.86a.642.642,0,0,0-1.1.454v1.661a.4.4,0,0,1-.4.4H2.84a.8.8,0,0,0-.8.8v.8a.8.8,0,0,0,.8.8H12.854a.4.4,0,0,1,.4.4V17.4a.642.642,0,0,0,1.1.454l3.267-3.268a.643.643,0,0,0,0-.909Z"
                              transform="translate(-2.037 -2.038)" fill="#919199"/>
                    </svg>
                </a>
            </div>
        @endif
    </div>

    <!-- Product Info Section -->
    <div class="p-2 text-left">
        <!-- Product Name -->
        <h3 class="fw-400 fs-13 text-truncate-2 lh-1-4 mb-1 text-center">
            <a href="{{ $product_url }}" class="d-block text-reset hov-text-primary"
                title="{{ $product->getTranslation('name') }}">
                {{ $product->getTranslation('name') }}
            </a>
        </h3>

        <!-- Price -->
        <div class="fs-14 d-flex justify-content-center mb-2">
            @if ($product->auction_product == 0)
                @if (home_base_price($product) != home_discounted_base_price($product))
                    <del class="fw-400 text-secondary mr-2">{{ home_base_price($product) }}</del>
                @endif
                <span class="fw-700 text-primary">{{ home_discounted_base_price($product) }}</span>
            @else
                <span class="fw-700 text-primary">{{ single_price($product->starting_bid) }}</span>
            @endif
        </div>

        <!-- Add to Cart / Place Bid -->
        <div class="text-center mt-2">
            @if ($product->auction_product == 0)
                <a href="javascript:void(0)" class="btn btn-sm btn-primary fw-600"
                   onclick="showAddToCartModal({{ $product->id }})">
                    <i class="las la-shopping-cart mr-1"></i>{{ translate('Add to Cart') }}
                </a>
            @elseif ($product->auction_start_date <= strtotime('now') && $product->auction_end_date >= strtotime('now'))
                @php
                    $carts = get_user_cart();
                    if (count($carts) > 0) {
                        $cart_added = $carts->pluck('product_id')->toArray();
                    }
                    $highest_bid = $product->bids->max('amount');
                    $min_bid_amount = $highest_bid != null ? $highest_bid + 1 : $product->starting_bid;
                @endphp
                <a href="javascript:void(0)" class="btn btn-sm btn-warning fw-600"
                   onclick="bid_single_modal({{ $product->id }}, {{ $min_bid_amount }})">
                    <i class="las la-gavel mr-1"></i>{{ translate('Place Bid') }}
                </a>
            @endif
        </div>
    </div>
</div>
