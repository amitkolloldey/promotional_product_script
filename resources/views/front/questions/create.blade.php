@extends('front.layouts.app')
@section('title')Quick Question @endsection
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
                        Quick Question
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
                                <h4>Want To Order {{$product->name}} or Request A Quote?</h4>
                                <hr>
                                <div class="order_buttons">
                                    <a href="{{route('order_create')}}" class="order_btn"
                                       data-toggle="tooltip" data-html="true"
                                       title="<p>We Will Be In Touch Shortly!</p>">
                                        <i class="fa fa-shopping-cart"></i> Create Order
                                    </a>
                                    <a href="{{route('quotation_create')}}" class="order_btn"
                                       data-toggle="tooltip" data-html="true"
                                       title="<p>We Will Be In Touch With The Pricing Shortly!</p>">
                                        <i class="fa fa-tasks"></i> Get A Quote
                                    </a>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="page_heading">
                                <h2>Ask Question About {{$product->name}}</h2>
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
                    <form action="{{route('question_store', $product->id)}}" method="POST">
                        {{csrf_field()}}
                        {{method_field('POST')}}
                        <div class="checkout-box ">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="panel-group">
                                        <div class="panel panel-default">
                                            <div class="form-group">
                                                <label for="name">Name <span class="required">*</span></label>
                                                <input id="name" class="form-control" type="text" name="name" @if(old('name')) value="{{old('name')}}" @elseif(auth()->check()) value="{{auth()->user()->name}}" @endif />
                                            </div>
                                            <div class="form-group">
                                                <label for="phone">Phone <span class="required">*</span></label>
                                                <input id="phone" class="form-control" type="tel" name="phone" @if(old('phone')) value="{{old('phone')}}" @elseif(auth()->check()) value="{{auth()->user()->phone_no}}" @endif />
                                            </div>
                                            <div class="form-group">
                                                <label for="email">Email <span class="required">*</span></label>
                                                <input id="email" class="form-control" type="email" name="email" @if(old('email')) value="{{old('email')}}" @elseif(auth()->check()) value="{{auth()->user()->email}}" @endif/>
                                            </div>
                                            <div class="form-group">
                                                <label for="company">Company<span class="required">*</span></label>
                                                <input id="company" class="form-control" type="text" name="company" @if(old('company')) value="{{old('company')}}" @elseif(auth()->check()) value="{{auth()->user()->company}}" @endif/>
                                            </div>
                                            <div class="form-group">
                                                <label for="message">Message <span class="required">*</span></label>
                                                <textarea id="message" class="form-control" name="message" >{{old('message')}}</textarea>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="confirm_order">
                                        <div class="order_buttons">
                                            <input id="product_id" class="form-control" type="hidden" name="product_id" value="{{$product->id}}" />
                                            <button type="submit" class="order_btn"><i class="fa fa-question-circle"></i>
                                                Submit Query
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

