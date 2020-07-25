@extends('front.layouts.app')
@section('title')Product Search @endsection
@section('styles')
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/instantsearch.css@7.3.1/themes/reset-min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/instantsearch.css@7.3.1/themes/algolia-min.css">
@endsection
@section('content')
    <div class="body-content outer-top-xs">
        <div class='container'>
            <div class='row'>
                <!-- /.sidebar -->
                <div class="col-xs-12 col-sm-12 col-md-12">
                    <div id="category" class="category-carousel hidden-xs">
                        <div class="item live_search_bar">
                            <div id="search-box"></div>
                            <div class="filter_label">Categories</div>
                            <div id="refinement-list">
                                <!-- RefinementList widget will appear here -->
                            </div>
                            <div class="filter_label">Price Range</div>
                            <div id="range_slider"></div>
                            <div class="filter_label">Primary Colors</div>
                            <div id="refinement-list-color">
                                <!-- RefinementList widget will appear here -->
                            </div>
                            <div id="stats-container"></div>
                            <div id="clear-refinements"></div>
                        </div>
                    </div>

                    <div class="search-result-container ">
                        <div class="tab-content category-list">
                            <div class="tab-pane active " id="grid-container">
                                <div class="category-product">
                                    <div class="clearfix filters-container m-t-10">
                                        <div class="row">
                                            <div class="col col-sm-12 col-md-12 col-xs-12 col-lg-12 text-right">
                                                <div class="pagination-container">
                                                    <div id="pagination">
                                                        <!-- Pagination widget will appear here -->
                                                    </div>
                                                    <!-- /.list-inline -->
                                                </div>
                                                <!-- /.pagination-container -->
                                            </div>
                                            <!-- /.col -->
                                        </div>
                                        <!-- /.row -->
                                    </div>
                                    <div class="row">
                                        <div id="hits">
                                            <!-- Hits widget will appear here -->
                                        </div>
                                        <!-- /.item -->
                                    </div>
                                    <!-- /.row -->
                                    <div class="clearfix filters-container bottom-row">
                                        <div class="text-right">
                                            <div class="pagination-container">
                                                <div>
                                                    <div id="pagination2">
                                                        <!-- Pagination widget will appear here -->
                                                    </div>
                                                </div>
                                                <!-- /.list-inline -->
                                            </div>
                                            <!-- /.pagination-container -->
                                        </div>
                                        <!-- /.text-right -->
                                    </div>
                                </div>
                                <!-- /.category-product -->
                            </div>
                            <!-- /.tab-pane -->
                        </div>
                        <!-- /.tab-content -->
                    </div>
                    <!-- /.search-result-container -->
                </div>
                <!-- /.col -->
            </div>
        </div><!-- /.container -->
    </div>

    @include('front.partials.recently_viewed')
@endsection
@section('scripts')
    <script>
        (function () {

            const searchClient = algoliasearch('6IYRI1SY5H', '6fe68e3d7ee94b5e5a226d7e430bec1b');

            const search = instantsearch({
                searchClient,
                indexName: 'products',
                urlSync: true,
                routing: true
            });
            search.addWidgets([
                instantsearch.widgets.hits({
                    container: '#hits',
                    templates: {
                        empty: 'No Product Found!',
                        item: function (item) {


                            const markup = `<div>
                                                <div class="item">
                                                    <div class="products">
                                                        <div class="product">
                                                            <div class="product-image">
                                                                <div class="image">
                                                                    <a href="{{config("app.url")}}/product/${item.slug}">
                                                                        <img src="{{config("app.url")}}/public/files/23/Photos/Products/${item.manufacturer_key}/${item.main_image}" alt="${item.name}">
                                                                    </a>
                                                                </div>
                                                </div>
                                                <div class="product-info text-left">
                                                     <h3 class="name text-muted"><a href="{{config("app.url")}}/product/${item.slug}">${item.name}</a>
                                                                </h3>
                                                                <div class="rating rateit-small"></div>
                                                                <div class="description description text-muted">${item.short_desc}</div>
                                                                <div class="product-price">
                                                                    <span class="price text-muted">
                                                                        <span>from </span>
                                                                        <strong>${item.min_price}</strong>
                                                                        <span> to </span>
                                                                        <strong>$${item.max_price}</strong>
                                                                         <p class="text-center text-muted">${item.min_quantity} Min quantity</p>
                                                                    </span>
                                                                </div>
                                                            <div class="product_buttons">
                                                                <a class="btn btn-primary icon"
                                                                   href="{{config("app.url")}}/product/${item.slug}">View Details</a>
                                                                   <a class="btn btn-primary icon"
                                                                       href="{{config("app.url")}}/compare/add/${item.id}">Compare</a>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>`;
                            return markup;
                        }
                    }
                })
            ]);

            // initialize SearchBox
            search.addWidget(
                instantsearch.widgets.searchBox({
                    container: '#search-box',
                    placeholder: 'Search for products'
                }),
            );

            // initialize pagination
            search.addWidget(
                instantsearch.widgets.pagination({
                    container: '#pagination',
                    maxPages: 20,
                    scrollTo: false,
                }),
            );

            // initialize pagination
            search.addWidget(
                instantsearch.widgets.pagination({
                    container: '#pagination2',
                    maxPages: 20,
                    scrollTo: false,
                }),
            );

            search.addWidget(
                instantsearch.widgets.stats({
                    container: '#stats-container'
                }),
            );

            //initialize RefinementList
            search.addWidget(
                instantsearch.widgets.refinementList({
                    container: '#refinement-list',
                    attribute: 'categories',
                    showMore: true,
                    showMoreLimit: 500
                }),
            );

            //initialize RefinementList
            search.addWidget(
                instantsearch.widgets.refinementList({
                    container: '#refinement-list-color',
                    attribute: 'primary_colors',
                    showMore: true,
                    showMoreLimit: 100
                }),
            );

            // initialize RefinementList
            search.addWidget(
                instantsearch.widgets.rangeSlider({
                    container: "#range_slider",
                    attribute: "min_price",
                    precision: 2,
                    tooltips: true,
                    step: .01,
                }),
            );

            search.addWidget(
                instantsearch.widgets.clearRefinements({
                    container: '#clear-refinements',
                })
            );

            search.start();
        })();
    </script>

@endsection

