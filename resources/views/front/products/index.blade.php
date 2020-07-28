@extends('front.layouts.app')
@section('title'){{$product['name']}} @endsection
@section('meta')
    @if ($site_data)
        <meta name="description"
              content="{{isset($product['meta']['description']) ? $product['meta']['description'] : $site_data['data']['site_meta_description']}}">
        <meta name="keywords"
              content="{{isset($product['meta']['keywords']) ? $product['meta']['keywords'] : $site_data['data']['site_meta_keywords']}}">
    @endif
@endsection
@section('content')
    <div class="breadcrumb">
        <div class="container">
            <div class="breadcrumb-inner">
                <ul class="list-inline">
                    <li class="home_link">
                        <a href="{{config('app.url')}}"><i class="fa fa-home"></i> <span><i
                                        class="fa fa-angle-right"></i></span></a>
                    </li>
                    @foreach($product['categories'] as $category)
                        <li>
                            <a href="{{config('app.url').'/category/'.$category['slug']}}">{{$category['name']}}
                                <span><i class="fa fa-angle-right"></i></span></a>
                        </li>
                    @endforeach
                    <li class="active">
                        {{$product['name']}}
                    </li>
                    <div class="product_buttons pull-right text-right">
                        <a class="btn btn-primary icon"
                           href="{{route('add_to_compare',$product['id'] )}}"><i class="fa fa-balance-scale"></i>
                            Compare</a>
                    </div>
                </ul>
            </div><!-- /.breadcrumb-inner -->
        </div><!-- /.container -->
    </div><!-- /.breadcrumb -->

    <div class="container">
        <div class="row single-product">
            @if(auth()->check() && !auth()->user()->email_verified_at)
                <div class="col-xs-12 col-sm-12 col-md-12">
                    <div class="panel-group login_register_box verification-notice">
                        <div class="panel panel-default">
                            <div class="panel-group checkout-steps">
                                @if (session('resent'))
                                    <div class="alert alert-success" role="alert">
                                        <p>A fresh verification link has been sent to your email address.</p>
                                    </div>
                                @endif

                                <p>Before proceeding, please check your email for a verification link. If you did not
                                    receive the email, <a href="{{ route('verification.resend') }}"
                                                          onclick="event.preventDefault(); document.getElementById('verification-form').submit();">{{ __('click here to request another') }}</a>.
                                </p>


                                <form id="verification-form" action="{{ route('verification.resend') }}" method="POST"
                                      style="display: none;">
                                    @csrf
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            @endif
            <div class="col-md-12">
                @if ($errors->any())
                    <div class="alert alert-danger">
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                @if (Session::has('success'))
                    <div class="alert alert-success">
                        <ul>
                            <li>{!! Session::get('success') !!}</li>
                        </ul>
                    </div>
                @endif
            </div>
            <div class="col-xs-12 col-sm-12 col-md-12">
                <div class="detail-block">
                    <div class="row">
                        <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6 gallery-holder">
                            <h1 class="name">{{$product['name']}}</h1>
                            <div class="product_code ">
                                <p>Product Code: {{$product['product_code']}}</p>
                                <div class="yotpo bottomLine"
                                     data-product-id="{{$product['id']}}"
                                     data-url="{{route('product_show', $product['slug'])}}" data-toggle="tooltip"
                                     title="Click on the Review tab to write a review">
                                </div>
                            </div>
                            <div class="product_images_wrapper">
                                <div class="colors_available">
                                    @php
                                        $count = 3
                                    @endphp
                                    @foreach($product['attributes'] as $attribute)
                                        @if(($attribute['image'] != "no_image.png"))
                                            <div class="item">
                                                <a data-lightbox="image-1" data-title="{{$attribute['name']}}"
                                                   href="{{asset('files/23/Photos/Products/').'/'.$product['manufacturer_key'].'/'.$attribute['image']}}">
                                                    {{$attribute['name']}}
                                                </a>
                                            </div>
                                        @else
                                            <div class="item">
                                                <span class="pro_color_name">
                                                    {{$attribute['name']}}
                                                </span>
                                            </div>
                                        @endif
                                        @php
                                            $count++
                                        @endphp
                                    @endforeach
                                </div>
                                <div class="product-item-holder size-big single-product-gallery small-gallery">
                                    <div id="owl-single-product">
                                        @if(isset($product['main_image']) && ($product['main_image'] != "no_image.png"))
                                            <div class="single-product-gallery-item" id="slide1">
                                                <a data-lightbox="image-1" data-title="{{$product['name']}}"
                                                   href="{{asset('files/23/Photos/Products/').'/'.$product['manufacturer_key'].'/'.$product['main_image']}}">
                                                    <img class="img-responsive" alt="{{$product['name']}}"
                                                         src="{{asset('front/assets/images/blank.gif')}}"
                                                         data-echo="{{asset('files/23/Photos/Products/').'/'.$product['manufacturer_key'].'/'.$product['main_image']}}"/>
                                                </a>
                                            </div><!-- /.single-product-gallery-item -->
                                        @else
                                            <div class="single-product-gallery-item" id="slide1">
                                                <a
                                                   href="#">
                                                    <img class="img-responsive" alt="{{$product['name']}}"
                                                         src="{{asset('files/23/Photos/no_image.png')}}"/>
                                                </a>
                                            </div><!-- /.single-product-gallery-item -->
                                        @endif
                                        @if(isset($product['alternative_image']) && ($product['alternative_image'] != "no_image.png"))
                                            <div class="single-product-gallery-item" id="slide2">
                                                <a data-lightbox="image-1" data-title="{{$product['name']}}"
                                                   href="{{asset('files/23/Photos/Products/').'/'.$product['manufacturer_key'].'/'.$product['alternative_image']}}">
                                                    <img class="img-responsive" alt="{{$product['name']}}"
                                                         src="{{asset('front/assets/images/blank.gif')}}"
                                                         data-echo="{{asset('files/23/Photos/Products/').'/'.$product['manufacturer_key'].'/'.$product['alternative_image']}}"/>
                                                </a>
                                            </div><!-- /.single-product-gallery-item -->
                                        @endif
                                        @php
                                            $count = 3
                                        @endphp
                                        @foreach($product['attributes'] as $attribute)
                                            @if(isset($attribute['image']) || ($attribute['image'] != "no_image.png"))
                                                <div class="single-product-gallery-item" id="slide{{$count}}">
                                                    <a data-lightbox="image-1" data-title="{{$attribute['name']}}"
                                                       href="{{asset('files/23/Photos/Products/').'/'.$product['manufacturer_key'].'/'.$attribute['image']}}">
                                                        <img class="img-responsive" alt="{{$attribute['name']}}"
                                                             src="{{asset('files/23/Photos/Products/').'/'.$product['manufacturer_key'].'/'.$attribute['image']}}"/>
                                                    </a>
                                                </div><!-- /.single-product-gallery-item -->
                                                @php
                                                    $count++
                                                @endphp
                                            @endif
                                        @endforeach
                                    </div><!-- /.single-product-slider -->
                                </div>
                            </div>
                        </div><!-- /.gallery-holder -->
                        <div class="col-sm-12 col-md-6 col-lg-6 product-info-block">
                            <div class="product-info">
                                <div class="product_summery">
                                    <div class="single_pricing">
                                        @if( $product['product_features'] || $product['product_item_size'] || $product['print_area'] || $product['decoration_areas'] || $product['dimensions'])
                                            <div class="single_pricing_heading pro_info">
                                                <h2 class="single_product_heading">Info</h2>
                                                @if($product['product_features'])
                                                    <p>{{$product['product_features']}}</p>
                                                @endif
                                                @if($product['product_item_size'])
                                                    <p>{{$product['product_item_size']}}</p>
                                                @endif
                                                @if($product['dimensions'])
                                                    <p><strong>Dimensions: </strong> {{$product['dimensions']}}</p>
                                                @endif
                                                @if($product['decoration_areas'])
                                                    <p><strong>Decoration
                                                            Area: </strong> {{$product['decoration_areas']}}</p>
                                                @endif
                                                @if($product['print_area'])
                                                    <p><strong>Print Area: </strong> {{$product['print_area']}}</p>
                                                @endif
                                            </div>
                                        @endif
                                        <div class="single_pricing_heading">
                                            <h2 class="single_product_heading">Unbranded Price</h2>
                                        </div>
                                        @if($product['product_type'] == 'promo_product')
                                            @if(count($get_category_markups['category_markups']))
                                                <div class="table_wrapper">
                                                    <table class="table table-bordered">
                                                        <tr>
                                                            <th>
                                                                <strong>
                                                                    Quantity
                                                                </strong>
                                                            </th>
                                                            @foreach($product['purchase_prices'] as $purchase_price)
                                                                @if(isset($purchase_price['qty_id']) && ($purchase_price['price'] != "CALL" || $purchase_price['price'] != "0"))
                                                                    <th>
                                                                        <strong>
                                                                            {{ $get_category_markups['quantity_titles'][ $purchase_price['qty_id'] ]['title'] }}
                                                                        </strong>
                                                                    </th>
                                                                @endif
                                                            @endforeach
                                                        </tr>
                                                        <tr>
                                                            <td>
                                                                <strong>
                                                                    Price
                                                                </strong>
                                                            </td>
                                                            @foreach($product['purchase_prices'] as $purchase_price)
                                                                @if(($purchase_price['price'] != "CALL" || $purchase_price['price'] != "0"))
                                                                    @php
                                                                        if (isset($purchase_price['qty_id'])){
                                                                            $category_markup_price = isset($get_category_markups['category_markups'][ $purchase_price['qty_id'] ]['lc_price']) ? $get_category_markups['category_markups'][ $purchase_price['qty_id'] ]['lc_price'] : "0";
                                                                        }else{
                                                                            $category_markup_price = 0;
                                                                        }
                                                                    @endphp
                                                                    <td>
                                                                        <span class="site_currency">$</span>
                                                                        {{number_format(floatval($purchase_price['price']) + (floatval($purchase_price['price']) * (floatval($category_markup_price) / 100)), 2, '.', ',')}}
                                                                    </td>
                                                                @endif
                                                            @endforeach
                                                        </tr>
                                                    </table>
                                                </div>

                                                <ul class="price_info_list">
                                                    <li>All prices are per unit & exclude GST</li>
                                                    <li>Setup Price: FREE</li>
                                                    <li>Price is for unbranded product</li>
                                                </ul>
                                            @else
                                                <p>Category Markup Price Is not Defined!</p>
                                            @endif
                                        @else
                                            @php
                                                $count = 0
                                            @endphp

                                            @foreach($get_category_markups['usb_type_titles'] as $usb_type)
                                                <a class="usb_type_title" href="#" data-toggle="collapse"
                                                   data-target="#{{seoUrl($usb_type['title'])}}-ub-price"
                                                   onclick="$('#{{seoUrl($usb_type['title'])}}-ub-price').collapse('toggle')">{{$usb_type['title']}}
                                                    Unbranded Price <i class="fa fa-plus pull-right"></i></a>
                                                <div class="collapse" id="{{seoUrl($usb_type['title'])}}-ub-price">
                                                    <div class="table_wrapper">
                                                        <table class="table table-bordered">
                                                            <tbody>
                                                            <tr>
                                                                @foreach($product['usb_purchase_prices'] as $usb_purchase_price)
                                                                    @if ($usb_purchase_price['usb_type_id'] == $usb_type['id'] )
                                                                        <th>
                                                                    <span class="quantity_title">
                                                                    {{ $get_category_markups['quantity_titles'][ $usb_purchase_price['quantity_id']]['title'] }}
                                                                    </span>
                                                                        </th>
                                                                    @endif
                                                                @endforeach
                                                            </tr>
                                                            <tr>
                                                                @foreach($product['usb_purchase_prices'] as $usb_purchase_price)
                                                                    @if(($usb_purchase_price['price'] != "CALL" || $usb_purchase_price['price'] != "0"))
                                                                        @if ($usb_purchase_price['usb_type_id'] == $usb_type['id'] )
                                                                            @php
                                                                                if (isset($usb_purchase_price['quantity_id'])){
                                                                                    $category_markup_price = isset($get_category_markups['category_markups'][ $usb_purchase_price['quantity_id'] ]['lc_price']) ? $get_category_markups['category_markups'][ $usb_purchase_price['quantity_id'] ]['lc_price'] : "0";
                                                                                }else{
                                                                                    $category_markup_price = 0;
                                                                                }
                                                                            @endphp
                                                                            <td>
                                                                                <span class="site_currency">$</span>
                                                                                {{number_format(floatval($usb_purchase_price['price']) + (floatval($usb_purchase_price['price']) * (floatval($category_markup_price) / 100)), 2, '.', ',')}}
                                                                            </td>
                                                                        @endif
                                                                    @endif
                                                                @endforeach
                                                            </tr>
                                                            </tbody>
                                                        </table>
                                                    </div>
                                                </div>
                                            @endforeach
                                            <ul class="price_info_list">
                                                <li>All prices are per unit & exclude GST</li>
                                                <li>Setup Price: FREE</li>
                                                <li>Price is for unbranded product</li>
                                            </ul>
                                        @endif
                                        @if(count($get_category_markups['category_markups']))
                                            <div class="branding_pricing">
                                                <div class="single_pricing_heading">
                                                    <h2 class="single_product_heading">Price with Branding Options</h2>
                                                </div>
                                                <div class="panel with-nav-tabs panel-default">
                                                    <div class="panel-heading">
                                                        <ul class="nav nav-tabs">
                                                            @php
                                                                $count = 0
                                                            @endphp
                                                            @foreach($product['personalisationtypes'] as $personalisationtype)
                                                                <li class="{{$count == 0 ? "active" : ""}}"><a
                                                                            href="#{{seoUrl($personalisationtype['name'])}}"
                                                                            data-toggle="tab"
                                                                            data-pro_slug="{{$product['slug']}}"
                                                                            data-ptype_id="{{$personalisationtype['id']}}">{{$personalisationtype['name']}}</a>
                                                                </li>
                                                                @php
                                                                    $count++
                                                                @endphp
                                                            @endforeach
                                                        </ul>
                                                    </div>
                                                    <div class="panel-body ">
                                                        <div class="tab-content pricing_tab">
                                                            <div id="show_matrix">
                                                                <p class="price_note">
                                                                    Click on the options to see pricing
                                                                </p>
                                                                <div id="loading_image">
                                                                    <img src="{{asset('front/assets/images/ajax.gif')}}"
                                                                         width="50px" alt="loading">
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="order_buttons">
                                                    <form action="{{route('cart_store', $product['id'])}}"
                                                          method="post">
                                                        {{csrf_field()}}
                                                        <button type="submit" class="order_btn"
                                                                data-toggle="tooltip" data-html="true"
                                                                title="<p>Payment Not Required For Creating Order</p>">
                                                            <i class="fa fa-shopping-cart"></i> Create Order
                                                        </button>
                                                    </form>

                                                    <form action="{{route('quotation_cart_store', $product['id'])}}"
                                                          method="post">
                                                        {{csrf_field()}}
                                                        <button type="submit" class="order_btn"
                                                                data-toggle="tooltip" data-html="true"
                                                                title="<p>Payment Not Required For Creating Order</p>">
                                                            <i class="fa fa-tasks"></i> Request Quotation
                                                        </button>
                                                    </form>

                                                    <form action="{{route('question_cart_store', $product['id'])}}"
                                                          method="post">
                                                        {{csrf_field()}}
                                                        <button type="submit" class="order_btn"><i
                                                                    class="fa fa-question-circle"></i> Quick
                                                            Question
                                                        </button>
                                                    </form>
                                                </div>
                                                <div class="addthis_inline_share_toolbox"></div>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div><!-- /.product-info -->
                        </div><!-- /.col-sm-7 -->
                    </div><!-- /.row -->
                </div>
                <div class="product-tabs inner-bottom-xs">
                    <div class="row">
                        <div class="col-sm-12 col-md-3 col-lg-3">
                            <ul id="product-tabs" class="nav nav-tabs nav-tab-cell">
                                <li><a data-toggle="tab" href="#description" class="active show">Description</a></li>
                                <li><a data-toggle="tab" href="#payment_terms">Payment Terms</a></li>
                                <li><a data-toggle="tab" href="#delivery_charges">Delivery Charges</a></li>
                                <li><a data-toggle="tab" href="#disclaimer">Disclaimer</a></li>
                                {{--                                <li><a data-toggle="tab" href="#review">Review</a></li>--}}
                            </ul><!-- /.nav-tabs #product-tabs -->
                        </div>
                        <div class="col-sm-12 col-md-9 col-lg-9">
                            <div class="tab-content">
                                <div id="description" class="tab-pane active show">
                                    <div class="product-tab">
                                        <p> {!! $product['long_desc'] !!}</p>
                                    </div>
                                </div><!-- /.tab-pane -->

                                <div id="payment_terms" class="tab-pane">
                                    <div class="product-tab text">
                                        {!! $site_data['payment_terms'] !!}
                                    </div>
                                </div><!-- /.tab-pane -->
                                <div id="delivery_charges" class="tab-pane">
                                    <div class="product-tab text">
                                        {!! isset($product['delivery_charges']) ? $product['delivery_charges'] :
                                        $site_data['delivery_charges'] !!}
                                    </div>
                                </div><!-- /.tab-pane -->
                                <div id="disclaimer" class="tab-pane">
                                    <div class="product-tab text">
                                        {!! isset($product['disclaimer']) ? $product['disclaimer'] : $site_data['disclaimer'] !!}
                                    </div>
                                </div><!-- /.tab-pane -->
                            </div><!-- /.tab-content -->

                        </div><!-- /.col -->
                    </div><!-- /.row -->
                </div><!-- /.product-tabs -->
            </div>
        </div>
    </div>
    <div class="reviews_wrapper" id="review">
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <div class="detail-block">
                        <div class="single_pricing_heading">
                            <h2 class="single_product_heading">Reviews</h2>
                        </div>
                        <div class="tab-content outer-top-xs">
                            <div>
                                <div class="product-tab">
                                    <div class="product-reviews">
                                        <div class="reviews">
                                            <div class="yotpo yotpo-main-widget"
                                                 data-product-id="{{$product['id']}}"
                                                 data-price="{{$product['min_price']}}"
                                                 data-currency="$"
                                                 data-name="{{$product['name']}}"
                                                 data-url="{{route('product_show', $product['slug'])}}"
                                                 data-image-url="{{asset('files/23/Photos/Products/').'/'.$product['manufacturer_key'].'/'.$product['main_image']}}">
                                            </div>
                                        </div><!-- /.reviews -->
                                    </div><!-- /.product-reviews -->
                                </div><!-- /.product-tab -->
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="upsell_wrapper">
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <div class="detail-block">
                        <div class="single_pricing_heading">
                            <h2 class="single_product_heading">Upsell Products</h2>
                        </div>
                        <div class="tab-content outer-top-xs">
                            <div class="product-slider">
                                <div class="owl-carousel home-owl-carousel custom-carousel owl-theme">
                                    @forelse($upsell_products as $product)
                                        @if($product['min_price'] && $product['max_price'] && $product['min_quantity'])
                                            <div class="item item-carousel">
                                                <div class="products">
                                                    <div class="product">
                                                        <div class="product-image">
                                                            <div class="image">
                                                                <a href="{{route('product_show',$product['slug'])}}">
                                                                    @if (isset($product['main_image']) && $product['main_image'] != "no_image.png")
                                                                        <img src="{{asset('files/23/Photos/Products/').'/'.$product['manufacturer_key'].'/'.$product['main_image']}}"
                                                                             alt="{{$product['name']}}">
                                                                    @else
                                                                        <img src="{{asset('files/23/Photos/no_image.png')}}"
                                                                             alt="{{$product['name']}}">
                                                                    @endif
                                                                </a>
                                                            </div>
                                                        </div>
                                                        <div class="product-info text-left">
                                                            <h3 class="name text-muted"><a
                                                                        href="{{config('app.url').'/product/'.$product['slug']}}">{{$product['name']}}</a>
                                                            </h3>
                                                            <div class="description text-muted">{!! $product['short_desc'] !!}</div>
                                                            <div class="product-price">
                                                                    <span class="price text-muted">
                                                                        <span>from </span>
                                                                        <strong>${{$product['min_price']}}</strong>
                                                                        <span> to </span>
                                                                        <strong>${{$product['max_price']}}</strong>
                                                                         <p class="text-center text-muted">{{$product['min_quantity']}} Min quantity</p>
                                                                    </span>
                                                            </div>
                                                        </div>
                                                        <div class="product_buttons">
                                                            <a class="btn btn-primary icon"
                                                               href="{{config('app.url').'/product/'.$product['slug']}}">View
                                                                Details</a>
                                                            <a class="btn btn-primary icon"
                                                               href="{{route('add_to_compare',$product['id'] )}}">Compare</a>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        @endif
                                    @empty
                                    @endforelse
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @include('front.partials.recently_viewed')
@endsection

@section('scripts')

    <!-- Go to www.addthis.com/dashboard to customize your tools -->
    <script type="text/javascript" src="//s7.addthis.com/js/300/addthis_widget.js#pubid=ra-5ead944a0df92e17"></script>

    <script type="text/javascript">
        (function e() {
            var e = document.createElement("script");
            e.type = "text/javascript", e.async = true, e.src = "//staticw2.yotpo.com/NJQt10YE7x9ViCzsn5bvRwC08oAjoVTtSIC4YTY9/widget.js";
            var t = document.getElementsByTagName("script")[0];
            t.parentNode.insertBefore(e, t)
        })();
    </script>
    @include('front.includes.scripts.products.view')
@endsection
