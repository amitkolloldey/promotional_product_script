@extends('front.layouts.app')
@section('title')Request Quote @endsection
@section('meta')
    <meta name="robots" content="noindex"/> @endsection
@section('content')
    <div class="breadcrumb">
        <div class="container">
            <div class="breadcrumb-inner">
                <ul class="list-inline">
                    <li class="home_link">
                        <a href="{{config('app.url')}}"><i class="fa fa-home"></i> <span><i class="fa fa-angle-right"></i></span></a>
                    </li>
                    <li class="active">
                        Request Quote
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
                                <h4>Want To Order {{$product->name}} or Ask A Question?</h4>
                                <hr>
                                <div class="order_buttons">
                                    <a href="{{route('order_create')}}" class="order_btn"
                                       data-toggle="tooltip" data-html="true"
                                       title="<p>We Will Be In Touch With You Shortly!</p>">
                                        <i class="fa fa-shopping-cart"></i> Create Order
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
                                <h2>Request Quote For {{$product->name}} </h2>
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
                    <form action="{{route('quotation_store', $product->id)}}" method="POST">
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
                                    <div class="panel-group">
                                        <div class="panel panel-default">
                                            <h2 class="checkout_heading">Basic Information</h2>
                                            <div class="form-group">
                                                <label for="name">Name <span class="required">*</span></label>
                                                <input id="name" class="form-control" type="text" name="name" @if(old('name')) value="{{old('name')}}" @elseif(auth()->check()) value="{{auth()->user()->name}}" @endif />
                                            </div>
                                            <div class="form-group">
                                                <label for="phone">Phone <span class="required">*</span></label>
                                                <input id="phone" class="form-control" type="tel" name="phone" @if(old('phone')) value="{{old('name')}}" @elseif(auth()->check()) value="{{auth()->user()->phone_no}}" @endif />
                                            </div>
                                            <div class="form-group">
                                                <label for="email">Email <span class="required">*</span></label>
                                                <input id="email" class="form-control" type="email" name="email" @if(old('email')) value="{{old('email')}}" @elseif(auth()->check()) value="{{auth()->user()->email}}" @endif />
                                            </div>
                                            <hr class="list-seperator">
                                            <h2 class="checkout_heading">Billing Information</h2>
                                            <div class="form-group">
                                                <label for="company">Company <span class="required">*</span></label>
                                                <input id="company" class="form-control" type="text" name="company" @if(old('company')) value="{{old('company')}}" @elseif(auth()->check()) value="{{auth()->user()->company}}" @endif />
                                            </div>
                                            <div class="form-group">
                                                <label for="address">Address <span class="required">*</span></label>
                                                <textarea name="address" id="address" cols="10" rows="3" class="form-control">{{old('address')}}</textarea>
                                            </div>
                                            <div class="form-group">
                                                <label for="suburb">Suburb <span class="required">*</span></label>
                                                <input id="suburb" class="form-control" type="text" name="suburb" value="{{old('suburb')}}" />
                                            </div>
                                            <div class="form-group">
                                                <label for="state">State <span class="required">*</span></label>
                                                <select name="state" id="state" class="form-control">
                                                    <option value="" {{old('state') == "" ? "selected" : ""}}>Select</option>
                                                    <option value="nsw" {{old('state') == "nsw" ? "selected" : ""}}>NSW</option>
                                                    <option value="qld" {{old('state') == "qld" ? "selected" : ""}}>QLD</option>
                                                    <option value="vic" {{old('state') == "vic" ? "selected" : ""}}>VIC</option>
                                                    <option value="wa" {{old('state') == "wa" ? "selected" : ""}}>WA</option>
                                                    <option value="sa" {{old('state') == "sa" ? "selected" : ""}}>SA</option>
                                                    <option value="tas" {{old('state') == "tas" ? "selected" : ""}}>TAS</option>
                                                    <option value="nt" {{old('state') == "nt" ? "selected" : ""}}>NT</option>
                                                    <option value="act" {{old('state') == "act" ? "selected" : ""}}>ACT</option>
                                                </select>
                                            </div>
                                            <div class="form-group">
                                                <label for="postcode">Postcode <span class="required">*</span></label>
                                                <input type="text" name="postcode" id="postcode" class="form-control" value="{{old('postcode')}}"/>
                                            </div>
                                            <hr class="list-seperator">
                                            @include('front.partials.artwork_upload')
                                            <hr class="list-seperator">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="confirm_order">
                                        <div class="order_buttons">
                                            <button type="submit" class="order_btn"><i class="fa fa-shopping-cart"></i>
                                                Request Quote
                                            </button>
                                            {!! NoCaptcha::display(['data-theme' => 'dark']) !!}
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
    @include('front.includes.scripts.quotations.create')
@endsection
