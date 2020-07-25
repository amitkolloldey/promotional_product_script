<!DOCTYPE html>
<html lang="en">
<head>
    <!-- Meta -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    @yield('meta')
    <meta name="robots" content="all">
    @if ($site_data)
        <link rel="icon" type="image/png"
              href="{{asset('files/23/Photos/Settings/').'/'. $site_data['data']['site_favicon']}}">
        <title>@yield('title')| {{$site_data['data']['site_name']}}</title>
    @endif

    <link rel="stylesheet" href="{{asset('front/assets/css/app.css')}}">

    {{--  Custom Styles  --}}
    <style>
        .dropdown-menu.events .menu_wrapper {
            column-count: 5;
        }

        .dropdown-menu.outdoors .menu_wrapper {
            column-count: 3;
        }

        .dropdown-menu.drinkware .menu_wrapper {
            column-count: 6;
        }

        .dropdown-menu.green .menu_wrapper {
            column-count: 4;
        }

        .dropdown-menu.health-beauty .menu_wrapper {
            column-count: 6;
        }

        .dropdown-menu.print .menu_wrapper {
            column-count: 8;
        }

        .dropdown-menu.office .menu_wrapper {
            column-count: 6;
        }

        .dropdown-menu.computer-it .menu_wrapper {
            column-count: 7;
        }

        .dropdown-menu.homeware .menu_wrapper {
            column-count: 5;
        }
    </style>

    @yield('styles')
</head>
<body class="cnt-home">
@include('sweetalert::alert')
<header class="header-style-1">
    <div class="top-bar animate-dropdown">
        <div class="container">
            <div class="header-top-inner">
                <div class="cnt-account">
                    <ul class="list-unstyled">
                        @if (Auth::check())
                            <li class="myaccount"><a href="{{route('page','my-account')}}"><i
                                            class="fa fa-user"></i> {{Auth::user()->name}}</a></li>
                            <li>
                                <a href="{{ route('logout') }}"
                                   onclick="event.preventDefault(); document.getElementById('logout-form').submit();"><i
                                            class="fa fa-sign-out"></i> Logout
                                </a>
                                <form id="logout-form" action="{{ route('logout') }}" method="POST"
                                      style="display: none;">
                                    @csrf
                                </form>
                            </li>
                        @else
                            <li class="login"><a href="{{route('order_authenticate')}}"><i class="fa fa-sign-in"></i>
                                    Login</a></li>
                        @endif
                    </ul>
                </div>
                <!-- /.cnt-account -->
                <div class="cnt-block">
                    <ul class="list-unstyled list-inline">
                        @if ($site_data)
                            <li><a href="#"><i class="fa fa-envelope"></i> {{$site_data['data']['site_email']}}</a></li>
                        @endif
                        @if ($site_data)
                            <li><a href="#"><i class="fa fa-phone"></i> {{$site_data['data']['site_phone']}}</a></li>
                        @endif
                        <li><a href="{{config('app.url').'/page/contact'}}"><i class="fa fa-map-marker"></i> Contact Us</a>
                        </li>
                        <li><a href="{{config('app.url').'/page/about'}}"><i class="fa fa-info-circle"></i> About Us</a>
                        </li>
                        <li><a href="{{config('app.url').'/page/blog'}}"><i class="fa fa-info-circle"></i> Blog</a></li>
                    </ul>
                    <!-- /.list-unstyled -->
                </div>
                <!-- /.cnt-cart -->
                <div class="clearfix"></div>
            </div>
            <!-- /.header-top-inner -->
        </div>
        <!-- /.container -->
    </div>
    <!-- /.header-top -->
    <div class="main-header">
        <div class="container">
            <div class="row">
                <div class="col-lg-3 col-xs-12 col-sm-12 col-md-3 logo-holder ">
                    <div class="logo">
                        @if ($site_data)
                            <a href="{{config('app.url')}}">
                                <img src="{{asset('files/23/Photos/Settings/')}}/{{$site_data['data']['site_logo']}}"
                                     alt="{{$site_data['data']['site_name']}}">
                            </a>
                        @endif
                    </div>
                    <!-- /.logo -->
                </div>
                <!-- /.logo-holder -->
                <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12 top-search-holder">
                    <!-- /.contact-row -->
                    <div class="search-area">
                        <div class="aa-input-container" id="aa-input-container">
                            <input type="search" id="aa-search-input" class="aa-input-search"
                                   placeholder="Search By Product Name or Code" name="search" autocomplete="on"/>
                            <svg class="aa-input-icon" viewBox="654 -372 1664 1664">
                                <path d="M1806,332c0-123.3-43.8-228.8-131.5-316.5C1586.8-72.2,1481.3-116,1358-116s-228.8,43.8-316.5,131.5  C953.8,103.2,910,208.7,910,332s43.8,228.8,131.5,316.5C1129.2,736.2,1234.7,780,1358,780s228.8-43.8,316.5-131.5  C1762.2,560.8,1806,455.3,1806,332z M2318,1164c0,34.7-12.7,64.7-38,90s-55.3,38-90,38c-36,0-66-12.7-90-38l-343-342  c-119.3,82.7-252.3,124-399,124c-95.3,0-186.5-18.5-273.5-55.5s-162-87-225-150s-113-138-150-225S654,427.3,654,332  s18.5-186.5,55.5-273.5s87-162,150-225s138-113,225-150S1262.7-372,1358-372s186.5,18.5,273.5,55.5s162,87,225,150s113,138,150,225  S2062,236.7,2062,332c0,146.7-41.3,279.7-124,399l343,343C2305.7,1098.7,2318,1128.7,2318,1164z"/>
                            </svg>
                        </div>
                    </div>
                    <!-- /.search-area -->
                </div>
                <!-- /.top-search-holder -->
                <div class="col-lg-3 col-md-3 col-sm-12 col-xs-12 animate-dropdown top-cart-row">
                    <div class="compare_widget">
                        <a href="#" id="cart"><i class="fa fa-balance-scale"></i> Compare List <span
                                    class="badge">{{session()->has('compare_products') ? count(session()->get('compare_products')) : 0}}</span></a>
                        @if(session()->has('compare_products'))
                            <div class="shopping-cart">
                                <div class="shopping-cart-header">
                                    <h3>Products to Compare</h3>
                                </div>
                                <ul class="shopping-cart-items">
                                    @forelse(session()->get('compare_products') as $product)
                                        <li class="clearfix">
                                            <a href="{{route('product_show', $product['slug'])}}" target="_blank">
                                                <img src="{{asset('files/23/Photos/Products/').'/'.$product['manufacturer_key'].'/'.$product['main_image']}}"
                                                     alt="{{$product['name']}}" width="50px"/>
                                                <span class="item-name text-muted">{{$product['name']}}</span>
                                                <span class="item-detail text-muted">{{$product['product_code']}}</span>
                                            </a>
                                            <span class="remove_compare">
                                                 <a href="{{route('remove_compare',$product['id'])}}"><i
                                                             class="fa fa-close"></i></a>
                                            </span>
                                        </li>
                                    @empty
                                        <li class="clearfix">
                                            <p class="text-muted">No Product To Compare!</p>
                                        </li>
                                    @endforelse
                                </ul>
                                @if(count(session()->get('compare_products')))
                                    <div class="product_buttons">
                                        <a href="{{route('product_compare')}}" class="btn btn-primary icon pull-right">Compare
                                            Products</a>
                                    </div>
                                @endif
                            </div> <!--end shopping-cart -->
                        @else
                            <div class="shopping-cart">
                                <div class="shopping-cart-header">
                                    <h3>Products to Compare</h3>
                                </div>
                                <ul class="shopping-cart-items">
                                    <li class="clearfix">
                                        <p class="text-muted">No Product To Compare!</p>
                                    </li>
                                </ul>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
            <!-- /.row -->
        </div>
        <!-- /.container -->
    </div>

    <!-- /.main-header -->
    <div class="header-nav animate-dropdown">
        <div class="container">
            <div class="row">
                <nav class="navbar navbar-expand-lg">
                    <button class="navbar-toggler" type="button" data-toggle="collapse"
                            data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent"
                            aria-expanded="false" aria-label="Toggle navigation">
                        <span class="navbar-toggler-icon"><i class="fa fa-bars fa-lg" aria-hidden="true"
                                                             title="Toggle navigation"></i></span>
                    </button>
                    <div class="collapse navbar-collapse" id="navbarSupportedContent">
                        <ul style="width: auto">
                            <li class="nav-item dropdown">
                                <a class="badge badge-primary pl-3 pr-3" href="{{route('page','what-s-new')}}">What's
                                    New</a>
                            </li>

                        </ul>
                        <ul style="width: auto" class="mr-3 ml-3">
                            <li class="nav-item dropdown">
                                <a class="badge badge-dark pl-3 pr-3" href="#"
                                   id="a_to_z_dropdown">A - Z</a>
                                <div class="dropdown-menu a_to_z_dropdown"
                                     aria-labelledby="a_to_z_dropdown">
                                    <div class="a_to_z_menu">
                                        <div class="a_to_z_wrapper">
                                            @foreach($a_to_z as $key => $category)
                                                <ul>
                                                    <li>
                                                        <strong> {{$key}}</strong>
                                                    </li>
                                                    @foreach($category as $value)
                                                        <li class="nav-item">
                                                            <a class="nav-link"
                                                               href="{{config('app.url').'/category/'.seoUrl($value)}}">{{$value}}</a>
                                                        </li>
                                                    @endforeach
                                                </ul>
                                            @endforeach
                                        </div>
                                    </div>
                                </div>
                            </li>
                        </ul>
                        <ul>
                            @foreach($parent_categories as $parent_category)
                                <li class="nav-item dropdown">
                                    <a href="{{config('app.url').'/category/'.$parent_category['slug']}}"
                                       id="navbarDropdown">
                                        {{$parent_category['name']}}
                                    </a>
                                    <div class="dropdown-menu {{$parent_category['slug']}}"
                                         aria-labelledby="navbarDropdown">
                                        <div class="main_menu">
                                            <div class="menu_wrapper">
                                                @foreach($parent_category['sub_category'] as $category)
                                                    <div class="sub_menu_wrapper">
                                                        <a href="{{config('app.url').'/category/'.$category['slug']}}"><span>{{$category['name']}}</span></a>
                                                        <ul class="sub_sub_menu_wrapper">
                                                            @foreach($category['sub_category'] as $subcategory)
                                                                <li class="nav-item">
                                                                    <a class="nav-link"
                                                                       href="{{config('app.url').'/category/'.$subcategory['slug']}}">{{$subcategory['name']}}</a>
                                                                </li>
                                                            @endforeach
                                                        </ul>
                                                    </div>
                                            @endforeach
                                            <!-- /.col-md-4  -->
                                            </div>
                                        </div>
                                        <!--  /.container  -->
                                    </div>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                </nav>
            </div>
        </div>
        <!-- /.container-class -->
    </div>
    <!-- /.header-nav -->
</header>