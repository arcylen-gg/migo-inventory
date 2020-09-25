@extends("layout")
@section("content")

<div class="content">
    
    <!-- Media Slider -->
    <div class="fullscreen background parallax top-container" style="background-image: url('{{ get_content($shop_theme_info, "home", "home_banner") }}');" data-img-width="1600" data-img-height="1139" data-diff="100">
        <div class="container">
            <div class="caption-logo-container"><img src="{{ get_content($shop_theme_info, "home", "home_banner_caption1") }}"></div>
            <div class="caption-container animated fadeInDown">
                <h1>{{ get_content($shop_theme_info, "home", "home_banner_caption2") }}</h1>
            </div>
        </div>
        <div class="scroll-down"><img src="/themes/{{ $shop_theme }}/img/scroll-down.png"></div>
    </div>
    <section class="brand-container">
        <div class="container">
            <div class="title">Our <span>Brand</span></div>
            <div class="sub">{!!get_content($shop_theme_info, "home", "home_our_brand_context") !!}</div>
            <div class="brand-row">
                <div class="brand-holder">
                    <div class="img"><img src="{{ get_content($shop_theme_info, "home", "home_our_brand_icon1") }}"></div>
                    <div class="name a">{{ get_content($shop_theme_info, "home", "home_our_brand_icon1_title") }}</div>
                </div>
                <div class="brand-holder">
                    <div class="img"><img src="{{ get_content($shop_theme_info, "home", "home_our_brand_icon2") }}"></div>
                    <div class="name b">{{ get_content($shop_theme_info, "home", "home_our_brand_icon2_title") }}</div>
                </div>
                <div class="brand-holder">
                    <div class="img"><img src="{{ get_content($shop_theme_info, "home", "home_our_brand_icon3") }}"></div>
                    <div class="name c">{{ get_content($shop_theme_info, "home", "home_our_brand_icon3_title") }}</div>
                </div>
                <div class="brand-holder">
                    <div class="img"><img src="{{ get_content($shop_theme_info, "home", "home_our_brand_icon4") }}"></div>
                    <div class="name d">{{ get_content($shop_theme_info, "home", "home_our_brand_icon4_title") }}</div>
                </div>
                <div class="brand-holder">
                    <div class="img"><img src="{{ get_content($shop_theme_info, "home", "home_our_brand_icon5") }}"></div>
                    <div class="name e">{{ get_content($shop_theme_info, "home", "home_our_brand_icon5_title") }}</div>
                </div>
            </div>
        </div>
    </section>
    <section class="opportunity-container" style="background-image: url('/themes/{{ $shop_theme }}/img/center-bg.jpg');">
        <div class="container">
            <div class="title">Your Opportunity</div>
            <div class="sub">{!! get_content($shop_theme_info, "home", "home_your_opportunity_context") !!}</div>
            <div class="row clearfix">
                <div class="col-md-4 col-sm-6">
                    <div class="opportunity-holder">
                        <div class="title"><img src="/themes/{{ $shop_theme }}/img/icon/cloud.png"> Downloadable Forms</div>
                        <ul>
                            <li><a target="_blank" href="javascript:">SHIFT Distributor Application Form</a></li>
                            <li><a target="_blank" href="javascript:">ID Application Form</a></li>
                            <li><a target="_blank" href="javascript:">Password Reset / Retreival Form</a></li>
                            <li><a target="_blank" href="javascript:">Spelling Correction Form</a></li>
                            <li><a target="_blank" href="javascript:">SHIFT Cares Claim Form</a></li>
                        </ul>
                    </div>
                </div>
                <div class="col-md-4 col-sm-6">
                    <div class="opportunity-holder">
                        <div class="title"><img src="/themes/{{ $shop_theme }}/img/icon/powerpoint.png"> Shift Business Presentation</div>
                        <ul>
                            <li><a target="_blank" href="javascript:">SHIFT Business Presentation</a></li>
                        </ul>
                        <div class="title" style="margin-top: 60px;">Shift Cellcare Presentation</div>
                        <ul>
                            <li><a target="_blank" href="javascript:">CELLCARE Complete Presentation</a></li>
                        </ul>
                    </div>
                </div>
                <div class="col-md-4 col-sm-12">
                    <div class="opportunity-holder">
                        <div class="title">
                            {{-- <img src="/themes/{{ $shop_theme }}/img/icon/cup.png"> --}}
                            Leadership Bonus Withdrawal Form
                        </div>
                        <div class="sub">Steps to withdraw your leadership bonus:</div>
                        <ol>
                            <li>Please download this form Payout Request Form.</li>
                            <li>Fill up the information required.</li>
                            <li>Submit to management@myshiftbusiness.com</li>
                            <li>Cut-off is every Sunday and releasing of checks is on Friday.</li>
                        </ol>
                        <div class="sub">Please take note that Business Centers outside of Cebu will receive payout deliveries on Monday via LBC or AP Cargo.</div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <section class="belief-container">
        <div class="container">
            <div class="row clearfix">
                <div class="col-md-6">
                    <div class="title">{{ get_content($shop_theme_info, "home", "home_division3_title") }}</div>
                    <div class="desc">{!! get_content($shop_theme_info, "home", "home_division3_context") !!}</div>
                    <div class="btn-container">
                        <button class="btn btn-primary">Join Us Today</button>
                    </div>
                </div>
                <div class="col-md-6">
                    <img class="img-responsive" src="{{ get_content($shop_theme_info, "home", "home_division3_image") }}">
                </div>
            </div>
        </div>
    </section>
    <section class="brand2-container">
        <div class="container">
            <div class="title">Our <span>Brand</span></div>
            <div class="row clearfix">
                <div class="col-md-6">
                    <img class="img-responsive" src="/themes/{{ $shop_theme }}/img/brand.jpg">
                </div>
                <div class="col-md-6">
                    <div class="info">
                        <div class="title">Cellcare Health Supplement</div>
                        <div class="desc">Lorem ipsum dolor sit amet, consectetuer adipiscing elit. Aenean commodo ligula eget dolor. Aenean massa. Cum sociis natoque penatibus et magnis dis parturient montes, nascetur ridiculus mus. Nullam dictum felis eu pede mollis pretium. Integer tincidunt. Cras dapibus. Vivamus elementum semper nisi. </div>
                        <div class="btn-container">
                            <div class="btn btn-primary">Shop Now</div> 
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    
    <!-- SCROLL TO TOP -->
    <div class="scroll-up"><img src="/themes/{{ $shop_theme }}/img/scroll-up.png"></div>

</div>

@endsection

@section("css")
<link rel="stylesheet" type="text/css" href="/themes/{{ $shop_theme }}/css/home.css">
@endsection

@section("script")
<script type="text/javascript" src="/themes/{{ $shop_theme }}/js/home.js"></script>
@endsection