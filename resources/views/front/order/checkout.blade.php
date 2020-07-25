@extends('front.layouts.app')
@section('title')Checkout @endsection
@section('content')
    @foreach($items as $product)
        <div class="container checkout_page">
            <div class="row single-product">
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
                                <h2>Finalise Your Order</h2>
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
                    <form action="{{route('order_submit')}}" method="POST" enctype="multipart/form-data">
                        {{csrf_field()}}
                        {{method_field('POST')}}
                        <div class="checkout-box">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="panel-group">
                                        <div class="panel panel-default">
                                            <div class="panel-heading">
                                                <h4 class="unicase-checkout-title">Checkout Details</h4>
                                            </div>
                                        </div>
                                        <div class="panel panel-default">
                                            <h2 class="checkout_heading">Basic Information</h2>
                                            <div class="form-group">
                                                <label for="name">Name <span class="required">*</span></label>
                                                <input id="name" class="form-control" type="text" name="name"
                                                      @if(old('name')) value="{{old('name')}}" @else value="{{Auth::user()->name}}" @endif/>
                                            </div>
                                            <div class="form-group">
                                                <label for="phone_no">Phone <span class="required">*</span></label>
                                                <input id="phone_no" class="form-control" type="tel" name="phone_no" @if(old('phone_no')) value="{{old('phone_no')}}" @else value="{{Auth::user()->phone_no}}" @endif />
                                            </div>
                                            <div class="form-group">
                                                <label for="email">Email <span class="required">*</span></label>
                                                <input id="email" class="form-control" type="email" name="email" @if(old('email')) value="{{old('email')}}" @else value="{{Auth::user()->email}}" @endif/>
                                            </div>
                                            <hr class="list-seperator">
                                            <h2 class="checkout_heading">Billing Information</h2>
                                            <div class="form-group">
                                                <label for="company">Company <span class="required">*</span></label>
                                                <input id="company" class="form-control" type="text" name="company" @if(old('company')) value="{{old('company')}}" @else value="{{Auth::user()->company}}" @endif/>
                                            </div>
                                            <div class="form-group">
                                                <label for="address">Address <span class="required">*</span></label>
                                                <textarea name="address" id="address" cols="10" rows="3"
                                                          class="form-control">{{old('address')}}</textarea>
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
                                                <input type="text" name="postcode" id="postcode" class="form-control"  value="{{old('postcode')}}"/>
                                            </div>
                                            <hr class="list-seperator">
                                            <h2 class="checkout_heading">Shipping Information</h2>
                                            <div class="form-group">
                                                <input type="checkbox" name="shipping_same_as_billing"
                                                       id="shipping_same_as_billing" checked value="1" >
                                                <label for="shipping_same_as_billing">Same As Billing</label>
                                            </div>
                                            <div id="shipping_info">
                                                <div class="form-group">
                                                    <label for="shipping_company">Company</label>
                                                    <input id="shipping_company" class="form-control" type="text"
                                                           name="shipping_company" value="{{old('shipping_company')}}" />
                                                </div>
                                                <div class="form-group">
                                                    <label for="shipping_address">Address </label>
                                                    <textarea name="shipping_address" id="shipping_address" cols="10"
                                                              rows="3" class="form-control">{{old('shipping_address')}}</textarea>
                                                </div>
                                                <div class="form-group">
                                                    <label for="shipping_suburb">Suburb</label>
                                                    <input id="shipping_suburb" class="form-control" type="text"
                                                           name="shipping_suburb" value="{{old('shipping_suburb')}}" />
                                                </div>
                                                <div class="form-group">
                                                    <label for="shipping_state">State</label>
                                                    <select name="shipping_state" id="state" class="form-control">
                                                        <option value="" {{old('shipping_state') == "" ? "selected" : ""}}>Select</option>
                                                        <option value="nsw" {{old('shipping_state') == "nsw" ? "selected" : ""}}>NSW</option>
                                                        <option value="qld" {{old('shipping_state') == "qld" ? "selected" : ""}}>QLD</option>
                                                        <option value="vic" {{old('shipping_state') == "vic" ? "selected" : ""}}>VIC</option>
                                                        <option value="wa" {{old('shipping_state') == "wa" ? "selected" : ""}}>WA</option>
                                                        <option value="sa" {{old('shipping_state') == "sa" ? "selected" : ""}}>SA</option>
                                                        <option value="tas" {{old('shipping_state') == "tas" ? "selected" : ""}}>TAS</option>
                                                        <option value="nt" {{old('shipping_state') == "nt" ? "selected" : ""}}>NT</option>
                                                        <option value="act" {{old('shipping_state') == "act" ? "selected" : ""}}>ACT</option>
                                                    </select>
                                                </div>
                                                <div class="form-group">
                                                    <label for="shipping_postcode">Postcode</label>
                                                    <input type="text" name="shipping_postcode" id="shipping_postcode"
                                                           class="form-control" value="{{old('shipping_postcode')}}"/>
                                                </div>
                                            </div>

                                            <hr class="list-seperator">
                                            <h2 class="checkout_heading">Notes</h2>
                                            <div class="form-group">
                                                <label for="order_note">Order Notes</label>
                                                <textarea name="order_note" cols="10" rows="3" class="form-control">{{old('order_note')}}</textarea>
                                            </div>

                                            <hr class="list-seperator">
                                            <h2 class="checkout_heading">Survey Questions</h2>
                                            <div class="form-group">
                                                <label for="how_you_hear">How Do You Hear About Us?<span class="required">*</span></label>
                                                <select name="how_you_hear" id="how_you_hear" class="form-control">
                                                    <option value="ec" {{old('how_you_hear') == "ec" ? "selected" : ""}}>Existing Customer</option>
                                                    <option value="gl" {{old('how_you_hear') == "gl" ? "selected" : ""}}>Google</option>
                                                    <option value="wom" {{old('how_you_hear') == "wom" ? "selected" : ""}}>Word of Mouth</option>
                                                    <option value="os" {{old('how_you_hear') == "os" ? "selected" : ""}}>Other Search</option>
                                                    <option value="ot" {{old('how_you_hear') == "ot" ? "selected" : ""}}>Other</option>
                                                </select>
                                            </div>

                                            <hr class="list-seperator">
                                            @include('front.partials.artwork_upload')
                                            <hr class="list-seperator">
                                        </div>
                                    </div>
                                </div>
                                @if ($product->associatedModel->product_type == "promo_product")
                                    @include('front.partials.order_info_promo')
                                @else
                                    @include('front.partials.order_info_usb')
                                @endif
                                <div class="col-md-12">
                                    <div class="confirm_order">
                                        <div class="order_buttons">
                                            <input type="hidden" name="product_id" value="{{$product->id}}">
                                            <button type="submit" class="order_btn" id="order_btn"><i class="fa fa-paper-plane"></i>
                                                Submit Order
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
    @include('front.includes.scripts.order.create')
@endsection
