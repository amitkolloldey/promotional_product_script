@extends('front.layouts.app')
@section('title')Thank You @endsection
@section('meta')<meta name="robots" content="noindex" /> @endsection
@section('content')
        <div class="container checkout_page">
            <div class="row single-product">
                <div class="col-xs-12 col-sm-12 col-md-12">
                    <div class="page_heading">
                        <h2>Thank You For Your Order!</h2>
                    </div>
                    <div class="checkout-box thank_you">
                        <div class="row">
                            <div class="col-md-12">
                                <div class=" text-center">
                                    <h1>Your Order No Is #{{$order->order_no}}</h1>
                                    <h2>Thank You For Your Order!</h2>
                                    <p>We Will Be In Touch With You Shortly!</p>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="confirm_order">
                                    <div class="order_buttons text-center">
                                        <a href="{{route('order_show', $order->order_no)}}" class="order_btn"><i class="fa fa-eye"></i> View Order Details
                                        </a>
                                        <a href="{{route('product_show',session()->get('order')->products[0]->slug )}}" class="order_btn" target="_blank"><i class="fa fa-arrow-circle-right"></i> Back To {{session()->get('order')->products[0]->name}}
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
@endsection