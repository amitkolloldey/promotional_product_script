@extends('front.layouts.app')
@section('title')My Submitted Order @endsection
@section('meta')
    <meta name="robots" content="noindex"/> @endsection
@section('content')
    <div class="container checkout_page order_details">
        <div class="row single-product">
            <div class="col-xs-12 col-sm-12 col-md-12">
                <div class="page_heading text-center">
                    <h2>Order Details For #{{$order['order_no']}}</h2>
                </div>
                <div class="checkout-box">
                    <div class="row">
                        <div class="col-xs-12 col-sm-4 col-md-4">
                            <!-- checkout-progress-sidebar -->
                            <div class="panel-group">
                                <div class="panel panel-default">
                                    <div class="panel-heading">
                                        <h4 class="unicase-checkout-title">Product Details</h4>
                                    </div>
                                    @foreach($order['products'] as $product)
                                        <div class="order_product_info">
                                            <div class="row product_order_card text-center">
                                                <a href="{{route('product_show',$product['slug'])}}"
                                                   target="_blank">
                                                    <div class="col-md-12">
                                                        <div class="order_product_image">
                                                            @if (isset($product['main_image']))
                                                                <img src="{{asset('files/23/Photos/Products/').'/'.$product['manufacturer_key'].'/'.$product['main_image']}}"
                                                                     alt="{{$product['name']}}" height="200px">
                                                            @else
                                                                <img src="{{asset('files/23/Photos/Products/no_image.png')}}"
                                                                     alt="{{$product['name']}}" height="200px">
                                                            @endif
                                                        </div>
                                                    </div>
                                                    <div class="col-md-12">
                                                        <div class="order_product_meta">
                                                            <h4>
                                                                {{$product['name']}}
                                                            </h4>
                                                            <span>
                                                            {{$product['product_code']}}
                                                        </span>
                                                        </div>
                                                    </div>
                                                </a>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                            <!-- checkout-progress-sidebar -->
                        </div><!-- /.row -->
                        <div class="col-xs-12 col-sm-4 col-md-4">
                            <div class="panel-group " id="accordion">
                                <div class="panel panel-default">
                                    <div class="panel-heading">
                                        <h4 class="unicase-checkout-title">Personalisation Options</h4>
                                    </div>
                                </div>
                                <div class="panel panel-default">
                                    <div class="panel-group checkout-steps">
                                        <h2 class="checkout_heading">Requirements</h2>
                                        <div class="form-group show_data">
                                            <label for="quantity">How many do you need?</label>
                                            <p>{{$order['quantity']}}</p>
                                        </div>
                                        <div class="form-group show_data">
                                            <label for="color">Which Color Do You Want?</label>
                                            <p>{{get_attribute_name_by_id($order['color'])}}</p>
                                        </div>
                                        <h2 class="checkout_heading">Personalisation Options</h2>
                                        <div class="form-group show_data">
                                            <label for="personalisation_options">Select a personalisation Option</label>
                                            <p>{{get_personalisation_type_name_by_id($order['personalisation_options'])}}</p>
                                        </div>
                                        <div class="form-group show_data">
                                            <label for="personalisation_color">Select a personalisation Color</label>
                                            <p>{{get_personalisation_color_by_ids($order['personalisation_color'])}}</p>
                                        </div>
                                        <!-- checkout-step-02  -->
                                    </div>
                                </div>
                            </div><!-- /.checkout-steps -->
                        </div>
                        <div class="col-xs-12 col-sm-4 col-md-4">
                            <div class="panel-group">
                                <div class="panel panel-default">
                                    <div class="panel-heading">
                                        <h4 class="unicase-checkout-title">Pricing</h4>
                                    </div>
                                    <div class="order_product_pricing">
                                        <div class="row">
                                            <div class="col-md-12">
                                                <div id="show_pricing">
                                                    <ul>
                                                        <li class="total_price"><span class="total_price_text">Total Price</span><span
                                                                    class="total_price_amount">${{$order['total_price'] ? $order['total_price'] : "0.00"}}</span>
                                                        </li>
                                                        <li class="price_row"><span
                                                                    class="total_quantity_text">Quantity</span><span
                                                                    class="total_quantity_amount">{{$order['quantity']}}</span>
                                                        </li>
                                                        <li class="price_row"><span class="total_unit_price_text">Unit Price</span><span
                                                                    class="total_quantity_amount">${{$order['unit_price'] ? $order['unit_price'] : "0.00"}}</span>
                                                        </li>
                                                        <li class="price_row"><span class="total_unit_price_text">personalisation, GST, Setup &amp; Delivery</span><span
                                                                    class="total_quantity_amount">$85</span></li>
                                                    </ul>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="panel panel-default">
                                        <div class="panel-group checkout-steps">
                                            <h2 class="checkout_heading">Notes</h2>
                                            <div class="form-group show_data">
                                                <label>Order Notes</label>
                                                <p>{{$order['order_note'] ? $order['order_note'] : "None"}}</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                        </div>
                        <div class="col-xs-12 col-sm-12 col-md-12">

                            <div class="panel-group " id="accordion">
                                <div class="row">

                                    <div class="col-md-12">
                                        <div class="panel panel-default">
                                            <div class="panel-heading">
                                                <h4 class="unicase-checkout-title">Contact Details</h4>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="panel panel-default">
                                            <div class="panel-group checkout-steps">
                                                <h2 class="checkout_heading">Basic Information</h2>
                                                <div class="form-group show_data">
                                                    <label>Name</label>
                                                    <p>{{$order['name']}}</p>
                                                </div>
                                                <div class="form-group show_data">
                                                    <label>Phone</label>
                                                    <p>{{$order['phone_no']}}</p>
                                                </div>
                                                <div class="form-group show_data">
                                                    <label>Email</label>
                                                    <p>{{$order['email']}}</p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="panel panel-default">
                                            <div class="panel-group checkout-steps">
                                                <h2 class="checkout_heading">Billing</h2>
                                                <div class="form-group show_data">
                                                    <label>Company</label>
                                                    <p>{{$order['company'] ? $order['company'] : "None"}}</p>
                                                </div>
                                                <div class="form-group show_data">
                                                    <label>Address</label>
                                                    <p>{{$order['address'] ? $order['address'] : "None"}}</p>
                                                </div>
                                                <div class="form-group show_data">
                                                    <label>Suburb</label>
                                                    <p>{{$order['suburb'] ? $order['suburb'] : "None"}}</p>
                                                </div>
                                                <div class="form-group show_data">
                                                    <label>State</label>
                                                    <p>{{$order['state'] ? $order['state'] : "None"}}</p>
                                                </div>
                                                <div class="form-group show_data">
                                                    <label>Post Code</label>
                                                    <p>{{$order['postcode'] ? $order['postcode'] : "None"}}</p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="panel panel-default">
                                            <div class="panel-group checkout-steps">
                                                <h2 class="checkout_heading">Shipping</h2>
                                                @if($order['shipping_same_as_billing'] == "1")
                                                    <div class="form-group show_data">
                                                        <p>Same As Billing</p>
                                                    </div>
                                                @else
                                                    <div class="form-group show_data">
                                                        <label>Company</label>
                                                        <p>{{$order['shipping_company'] ? $order['shipping_company'] : "None"}}</p>
                                                    </div>
                                                    <div class="form-group show_data">
                                                        <label>Address</label>
                                                        <p>{{$order['address'] ? $order['address'] : "None"}}</p>
                                                    </div>
                                                    <div class="form-group show_data">
                                                        <label>Suburb</label>
                                                        <p>{{$order['suburb'] ? $order['suburb'] : "None"}}</p>
                                                    </div>
                                                    <div class="form-group show_data">
                                                        <label>State</label>
                                                        <p>{{$order['state'] ? $order['state'] : "None"}}</p>
                                                    </div>
                                                    <div class="form-group show_data">
                                                        <label>Post Code</label>
                                                        <p>{{$order['postcode'] ? $order['postcode'] : "None"}}</p>
                                                    </div>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <div class="confirm_order">
                                            <div class="order_buttons">
                                                <button type="submit" class="order_btn" onclick="orderPrint();"><i
                                                            class="fa fa-print"></i> Print
                                                </button>
                                                <a href="mailto:?subject=Brandable Order Details For order #{{$order['order_no']}}&body={{route('order_show',$order['order_no'])}}"
                                                   type="submit" class="order_btn"><i class="fa fa-envelope"></i> Email</a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div><!-- /.checkout-steps -->
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        function orderPrint() {
            window.print();
        }
    </script>
@endsection
