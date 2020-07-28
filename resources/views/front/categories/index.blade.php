@extends('front.layouts.app')
@section('title'){{$category['name']}} @endsection
@section('meta')
    @if (!empty($site_data))
        <meta name="description"
              content="{{isset($category['meta']['description']) ? $category['meta']['description'] : $site_data['data']['site_meta_description']}}">
        <meta name="keywords"
              content="{{isset($category['meta']['keywords']) ? $category['meta']['keywords'] : $site_data['data']['site_meta_keywords']}}">
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
                    @if(!$category['parent_id'] == null)
                        <li>
                            <a href="{{route('category_show', $category_names[$category['parent_id']]['slug'])}}">{{$category_names[$category['parent_id']]['name']}}
                                <span><i class="fa fa-angle-right"></i></span>
                            </a>
                        </li>
                        <li class="active">
                            {{$category['name']}}
                        </li>
                    @else
                        <li class="active">
                            {{$category['name']}}
                        </li>
                    @endif
                </ul>
            </div><!-- /.breadcrumb-inner -->
        </div>
    </div>
    <div class="body-content outer-top-xs">
        <div class='container'>
            <div class='row'>
                <!-- /.sidebar -->
                <div class="col-xs-12 col-sm-12 col-md-12 rht-col">
                    <div id="category" class="category-carousel hidden-xs">
                        <div class="item">
                            <div class="image">
                                @if (isset($category['main_image']))
                                    <img src="{{asset('files/23/Photos/Categories/').'/'.$category['main_image']}}"
                                         alt="{{$category['name']}}">
                                @else
                                    <img src="{{asset('files/23/Photos/Categories/banner_no_image.png')}}"
                                         alt="{{$category['name']}}">
                                @endif
                            </div>
                        </div>
                        <div class="caption vertical-top text-left">
                            <div class="big-text dark"> {{$category['name']}}</div>
                        </div>
                        <!-- /.caption -->
                    </div>
                    <div class="category-description">
                        {!! $category['description'] !!}
                    </div>
                    @if(count($category['sub_category']))
                        <div class="search-result-container ">
                            <div class="tab-content category-list">
                                <div class="category-product">
                                    <div class="row subcategory">
                                        <div class="col-sm-12 col-md-3 col-lg-3 extra_cat">
                                            <div class="item">
                                                <div class="products">
                                                    <div class="product">
                                                        <!-- /.product-image -->
                                                        <div class="product-info text-left">
                                                            <h1 class="name">Sub Categories <i
                                                                        class="fa fa-angle-double-right"></i>
                                                            </h1>
                                                        </div>
                                                        <!-- /.product-price -->
                                                    </div>
                                                </div>
                                                <!-- /.product -->
                                            </div>
                                            <!-- /.products -->
                                        </div>
                                        @foreach($category['sub_category'] as $subcategory)
                                            <div class="col-sm-12 col-md-3 col-lg-3">
                                                <div class="item">
                                                    <div class="products">
                                                        <div class="product">
                                                            <div class="product-image">
                                                                <div class="image">
                                                                    <a href="{{config('app.url').'/category/'. $subcategory['slug']}}">
                                                                        @if (isset($subcategory['thumbnail_image']))
                                                                            <img src="{{asset('files/23/Photos/Categories/').'/'.$subcategory['thumbnail_image']}}"
                                                                                 alt="{{$subcategory['name']}}">
                                                                        @else
                                                                            <img src="{{asset('files/23/Photos/Categories/no_image.png')}}"
                                                                                 alt="{{$category['name']}}">
                                                                        @endif
                                                                    </a>
                                                                </div>
                                                                <!-- /.image -->
                                                                <div class="tag new"><span>{{count($subcategory['products'])}}
                                                            items</span></div>
                                                            </div>
                                                            <!-- /.product-image -->
                                                            <div class="product-info text-left">
                                                                <h3 class="name"><a
                                                                            href="{{config('app.url').'/category/'.$subcategory['slug']}}">{{$subcategory['name']}}</a>
                                                                </h3>
                                                            </div>
                                                            <!-- /.product-price -->
                                                        </div>
                                                    </div>
                                                    <!-- /.product -->
                                                </div>
                                                <!-- /.products -->
                                            </div>
                                        @endforeach
                                    </div>
                                    <!-- /.item -->
                                </div>
                            </div>
                        </div>
                    @endif

                    @if((count($category['sub_category']) == 0) && count($category['products']))
                        <div class="search-result-container ">
                            <div id="myTabContent" class="tab-content category-list">
                                <div class="tab-pane active " id="grid-container">
                                    <div class="category-product">
                                        <div class="clearfix filters-container ">
                                            <div class="row">
                                                <!-- /.col -->
                                                <div class="col col-sm-12 col-md-12 col-xs-12 col-lg-12 text-right">
                                                    <div class="pagination-container">
                                                    {{ customPaginate($category['products'], route('category_show', $category['slug']))->links()}}
                                                    <!-- /.list-inline -->
                                                    </div>
                                                    <!-- /.pagination-container -->
                                                </div>
                                                <!-- /.col -->
                                            </div>
                                            <!-- /.row -->
                                        </div>
                                        <div class="row">
                                            @foreach( customPaginate($category['products'], route('category_show',
                                            $category['slug'])) as $product)
                                                @if($product['min_price'] && $product['max_price'] && $product['min_quantity'])
                                                    <div class="col-sm-12 col-md-4 col-lg-3">
                                                        <div class="item">
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
                                                                        <!-- /.image -->
                                                                    </div>
                                                                    <!-- /.product-image -->
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
                                                                        <div class="product_buttons">
                                                                            <a class="btn btn-primary icon"
                                                                               href="{{config('app.url').'/product/'.$product['slug']}}">View
                                                                                Details</a>
                                                                            <a class="btn btn-primary icon"
                                                                               href="{{route('add_to_compare',$product['id'] )}}">Compare</a>
                                                                        </div>
                                                                        <!-- /.product-price -->
                                                                    </div>
                                                                    <!-- /.product-info -->
                                                                </div>
                                                                <!-- /.product -->
                                                            </div>
                                                            <!-- /.products -->
                                                        </div>
                                                    </div>
                                            @endif
                                        @endforeach
                                        <!-- /.item -->
                                        </div>
                                        <!-- /.row -->
                                        <div class="clearfix filters-container">
                                            <div class="row">
                                                <!-- /.col -->
                                                <div class="col col-sm-12 col-md-12 col-xs-12 col-lg-12 text-right">
                                                    <div class="pagination-container">
                                                        {{ customPaginate($category['products'], route('category_show', $category['slug']))->links()}}
                                                    </div>
                                                </div>
                                            </div>
                                            <!-- /.row -->
                                        </div>
                                    </div>
                                    <!-- /.category-product -->
                                </div>
                                <!-- /.tab-pane -->
                            </div>
                            <!-- /.tab-content -->
                        </div>
                    @endif
                </div>
                <!-- /.search-result-container -->
            </div>
            <!-- /.col -->
        </div>
    </div><!-- /.container -->

    @include('front.partials.recently_viewed')
@endsection


