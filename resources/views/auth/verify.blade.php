@extends('front.layouts.app')
@section('title')Authenticate @endsection
@section('content')

    <div class="container checkout_page">
        <div class="row single-product">
            <div class="col-xs-12 col-sm-12 col-md-12">
                <div class="panel-group login_register_box">
                    <div class="page_heading">
                        <h2>Verify Your Email Address</h2>
                    </div>
                    <div class="panel panel-default">
                        @include('front.includes.errors')
                    </div>
                </div>
            </div>

            <div class="col-xs-12 col-sm-12 col-md-12">
                <div class="panel-group login_register_box">

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
        </div>
    </div>
@endsection
