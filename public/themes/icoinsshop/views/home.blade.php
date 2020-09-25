@extends("layout")
@section("content")
<div class="content">
    <!-- Media Slider -->
    <div id="home" class="page-section slider-wrapper" style="background-image: url('/themes/{{ $shop_theme }}/img/cover-photo.jpg')">
        <div class="container">
            <div class="holder fadeMe">
                <div class="row clearfix">
                    <div class="col-md-6">
                        <div class="caption-container">
                            <div class="text-1 wow fadeInDown" data-wow-delay=".2s" data-wow-duration="2s">{!! get_content($shop_theme_info, "home", "home_caption_header") !!}</div>
                            <div class="text-2 wow fadeInDown" data-wow-delay=".4s" data-wow-duration="2s">{!! get_content($shop_theme_info, "home", "home_caption_subheader") !!} </div>
                        </div>
                        <div class="button-container">
                            <a href="/members/register"><button class="btn-join wow fadeInDown" data-wow-delay=".6s" data-wow-duration="2s">JOIN US TODAY</button></a>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="card wow fadeInRight" data-wow-duration="2s">
                            <div class="card-content">
                                <div class="title">
                                    <span>Current Exchange<div class="underline"></div></span>
                                </div>
                                <div class="card-holder">
                                    <div class="icon-text-container">
                                        <img src="/themes/{{ $shop_theme }}/img/card-img.png">
                                        <span>
                                            <div class="text-1">KROPS</div>
                                            <div class="text-2">{!! get_content($shop_theme_info, "home", "home_kro_value") !!}</div>
                                        </span>
                                    </div>
                                    <div class="border"></div>
                                    <div class="details">
                                        Krops has the potential of becoming the largest food hub in the world without even owning a single farm.
                                    </div>
                                    @if(!$mlm_member)
                                        <div class="button-container">
                                            <a href="javascript:"><button class="btn-buy-tokens">BUY TOKENS</button></a>
                                        </div>
                                    @else
                                        <div class="button-container">
                                            <a href="javascript:"><button class="btn-buy-tokens">BUY TOKENS</button></a>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="scroll-down fadeMe wow fadeInDown" data-wow-delay=".8s" data-wow-duration="2s"><a class="navigation__link" href="#about"><img src="/themes/{{ $shop_theme }}/img/arrow-down.png"></a></div>
        </div>
    </div>

    <!-- ABOUT -->
    <section id="about" class="page-section wrapper-1" style="background-image: url('/themes/{{ $shop_theme }}/img/networkbg.png')">
        <div class="container">
            <div class="wrapper-title wow fadeIn" data-wow-duration="3s"><span>About</span> Us</div>
            <div class="img-holder wow fadeInDown" data-wow-delay=".4s" data-wow-duration="2s">
                <img src="{{ get_content($shop_theme_info, "home", "home_about_image") }}">
            </div>
            <div class="wrapper-texts wow fadeInDown" data-wow-delay=".6s" data-wow-duration="1s">{!! get_content($shop_theme_info, "home", "home_about_description") !!}</div>
        </div>
    </section>
    
    <!-- MISSION VISION -->
    <section class="page-section wrapper-2">
        <div id="missionvision" class="unskew" style="background-image: url('/themes/{{ $shop_theme }}/img/missionvision-bg.png')">
            <div class="container">
                <div class="row clearfix">
                    <div class="col-md-6">
                        <div class="img-holder wow fadeIn" data-wow-delay=".2s" data-wow-duration="2s">
                            <img src="/themes/{{ $shop_theme }}/img/mission.png">
                        </div>
                        <div class="title wow fadeInDown" data-wow-delay=".3s" data-wow-duration="2s">MISSION<div class="underline"></div>
                        </div>
                        <div class="texts wow fadeInDown" data-wow-delay=".4s" data-wow-duration="2s">{!! get_content($shop_theme_info, "home", "home_mission") !!}</div>
                    </div>
                    <div class="col-md-6">
                        <div class="img-holder wow fadeIn" data-wow-delay=".5s" data-wow-duration="2s">
                            <img src="/themes/{{ $shop_theme }}/img/vision.png">
                        </div>
                        <div class="title wow fadeInDown" data-wow-delay=".6s" data-wow-duration="2s">VISION<div class="underline"></div>
                        </div>
                        <div class="texts wow fadeInDown" data-wow-delay=".8s" data-wow-duration="2s">{!! get_content($shop_theme_info, "home", "home_vision") !!}</div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- HOW IT WORKS -->
    <section id="howitworks" class="page-section wrapper-3" style="background-image: url('/themes/{{ $shop_theme }}/img/networkbg.png')">
        <div class="container">
            <div class="wrapper-title wow fadeInDown" data-wow-duration="3s"><span>How</span> It Works</div>
            <div class="row clearfix">
                <div class="col-md-6">
                    <div class="left">
                        <div class="text-header wow fadeInLeft" data-wow-delay=".2s" data-wow-duration="2s">How ICOinsshop Works</div>
                        <div class="text-content wow fadeInLeft" data-wow-delay=".4s" data-wow-duration="2s">{!! get_content($shop_theme_info, "home", "home_how_it_works_description") !!}</div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="right">
                        <div class="img-holder wow fadeIn" data-wow-delay=".8s" data-wow-duration="2s">
                            <img src="{{ get_content($shop_theme_info, "home", "home_how_it_works_image") }}">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- PRODUCTS -->
    <section class="page-section wrapper-4">
        <div id="products" class="container unskew">
            <div class="wrapper-title wow fadeInDown" data-wow-duration="3s"><span>ICO</span> Token Products <div class="underline"></div></div>
            <div class="product-display">

                <div class="product-holder wow fadeIn" data-wow-delay=".2s" data-wow-duration="2s">
                    <div class="top">
                        <img src="/themes/{{ $shop_theme }}/img/krops.jpg">
                    </div>
                    <div class="bottom">
                        <div class="texts"> KROPS is offering tokenized shares of the company through an offering of 16,000,000 or sixteen million KROPS tokens of KropCoins.</div>
                        <div class="btn-container">
                            <a href="javascript:"><button class="btn-more-info">MORE INFO</button></a>
                            <a href="javascript:"><button class="btn-buy-tokens">BUY TOKENS</button></a>
                        </div>
                    </div>
                </div>
            
                <div class="product-holder wow fadeIn" data-wow-delay=".4s" data-wow-duration="2s">
                    <div class="img-holder">
                        <img src="/themes/{{ $shop_theme }}/img/logo-2.png">
                    </div>
                    <div class="text">Other tokens are coming soon!</div>
                </div>
            
                <div class="product-holder wow fadeIn" data-wow-delay=".6s" data-wow-duration="2s">
                    <div class="img-holder">
                        <img src="/themes/{{ $shop_theme }}/img/logo-2.png">
                    </div>
                    <div class="text">Other tokens are coming soon!</div>
                </div>

                <div class="product-holder wow fadeIn" data-wow-delay=".8s" data-wow-duration="2s">
                    <div class="img-holder">
                        <img src="/themes/{{ $shop_theme }}/img/logo-2.png">
                    </div>
                    <div class="text">Other tokens are coming soon!</div>
                </div>

            </div>
        </div>
    </section>

    <!-- NEWS AND ANNOUNCEMENTS -->
    <section id="news" class="page-section wrapper-5">
        <div class="container">
            <div class="wrapper-title wow fadeInDown" data-wow-duration="3s"><span>News</span> and Announcement</div>
            <div class="news-display">
                @if(count(get_front_news($shop_id)) > 0)
                    @foreach(limit_foreach(get_front_news($shop_id), 3) as $news)
                        <div class="news-holder match-height wow fadeInLeft" data-wow-delay=".2s" data-wow-duration="2s">
                            <div class="row-no-padding clearfix">
                                <div class="col-md-6 col-xs-6">
                                    <div class="img-holder">
                                        <a href="/news?id={{ $news->post_id }}">
                                            <img src="{{ $news->post_image }}">
                                        </a>
                                    </div>
                                </div>
                                <div class="col-md-6 col-xs-6">
                                    <div class="caption-holder">
                                        <div class="caption">
                                            <a href="/news?id={{ $news->post_id }}">
                                                {{ $news->post_title }} 
                                            </a>
                                        </div>
                                        <div class="date">{{ $news->post_date }}</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                @else
                    <span>Coming Soon!</span>
                @endif        
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
<script type="text/javascript">

$(document).ready(function($) 
{
    /*TEXT FADEOUT*/
    $(window).scroll(function()
    {
        $(".fadeMe").css("opacity", 1 - $(window).scrollTop() / 250);
    });

    // $('.single-item').slick
    // ({
    //     prevArrow:"<img class='a-left control-c prev slick-prev' src='/themes/img/arrow-left.png'>",
    //     nextArrow:"<img class='a-right control-c next slick-next' src='/themes/img/arrow-right.png'>",
    //     dots: false,
    //     autoplay: true,
    //     slidesToShow: 3,
    //     autoplaySpeed: 2000,
    // });

});

</script>

<script type="text/javascript" src="/themes/{{ $shop_theme }}/js/home.js"></script>

@endsection