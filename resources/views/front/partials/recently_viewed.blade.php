@if((session()->has('recently_viewed_products')) && (count(session()->get('recently_viewed_products'))))
    <div class="upsell_wrapper">
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <div class="detail-block">
                        <div class="single_pricing_heading">
                            <h2 class="single_product_heading">Recently Viewed Products</h2>
                        </div>
                        <div class="tab-content outer-top-xs">
                            <div class="product-slider">
                                <div class="owl-carousel home-owl-carousel custom-carousel owl-theme">
                                    @forelse(array_reverse(session()->get('recently_viewed_products')) as $product)
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
@endif