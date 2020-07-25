<div class="clients_wrapper">
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="detail-block">
                    <div class="our_clients_heading">
                        <h1>Our Clients</h1>
                        <h4>Here are just a few of the hundreds of companies that trust <strong>Brandable for their
                                promotional products</strong>
                        </h4>
                    </div>
                    <div class="tab-content outer-top-xs">
                        <div class="product-slider">
                            <div class="owl-carousel home-owl-carousel custom-carousel owl-theme">
                                @forelse($clients as $client)
                                    <div class="logo-item">
                                        <a href="{{$client['link']}}" target="_blank">
                                            <img src="{{asset('files/23/Photos/Client/')}}/{{$client['grey_image']}}"
                                                 alt="{{$client['name']}}" class="grey">
                                            <img src="{{asset('files/23/Photos/Client/')}}/{{$client['colored_image']}}"
                                                 alt="{{$client['name']}}" class="colored">
                                        </a>
                                    </div>
                                @empty
                                @endforelse
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="row our-features-box">
    <div class="container">
        <ul>
            <li>
                <div class="feature-box">
                    <div class="icon-truck"></div>
                    <div class="content-blocks">We ship worldwide</div>
                </div>
            </li>
            <li>
                <div class="feature-box">
                    <div class="icon-support"></div>
                    <div class="content-blocks">call
                        +1 800 789 0000
                    </div>
                </div>
            </li>
            <li>
                <div class="feature-box">
                    <div class="icon-money"></div>
                    <div class="content-blocks">Money Back Guarantee</div>
                </div>
            </li>
            <li>
                <div class="feature-box">
                    <div class="icon-return"></div>
                    <div class="content">30 days return</div>
                </div>
            </li>
        </ul>
    </div>
</div>
<footer id="footer" class="footer color-bg">
    <div class="footer-bottom">
        <div class="container">
            <div class="row">
                <div class="col-xs-12 col-sm-6 col-md-3">
                    <div class="address-block">
                        <!-- /.module-heading -->
                        <div class="module-body">
                            <ul class="toggle-footer" style="">
                                <li class="media">
                                    <div class="pull-left"><span class="icon fa-stack fa-lg"> <i class="fa fa-map-marker fa-stack-1x fa-inverse"></i> </span></div>
                                    <div class="media-body">
                                        <p>Brandable Pty Ltd., 789 Main rd, Anytown, CA 12345 USA</p>
                                    </div>
                                </li>
                                <li class="media">
                                    <div class="pull-left">
                                        <span class="icon fa-stack fa-lg"> <i class="fa fa-mobile fa-stack-1x fa-inverse"></i>
                                        </span>
                                    </div>
                                    <div class="media-body">
                                        <p>+(888) 123-4567<br>
                                            +(888) 456-7890
                                        </p>
                                    </div>
                                </li>
                                <li class="media">
                                    <div class="pull-left"><span class="icon fa-stack fa-lg"> <i class="fa fa-envelope fa-stack-1x fa-inverse"></i> </span></div>
                                    <div class="media-body"><span><a href="#">marazzo@themesground.com</a></span></div>
                                </li>
                            </ul>
                        </div>
                    </div>
                    <!-- /.module-body -->
                </div>
                <!-- /.col -->

                <div class="col-xs-12 col-sm-6 col-md-3">
                    <div class="module-heading">
                        <h4 class="module-title">Customer Service</h4>
                    </div>
                    <!-- /.module-heading -->

                    <div class="module-body">
                        <ul class='list-unstyled'>
                            <li class="first"><a href="#" title="Contact us">My Account</a></li>
                            <li><a href="#" title="About us">Order History</a></li>
                            <li><a href="#" title="faq">FAQ</a></li>
                            <li><a href="#" title="Popular Searches">Specials</a></li>
                            <li class="last"><a href="#" title="Where is my order?">Help Center</a></li>
                        </ul>
                    </div>
                    <!-- /.module-body -->
                </div>
                <!-- /.col -->

                <div class="col-xs-12 col-sm-6 col-md-3">
                    <div class="module-heading">
                        <h4 class="module-title">Corporation</h4>
                    </div>
                    <!-- /.module-heading -->

                    <div class="module-body">
                        <ul class='list-unstyled'>
                            <li class="first"><a title="Your Account" href="#">About us</a></li>
                            <li><a title="Information" href="#">Customer Service</a></li>
                            <li><a title="Addresses" href="#">Company</a></li>
                            <li><a title="Addresses" href="#">Investor Relations</a></li>
                            <li class="last"><a title="Orders History" href="#">Advanced Search</a></li>
                        </ul>
                    </div>
                    <!-- /.module-body -->
                </div>
                <!-- /.col -->

                <div class="col-xs-12 col-sm-6 col-md-3">
                    <div class="module-heading">
                        <h4 class="module-title">Why Choose Us</h4>
                    </div>
                    <!-- /.module-heading -->

                    <div class="module-body">
                        <ul class='list-unstyled'>
                            <li class="first"><a href="#" title="About us">Shopping Guide</a></li>
                            <li><a href="#" title="Blog">Blog</a></li>
                            <li><a href="#" title="Company">Company</a></li>
                            <li><a href="#" title="Investor Relations">Investor Relations</a></li>
                            <li class=" last"><a href="contact-us.html" title="Suppliers">Contact Us</a></li>
                        </ul>
                    </div>
                    <!-- /.module-body -->
                </div>
            </div>
        </div>
    </div>
    <div class="copyright-bar">
        <div class="container">
            <div class="row">
                <div class="col-md-6 no-padding social">
                    <ul class="link">
                        @if ($site_data)
                            <li class="fb pull-left"><a target="_blank" rel="nofollow" href="{{$site_data['data']['site_facebook']}}" title="Facebook"></a></li>
                            <li class="tw pull-left"><a target="_blank" rel="nofollow" href="{{$site_data['data']['site_twitter']}}" title="Twitter"></a></li>
                            <li class="linkedin pull-left"><a target="_blank" rel="nofollow" href="{{$site_data['data']['site_linkedin']}}" title="Linkedin"></a></li>
                        @endif
                    </ul>
                </div>
                <div class="col-md-6 no-padding copyright text-right" >&copy; {{ now()->year }} Brandable Pty Ltd. All Rights Reserved.
                </div>
            </div>
        </div>
    </div>
</footer>
{!! NoCaptcha::renderJs() !!}
<!-- JavaScripts placed at the end of the document so the pages load faster -->
<script src="{{asset('front/assets/js/app.js')}}"></script>

<script>
    (function () {
        const client = algoliasearch("6IYRI1SY5H", "6fe68e3d7ee94b5e5a226d7e430bec1b");
        const products = client.initIndex('products');
        var enterPressed = false;
        autocomplete('#aa-search-input', {hint: false}, [
            {
                source: autocomplete.sources.hits(products, {hitsPerPage: 20}),
                displayKey: 'name',
                templates: {
                    suggestion: function (suggestion) {
                        const markup = `<div class="algolia-result">
                            <span>
                            <img src="{{config('app.url')}}/files/23/Photos/Products/${suggestion.manufacturer_key}/${suggestion.main_image}" class="algolia-thumb">
                                ${suggestion._highlightResult.name.value}
                            </span>
                        </div>
                        <div class="algolia-details">
                            <span>${suggestion._highlightResult.product_code.value}</span>`;
                        return markup;
                    },
                    empty: function (result) {
                        return 'Sorry, we did not find any results for "' + result.query + '"';
                    }
                }
            }
        ]).on('autocomplete:selected', function (event, suggestion, dataset) {
            window.location.href = '{{config('app.url')}}/product/' + suggestion.slug;
            enterPressed = true;
        }).on('keyup', function (event) {
            if (event.keyCode == 13 && !enterPressed) {
                window.location.href = '{{config('app.url')}}/products/search?products%5Bquery%5D=' + document.getElementById('aa-search-input').value;
            }
        });
    })();
</script>
<script>
    $(function () {
        $('[data-toggle="tooltip"]').tooltip()
    })
</script>
@yield('scripts')
</body>
</html>
