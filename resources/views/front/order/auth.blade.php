@extends('front.layouts.app')
@section('title')Authenticate @endsection
@section('content')
<div class="container checkout_page">
    <div class="row single-product">
        <div class="col-xs-12 col-sm-12 col-md-12">
            <div class="panel-group login_register_box">
                <div class="page_heading">
                     <h2>Authentication</h2>
                </div>
                <div class="panel panel-default">
                    @include('front.includes.errors')
                </div>
            </div>
        </div>
        <div class="col-xs-12 col-sm-6 col-md-6">
            <div class="panel-group login_register_box">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <h4 class="unicase-checkout-title">Login</h4>
                    </div>
                </div>
                <div class="panel panel-default">
                    <div class="panel-group checkout-steps">
                        <form method="POST" action="{{ route('login') }}">
                            @csrf
                            <div class="form-group">
                                <label for="email">Email <span class="required">*</span></label>

                                <input id="email" class="form-control" type="email" name="email" />
                            </div>
                            <div class="form-group">
                                <label for="password">Password <span class="required">*</span></label>

                                <input id="password" class="form-control" type="password" name="password" />
                            </div>
                            <div class="form-group">
                                <button type="submit" class="order_btn">Login</button>
                            </div>
                            {!! NoCaptcha::display(['data-theme' => 'dark']) !!}
                        </form>
                    </div>
                </div>
            </div><!-- /.checkout-steps -->
        </div>
        <div class="col-xs-12 col-sm-6 col-md-6">
            <div class="panel-group login_register_box">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <h4 class="unicase-checkout-title">Registration</h4>
                    </div>
                </div>
                <div class="panel panel-default">
                    <div class="panel-group checkout-steps">
                        <form action="{{ route('register') }}" method="POST">
                            @csrf
                            <div class="form-group">
                                <label for="name">Name <span class="required">*</span></label>

                                <input id="name" class="form-control" type="text" name="name" value="{{old('name')}}" />
                            </div>
                            <div class="form-group">
                                <label for="email">Email <span class="required">*</span></label>

                                <input id="email" class="form-control" type="email" name="email" value="{{old('email')}}"/>
                            </div>
                            <div class="form-group">
                                <label for="company">Company <span class="required">*</span></label>
                                <input id="company" class="form-control" type="text" name="company" value="{{old('company')}}"/>
                            </div>
                            <div class="form-group">
                                <label for="phone">Phone <span class="required">*</span></label>
                                <input id="phone" class="form-control" type="text" name="phone_no" value="{{old('phone_no')}}" />
                            </div>
                            <div class="form-group">
                                <label for="password">Password <span class="required">*</span></label>

                                <input id="password" class="form-control" type="password" name="password" value="{{old('password')}}"/>
                            </div>
                            <div class="form-group">
                                <label for="confirm_password">Confirm Password <span class="required">*</span></label>

                                <input id="password_confirmation" class="form-control" type="password"
                                    name="password_confirmation" value="{{old('password_confirmation')}}"/>
                            </div>
                            <div class="form-group">
                                <button type="submit" class="order_btn">Register</button>
                            </div>
                            {!! NoCaptcha::display(['data-theme' => 'dark']) !!}
                        </form>
                    </div>
                </div>
            </div><!-- /.checkout-steps -->
        </div>
    </div>
</div>
</div>
@endsection
