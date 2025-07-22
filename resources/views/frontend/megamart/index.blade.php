@extends('frontend.layouts.app')

@section('content')
    <style>
        #section_featured .slick-slider .slick-list{
            background: #fff;
        }
        #section_featured .slick-slider .slick-list .slick-slide,
        #section_best_selling .slick-slider .slick-list .slick-slide,
        #section_newest .slick-slider .slick-list .slick-slide {
            margin-bottom: -5px;
        }
        #section_newest_preorder_products .slick-slider .slick-list .slick-slide {
            margin-bottom: -5px;
        }
        #section_featured_preorder_products .slick-slider .slick-list .slick-slide {
            margin-bottom: -5px;
        }
        .hov-animate-outline:hover::before,
        .hov-animate-outline:hover::after {
            width: calc(100% - 2px);
        }
        @media (max-width: 575px){
            #section_featured .slick-slider .slick-list .slick-slide {
                margin-bottom: -4px;
            }
        }
        @media (min-width: 992px) {
            .aiz-count-down-box-div{
                position: absolute;
                top: 70%;
                left: 50%;
                -webkit-transform: translate(-50%, -50%);
                transform: translate(-50%, -50%);
            }
        }
    </style>

    @php $lang = get_system_language()->code;  @endphp
    <!-- Sliders -->
    <div class="home-banner-area " style="background-color: {{ get_setting('slider_section_bg_color', '#dedede') }}">
        <div class="@if(get_setting('slider_section_full_width') == 1) p-0 @else container @endif">
            <!-- Sliders -->
            <div class="home-slider slider-full">
                @if (get_setting('home_slider_images', null, $lang) != null)
                    <div class="aiz-carousel dots-inside-bottom mobile-img-auto-height" data-autoplay="true" data-infinite="true">
                        @php
                            $decoded_slider_images = json_decode(get_setting('home_slider_images', null, $lang), true);
                            $sliders = get_slider_images($decoded_slider_images);
                            $home_slider_links = get_setting('home_slider_links', null, $lang);
                        @endphp
                        @foreach ($sliders as $key => $slider)
                            <div class="carousel-box">
                                <a href="{{ isset(json_decode($home_slider_links, true)[$key]) ? json_decode($home_slider_links, true)[$key] : '' }}">
                                    <!-- Image -->
                                    <div class="d-block mw-100 img-fit overflow-hidden">
                                        <img class="img-fit  m-auto has-transition ls-is-cached lazyloaded"
                                        src="{{ $slider ? my_asset($slider->file_name) : static_asset('assets/img/placeholder.jpg') }}"
                                        alt="{{ env('APP_NAME') }} promo"
                                        onerror="this.onerror=null;this.src='{{ static_asset('assets/img/placeholder-rect.jpg') }}';">
                                    </div>
                                </a>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>
        </div>
    </div>
    <!-- Top Bar Banner -->
    @php
        $topbar_banner = get_setting('topbar_banner');
        $topbar_banner_medium = get_setting('topbar_banner_medium');
        $topbar_banner_small = get_setting('topbar_banner_small');
        $topbar_banner_asset = uploaded_asset($topbar_banner);
    @endphp
    @if ($topbar_banner != null)
        <div class="position-relative top-banner removable-session z-1035 d-none" data-key="top-banner">
            <a href="{{ get_setting('topbar_banner_link') }}" class="d-block text-reset h-40px h-lg-60px">
                <!-- For Large device -->
                <img src="{{ $topbar_banner_asset }}" class="d-none d-xl-block img-fit h-100" alt="{{ translate('topbar_banner') }}">
                <!-- For Medium device -->
                <img src="{{ $topbar_banner_medium != null ? uploaded_asset($topbar_banner_medium) : $topbar_banner_asset }}"
                    class="d-none d-md-block d-xl-none img-fit h-100" alt="{{ translate('topbar_banner') }}">
                <!-- For Small device -->
                <img src="{{ $topbar_banner_small != null ? uploaded_asset($topbar_banner_small) : $topbar_banner_asset }}"
                    class="d-md-none img-fit h-100" alt="{{ translate('topbar_banner') }}">
            </a>
        </div>
    @endif

    <!-- Hot News Section  Start-->
    {{-- <div class="bg-pink d-flex flex-column flex-md-row text-white justify-content-center align-items-center py-3 px-2 text-center text-md-left">
        <h6 class="mb-2 mb-md-0 small font-weight-bold">BIGGEST CELEBRATION OFFER 30% OFF ON ANARKALI COLLECTION -</h6>
        <a href="#" class="shopNow btn btn-light btn-sm ml-md-3">Shop Now</a>
    </div> --}}
    <!-- Hot News Section End-->

    <!-- Flash Deals,Todays Deals, Campaign, Best Selling Banner Start-->
<section class="container mt-5">
    <div class="row">
          {{-- Flash Deal --}}
            @php
                $flash_deal = get_featured_flash_deal();
                $flash_products = get_flash_deal_products($flash_deal->id)
            @endphp
            <div class="col-12 col-md-4 col-lg-3 mb-3 d-flex px-1 py-1 justify-content-center">
                <div class="position-relative banner-hover overflow-hidden w-100">
                    <a href="{{ route('flash-deals') }}">
                        <img src="{{ uploaded_asset($flash_deal->banner) }}" alt="Flash" class="img-fluid w-100">
                    </a>
                    <div class="hover-text position-absolute w-100 px-3">
                        <div class="bg-white rounded p-2 p-md-3">
                            <h5 >{{ translate('Flash Sale') }}</h5>
                            <p>{{$flash_products->count()}} Products</p>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Today Deal --}}
            @php
                $todays_deal_banner = get_setting('todays_deal_banner', null, $lang);
                $today_deal_products = get_todays_deal_products();
            @endphp
            <div class="col-12 col-md-4 col-lg-3 mb-3 d-flex px-1 py-1 justify-content-center">
                <div class="position-relative banner-hover overflow-hidden w-100">
                    <a href="{{ route('todays-deal') }}">
                        <img src="{{ uploaded_asset($todays_deal_banner) }}" alt="Today's Deal" class="img-fluid w-100">
                    </a>
                    <div class="hover-text position-absolute w-100 px-3">
                        <div class="bg-white rounded p-2 p-md-3">
                            <h5 >{{ translate("Today’s Deal") }}</h5>
                            <p>{{$today_deal_products->count()}} Products</p>
                        </div>
                    </div>
                </div>
            </div>

             {{-- Featured --}}
            @php
                $featured_banner = get_setting('featured_banner', null, $lang);
                $featured_products = get_featured_products();
            @endphp
            <div class="col-12 col-md-4 col-lg-3 mb-3 d-flex px-1 py-1 justify-content-center">
                <div class="position-relative banner-hover overflow-hidden w-100">
                    <a href="{{ route('todays-deal') }}">
                        <img src="{{ uploaded_asset($featured_banner) }}" alt="Today's Deal" class="img-fluid w-100">
                    </a>
                    <div class="hover-text position-absolute w-100 px-3">
                        <div class="bg-white rounded p-2 p-md-3">
                            <h5 >{{ translate("Featured") }}</h5>
                            <p>{{$featured_products->count()}} Products</p>
                        </div>
                    </div>
                </div>
            </div>

             {{-- Best Saling --}}
            @php
                $best_saling_banner = get_setting('best_saling_banner', null, $lang);
                $best_selling_products = get_best_selling_products(50);
            @endphp
            <div class="col-12 col-md-4 col-lg-3 mb-3 d-flex px-1 py-1 justify-content-center">
                <div class="position-relative banner-hover overflow-hidden w-100">
                    <a href="{{ route('todays-deal') }}">
                        <img src="{{ uploaded_asset($best_saling_banner) }}" alt="Today's Deal" class="img-fluid w-100">
                    </a>
                    <div class="hover-text position-absolute w-100 px-3">
                        <div class="bg-white rounded p-2 p-md-3">
                            <h5 >{{ translate("Best Selling") }}</h5>
                            <p>{{$best_selling_products->count()}} Products</p>
                        </div>
                    </div>
                </div>
            </div>
    </div>
</section>
   <!-- Flash Deals,Todays Deals, Campaign, Best Selling Banner  End-->
   <!-- Category Section Start-->
    <section class="w-100 container my-5">
        <div class="row no-gutters">
             @foreach (get_level_zero_categories()->take(12) as $key => $category)
              @php
                $category_name = $category->getTranslation('name');
             @endphp
                    <a href="{{ route('products.category', $category->slug) }}" class="col-12 col-sm-4 col-lg-3 px-2 py-2 cate-badge">
                        <div class="card-inner d-flex align-items-center justify-content-between">
                            <div class="d-flex align-items-center">
                                    <img class="rounded-circle mr-2 lazyload" style="width: 30px; height: 30px; object-fit: cover;" src="{{ static_asset('assets/img/placeholder.jpg') }}"
                        data-src="{{ isset($category->catIcon->file_name) ? my_asset($category->catIcon->file_name) : static_asset('assets/img/placeholder.jpg') }}" width="16" alt="{{ $category_name }}"
                        onerror="this.onerror=null;this.src='{{ static_asset('assets/img/placeholder.jpg') }}';">
                                <h6 class="mb-0 category-heading">{{ $category_name }}</h6>
                            </div>
                            <div class="cate-badge-circle">
                                {{ $category->products->count() }}
                            </div>
                        </div>
                    </a>

            @endforeach
           
        </div>
    </section>
    <!-- Flash Deal -->
    <!-- Featured Products -->
    <div id="section_featured">

    </div>

{{-- 
    @if (addon_is_activated('preorder'))

    <!-- Banner section 2 -->
    @php $homepreorder_banner_1Images = get_setting('home_preorder_banner_1_images', null, $lang);   @endphp
    @if ($homepreorder_banner_1Images != null)
        <div class="mb-2 mb-md-3 mt-2 mt-md-3">
            <div class="container">
                @php
                    $banner_2_imags = json_decode($homepreorder_banner_1Images);
                    $data_md = count($banner_2_imags) >= 2 ? 2 : 1;
                    $home_preorder_banner_1_links = get_setting('home_preorder_banner_1_links', null, $lang);
                @endphp
                <div class="aiz-carousel gutters-16 overflow-hidden arrow-inactive-none arrow-dark arrow-x-15"
                    data-items="{{ count($banner_2_imags) }}" data-xxl-items="{{ count($banner_2_imags) }}"
                    data-xl-items="{{ count($banner_2_imags) }}" data-lg-items="{{ $data_md }}"
                    data-md-items="{{ $data_md }}" data-sm-items="1" data-xs-items="1" data-arrows="true"
                    data-dots="false">
                    @foreach ($banner_2_imags as $key => $value)
                        <div class="carousel-box overflow-hidden hov-scale-img">
                            <a href="{{ isset(json_decode($home_preorder_banner_1_links, true)[$key]) ? json_decode($home_preorder_banner_1_links, true)[$key] : '' }}"
                                class="d-block text-reset overflow-hidden">
                                <img src="{{ static_asset('assets/img/placeholder-rect.jpg') }}"
                                    data-src="{{ uploaded_asset($value) }}" alt="{{ env('APP_NAME') }} promo"
                                    class="img-fluid lazyload w-100 has-transition"
                                    onerror="this.onerror=null;this.src='{{ static_asset('assets/img/placeholder-rect.jpg') }}';">
                            </a>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    @endif
 --}}
{{-- 
    <!-- Featured Preorder Products -->
    <div id="section_featured_preorder_products">

    </div>
    @endif
  --}}

    <!-- Banner section 2 -->
    @php $homeBanner2Images = get_setting('home_banner2_images', null, $lang);   @endphp
    @if ($homeBanner2Images != null)
        <div class="mb-2 mb-md-3 mt-2 mt-md-3">
            <div class="container">
                @php
                    $banner_2_imags = json_decode($homeBanner2Images);
                    $data_md = count($banner_2_imags) >= 2 ? 2 : 1;
                    $home_banner2_links = get_setting('home_banner2_links', null, $lang);
                @endphp
                <div class="aiz-carousel gutters-16 overflow-hidden arrow-inactive-none arrow-dark arrow-x-15"
                    data-items="{{ count($banner_2_imags) }}" data-xxl-items="{{ count($banner_2_imags) }}"
                    data-xl-items="{{ count($banner_2_imags) }}" data-lg-items="{{ $data_md }}"
                    data-md-items="{{ $data_md }}" data-sm-items="1" data-xs-items="1" data-arrows="true"
                    data-dots="false">
                    @foreach ($banner_2_imags as $key => $value)
                        <div class="carousel-box overflow-hidden hov-scale-img">
                            <a href="{{ isset(json_decode($home_banner2_links, true)[$key]) ? json_decode($home_banner2_links, true)[$key] : '' }}"
                                class="d-block text-reset overflow-hidden">
                                <img src="{{ static_asset('assets/img/placeholder-rect.jpg') }}"
                                    data-src="{{ uploaded_asset($value) }}" alt="{{ env('APP_NAME') }} promo"
                                    class="img-fluid lazyload w-100 has-transition"
                                    onerror="this.onerror=null;this.src='{{ static_asset('assets/img/placeholder-rect.jpg') }}';">
                            </a>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    @endif

    <!-- Best Selling, New Products, Banner section 2 -->
    @php
        $homeBanner3Images = get_setting('home_banner3_images', null, $lang);
        $col_val =12;
        if ($homeBanner3Images != null){
            $col_val =4;
        }
    @endphp
    <div class="container">
        <div class="row gutters-16">
            <div class="col-xl-{{ $col_val }} mb-2 mb-md-3 mt-2 mt-md-3">
                <!-- Best Selling  -->
                <div id="section_best_selling">

                </div>
            </div>

            <div class="col-xl-{{ $col_val }} mb-2 mb-md-3 mt-2 mt-md-3">
                <!-- New Products -->
                <div id="section_newest">

                </div>
            </div>



            <!-- Banner section 3 -->
            @if ($homeBanner3Images != null)
                <div class="col-xl-4 mb-2 mb-md-3 mt-2 mt-md-3 d-none d-xl-block">
                    @php
                        $banner_3_imags = json_decode($homeBanner3Images);
                        $home_banner3_links = get_setting('home_banner3_links', null, $lang);
                    @endphp
                    <div class="aiz-carousel overflow-hidden arrow-inactive-none arrow-dark arrow-x-0"
                        data-items="1" data-arrows="true" data-dots="false" data-autoplay="true">
                        @foreach ($banner_3_imags as $key => $value)
                            <div class="carousel-box overflow-hidden hov-scale-img">
                                <a href="{{ isset(json_decode($home_banner3_links, true)[$key]) ? json_decode($home_banner3_links, true)[$key] : '' }}"
                                    class="d-block text-reset overflow-hidden" style="height: 685px;">
                                    <img src="{{ static_asset('assets/img/placeholder-rect.jpg') }}"
                                        data-src="{{ uploaded_asset($value) }}" alt="{{ env('APP_NAME') }} promo"
                                        class="img-fit h-100 lazyload has-transition"
                                        onerror="this.onerror=null;this.src='{{ static_asset('assets/img/placeholder-rect.jpg') }}';">
                                </a>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif
        </div>
    </div>

    {{-- <!-- Banner section 4, Top Sellers -->
    @if (get_setting('vendor_system_activation') == 1)
        @php
            $best_selers = get_best_sellers(10);
            $homeBanner4Images = get_setting('home_banner4_images', null, $lang);
            $data_rows = 1;
            $xxl_items = 5;
            $xl_items = 4;
            $lg_items = 3.4;
            $md_items = 2.5;
            if ($homeBanner4Images != null){
                $data_rows = 4;
                $xxl_items = 2;
                $xl_items = 2;
                $lg_items = 2;
                $md_items = 3;
            }
        @endphp
        @if (count($best_selers) > 0)
        <section class="">
            <div class="container">
                <div class="row">
                    <!-- Banner section 4 -->
                    @if ($homeBanner4Images != null)
                        <div class="col-xl-8 col-lg-4 mb-2 mb-md-3 mt-2 mt-md-3 d-none d-lg-block">
                            @php
                                $banner_4_imags = json_decode($homeBanner4Images);
                                $home_banner4_links = get_setting('home_banner4_links', null, $lang);
                            @endphp
                            <div class="aiz-carousel overflow-hidden arrow-inactive-none arrow-dark arrow-x-0"
                                data-items="1" data-arrows="true" data-dots="false" data-autoplay="true">
                                @foreach ($banner_4_imags as $key => $value)
                                    <div class="carousel-box overflow-hidden hov-scale-img">
                                        <a href="{{ isset(json_decode($home_banner4_links, true)[$key]) ? json_decode($home_banner4_links, true)[$key] : '' }}"
                                            class="d-block text-reset overflow-hidden" style="height: 650px;">
                                            <img src="{{ static_asset('assets/img/placeholder-rect.jpg') }}"
                                                data-src="{{ uploaded_asset($value) }}" alt="{{ env('APP_NAME') }} promo"
                                                class="img-fit h-100 lazyload has-transition"
                                                onerror="this.onerror=null;this.src='{{ static_asset('assets/img/placeholder-rect.jpg') }}';">
                                        </a>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif

                    <!-- Top Sellers -->
                    <div class="col mb-2 mb-md-3 mt-2 mt-md-3">
                        <div class="h-100" id="section_top_sellers">
                            <div class=" px-3 py-2rem">
                                <!-- Top Section -->
                                <div class="d-flex mb-3 mb-md-4 align-items-baseline justify-content-between">
                                    <!-- Title -->
                                    <h3 class="fs-16 fs-md-20 fw-700 mb-2 mb-sm-0">
                                        <span class="pb-3">{{ translate('Top Sellers') }}</span>
                                    </h3>
                                    <!-- Links -->
                                    <div class="d-flex">
                                        <a class="text-blue fs-10 fs-md-12 fw-700 hov-text-primary animate-underline-primary"
                                            href="{{ route('sellers') }}">{{ translate('View All Sellers') }}</a>
                                    </div>
                                </div>
                                <!-- Sellers Section -->
                                <div class="aiz-carousel arrow-x-0 arrow-inactive-none" data-rows="{{ $data_rows }}" data-items="{{ $xxl_items }}" data-xxl-items="{{ $xxl_items }}"
                                    data-xl-items="{{ $xl_items }}" data-lg-items="{{ $lg_items }}" data-md-items="{{ $md_items }}" data-sm-items="2" data-xs-items="1.4"
                                    data-arrows="true" data-dots="false">
                                    @foreach ($best_selers as $key => $seller)
                                        @if ($seller->user != null)
                                            <div
                                                class="carousel-box h-100 position-relative text-center has-transition hov-animate-outline">
                                                <div class="position-relative px-3 px-xl-2 py-3">
                                                    <!-- Shop logo & Verification Status -->
                                                    <div class="mx-auto size-100px">
                                                        <a href="{{ route('shop.visit', $seller->slug) }}"
                                                            class="d-flex mx-auto justify-content-center align-item-center size-100px border overflow-hidden hov-scale-img"
                                                            tabindex="0"
                                                            style="border: 1px solid #e5e5e5; border-radius: 50%; box-shadow: 0px 10px 20px rgba(0, 0, 0, 0.06);">
                                                            <img src="{{ static_asset('assets/img/placeholder-rect.jpg') }}"
                                                                data-src="{{ uploaded_asset($seller->logo) }}" alt="{{ $seller->name }}"
                                                                class="img-fit lazyload has-transition"
                                                                onerror="this.onerror=null;this.src='{{ static_asset('assets/img/placeholder-rect.jpg') }}';">
                                                        </a>
                                                    </div>
                                                    <!-- Shop name -->
                                                    <h2 class="fs-14 fw-700 text-dark text-truncate-2 h-40px mt-3 mt-md-4 mb-0 mb-md-3">
                                                        <a href="{{ route('shop.visit', $seller->slug) }}"
                                                            class="text-reset hov-text-primary" tabindex="0">{{ $seller->name }}</a>
                                                    </h2>
                                                    <!-- Shop Rating -->
                                                    <div class="rating rating-mr-2 text-dark mb-3">
                                                        {{ renderStarRating($seller->rating) }} <br>
                                                        <span class="opacity-60 fs-14">({{ $seller->num_of_reviews }}
                                                            {{ translate('Reviews') }})</span>
                                                    </div>
                                                </div>
                                            </div>
                                        @endif
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </section>
        @endif
    @endif --}}

    <!-- Top Brands, Banner section 5, Banner section 6 -->
    {{-- @if (get_setting('top_brands') != null)
        @php
            $top_brands = json_decode(get_setting('top_brands'));
            $brands = get_brands($top_brands);
            $homeBanner5Images = get_setting('home_banner5_images', null, $lang);
            $homeBanner6Images = get_setting('home_banner6_images', null, $lang);
            $col_val = 'col-xl-4';
            $data_rows = 3;
            $xxl_items = 2;
            $xl_items = 2;
            $lg_items = 4;
            $md_items = 3;
            $sm_items = 2;
            $xs_items = 1.4;
            if ($homeBanner5Images == null && $homeBanner6Images == null){
                $data_rows = 2;
                $xxl_items = 6;
                $xl_items = 5;
            } elseif ($homeBanner5Images == null || $homeBanner6Images == null) {
                $col_val = 'col-xxl-8 col-xl-6';
                $data_rows = 3;
                $xxl_items = 2;
                $xl_items = 3;
            }
        @endphp
        <section class="mb-2 mb-md-3 mt-2 mt-md-3">
            <div class="container">
                <div class="row">

                    <!-- Top Brands -->
                    <div class="col py-3 py-lg-0">
                        <div class="h-100" id="section_top_brands">
                            <div class=" px-3 pt-3">
                                <!-- Top Section -->
                                <div class="d-flex mb-3 mb-md-4 align-items-baseline justify-content-between">
                                    <!-- Title -->
                                    <h3 class="fs-16 fs-md-20 fw-700 mb-2 mb-sm-0">
                                        <span class="pb-3">{{ translate('Top Brands') }}</span>
                                    </h3>
                                    <!-- Links -->
                                    <div class="d-flex">
                                        <a class="text-blue fs-10 fs-md-12 fw-700 hov-text-primary animate-underline-primary"
                                            href="{{ route('brands.all') }}">{{ translate('View All Brands') }}</a>
                                    </div>
                                </div>
                                <!-- Brands Section -->
                                <div class="aiz-carousel arrow-x-0 arrow-inactive-none py-4" data-rows="{{ $data_rows }}" data-items="{{ $xxl_items }}" data-xxl-items="{{ $xxl_items }}"
                                    data-xl-items="{{ $xl_items }}" data-lg-items="{{ $lg_items }}" data-md-items="{{ $md_items }}" data-sm-items="{{ $sm_items }}" data-xs-items="{{ $xs_items }}"
                                    data-arrows="true" data-dots="false">
                                    @foreach ($brands as $brand)
                                        <div class="carousel-box position-relative text-center hov-scale-img has-transition hov-shadow-out z-1">
                                            <a href="{{ route('products.brand', $brand->slug) }}" class="d-block p-sm-2">
                                                <img src="{{ $brand->logo != null ? uploaded_asset($brand->logo) : static_asset('assets/img/placeholder.jpg') }}"
                                                    class="lazyload h-100px h-md-110px mx-auto has-transition p-2 p-sm-4"
                                                    alt="{{ $brand->getTranslation('name') }}"
                                                    onerror="this.onerror=null;this.src='{{ static_asset('assets/img/placeholder.jpg') }}';">
                                                <p class="text-center text-dark fs-12 fs-md-14 fw-700 mt-2 mb-2 text-truncate" title="{{ $brand->getTranslation('name') }}">
                                                    {{ $brand->getTranslation('name') }}
                                                </p>
                                            </a>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Banner section 5 -->
                    @if ($homeBanner5Images != null)
                        @php
                            $banner_5_imags = json_decode($homeBanner5Images);
                            $home_banner5_links = get_setting('home_banner5_links', null, $lang);
                        @endphp
                        <div class="{{ $col_val }} d-none d-xl-block">
                            <div class="aiz-carousel overflow-hidden arrow-inactive-none arrow-dark arrow-x-0"
                                data-items="1" data-arrows="true" data-dots="false" data-autoplay="true">
                                @foreach ($banner_5_imags as $key => $value)
                                    <div class="carousel-box overflow-hidden hov-scale-img">
                                        <a href="{{ isset(json_decode($home_banner5_links, true)[$key]) ? json_decode($home_banner5_links, true)[$key] : '' }}"
                                            class="d-block text-reset overflow-hidden" style="height: 605px;">
                                            <img src="{{ static_asset('assets/img/placeholder-rect.jpg') }}"
                                                data-src="{{ uploaded_asset($value) }}" alt="{{ env('APP_NAME') }} promo"
                                                class="img-fit h-100 lazyload has-transition"
                                                onerror="this.onerror=null;this.src='{{ static_asset('assets/img/placeholder-rect.jpg') }}';">
                                        </a>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif

                    <!-- Banner section 6 -->
                    @if ($homeBanner6Images != null)
                        @php
                            $banner_6_imags = json_decode($homeBanner6Images);
                            $home_banner6_links = get_setting('home_banner6_links', null, $lang);
                        @endphp
                        <div class="{{ $col_val }} d-none d-xl-block"><div class="aiz-carousel overflow-hidden arrow-inactive-none arrow-dark arrow-x-0"
                            data-items="1" data-arrows="true" data-dots="false" data-autoplay="true">
                                @foreach ($banner_6_imags as $key => $value)
                                    <div class="carousel-box overflow-hidden hov-scale-img">
                                        <a href="{{ isset(json_decode($home_banner6_links, true)[$key]) ? json_decode($home_banner6_links, true)[$key] : '' }}"
                                            class="d-block text-reset overflow-hidden" style="height: 605px;">
                                            <img src="{{ static_asset('assets/img/placeholder-rect.jpg') }}"
                                                data-src="{{ uploaded_asset($value) }}" alt="{{ env('APP_NAME') }} promo"
                                                class="img-fit h-100 lazyload has-transition"
                                                onerror="this.onerror=null;this.src='{{ static_asset('assets/img/placeholder-rect.jpg') }}';">
                                        </a>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif

                </div>
            </div>
        </section>
    @endif

    <!-- Auction Product -->
    @if (addon_is_activated('auction'))
        <div id="auction_products">

        </div>
    @endif --}}

    <!-- Cupon -->
    {{-- @if (get_setting('coupon_system') == 1)
        <div class="mt-2 mt-md-3 mb-2 mb-md-3">
            <div class="container">
                <div class="position-relative py-5 px-3 px-sm-4 px-lg-5" style="background-color: {{ get_setting('cupon_background_color', '#292933') }}">
                    <div class="text-center text-xl-left position-relative z-5">
                        <div class="d-lg-flex justify-content-lg-between">
                            <div class="order-lg-1 mb-3 mb-lg-0">
                                <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="206.12" height="175.997" viewBox="0 0 206.12 175.997">
                                    <defs>
                                      <clipPath id="clip-path">
                                        <path id="Union_10" data-name="Union 10" d="M-.008,77.361l142.979-.327-22.578.051.176-77.132L143.148-.1l-.177,77.132-.064,28.218L-.072,105.58Z" transform="translate(0 0)" fill="none" stroke="#000" stroke-width="2"/>
                                      </clipPath>
                                    </defs>
                                    <g id="Group_24326" data-name="Group 24326" transform="translate(-274.202 -5254.612)" opacity="0.5">
                                      <g id="Mask_Group_23" data-name="Mask Group 23" transform="translate(304.445 5355.902) rotate(-45)" clip-path="url(#clip-path)">
                                        <g id="Group_24322" data-name="Group 24322" transform="translate(7.681 5.856)">
                                          <g id="Subtraction_167" data-name="Subtraction 167" transform="translate(0 0)" fill="none">
                                            <path d="M127.451,90.3H8a8.009,8.009,0,0,1-8-8V60.2a14.953,14.953,0,0,0,10.642-4.408A14.951,14.951,0,0,0,15.05,45.15a14.953,14.953,0,0,0-4.408-10.643A14.951,14.951,0,0,0,0,30.1V8A8.009,8.009,0,0,1,8,0H127.451a8.009,8.009,0,0,1,8,8V29.79a15.05,15.05,0,1,0,0,30.1V82.3A8.009,8.009,0,0,1,127.451,90.3Z" stroke="none"/>
                                            <path d="M 127.450813293457 88.30060577392578 C 130.75927734375 88.30060577392578 133.4509124755859 85.60896301269531 133.4509124755859 82.30050659179688 L 133.4508972167969 61.77521514892578 C 129.6533966064453 61.33430480957031 126.1383361816406 59.64068222045898 123.394172668457 56.89652252197266 C 120.1737594604492 53.67610168457031 118.4001998901367 49.39426422119141 118.4001998901367 44.83980178833008 C 118.4001998901367 40.28572463989258 120.1737747192383 36.0041618347168 123.3942184448242 32.78384399414062 C 126.1376495361328 30.04052734375 129.6527099609375 28.34706115722656 133.4509124755859 27.9056282043457 L 133.4509124755859 8.000102996826172 C 133.4509124755859 4.691642761230469 130.75927734375 2.000002861022949 127.450813293457 2.000002861022949 L 8.000096321105957 2.000002861022949 C 4.691636085510254 2.000002861022949 1.999996185302734 4.691642761230469 1.999996185302734 8.000102996826172 L 1.999996185302734 28.21491050720215 C 5.797210216522217 28.65582466125488 9.31190013885498 30.34944725036621 12.05595588684082 33.09362411499023 C 15.27627658843994 36.31408309936523 17.04979705810547 40.59588241577148 17.04979705810547 45.15030288696289 C 17.04979705810547 49.70434188842773 15.27627658843994 53.98588180541992 12.05591583251953 57.20624160766602 C 9.312583923339844 59.94955825805664 5.797909259796143 61.64302062988281 1.999996185302734 62.08445739746094 L 1.999996185302734 82.30050659179688 C 1.999996185302734 85.60896301269531 4.691636085510254 88.30060577392578 8.000096321105957 88.30060577392578 L 127.450813293457 88.30060577392578 M 127.450813293457 90.30060577392578 L 8.000096321105957 90.30060577392578 C 3.588836193084717 90.30060577392578 -3.762207143154228e-06 86.71176147460938 -3.762207143154228e-06 82.30050659179688 L -3.762207143154228e-06 60.20010375976562 C 4.022176265716553 60.19910430908203 7.799756050109863 58.63396453857422 10.64171600341797 55.79202270507812 C 13.48431587219238 52.94942474365234 15.04979610443115 49.17012405395508 15.04979610443115 45.15030288696289 C 15.04979610443115 41.13010406494141 13.48431587219238 37.35052108764648 10.64171600341797 34.5078010559082 C 7.799176216125488 31.66514205932617 4.019876003265381 30.0996036529541 -3.762207143154228e-06 30.0996036529541 L -3.762207143154228e-06 8.000102996826172 C -3.762207143154228e-06 3.588842868804932 3.588836193084717 2.886962874981691e-06 8.000096321105957 2.886962874981691e-06 L 127.450813293457 2.886962874981691e-06 C 131.8620758056641 2.886962874981691e-06 135.4509124755859 3.588842868804932 135.4509124755859 8.000102996826172 L 135.4509124755859 29.79000282287598 C 131.4283294677734 29.79100227355957 127.6504745483398 31.35614204406738 124.8083953857422 34.19808197021484 C 121.9657363891602 37.04064178466797 120.4001998901367 40.81994247436523 120.4001998901367 44.83980178833008 C 120.4001998901367 48.86006164550781 121.9657363891602 52.63964462280273 124.8083953857422 55.48230361938477 C 127.6510543823242 58.3249626159668 131.4306488037109 59.8905029296875 135.4508972167969 59.8905029296875 L 135.4509124755859 82.30050659179688 C 135.4509124755859 86.71176147460938 131.8620758056641 90.30060577392578 127.450813293457 90.30060577392578 Z" stroke="none" fill="#000"/>
                                          </g>
                                        </g>
                                      </g>
                                      <g id="Group_24321" data-name="Group 24321" transform="translate(274.202 5357.276) rotate(-45)">
                                        <g id="Subtraction_167-2" data-name="Subtraction 167" transform="translate(0 0)" fill="none">
                                          <path d="M127.451,90.3H8a8.009,8.009,0,0,1-8-8V60.2a14.953,14.953,0,0,0,10.642-4.408A14.951,14.951,0,0,0,15.05,45.15a14.953,14.953,0,0,0-4.408-10.643A14.951,14.951,0,0,0,0,30.1V8A8.009,8.009,0,0,1,8,0H127.451a8.009,8.009,0,0,1,8,8V29.79a15.05,15.05,0,1,0,0,30.1V82.3A8.009,8.009,0,0,1,127.451,90.3Z" stroke="none"/>
                                          <path d="M 127.450813293457 88.30060577392578 C 130.75927734375 88.30060577392578 133.4509124755859 85.60896301269531 133.4509124755859 82.30050659179688 L 133.4508972167969 61.77521514892578 C 129.6533966064453 61.33430480957031 126.1383361816406 59.64068222045898 123.394172668457 56.89652252197266 C 120.1737594604492 53.67610168457031 118.4001998901367 49.39426422119141 118.4001998901367 44.83980178833008 C 118.4001998901367 40.28572463989258 120.1737747192383 36.0041618347168 123.3942184448242 32.78384399414062 C 126.1376495361328 30.04052734375 129.6527099609375 28.34706115722656 133.4509124755859 27.9056282043457 L 133.4509124755859 8.000102996826172 C 133.4509124755859 4.691642761230469 130.75927734375 2.000002861022949 127.450813293457 2.000002861022949 L 8.000096321105957 2.000002861022949 C 4.691636085510254 2.000002861022949 1.999996185302734 4.691642761230469 1.999996185302734 8.000102996826172 L 1.999996185302734 28.21491050720215 C 5.797210216522217 28.65582466125488 9.31190013885498 30.34944725036621 12.05595588684082 33.09362411499023 C 15.27627658843994 36.31408309936523 17.04979705810547 40.59588241577148 17.04979705810547 45.15030288696289 C 17.04979705810547 49.70434188842773 15.27627658843994 53.98588180541992 12.05591583251953 57.20624160766602 C 9.312583923339844 59.94955825805664 5.797909259796143 61.64302062988281 1.999996185302734 62.08445739746094 L 1.999996185302734 82.30050659179688 C 1.999996185302734 85.60896301269531 4.691636085510254 88.30060577392578 8.000096321105957 88.30060577392578 L 127.450813293457 88.30060577392578 M 127.450813293457 90.30060577392578 L 8.000096321105957 90.30060577392578 C 3.588836193084717 90.30060577392578 -3.762207143154228e-06 86.71176147460938 -3.762207143154228e-06 82.30050659179688 L -3.762207143154228e-06 60.20010375976562 C 4.022176265716553 60.19910430908203 7.799756050109863 58.63396453857422 10.64171600341797 55.79202270507812 C 13.48431587219238 52.94942474365234 15.04979610443115 49.17012405395508 15.04979610443115 45.15030288696289 C 15.04979610443115 41.13010406494141 13.48431587219238 37.35052108764648 10.64171600341797 34.5078010559082 C 7.799176216125488 31.66514205932617 4.019876003265381 30.0996036529541 -3.762207143154228e-06 30.0996036529541 L -3.762207143154228e-06 8.000102996826172 C -3.762207143154228e-06 3.588842868804932 3.588836193084717 2.886962874981691e-06 8.000096321105957 2.886962874981691e-06 L 127.450813293457 2.886962874981691e-06 C 131.8620758056641 2.886962874981691e-06 135.4509124755859 3.588842868804932 135.4509124755859 8.000102996826172 L 135.4509124755859 29.79000282287598 C 131.4283294677734 29.79100227355957 127.6504745483398 31.35614204406738 124.8083953857422 34.19808197021484 C 121.9657363891602 37.04064178466797 120.4001998901367 40.81994247436523 120.4001998901367 44.83980178833008 C 120.4001998901367 48.86006164550781 121.9657363891602 52.63964462280273 124.8083953857422 55.48230361938477 C 127.6510543823242 58.3249626159668 131.4306488037109 59.8905029296875 135.4508972167969 59.8905029296875 L 135.4509124755859 82.30050659179688 C 135.4509124755859 86.71176147460938 131.8620758056641 90.30060577392578 127.450813293457 90.30060577392578 Z" stroke="none" fill="#000"/>
                                        </g>
                                        <g id="Group_24325" data-name="Group 24325" transform="translate(26.233 43.075)">
                                          <path id="Path_41600" data-name="Path 41600" d="M.006.024,15.056-.01l-.009,3.763L0,3.787Z" transform="translate(22.575 0.058)"/>
                                          <path id="Path_41601" data-name="Path 41601" d="M.006.024,15.056-.01l-.009,3.763L0,3.787Z" transform="translate(45.151 0.006)"/>
                                          <path id="Path_41602" data-name="Path 41602" d="M.006.024,15.056-.01l-.009,3.763L0,3.787Z" transform="translate(67.725 -0.046)"/>
                                          <path id="Path_41603" data-name="Path 41603" d="M.006.024,15.056-.01l-.009,3.763L0,3.787Z" transform="translate(0 0.11)"/>
                                        </g>
                                      </g>
                                    </g>
                                </svg>
                            </div>
                            <div class="">
                                <h5 class="fs-36 fw-400 text-dark mb-3">{{ translate(get_setting('cupon_title')) }}</h5>
                                <h5 class="fs-20 fw-400 text-dark">{{ translate(get_setting('cupon_subtitle')) }}</h5>
                                <div class="mt-5">
                                    <a href="{{ route('coupons.all') }}"
                                        class="btn btn-dark fs-16 px-5 rounded-4"
                                        style="box-shadow: 0px 20px 30px rgba(0, 0, 0, 0.16);">{{ translate('View All Coupons') }}</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif --}}

    <!-- Classified Product -->
    {{-- @if (get_setting('classified_product') == 1)
        @php
            $classified_products = get_home_page_classified_products(6);
        @endphp
        @if (count($classified_products) > 0)
            <section class="py-3" style="">
                <div class="container">
                    <div class="border">
                        <!-- Top Section -->
                        <div class="d-flex p-3 p-sm-4 align-items-baseline justify-content-between">
                            <!-- Title -->
                            <h3 class="fs-16 fs-md-20 fw-700 mb-0">
                                <span class="">{{ translate('Classified Ads') }}</span>
                            </h3>
                            <!-- Links -->
                            <div class="d-flex">
                                <a class="text-blue fs-10 fs-md-12 fw-700 hov-text-primary animate-underline-primary"
                                    href="{{ route('customer.products') }}">{{ translate('View All Products') }}</a>
                            </div>
                        </div>
                        <div class="d-sm-flex bg-white pb-3 pb-md-4">
                            <!-- Banner -->
                            @php
                                $classifiedBannerImage = get_setting('classified_banner_image_small', null, $lang);
                            @endphp
                            <div class="px-3 px-sm-4">
                                <div class="w-sm-270px h-320px mx-auto">
                                    <a href="{{ route('customer.products') }}" class="d-block h-100 w-100 w-xl-auto hov-scale-img overflow-hidden">
                                        <img src="{{ uploaded_asset($classifiedBannerImage) }}"
                                            alt="{{ translate('Classified Ads') }}"
                                            class="img-fit h-100 has-transition"
                                            onerror="this.onerror=null;this.src='{{ static_asset('assets/img/placeholder.jpg') }}';">
                                    </a>
                                </div>
                            </div>
                            <!-- Products -->
                            <div class="px-0 px-sm-4 w-100 overflow-hidden">
                                <div class="aiz-carousel arrow-x-0 arrow-inactive-none" data-items="5"
                                    data-xxl-items="5" data-xl-items="3.5" data-lg-items="3" data-md-items="2" data-sm-items="1"
                                    data-xs-items="2" data-arrows='true' data-infinite='false'>
                                    @foreach ($classified_products as $key => $classified_product)
                                        <div class="px-3">
                                            <a href="{{ route('customer.product', $classified_product->slug) }}"
                                                class="d-block overflow-hidden h-140px h-md-170px text-center hov-scale-img mb-3">
                                                <img class="img-fluid lazyload mx-auto has-transition"
                                                    src="{{ static_asset('assets/img/placeholder.jpg') }}"
                                                    data-src="{{ isset($classified_product->thumbnail->file_name) ? my_asset($classified_product->thumbnail->file_name) : static_asset('assets/img/placeholder.jpg') }}"
                                                    alt="{{ $classified_product->getTranslation('name') }}"
                                                    onerror="this.onerror=null;this.src='{{ static_asset('assets/img/placeholder.jpg') }}';">
                                            </a>
                                            <h3
                                                class="fw-400 fs-14 text-dark text-truncate-2 lh-1-4 mb-3 h-35px d-none d-sm-block">
                                                <a href="{{ route('customer.product', $classified_product->slug) }}"
                                                    class="d-block text-reset hov-text-primary">{{ $classified_product->getTranslation('name') }}</a>
                                            </h3>
                                            <div class="fs-14 mb-3">
                                                <span
                                                    class="text-secondary">{{ $classified_product->user ? $classified_product->user->name : '' }}</span><br>
                                                <span
                                                    class="fw-700 text-primary">{{ single_price($classified_product->unit_price) }}</span>
                                            </div>
                                            @if ($classified_product->conditon == 'new')
                                                <span
                                                    class="badge badge-inline badge-soft-info fs-13 fw-700 px-3 py-2 text-info"
                                                    style="border-radius: 20px;">{{ translate('New') }}</span>
                                            @elseif($classified_product->conditon == 'used')
                                                <span
                                                    class="badge badge-inline badge-soft-secondary-base fs-13 fw-700 px-3 py-2 text-danger"
                                                    style="border-radius: 20px;">{{ translate('Used') }}</span>
                                            @endif
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
        @endif
    @endif --}}
    @if (addon_is_activated('preorder'))
    <!-- Newest Preorder Products -->
        @include('preorder.frontend.home_page.newest_preorder')
    @endif
    <!-- Category wise Products -->
    {{-- <div id="section_home_categories">

    </div> --}}

@endsection

