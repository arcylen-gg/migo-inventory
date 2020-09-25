@extends("layout")
@section("content")
<div class="content">
    <!-- Media Slider -->
    <div id="home" class="slider-wrapper single-item">
        <img src="/themes/{{ $shop_theme }}/img/home-banner.jpg">
        <img src="/themes/{{ $shop_theme }}/img/home-banner-2.jpg">
    </div>

    <div id="about" class="wrapper-1">
        <div class="container">
            <div class="row clearfix">
                <div class="col-md-6">
                    <div class="left-container">
                        <div class="title-container">
                            <span class="icon-container"><img src="/themes/living-water/img/water-drops.png"></span><span class="title">ABOUT US</span>
                        </div>
                        <div class="description-container">
                            <p>{!! get_content($shop_theme_info, "home", "home_about_description") !!}</p>
                        </div>
                        <div class="button-container">
                            <a href="#"><button>READ MORE</button></a>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="right-container">
                        <a class="lsb-preview" href="{{ get_content($shop_theme_info, "home", "home_about_image") }}">
                            <div class="image-holder">
                                <img src="{{ get_content($shop_theme_info, "home", "home_about_image") }}">
                            </div>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div id="process" class="wrapper-2">
        <div class="container">
            <div class="title-container">
                <span class="icon-container"><img src="/themes/living-water/img/water-drops.png"></span><span class="title">OUR PROCESS</span>
            </div>
            <div class="row clearfix">
                <div class="col-md-6">
                    <div class="process-container">
                        <div class="process-holder">
                            <a class="lsb-preview" href="{{ get_content($shop_theme_info, "home", "home_process_image_1") }}">
                                <div class="process-image">
                                    <img src="{{ get_content($shop_theme_info, "home", "home_process_image_1") }}">
                                </div>
                            </a>
                        </div>
                        <div class="details-container">
                            <div class="title">{!! get_content($shop_theme_info, "home", "home_process_title_1") !!}</div>
                            <div class="description">{!! get_content($shop_theme_info, "home", "home_process_description_1") !!}</div>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="process-container">
                        <div class="process-holder">
                            <a class="lsb-preview" href="{{ get_content($shop_theme_info, "home", "home_process_image_2") }}">
                                <div class="process-image">
                                    <img src="{{ get_content($shop_theme_info, "home", "home_process_image_2") }}">
                                </div>
                            </a>
                        </div>
                        <div class="details-container">
                            <div class="title">{!! get_content($shop_theme_info, "home", "home_process_title_2") !!}</div>
                            <div class="description">{!! get_content($shop_theme_info, "home", "home_process_description_2") !!}</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div id="product" class="wrapper-3">
        <div class="container">
            <div class="title-container">
                <span class="icon-container"><img src="/themes/living-water/img/water-drops.png"></span><span class="title">A HEALTHY WATER FOR EVERYONE</span>
            </div>
            <div class="row clearfix">
                <div class="col-md-6">
                    <div class="left-container">
                        <a class="lsb-preview" href="{{ get_content($shop_theme_info, "home", "home_product_image") }}">
                            <div class="image-holder">
                                <img src="{{ get_content($shop_theme_info, "home", "home_product_image") }}">
                            </div>
                        </a>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="right-container">
                        <div class="description-container">
                            <p>{!! get_content($shop_theme_info, "home", "home_product_description") !!}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div id="contact" class="wrapper-4">
        <div class="container">
            <div class="row clearfix">
                <div class="col-md-6">
                    <div class="title-container">
                        <span class="icon-container"><img src="/themes/living-water/img/water-drops.png"></span><span class="title">GET IN TOUCH WITH US</span>
                    </div>
                     <div class="row clearfix">
                        <div class="col-md-6">
                            <div class="form-group">
                                <input type="text" class="form-control" placeholder="First Name*">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <input type="text" class="form-control" placeholder="Last Name*">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                    <input type="phone" class="form-control" placeholder="Phone Number*">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <input type="email" class="form-control" placeholder="Email Address*">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <input type="text area" class="form-control" placeholder="Subject*">
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="form-group">
                                <textarea type="text" class="form-control text-message" placeholder="Message*"></textarea>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="button-container">
                                <button>SUBMIT</button>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="info-container">
                        <div class="title">Main Office: </div>
                        <div class="details-container">
                            <span class="icon"><i class="fa fa-map-marker" aria-hidden="true"></i></span><span class="details">{!! get_content($shop_theme_info, "contact_details", "contact_company_address") !!}</span>
                        </div>
                        <div class="title">Email Address: </div>
                        <div class="details-container">
                            <span class="icon"><i class="fa fa-envelope" aria-hidden="true"></i></span><span class="details">{!! get_content($shop_theme_info, "contact_details", "contact_company_email_address") !!}</span>
                        </div>
                        <div class="title">Contact Number: </div>
                        <div class="details-container">
                            <span class="icon"><i class="fa fa-phone" aria-hidden="true"></i></span><span class="details">{!! get_content($shop_theme_info, "contact_details", "contact_company_contact_number") !!}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- SCROLL TO TOP -->
    <div class="scroll-up"><img src="/themes/{{ $shop_theme }}/img/scroll-up.png"></div>
</div>
@endsection

@section("css")
<link rel="stylesheet" type="text/css" href="/themes/{{ $shop_theme }}/css/home.css">
@endsection

@section("script")

<script type="text/javascript">

    $(document).ready(function()
    {
        $('.single-item').slick
        ({
            prevArrow:"<img class='a-left control-c prev slick-prev' src='/themes/{{ $shop_theme }}/img/arrow-left.png'>",
            nextArrow:"<img class='a-right control-c next slick-next' src='/themes/{{ $shop_theme }}/img/arrow-right.png'>",
            dots: false,
            autoplay: true,
            autoplaySpeed: 3000,
        });

        lightbox.option({
          'disableScrolling': true,
          'wrapAround': true
        });

    });
    
</script>

<script type="text/javascript">
/*$(document).ready(function($) {

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
});*/
</script>

<script type="text/javascript" src="/themes/{{ $shop_theme }}/js/home.js"></script>

@endsection