@extends('front.layouts.app')
@section('title')Compare Products @endsection
@section('meta')
    <meta name="robots" content="noindex"/>
@endsection
@section('content')
    <div class="container checkout_page order_details">
        <div class="row single-product">
            <div class="col-xs-12 col-sm-12 col-md-12">
                <div class="page_heading text-center">
                    <h2>Product Comparison</h2>
                </div>
                <div class="checkout-box">
                    <div class="row">
                        <div class="product_compare_wrapper">
                            <table class="table">
                                <tr>
                                    @forelse($compare_products as $product)
                                        <td>
                                            <span class="remove_compare">
                                                <a href="{{route('remove_compare', $product['id'])}}"><i
                                                            class="fa fa-close"></i></a>
                                            </span>
                                            <ul class="single_product_attributes text-center ">
                                                <li class="compare_image">
                                                    <img src="{{asset('files/23/Photos/Products/').'/'.$product['manufacturer_key'].'/'.$product['main_image']}}"
                                                         alt="{{$product['name']}}" width="100px">
                                                </li>
                                                <li class="compare_name">
                                                    <strong>Name:</strong> {{$product['name']}}
                                                </li>
                                                <li class="compare_code">
                                                    <strong>Code:</strong> {{$product['product_code']}}
                                                </li>
                                                <li class="compare_unbranded_price">

                                                    <ul>
                                                        <li class="compare_price_quantity">
                                                            <strong>Price & Quantity</strong>
                                                        </li>
                                                        <li>
                                                            <strong>Min Price:</strong> {{$product['min_price']}}
                                                        </li>
                                                        <li>
                                                            <strong>Max Price:</strong> {{$product['max_price']}}
                                                        </li>
                                                        <li>
                                                            <strong>Minimum Purchase
                                                                Quantity:</strong> {{$product['min_quantity']}}
                                                        </li>
                                                    </ul>
                                                </li>
                                                <li class="compare_size">
                                                    <ul>
                                                        <li class="compare_price_quantity">
                                                            <strong>Decoration Areas</strong>
                                                        </li>
                                                        <li>
                                                            {{$product['decoration_areas']}}
                                                        </li>
                                                    </ul>
                                                </li>
                                                <li class="compare_decoration_area">
                                                    <ul>
                                                        <li class="compare_price_quantity">
                                                            <strong>Colors</strong>
                                                        </li>
                                                        @forelse($product['attributes'] as $attribute)
                                                            <li>
                                                                {{$attribute['name']}}
                                                            </li>
                                                        @empty
                                                            <li>
                                                                None
                                                            </li>
                                                        @endforelse
                                                    </ul>
                                                </li>
                                                <li class="compare_decoration_area">
                                                    <ul>
                                                        <li class="compare_price_quantity">
                                                            <strong>Personalisation Options</strong>
                                                        </li>
                                                        @forelse($product['personalisationtypes'] as $personalisation_type)
                                                            <li>
                                                                {{$personalisation_type['name']}}
                                                            </li>
                                                        @empty
                                                            <li>
                                                                None
                                                            </li>
                                                        @endforelse
                                                    </ul>
                                                </li>
                                                <li class="compare_view_details">
                                                    <form action="{{route('cart_store', $product['id'])}}"
                                                          method="post">
                                                        {{csrf_field()}}
                                                        <button type="submit" class="order_btn"><i class="fa fa-shopping-cart"></i> Create Order
                                                        </button>
                                                </li>
                                            </ul>
                                        </td>
                                    @empty
                                        <td>
                                            No Products To Compare!
                                        </td>
                                    @endforelse
                                        @if(count($compare_products))
                                            <div class="product_buttons">
                                                <a href="{{route('remove_all_compare')}}" class="btn btn-danger">Remove
                                                    All</a>
                                            </div>
                                        @endif
                                </tr>
                            </table>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @include('front.partials.recently_viewed')
@endsection
