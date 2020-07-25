@extends('front.layouts.app')
@section('title')Reset Password @endsection
@section('content')
    <div class="container checkout_page">
        <div class="row single-product">
            <div class="col-xs-12 col-sm-12 col-md-12">
                <div class="panel-group login_register_box">
                    <div class="page_heading">
                        <h2>Reset Password</h2>
                    </div>
                </div>
            </div>
            <div class="col-xs-12 col-sm-12 col-md-12">
                <div class="panel-group login_register_box">
                    <div class="panel panel-default">
                        <div class="panel-group checkout-steps">
                            <form method="POST" action="{{ route('password.update') }}">
                                @csrf
                                <input type="hidden" name="token" value="{{ $token }}">
                                <div class="form-group row">
                                    <label for="email" class="col-md-3 col-form-label text-md-right">{{ __('E-Mail Address') }}</label>
                                    <div class="col-md-6">
                                        <input id="email" type="email"
                                               class="form-control @error('email') is-invalid @enderror" name="email"
                                               value="{{ $email ?? old('email') }}" required autocomplete="email"
                                               autofocus>
                                        @error('email')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                        @enderror
                                    </div>
                                    <div class="col-md-3"></div>
                                </div>
                                <div class="form-group row">
                                    <label for="password"
                                           class="col-md-3 col-form-label text-md-right">{{ __('Password') }}</label>
                                    <div class="col-md-6">
                                        <input id="password" type="password"
                                               class="form-control @error('password') is-invalid @enderror"
                                               name="password" required autocomplete="new-password">
                                        @error('password')
                                        <span class="invalid-feedback" role="alert">
                                             <strong>{{ $message }}</strong>
                                        </span>
                                        @enderror
                                    </div>
                                    <div class="col-md-3"></div>
                                </div>
                                <div class="form-group row">
                                    <label for="password-confirm"
                                           class="col-md-3 col-form-label text-md-right">{{ __('Confirm Password') }}</label>
                                    <div class="col-md-6">
                                        <input id="password-confirm" type="password" class="form-control"
                                               name="password_confirmation" required autocomplete="new-password">
                                    </div>
                                    <div class="col-md-3"></div>
                                </div>
                                <div class="form-group row mb-0">
                                    <div class="col-md-6 offset-md-3 text-center">
                                        <button type="submit" class="btn btn-primary">
                                            {{ __('Reset Password') }}
                                        </button>
                                    </div>
                                </div>
                                {!! NoCaptcha::display(['data-theme' => 'dark']) !!}
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection