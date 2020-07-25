@extends('front.layouts.app')
@section('title'){{$post['title']}} @endsection
@section('meta')
    @if (!empty($site_data))
        <meta name="description"
              content="{{isset($post['meta']['description']) ? $post['meta']['description'] : $site_data['data']['site_meta_description']}}">
        <meta name="keywords"
              content="{{isset($post['meta']['keywords']) ? $post['meta']['keywords'] : $site_data['data']['site_meta_keywords']}}">
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
                        {{$post['title']}}
                    </li>
                </ul>
            </div><!-- /.breadcrumb-inner -->
        </div>
    </div>
    <div class="body-content outer-top-xs">
        <div class='container'>
            <div class='row single-post'>
                <!-- /.sidebar -->
                <div class="col-md-2"></div>
                <div class="col-xs-12 col-sm-12 col-md-8 rht-col">
                    <div class="page_heading text-center">
                        <h2>{{$post['title']}}</h2>
                    </div>
                    <div class="page_content_wrapper">
                        <div class="post-image">
                            <div class="image">
                                <a href="{{config('app.url').'/post/'.$post['slug']}}">
                                    @if (isset($post['image']))
                                        <img src="{{asset('files/23/Photos/Posts/').'/'.$post['image']}}"
                                             alt="{{$post['title']}}">
                                    @else
                                        <img src="{{asset('files/23/Photos/Posts/no_image.png')}}"
                                             alt="{{$post['title']}}">
                                    @endif
                                </a>
                            </div>
                        </div>
                        <div class="blog-post-info text-left">
                            <span class="info">By Brandable | {{Carbon\Carbon::parse($post['created_at'])->diffForHumans()}} </span>
                        </div>
                        {!! $post['content'] !!}
                    </div>
                </div>
                <!-- /.search-result-container -->
            </div>
            <!-- /.col -->
        </div>
    </div><!-- /.container -->
@endsection
