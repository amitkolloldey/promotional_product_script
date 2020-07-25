@extends('front.layouts.app')
@section('title')Promotional Products Australia @endsection
@section('content')
    <div class="body-content outer-top-vs">
        <div class="container">
            <div class="row">
                <div class="col-xs-12 col-sm-12 col-md-12 homebanner-holder">
                    <div id="hero">
                        <div id="owl-main" class="owl-carousel owl-inner-nav owl-ui-sm">
                            <div class="item"
                                 style="background-image: url({{asset("front/assets/images/sliders/01.jpg")}});">
                                <div class="container-fluid">
                                    <div class="caption bg-color vertical-center text-left">
                                        <div class="slider-header fadeInDown-1">The Brand</div>
                                        <div class="big-text fadeInDown-1"> Behind The Brands</div>
                                        <div class="button-holder fadeInDown-3"><a href="{{route('search')}}"
                                                                                   class="btn-lg btn btn-uppercase btn-primary shop-now-button">Shop
                                                Now</a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="item"
                                 style="background-image: url({{asset("front/assets/images/sliders/02.jpg")}});">
                                <div class="container-fluid">
                                    <div class="caption bg-color vertical-center text-left">
                                        <div class="slider-header fadeInDown-1">Australia's Largest</div>
                                        <div class="big-text fadeInDown-1"> Range of Promotional Products</div>
                                        <div class="button-holder fadeInDown-3"><a href="{{route('search')}}"
                                                                                   class="btn-lg btn btn-uppercase btn-primary shop-now-button">Shop
                                                Now</a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div id="product-tabs-slider" class="scroll-tabs outer-top-vs">
                        <div class="more-info-tab clearfix">
                            <h3 class="new-product-title pull-left">New Products</h3>
                            <ul class="nav nav-tabs nav-tab-line pull-right" id="new-products-1">
                                @php
                                    $count = 0
                                @endphp
                                @if(isset($new_parent_category_names))
                                    @foreach($new_parent_category_names as $category)
                                        <li><a
                                                    data-transition-type="backSlide"
                                                    href="#{{seoUrl($category)}}"
                                                    data-toggle="tab"
                                                    class="{{$count == 0 ? "active show" : ""}}">{{$category}}</a>
                                        </li>
                                        @php
                                            $count++
                                        @endphp
                                    @endforeach
                                @endif
                            </ul>
                        </div>
                        <div class="tab-content outer-top-xs">
                            @php
                                $count = 0
                            @endphp
                            @foreach($new_parent_categories as $category)
                                <div class="tab-pane {{$count == 0 ? "active" : ""}}"
                                     id="{{seoUrl($category['name'])}}">
                                    <div class="product-slider">
                                        <div class="owl-carousel home-owl-carousel custom-carousel owl-theme">
                                            @foreach($category['products'] as $new_product)
                                                @if ($new_product['pivot']['is_new'] == 1)
                                                    @if($new_product['min_price'] && $new_product['max_price'] && $new_product['min_quantity'])
                                                        <div class="item item-carousel">
                                                            <div class="products">
                                                                <div class="product">
                                                                    <div class="product-image">
                                                                        <div class="image">
                                                                            <a href="{{route('product_show',$new_product['slug'])}}">
                                                                                <img src="{{asset('files/23/Photos/Products/').'/'.$new_product['manufacturer_key'].'/'.$new_product['main_image']}}"
                                                                                     alt="{{$new_product['name']}}">
                                                                            </a>
                                                                        </div>
                                                                    </div>
                                                                    <div class="product-info text-left">
                                                                        <h3 class="name text-muted"><a
                                                                                    href="{{config('app.url').'/product/'.$new_product['slug']}}">{{$new_product['name']}}</a>
                                                                        </h3>
                                                                        <div class="description text-muted">{!! $new_product['short_desc'] !!}</div>
                                                                        <div class="product-price">
                                                                                <span class="price text-muted">
                                                                                    <span>from </span>
                                                                                    <strong>${{$new_product['min_price']}}</strong>
                                                                                    <span> to </span>
                                                                                    <strong>${{$new_product['max_price']}}</strong>
                                                                                     <p class="text-center text-muted">{{$new_product['min_quantity']}} Min quantity</p>
                                                                                </span>
                                                                        </div>
                                                                    </div>
                                                                    <div class="product_buttons">
                                                                        <a class="btn btn-primary icon"
                                                                           href="{{config('app.url').'/product/'.$new_product['slug']}}">View
                                                                            Details</a>
                                                                        <a class="btn btn-primary icon"
                                                                           href="{{route('add_to_compare',$new_product['id'] )}}">Compare</a>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    @endif
                                                @endif
                                            @endforeach
                                        </div>
                                    </div>
                                </div>
                                @php
                                    $count++
                                @endphp
                            @endforeach
                        </div>
                    </div>
                    <div id="product-tabs-slider2" class="scroll-tabs outer-top-vs">
                        <div class="more-info-tab clearfix">
                            <h3 class="new-product-title pull-left">Popular Products</h3>
                            <ul class="nav nav-tabs nav-tab-line pull-right" id="new-products-2">
                                @php
                                    $count = 0
                                @endphp
                                @if(isset($popular_parent_category_names))
                                    @foreach($popular_parent_category_names as $category)
                                        <li>
                                            <a data-transition-type="backSlide"
                                               href="#popular-{{seoUrl($category)}}"
                                               data-toggle="tab"
                                               class="{{$count == 0 ? "active show" : ""}}">{{$category}}</a>
                                        </li>
                                        @php
                                            $count++
                                        @endphp
                                    @endforeach
                                @endif
                            </ul>
                        </div>
                        <div class="tab-content outer-top-xs">
                            @php
                                $count = 0
                            @endphp
                            @foreach($popular_parent_categories as $category)
                                <div class="tab-pane {{$count == 0 ? "active" : ""}}"
                                     id="popular-{{seoUrl($category['name'])}}">
                                    <div class="product-slider">
                                        <div class="owl-carousel home-owl-carousel custom-carousel owl-theme">
                                            @foreach($category['products'] as $popular_product)
                                                @if ($popular_product['pivot']['is_popular'] == 1)
                                                    @if($popular_product['min_price'] && $popular_product['max_price'] && $popular_product['min_quantity'])
                                                        <div class="item item-carousel">
                                                            <div class="products">
                                                                <div class="product">
                                                                    <div class="product-image">
                                                                        <div class="image">
                                                                            <a href="{{route('product_show',$popular_product['slug'])}}">
                                                                                <img src="{{asset('files/23/Photos/Products/').'/'.$popular_product['manufacturer_key'].'/'.$popular_product['main_image']}}"
                                                                                     alt="{{$popular_product['name']}}">
                                                                            </a>
                                                                        </div>
                                                                    </div>
                                                                    <div class="product-info text-left">
                                                                        <h3 class="name text-muted"><a
                                                                                    href="{{config('app.url').'/product/'.$popular_product['slug']}}">{{$popular_product['name']}}</a>
                                                                        </h3>
                                                                        <div class="description text-muted">{!! $popular_product['short_desc'] !!}</div>
                                                                        <div class="product-price">
                                                                                <span class="price text-muted">
                                                                                    <span>from </span>
                                                                                    <strong>${{$popular_product['min_price']}}</strong>
                                                                                    <span> to </span>
                                                                                    <strong>${{$popular_product['max_price']}}</strong>
                                                                                     <p class="text-center text-muted">{{$popular_product['min_quantity']}} Min quantity</p>
                                                                                </span>
                                                                        </div>
                                                                    </div>
                                                                    <div class="product_buttons">
                                                                        <a class="btn btn-primary icon"
                                                                           href="{{config('app.url').'/product/'.$popular_product['slug']}}">View
                                                                            Details</a>
                                                                        <a class="btn btn-primary icon"
                                                                           href="{{route('add_to_compare',$new_product['id'] )}}">Compare</a>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    @endif
                                                @endif
                                            @endforeach
                                        </div>
                                    </div>
                                </div>
                                @php
                                    $count++
                                @endphp
                            @endforeach
                        </div>
                    </div>
                    <section class="section latest-blog outer-bottom-vs">
                        <h3 class="section-title">Latest form Blog</h3>
                        <div class="blog-slider-container outer-top-xs">
                            <div class="owl-carousel blog-slider custom-carousel">
                                @foreach($posts as $post)
                                    <div class="item">
                                        <div class="blog-post">
                                            <div class="blog-post-info text-left">
                                                <h3 class="name"><a
                                                            href="{{config('app.url').'/post/'.$post['slug']}}">{{$post['title']}}</a>
                                                </h3>
                                                <span class="info">By Brandable | {{Carbon\Carbon::parse($post['created_at'])->diffForHumans()}} </span>
                                                <p class="text">{{ strip_tags(shortenDescription($post['content'], 20)) }}</p>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </section>

                </div>
            </div>
        </div>
    </div>

@endsection
