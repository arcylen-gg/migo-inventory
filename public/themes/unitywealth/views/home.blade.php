@extends("layout")
@section("content")
<div class="content">
    <!-- Media Slider -->
    <div id="home" class="slider-wrapper" style="background-image: url('{{ get_content($shop_theme_info, "home", "home_banner") }}')">
        <div class="container">
            <div class="caption-logo-container"><img src="{{ get_content($shop_theme_info, "home", "home_banner_image_logo") }}"></div>
            <div class="caption-container">
                {{-- <p>Lorem ipsum dolor sit amet, consectetuer adipiscing elit.</p> --}}
            </div>
            <a href="#company-profile" class="smoth-scroll"><div class="btn-container animated fadeInDown">SHOW MORE</div></a>
            <div class="scroll-down animated fadeInDown"><i class="fa fa-chevron-down" aria-hidden="true"></i></div>
            <!-- COMPANY PROFILE -->
            <div id="company-profile" class="wrapper-2">
                <div class="container">
                    <div class="row clearfix">
                        <div class="col-md-6">
                            <div class="content-details">
                                <p class="title wow fadeInLeft" data-wow-delay=".1s">COMPANY PROFILE</p>
                                <p class="desc wow fadeInLeft" data-wow-delay=".2s">
                                    {{ get_content($shop_theme_info, "home", "home_company_profile") }} 
                                </p>
                                <p class="title-2 wow fadeInLeft" data-wow-delay=".3s">MISSION</p>
                                <p class="desc wow fadeInLeft" data-wow-delay=".4s">
                                    {{ get_content($shop_theme_info, "home", "home_company_mission") }} 
                                </p>
                                <p class="title-2 wow fadeInLeft" data-wow-delay=".5s">VISION</p>
                                <p class="des wow fadeInLeft" data-wow-delay=".6s">
                                    {{ get_content($shop_theme_info, "home", "home_company_vision") }} 
                                </p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="img-container wow fadeInRight">
                                <img src="{{ get_content($shop_theme_info, "home", "home_company_profile_image") }}">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    {{-- <div class="slider-wrapper" style="background-image: url('/themes/{{ $shop_theme }}/img/countdown-img.jpg')">
        <div class="container">
            <div class="row">
                <div class="col-sm-12 col-xs-12">
                    <div class="logo wow fadeInDown">
                        <img class="top-logo" style="width: 30%;" src="/themes/{{ $shop_theme }}/img/count-down-logo.png">
                    </div>
                    <!-- <h2 class="wow fadeInLeftBig">We Are Updating</h2> -->
                    <div class="wow fadeInDown" style="padding-top: 20px;">
                        <img class="product-img" style="width: 25%;" src="/themes/{{ $shop_theme }}/img/product.png">
                    </div>
                    <div style="margin-top: 10px;" class="timer wow fadeInUp">
                        <div class="days-wrapper">
                            <span class="days"></span> <br> <span style="color: #00a4e6;">days</span>
                        </div>
                        <span class="slash">/</span>
                        <div class="hours-wrapper">
                            <span class="hours"></span> <br> <span style="color: #00a4e6;">hours</span> 
                        </div>
                        <span class="slash">/</span>
                        <div class="minutes-wrapper">
                            <span class="minutes"></span> <br> <span style="color: #00a4e6;">minutes</span> 
                        </div>
                        <span class="slash">/</span>
                        <div class="seconds-wrapper">
                            <span class="seconds"></span> <br> <span style="color: #00a4e6;">seconds</span>
                        </div>
                    </div>
                    <div class="wow fadeInLeftBig">
                        <p style="color: #fff; font-size: 21px; padding-top: 20px;">
                            We are working very hard on the new version of our site
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div> --}}
    <!-- SCROLL TO TOP -->
    <div class="scroll-up"><img src="/themes/{{ $shop_theme }}/img/scroll-up.png"></div>
</div>
@endsection

@section("css")
<link rel="stylesheet" type="text/css" href="/themes/{{ $shop_theme }}/css/home.css">
@endsection

@section("script")
<script type="text/javascript">
$(document).ready(function($) {
    
        /*TEXT FADEOUT*/
        $(window).scroll(function(){
            $(".caption-container, .caption-logo-container").css("opacity", 1 - $(window).scrollTop() / 250);
        });

        //START MISSION AND VISION
        $(".title-vision").click(function()
        {
            $("#vision").removeClass("hide");
            $("#mission").addClass("hide");
            $(".title-vision").addClass("highlighted");
            $(".title-mission").removeClass("highlighted");
            
        });
        
        $(".title-mission").click(function()
        {
            $("#vision").addClass("hide");
            $("#mission").removeClass("hide");
            $(".title-mission").addClass("highlighted");
            $(".title-vision").removeClass("highlighted");
        });
        //END MISSION ANF VISION
});
</script>

@endsection