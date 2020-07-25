<!-- /.side-menu -->
<!-- ================================== TOP NAVIGATION : END ================================== -->
<div class="sidebar-module-container">
    <div class="sidebar-filter">
        <!-- ============================================== SIDEBAR CATEGORY ============================================== -->
        <div class="sidebar-widget">
            <h3 class="section-title">Shop by</h3>
            <div class="widget-header">
                <h4 class="widget-title">Category</h4>
            </div>
            <div class="sidebar-widget-body">
                @foreach($parent_categories as $parent_category)
                    <div class="accordion">
                        <div class="accordion-group">
                            <div class="accordion-heading"><a href="#{{$parent_category['slug']}}" data-toggle="collapse" class="accordion-toggle collapsed"> {{$parent_category['name']}} </a>
                            </div>
                            <!-- /.accordion-heading -->
                            <div class="accordion-body collapse" id="{{$parent_category['slug']}}" style="height: 0px;">
                                <div class="accordion-inner">
                                    <ul>
                                        @foreach($parent_category['sub_category'] as $category)
                                            <li><a href="{{config('app.url').'/category/'.$category['slug']}}">{{$category['name']}}</a>
                                                <ul>
                                                    @foreach($category['sub_category'] as $subcategory)
                                                    <li><a href="{{config('app.url').'/category/'.$subcategory['slug']}}">{{$subcategory['name']}}</a></li>
                                                    @endforeach
                                                </ul>
                                            </li>
                                        @endforeach
                                    </ul>
                                </div>
                                <!-- /.accordion-inner -->
                            </div>
                            <!-- /.accordion-body -->
                        </div>
                        <!-- /.accordion-group -->
                    </div>
                    <!-- /.accordion -->
                @endforeach
            </div>
            <!-- /.sidebar-widget-body -->
        </div>
        <!-- /.sidebar-widget -->
        <!-- ============================================== SIDEBAR CATEGORY : END ============================================== -->

        <!-- /.Testimonials -->
        <div class="sidebar-widget  outer-top-vs ">
            <div id="advertisement" class="advertisement">
                <div class="item">
                    <div class="avatar"><img src="{{asset('front/assets/images/testimonials/member1.png')}}"></div>
                    <div class="testimonials"><em>"</em> Vtae sodales aliq uam morbi non sem lacus port
                        mollis. Nunc condime tum metus eud molest sed consectetuer. Sed quia non numquam
                        eius modi tempora incidunt ut labore et dolore magnam aliquam quaerat.<em>"</em>
                    </div>
                    <div class="clients_author">John Doe <span>Abc Company</span></div>
                    <!-- /.container-fluid -->
                </div>
                <!-- /.item -->

                <div class="item">
                    <div class="avatar"><img src="{{asset('front/assets/images/testimonials/member3.png')}}" alt="Image"></div>
                    <div class="testimonials"><em>"</em>Vtae sodales aliq uam morbi non sem lacus port
                        mollis. Nunc condime tum metus eud molest sed consectetuer. Sed quia non numquam
                        eius modi tempora incidunt ut labore et dolore magnam aliquam quaerat.<em>"</em>
                    </div>
                    <div class="clients_author">Stephen Doe <span>Xperia Designs</span></div>
                </div>
                <!-- /.item -->

                <div class="item">
                    <div class="avatar"><img src="{{asset('front/assets/images/testimonials/member2.png')}}" alt="Image"></div>
                    <div class="testimonials"><em>"</em>Vtae sodales aliq uam morbi non sem lacus port
                        mollis. Nunc condime tum metus eud molest sed consectetuer. Sed quia non numquam
                        eius modi tempora incidunt ut labore et dolore magnam aliquam quaerat.<em>"</em>
                    </div>
                    <div class="clients_author">Saraha Smith <span>Datsun &amp; Co</span></div>
                    <!-- /.container-fluid -->
                </div>
                <!-- /.item -->

            </div>
            <!-- /.owl-carousel -->
        </div>
        <!-- ============================================== Testimonials: END ============================================== -->
    </div>
    <!-- /.sidebar-filter -->
</div>
<!-- /.sidebar-module-container -->
