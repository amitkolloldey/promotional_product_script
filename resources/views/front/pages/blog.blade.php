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
                    <div class="page_content_wrapper">
                        <div class="row">
                            @foreach( customPaginate($posts, route('page', $page['slug'])) as $post)
                                <div class="col-sm-6 col-md-4 col-lg-3">
                                    <div class="item">
                                        <div class="posts">
                                            <div class="post">
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
                                                    <h3 class="name"><a href="{{config('app.url').'/post/'.$post['slug']}}">{{$post['title']}}</a></h3>
                                                    <span class="info">By Brandable | {{Carbon\Carbon::parse($post['created_at'])->diffForHumans()}} </span>
                                                    <p class="text">{{ strip_tags(shortenDescription($post['content'], 20)) }}</p>
                                                </div>
                                            </div>
                                            <!-- /.product -->
                                        </div>
                                        <!-- /.products -->
                                    </div>
                                </div>
                            @endforeach
                        <!-- /.item -->
                        </div>
                        <div class="clearfix filters-container">
                            <div class="row">
                                <!-- /.col -->
                                <div class="col col-sm-12 col-md-12 col-xs-12 col-lg-12 text-right">
                                    <div class="pagination-container">
                                    {{ customPaginate($posts, route('page', $page['slug']))->links()}}
                                    <!-- /.list-inline -->
                                    </div>
                                    <!-- /.pagination-container -->
                                </div>
                                <!-- /.col -->
                            </div>
                            <!-- /.row -->
                        </div>
                    </div>
                </div>
                <!-- /.search-result-container -->
            </div>
            <!-- /.col -->
        </div>
    </div><!-- /.container -->
@endsection
