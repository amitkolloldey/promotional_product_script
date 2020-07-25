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
                        {!! $page['content'] !!}
                    </div>
                </div>
                <!-- /.search-result-container -->
            </div>
            <!-- /.col -->
        </div>
    </div><!-- /.container -->
@endsection
