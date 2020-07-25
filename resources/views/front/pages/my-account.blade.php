@extends('front.layouts.app')
@section('title'){{$page['title']}} @endsection
@section('meta')
    @if (!empty($site_data))
        <meta name="description"
              content="{{isset($page['meta']['description']) ? $page['meta']['description'] : $site_data['data']['site_meta_description']}}">
        <meta name="keywords"
              content="{{isset($page['meta']['keywords']) ? $page['meta']['keywords'] : $site_data['data']['site_meta_keywords']}}">
    @endif
@endsection
@section('styles')
    <link rel="stylesheet" type="text/css" href="//cdn.datatables.net/v/dt/dt-1.10.21/datatables.min.css"/>
@endsection
@section('content')
    <div class="breadcrumb">
        <div class="container">
            <div class="breadcrumb-inner">
                <ul class="list-inline">
                    <li class="home_link">
                        <a href="{{config('app.url')}}"><i class="fa fa-home"></i> <span><i class="fa fa-angle-right"></i></span></a>
                    </li>
                    <li class="active">
                        {{$page['title']}}
                    </li>
                </ul>
            </div><!-- /.breadcrumb-inner -->
        </div>
    </div>

    <div class="body-content outer-top-xs">
        <div class='container'>
            <div class='row'>
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
                <!-- /.sidebar -->
                <div class="col-xs-12 col-sm-12 col-md-12 rht-col">
                    @if(count(Cart::getContent()))
                        <div class="alert alert-success my_account_alert">
                            <h4>You have {{count(Cart::getContent())}} item is added to your cart!</h4>
                            <ul class="my_account_btns">
                                @foreach(Cart::getContent() as $product)
                                    <li>
                                        <a href="{{route('order_create')}}" class="order_btn"
                                           data-toggle="tooltip" data-html="true"
                                           title="<p>We Will Be In Touch With You Shortly!</p>">
                                            <i class="fa fa-shopping-cart"></i> Create Order
                                        </a>
                                    </li>
                                    <li>
                                        <a href="{{route('quotation_create')}}" class="order_btn"
                                           data-toggle="tooltip" data-html="true"
                                           title="<p>We Will Be In Touch With The Pricing Shortly!</p>">
                                            <i class="fa fa-tasks"></i> Get A Quote
                                        </a>
                                    </li>
                                    <li>
                                        <a class="order_btn" data-toggle="tooltip" data-html="true" href=""
                                           title="<p>Ask A Quick Question About The Product.</p>">
                                            <i class="fa fa-question-circle"></i> Quick Question
                                        </a>
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                    <div class="page_heading text-center">
                        <h2>{{$page['title']}}</h2>
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
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif
                    <div class="page_content_wrapper">
                        <h4>Howdy <em>{{auth()->user()->name}}</em>, Welcome To Your Dashboard!</h4>
                        <hr>
                        {!! $page['content'] !!}
                        <div class="product-tabs inner-bottom-xs">
                            <div class="row">
                                <div class="col-sm-12 col-md-3 col-lg-3">
                                    <ul id="product-tabs" class="nav nav-tabs nav-tab-cell">
                                        <li><a data-toggle="tab" href="#account-details" class="active show">Account
                                                Details</a>
                                        </li>
                                        <li><a data-toggle="tab" href="#settings">Settings</a></li>
                                        <li><a data-toggle="tab" href="#orders">Orders</a></li>
                                        <li><a data-toggle="tab" href="#quotations">Quote Requests</a></li>
                                        <li><a data-toggle="tab" href="#questions">Asked Questions</a></li>
                                    </ul><!-- /.nav-tabs #product-tabs -->
                                </div>
                                <div class="col-sm-12 col-md-9 col-lg-9">
                                    <div class="tab-content">
                                        <div id="account-details" class="tab-pane active show">
                                            <div class="product-tab">
                                                <form action="{{route('front_user_update', auth()->user()->id)}}"
                                                      method="post">
                                                    @csrf
                                                    <div class="form-group">
                                                        <label for="name">Name <span class="required">*</span></label>
                                                        <input id="name" class="form-control" type="text" name="name"
                                                               @if(old('name')) value="{{old('name')}}"
                                                               @else value="{{auth()->user()->name}}" @endif/>
                                                    </div>
                                                    <div class="form-group">
                                                        <label for="phone_no">Phone <span
                                                                    class="required">*</span></label>
                                                        <input id="phone_no" class="form-control" type="tel"
                                                               name="phone_no"
                                                               @if(old('phone_no')) value="{{old('phone_no')}}"
                                                               @elseif(auth()->user()->phone_no) value="{{auth()->user()->phone_no}}"
                                                               @else value="" @endif />
                                                    </div>
                                                    <div class="form-group">
                                                        <label for="email">Email <span class="required">*</span></label>
                                                        <input id="email" class="form-control" type="email" name="email"
                                                               value="{{auth()->user()->email}}" disabled/>
                                                    </div>
                                                    <div class="form-group">
                                                        <label for="company">Company <span
                                                                    class="required">*</span></label>
                                                        <input id="company" class="form-control" type="tel"
                                                               name="company"
                                                               @if(old('company')) value="{{old('company')}}"
                                                               @elseif(auth()->user()->company) value="{{auth()->user()->company}}"
                                                               @else value="" @endif />
                                                    </div>
                                                    <div class="form-group">
                                                        <div class="order_buttons">
                                                            <button class="btn btn-primary">Update</button>
                                                        </div>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                        <div id="settings" class="tab-pane">
                                            <div class="product-tab text">
                                                <h6>Reset Your Password From <a href="{{route('password.request')}}">Here</a>
                                                </h6>
                                            </div>
                                        </div>
                                        <div id="orders" class="tab-pane">
                                            <div class="product-tab text">
                                                <table class="table" id="orders_table">
                                                    <thead>
                                                    <tr>
                                                        <th scope="col"># Order No</th>
                                                        <th scope="col">Product</th>
                                                        <th scope="col">Quantity</th>
                                                        <th scope="col">Total Price</th>
                                                        <th scope="col">Status</th>
                                                        <th scope="col">Action</th>
                                                    </tr>
                                                    </thead>
                                                    <tbody>
                                                    @foreach($orders as $order)
                                                        <tr>
                                                            <th scope="row">#{{$order->order_no}}</th>
                                                            <td>
                                                                @if(isset($order->products[0]))
                                                                    <a href="{{route('product_show',$order->products[0]->slug)}}">{{$order->products[0]->name}}</a>
                                                                @endif
                                                            </td>
                                                            <td>{{$order->quantity}}</td>
                                                            <td>${{$order->total_price}}</td>
                                                            <td> {!! ($order->status == 'pending') ? "<span class='badge badge-warning'>Pending</span>" : "<span class='badge badge-success'>Completed</span>" !!}</td>
                                                            <td><a href="{{route('order_show', $order->order_no)}}"><i
                                                                            class="fa fa-eye"></i> View</a></td>
                                                        </tr>
                                                    @endforeach
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                        <div id="quotations" class="tab-pane">
                                            <div class="product-tab text">
                                                <h6>Quote Request Created Using E-Mail {{auth()->user()->email}}</h6>
                                                <hr>
                                                <table class="table" id="quotations_table">
                                                    <thead>
                                                    <tr>
                                                        <th scope="col">Product</th>
                                                        <th scope="col">Price Sheet</th>
                                                    </tr>
                                                    </thead>
                                                    <tbody>
                                                    @foreach($quotations as $quotation)
                                                        <tr>
                                                            <td>
                                                                @if(isset($quotation->products[0]))
                                                                    <a href="{{route('product_show',$quotation->products[0]->slug)}}">{{$quotation->products[0]->name}}</a>
                                                                @endif
                                                            </td>
                                                            <td>
                                                                @if(isset($quotation->quote_link))
                                                                    <a href="{{$quotation->quote_link}}"><i
                                                                                class="fa fa-eye"></i> View Our Pricing</a>
                                                                @else
                                                                    Price Sheet Not Added
                                                                @endif
                                                            </td>
                                                        </tr>
                                                    @endforeach
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div><!-- /.tab-pane -->
                                        <div id="questions" class="tab-pane">
                                            <div class="product-tab text">
                                                <h6>Questions Created Using E-Mail {{auth()->user()->email}}</h6>
                                                <hr>
                                                <table class="table" id="questions_table">
                                                    <thead>
                                                    <tr>
                                                        <th scope="col">Product</th>
                                                        <th scope="col">Message</th>
                                                    </tr>
                                                    </thead>
                                                    <tbody>
                                                    @foreach($questions as $question)
                                                        <tr>
                                                            <td>
                                                                @if(isset($question->products[0]))
                                                                    <a href="{{route('product_show',$question->products[0]->slug)}}">{{$question->products[0]->name}}</a>
                                                                @endif
                                                            </td>
                                                            <td>
                                                                {{shortenDescription($question->message, 50)}}
                                                            </td>
                                                        </tr>
                                                    @endforeach
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div><!-- /.tab-pane -->
                                    </div><!-- /.tab-content -->
                                </div><!-- /.col -->
                            </div><!-- /.row -->
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @include('front.partials.recently_viewed')
@endsection
@section('scripts')
    <script type="text/javascript" src="https://cdn.datatables.net/v/dt/dt-1.10.21/datatables.min.js"></script>
    <script>
        $(document).ready(function () {
            $('#orders_table').DataTable();
            $('#quotations_table').DataTable();
            $('#questions_table').DataTable();
        });
    </script>
@endsection