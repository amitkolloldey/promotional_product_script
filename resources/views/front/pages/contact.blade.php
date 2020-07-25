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

@section('content')
    <div class="breadcrumb">
        <div class="container">
            <div class="breadcrumb-inner">
                <ul class="list-inline">
                    <li class="home_link">
                        <a href="{{config('app.url')}}"><i class="fa fa-home"></i> <span><i  class="fa fa-angle-right"></i></span></a>
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
                <!-- /.sidebar -->
                <div class="col-xs-12 col-sm-12 col-md-12 rht-col">
                    <div class="page_heading text-center">
                        <h2>{{$page['title']}}</h2>
                    </div>
                    <div class="page_content_wrapper">
                        <div class="contact_info">
                            {!! $page['content'] !!}
                        </div>
                        <div class="form-wrapper row">

                           <div class="col-md-3">
                               <div class="contact_image">
                                   <img src="{{asset('files/23/Photos/Pages/').'/'.$page['image']}}" alt="{{$page['title']}}" >
                               </div>
                           </div>
                           <div class="col-md-9">
                               <div class="contact_form">
                                   <h3>
                                       Give A Call Back
                                   </h3>
                                   @if ($errors->any())
                                       <div class="alert alert-danger">
                                           <ul>
                                               @foreach ($errors->all() as $error)
                                                   <li>{{ $error }}</li>
                                               @endforeach
                                           </ul>
                                       </div>
                                   @endif
                                   @if (\Session::has('success'))
                                       <div class="alert alert-success">
                                           <ul>
                                               <li>{!! \Session::get('success') !!}</li>
                                           </ul>
                                       </div>
                                   @endif
                                   <form action="{{route('message_store')}}" method="POST"> {{csrf_field()}} {{method_field('POST')}}
                                       <div class="row">
                                           <div class="col-lg-6 col-md-6 mb-3"><input type="text" placeholder="First Name" class="form-control" name="fname" value="{{ old('fname') }}"></div>
                                           <div class="col-lg-6 col-md-6 mb-3"><input type="text" placeholder="Last Name" class="form-control" name="lname" value="{{ old('lname') }}"></div>
                                           <div class="col-lg-12 mb-3"><input type="email" placeholder="Email" class="form-control"
                                                                              name="email"  value="{{ old('email') }}"></div>
                                           <div class="col-lg-12 mb-3"><input type="text" placeholder="Subject"
                                                                              class="form-control" name="subject" value="{{ old('subject') }}"></div>
                                           <div class="col-lg-12 mb-3"><input type="tel" placeholder="Phone" class="form-control"
                                                                              name="phone" value="{{ old('phone') }}"></div>
                                           <div class="col-lg-12 mb-3"><textarea class="form-control" rows="7" placeholder="Message" name="message">{{ old('message') }}</textarea>
                                           </div>
                                           <div class="col-lg-12 text-center mb-3">
                                               <button class="btn btn-primary">Send</button>
                                           </div>
                                       </div>
                                       {!! NoCaptcha::display(['data-theme' => 'dark']) !!}
                                   </form>
                               </div>
                           </div>
                        </div>
                    </div>
                </div>
                <!-- /.search-result-container -->
            </div>
            <!-- /.col -->
        </div>
    </div><!-- /.container -->
@endsection
