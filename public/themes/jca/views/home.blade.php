@extends("layout")
@section("content")
<div class="content">
<!-- Media Slider -->
    <div class="slider-wrapper" style="background-image: url('{{ get_content($shop_theme_info, "home", "home_banner") }}')">
        <div class="container">
            <div class="caption-logo-container"><img src="/themes/{{ $shop_theme }}/img/logo-caption2.png"></div>
            <div class="caption-container animated fadeInDown">
                <p class="head-text">{{ get_content($shop_theme_info, "home", "home_banner_caption1") }}</p>
                <p class="head-text">{{ get_content($shop_theme_info, "home", "home_banner_caption2") }}</p>
            </div>
        </div>
    </div>
    <!-- WHO WE ARE -->
    <div id="aboutus" class="wrapper-1">
        <div class="container">
            <div class="wow fadeInDown title-container">
                <span>Who</span>
                <span>Are We</span>
            </div>
            <div class="content-container row clearfix">
                <div class="col-md-6">
                    <div class="content-title wow fadeInUp">{{ get_content($shop_theme_info, "home", "home_who_are_we_title") }}</div>
                    <div class="context wow fadeInLeft">
                        {!! get_content($shop_theme_info, "home", "home_who_are_we_context") !!}
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="image-container wow fadeInRight">
                        <img src="{{ get_content($shop_theme_info, "home", "home_who_are_we_image") }}">
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- WHY JCA -->
    <div class="wrapper-2">
        <div class="container">
            <div class="title-container">
                <div class="wow fadeInDown title-container">
                    <span>Why</span>
                    <span>JCA Wellness International</span>
                </div>
            </div>
            <div class="content-container row clearfix">
                <!-- WE BELIEVE -->
                <div class="col-md-4">
                    <div class="per-image-container">
                        <img src="/themes/{{ $shop_theme }}/img/we-believe.png">
                        <div class="content-text-container">
                            <h1 class="wow fadeInLeft" data-wow-delay=".1s">{{ get_content($shop_theme_info, "home", "home_why_jca_wellness_title1") }}</h1>
                            <div class="title-line"></div>
                            <p class="wow fadeInLeft" data-wow-delay=".3s">
                                {{ get_content($shop_theme_info, "home", "home_why_jca_context1") }}
                            </p>
                        </div>
                    </div>
                </div>
                <!-- WE ARE SAFE -->
                <div class="col-md-4">
                    <div class="per-image-container">
                        <img src="/themes/{{ $shop_theme }}/img/we-are-safe.png">
                        <div class="content-text-container">
                            <h1 class="wow fadeInLeft" data-wow-delay=".2s">{{ get_content($shop_theme_info, "home", "home_why_jca_wellness_title2") }}</h1>
                            <div class="title-line"></div>
                            <p class="wow fadeInLeft" data-wow-delay=".4s">
                                {{ get_content($shop_theme_info, "home", "home_why_jca_context2") }}
                            </p>
                        </div>
                    </div>
                </div>
                <!-- WE AIM -->
                <div class="col-md-4">
                    <div class="per-image-container">
                        <img src="/themes/{{ $shop_theme }}/img/we-aim.png">
                        <div class="content-text-container">
                            <h1 class="wow fadeInLeft" data-wow-delay=".3s">{{ get_content($shop_theme_info, "home", "home_why_jca_wellness_title3") }}</h1>
                            <div class="title-line"></div>
                            <p class="wow fadeInLeft" data-wow-delay=".5s">
                                {{ get_content($shop_theme_info, "home", "home_why_jca_context3") }}
                            </p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="jca-title-container">
                <p class="main-title wow fadeInDown" data-wow-delay=".2s"><font class="shade-green">Benefits In</font> JCA Wellness International</p>
                <p class="sub-title wow fadeInDown" data-wow-delay=".4s">
                    {{ get_content($shop_theme_info, "home", "home_benefits_subtitle") }}
                </p>
            </div>
            <div class="bulleted-list-container">
                <div class="content-container row clearfix">
                    <div class="col-md-4">
                        <ul>
                            <li class="liststyle wow animated fadeInLeft" data-wow-delay=".1s">
                                <p class="content">
                                   {{ get_content($shop_theme_info, "home", "home_benefits_context1") }}
                                </p>
                            </li>
                        </ul>
                    </div>
                    <div class="col-md-4">
                        <ul>
                            <li class="liststyle wow animated fadeInLeft" data-wow-delay=".2s">
                                <p class="content">
                                   {{ get_content($shop_theme_info, "home", "home_benefits_context2") }}
                                </p>
                            </li>
                        </ul>
                    </div>
                    <div class="col-md-4">
                        <ul>
                            <li class="liststyle wow animated fadeInLeft" data-wow-delay=".3s">
                                <p class="content">
                                   {{ get_content($shop_theme_info, "home", "home_benefits_context3") }}
                                </p>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
            <div class="button-container">
                <a href="{{ get_content($shop_theme_info, "home", "home_legalities") }}" class="lsb-preview">
                    <button class="legalities-button wow fadeInUp">
                        <img class="button-img" src="/themes/{{ $shop_theme }}/img/legalities-icon-button.png">
                        <p class="button-name">LEGALITIES</p>
                    </button>
                </a>
            </div>
        </div>
    </div>
    <!-- VISION MISION -->
    <div id="mission-vision" class="wrapper-3" style="background-image: url('/themes/{{ $shop_theme }}/img/mission-vision-background.png');">
        <div class="container">
                <div class="content-container row clearfix">    
                    <div class="col-md-8">
                        <div class="content-container row clearfix">
                            <div class="col-md-12">
                                <div class="jca-title-container">
                                    <p class="wow fadeInLeft title-company" data-wow-delay=".2s">COMPANY</p>
                                    <div class="wow fadeInLeft title-mission highlighted" data-wow-duration="1s" data-wow-delay=".3s">MISSION</div>
                                    <div class="wow fadeInLeft title-vision" data-wow-delay=".3s">VISION</div>
                                </div>
                            </div>
                        </div>
                        <div id="mission" class="mission-vision-container">
                            <div class="content-container row clearfix">
                                <div class="col-md-6">
                                    <p class="wow animated fadeInLeft sub-title" data-wow-delay=".2s">
                                        {{ get_content($shop_theme_info, "home", "home_mission_title1") }}
                                    </p>
                                    <p class="wow animated fadeInLeft content" data-wow-delay=".2s">
                                        {{ get_content($shop_theme_info, "home", "home_mission_context1") }}
                                    </p>
                                </div>
                                <div class="col-md-6">
                                    <p class="wow animated fadeInLeft sub-title" data-wow-delay=".3s">
                                        {{ get_content($shop_theme_info, "home", "home_mission_title2") }}
                                    </p>
                                    <p class="wow animated fadeInLeft content" data-wow-delay=".3s">
                                        {{ get_content($shop_theme_info, "home", "home_mission_context2") }}
                                    </p>
                                </div>
                                <div class="col-md-6">
                                    <p class="wow animated fadeInLeft sub-title" data-wow-delay=".4s">
                                        {{ get_content($shop_theme_info, "home", "home_mission_title3") }}
                                    </p>
                                    <p class="wow animated fadeInLeft content" data-wow-delay=".4s">
                                        {{ get_content($shop_theme_info, "home", "home_mission_context3") }}
                                    </p>
                                </div>
                                <div class="col-md-6">
                                    <p class="wow animated fadeInLeft sub-title" data-wow-delay=".5s">
                                        {{ get_content($shop_theme_info, "home", "home_mission_title4") }}
                                    </p>
                                    <p class="wow animated fadeInLeft content" data-wow-delay=".5s">As this company will start its market in the Philippines, it also 
                                        {{ get_content($shop_theme_info, "home", "home_mission_context4") }}
                                    </p>
                                </div>
                            </div>
                        </div>
                        <div id="vision" class="mission-vision-container hide">
                            <div class="content-container row clearfix">
                                <div class="col-md-6">
                                    <ul>
                                        <li class="liststyle wow animated fadeInLeft" data-wow-delay=".3s">
                                            <p class="content">
                                               {{ get_content($shop_theme_info, "home", "home_vision_context1") }} 
                                            </p>
                                        </li>
                                    </ul>
                                </div>
                                <div class="col-md-6">
                                    <ul>
                                        <li class="liststyle wow animated fadeInLeft" data-wow-delay=".4s">
                                            <p class="content">
                                               {{ get_content($shop_theme_info, "home", "home_vision_context2") }} 
                                            </p>
                                        </li>
                                    </ul>
                                </div>
                                <div class="col-md-6">
                                    <ul>
                                        <li class="liststyle wow animated fadeInLeft" data-wow-delay=".5s">
                                            <p class="content">
                                               {{ get_content($shop_theme_info, "home", "home_vision_context3") }} 
                                            </p>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                        </div>
                    <!-- <div class="col-md-4">
                        <img class="img-background" src="/themes/{{ $shop_theme }}/img/mission-vision-background.png">
                    </div> -->
                </div>
        </div>
    </div>
    <!-- COMPANY PRODUCT -->
    <!-- <div id="products" class="wrapper-4">
        <div class="container">
            <div class="jca-title-container wow fadeInDown">
                <p class="main-title"><font class="shade-green">Products &</font> Services</p>
            </div>
            <div class="products-services-container">
                <div class="content-container row clearfix">
                    <div class="col-md-4">
                        <img class="products-services-img" src="/themes/{{ $shop_theme }}/img/product&services-001.png">
                        <p class="animated fadeInLeft img-title">Swiss Apple Stem Cell Soap with Glutathione and Collagen</p>
                    </div>
                    <div class="col-md-4">
                        <img class="products-services-img" src="/themes/{{ $shop_theme }}/img/product&services-002.png">
                        <p class="animated fadeInLeft img-title">Swiss Apple Stem Cell Serum</p>
                    </div>
                    <div class="col-md-4">
                        <img class="products-services-img" src="/themes/{{ $shop_theme }}/img/product&services-003.png">
                        <p class="animated fadeInLeft img-title">Stem Cell Anti-Aging Injectable</p>
                    </div>
                </div>
            </div>
        </div>
    </div> -->

    <div id="products" class="wrapper-4" style="overflow: hidden;">
        <div class="container">
            <div class="jca-title-container wow animated fadeInDown">
                <p class="main-title"><font class="shade-green">Products &</font> Services</p>
            </div>
            <div class="products-services-container">
                <div class="wow animated fadeInLeft img-container" data-wow-delay=".2s">
                    <img src="{{ get_content($shop_theme_info, "home", "home_products_and_services_image") }}">
                </div>
            </div>
        </div>
    </div>

    <div class="wrapper-5">
        <div class="container">
            
            <div class="row clearfix">
                <div class="col-md-6 mobile-view">
                    <div class="wow animated fadeInLeft img-container" data-wow-delay=".2s">
                        <img src="{{ get_content($shop_theme_info, "home", "home_item1_image") }}">
                    </div>
                    <!-- <div class="text-center wow animated fadeInLeft" data-wow-delay=".2s">
                        <button class="btn btn-primary product-add-cart" item-id="2726" quantity="1">ADD TO CART <i class="fa fa-shopping-cart" aria-hidden="true"></i></button>
                    </div> -->
                </div>
                <div class="col-md-6">
                    <div class="details-container">
                        <div class="wow animated fadeInRight header" data-wow-delay=".3s">{{ get_content($shop_theme_info, "home", "home_item1_name_green") }}&nbsp;<span>{{ get_content($shop_theme_info, "home", "home_item1_name_gold") }}</span></div>
                        <div class="wow animated fadeInRight sub-header" data-wow-delay=".4s">
                            <span>Contents:</span>&nbsp;{{ get_content($shop_theme_info, "home", "home_item1_contents") }}
                        </div>
                        <div class="benefits">
                            <div class="wow animated fadeInRight header" data-wow-delay=".5s">Benefits: </div>
                            <div class="wow animated fadeInRight" data-wow-delay=".6s">
                                {!! get_content($shop_theme_info, "home", "home_item1_benefits") !!}   
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 mobile-view-2">
                    <div class="wow animated fadeInLeft img-container" data-wow-delay=".2s">
                        <img src="{{ get_content($shop_theme_info, "home", "home_item1_image") }}">
                    </div>
                    <!-- <div class="text-center wow animated fadeInLeft" data-wow-delay=".2s">
                        <button class="btn btn-primary product-add-cart" item-id="2726" quantity="1">ADD TO CART <i class="fa fa-shopping-cart" aria-hidden="true"></i></button>
                    </div> -->
                </div>
            </div>


            <div class="row clearfix">
                <div class="col-md-6">
                    <div class="wow animated fadeInLeft img-container" data-wow-delay=".2s">
                        <img src="{{ get_content($shop_theme_info, "home", "home_item2_image") }}">
                    </div>
                    <!-- <div class="text-center wow animated fadeInLeft" data-wow-delay=".2s">
                        <button class="btn btn-primary product-add-cart" item-id="2727" quantity="1">ADD TO CART <i class="fa fa-shopping-cart" aria-hidden="true"></i></button>
                    </div> -->
                </div>
                <div class="col-md-6">
                    <div class="details-container">
                        <div class="wow animated fadeInRight header" data-wow-delay=".3s">{{ get_content($shop_theme_info, "home", "home_item2_name_green") }}<span>
                        {{ get_content($shop_theme_info, "home", "home_item2_name_gold") }}</span></div>
                        <div class="wow animated fadeInRight sub-header" data-wow-delay=".4s">
                            <span>Contents:</span>{{ get_content($shop_theme_info, "home", "home_item2_contents") }}
                        </div>
                        <div class="benefits">
                            <div class="wow animated fadeInRight header" data-wow-delay=".5s">Benefits:</div>
                            <div class="wow animated fadeInRight" data-wow-delay=".6s">
                               {!! get_content($shop_theme_info, "home", "home_item2_benefits") !!} 
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="wrapper-6" style="overflow: hidden;">
        <div class="container">
            <div class="row clearfix">
                <div class="col-md-6">
                    <div class="details-container">
                        <div class="wow animated fadeInLeft header">{{ get_content($shop_theme_info, "home", "home_item3_name_green") }}&nbsp;<span>{{ get_content($shop_theme_info, "home", "home_item3_name_gold") }}</span></div>
                        <div class="benefits">
                            <div class="wow animated fadeInRight header" data-wow-delay=".5s" style="font-size: 15px;">Benefits: </div>
                            <div class="wow animated fadeInRight" data-wow-delay=".6s">
                               {!! get_content($shop_theme_info, "home", "home_item3_benefits") !!} 
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="wow animated fadeInRight img-container" data-wow-delay=".3s">
                        <img src="{{ get_content($shop_theme_info, "home", "home_item3_image") }}">
                    </div>
                    <!-- <div class="text-center wow animated fadeInRight" data-wow-delay=".3s">
                        <button class="btn btn-primary product-add-cart" item-id="2728" quantity="1">ADD TO CART <i class="fa fa-shopping-cart" aria-hidden="true"></i></button>
                    </div> -->
                </div>
            </div>
        </div>
    </div>


    <!-- SCROLL TO TOP -->
    <div class="scroll-up"><img src="/themes/{{ $shop_theme }}/img/scroll-up.png"></div>
</div>
@endsection

@section("css")
<link rel="stylesheet" type="text/css" href="/themes/{{ $shop_theme }}/css/home.css?updated3">
<link rel="stylesheet" type="text/css" href="/themes/{{ $shop_theme }}/css/responsive.css">
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