@extends('admin.layouts.app')
@section('title')Edit Order @endsection
@section('page_title')Edit Order @endsection
@section('content')
@include('admin.partials.error_message')

<div class="row">

    <form action="{{route('order_update',$order->id)}}" method="post" enctype="multipart/form-data">
        {{csrf_field()}}
        {{method_field('PUT')}}
        <div class="col-md-6">
            <div class="form-group">
                <label>Order No #{{$order->order_no}}</label>

            </div>
        </div>


        <div class="col-md-12 mt-3 mb-3 card">
            <!-- Nav pills -->
            <ul class="nav nav-pills  mb-2" id="order_tabs">
                <li class="nav-item">
                    <a class="nav-link active" data-toggle="tab" href="#product">Product Details</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" data-toggle="tab" href="#order">Order Details</a>
                </li>
            </ul>
            <!-- Tab panes -->
            <div class="tab-content">
                <div class="tab-pane active" id="product">
                    <div class="card">
                        <div class="card-body">
                            <div class="form-group">
                                @foreach ($order->products as $product)

                                <div class="order_image">
                                    <img src="{{asset('files/23/Photos/Products/').'/'.$product->main_image}}" height="100px">
                                </div>

                                <p><strong>Product Name:</strong> {{$product->name}}</p>
                                <p><strong>Product Code:</strong> {{$product->product_code}}</p>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
                <div class="tab-pane" id="order">
                    <div class="card">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6">

                                    <div class="form-group show_data">
                                        <label for="quantity">Quantity</label>
                                        <p>{{$order->quantity}}</p>
                                    </div>
                                    <div class="form-group show_data">
                                        <label for="color">Color</label>
                                        <p>{{get_attribute_name_by_id($order->color)}}</p>
                                    </div>

                                    <div class="form-group show_data">
                                        <label for="personalisation_options">Personalisation Option</label>
                                        <p>{{get_personalisation_type_name_by_id($order->personalisation_options)}}</p>
                                    </div>
                                    <div class="form-group show_data">
                                        <label for="personalisation_color">Personalisation Color</label>
                                        <p>{{get_personalisation_color_by_ids($order->personalisation_color)}}</p>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <ul>
                                        <li class="total_price"><span class="total_price_text">Total Price</span><span
                                                class="total_price_amount">${{$order['total_price'] ? $order['total_price'] : "0.00"}}</span>
                                        </li>
                                        <li class="price_row"><span class="total_quantity_text">Quantity</span><span
                                                class="total_quantity_amount">{{$order['quantity']}}</span>
                                        </li>
                                        <li class="price_row"><span class="total_unit_price_text">Unit Price</span><span
                                                class="total_quantity_amount">${{$order['unit_price'] ? $order['unit_price'] : "0.00"}}</span>
                                        </li>
                                        <li class="price_row"><span class="total_unit_price_text">personalisation, GST,
                                                Setup &amp;
                                                Delivery</span><span class="total_quantity_amount">$85</span></li>
                                    </ul>
                                    <div class="form-group show_data">
                                        <label>Order Notes</label>
                                        <p>{{$order['order_note'] ? $order['order_note'] : "None"}}</p>
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>

                <div class="form-group">
                    <button type="submit" class="btn btn-primary">Update</button>
                </div>
            </div>

    </form>

</div>

@endsection
@section('scripts')
@include('admin.includes.scripts.orders.edit')
@endsection
