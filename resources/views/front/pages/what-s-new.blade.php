@extends('front.layouts.app')
@section('title'){{$page['title']}} @endsection
@section('meta')
    @if (!empty($site_data))
        <meta name="description"
              content="{{isset($page['meta']['description']) ? $page['meta']['description'] : $site_data['data']['site_meta_description']}}">
        <meta name="keywords"
              content="{{isset($page['meta']['keywords']) ? $page['meta']['keywords'] : $site_data['data']['site_meta_keywords']}}">
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
                    <li class="active">
                        {{$page['title']}}
                    </li>
                </ul>
            </div><!-- /.breadcrumb-inner -->
        </div>
    </div>
    <div class="body-content outer-top-xs">
        <div class='container'>
            <div class='row'>
                <div class="col-xs-12 col-sm-12 col-md-12 rht-col">
                    <div class="page_heading text-center">
                        <h1>{{$page['title']}}</h1>
                    </div>
                    <div class="page_content_wrapper row">
                        {!! $page['content'] !!}
                    </div>
                </div>
                @foreach($parent_categories as $category)
                    <div class="col-xs-12 col-sm-12 col-md-12 rht-col">
                        <div class="page_heading text-center">
                            <h2>{{$category['name']}}</h2>
                        </div>
                        <div class="page_content_wrapper row">
                            @foreach($category['products'] as $new_product)
                                @if ($new_product['pivot']['is_new'] == 1)
                                    @if($new_product['min_price'] && $new_product['max_price'] && $new_product['min_quantity'])
                                        <div class="col-sm-6 col-md-4 col-lg-3">
                                            <div class="item">
                                                <div class="products">
                                                    <div class="product">
                                                        <div class="product-image">
                                                            <div class="image">
                                                                <a href="{{config('app.url').'/product/'.$new_product['slug']}}">
                                                                    @if (isset($new_product['main_image']))
                                                                        <img src="{{asset('files/23/Photos/Products/').'/'.$new_product['manufacturer_key'].'/'.$new_product['main_image']}}"
                                                                             alt="{{$new_product['name']}}">
                                                                    @else
                                                                        <img src="{{asset('files/23/Photos/Products/no_image.png')}}"
                                                                             alt="{{$new_product['name']}}">
                                                                    @endif
                                                                </a>
                                                            </div>
                                                            <!-- /.image -->
                                                        </div>
                                                        <!-- /.product-image -->
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
                                                            <div class="product_buttons">
                                                                <a class="btn btn-primary icon"
                                                                   href="{{config('app.url').'/product/'.$new_product['slug']}}">View
                                                                    Details</a>
                                                                <a class="btn btn-primary icon"
                                                                   href="{{route('add_to_compare',$new_product['id'] )}}">Compare</a>
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
                                @endif
                            @endforeach
                        </div>
                    </div>
                @endforeach
            </div>
            <!-- /.col -->
        </div>
    </div><!-- /.container -->
@endsection