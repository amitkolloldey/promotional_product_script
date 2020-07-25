@extends('front.layouts.app')
@section('title')Order @endsection
@section('meta')
    <meta name="robots" content="noindex"/> @endsection
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
                        Create Order
                    </li>
                </ul>
            </div><!-- /.breadcrumb-inner -->
        </div>
    </div>
    @foreach($items as $product)
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
        
                                    <p>Before proceeding, please check your email for a verification link. If you did not receive the email, <a href="{{ route('verification.resend') }}"
                                                                                     onclick="event.preventDefault(); document.getElementById('verification-form').submit();">{{ __('click here to request another') }}</a>.</p>
        
        
                                    <form id="verification-form" action="{{ route('verification.resend') }}" method="POST"
                                          style="display: none;">
                                        @csrf
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endif
                <div class="col-xs-12 col-sm-12 col-md-12">
                     
                    <div class="row">
                        <div class="col-md-12">
                            <div class="alert alert-success mt-4 text-center">
                                <h4>Want Get A Quote For {{$product->name}} or Ask A Question?</h4>
                                <hr>
                                <div class="order_buttons">
                                    <a href="{{route('quotation_create')}}" class="order_btn"
                                       data-toggle="tooltip" data-html="true"
                                       title="<p>We Will Be In Touch With The Pricing Shortly!</p>">
                                        <i class="fa fa-tasks"></i> Get A Quote
                                    </a>
                                    <a class="order_btn" data-toggle="tooltip" data-html="true" href="{{route('question_create')}}"
                                       title="<p>Ask A Quick Question About The Product.</p>">
                                        <i class="fa fa-question-circle"></i> Quick Question
                                    </a>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="page_heading">
                                <h2>Create Order For {{$product->name}}</h2>
                            </div>
                        </div>
                    </div>

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
                    <form action="{{route('order_cart_store')}}" method="POST">
                        {{csrf_field()}}
                        {{method_field('POST')}}
                        <div class="checkout-box ">
                            <div class="row">
                                @if ($product->associatedModel->product_type ==  "promo_product")
                                    @include('front.partials.order_info_promo')
                                @else
                                    @include('front.partials.order_info_usb')
                                @endif
                                <div class="col-md-12">
                                    <div class="confirm_order">
                                        <div class="order_buttons">
                                            <button type="submit" class="order_btn" id="order_btn"><i class="fa fa-shopping-cart"></i>
                                                Confirm Your Order
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endforeach

    @include('front.partials.recently_viewed')
@endsection
@section('scripts')
    @include('front.includes.scripts.order.create')
@endsection
