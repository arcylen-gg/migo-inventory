@extends("layout")
@section("content")
<div class="content">
    <!-- Media Slider -->
    <div class="slider-wrapper" style="background-image: url('{{ get_content($shop_theme_info, "home", "home_banner") }}')">
        <div class="container">
            <div class="caption-logo-container"><img src="{{ get_content($shop_theme_info, "home", "home_banner_logo") }}"></div>
            <div class="scroll-down-container">
                <a class="smoth-scroll" href="#aboutus"><span class="animated fadeInDown"><i class="fa fa-chevron-down" aria-hidden="true"></i></span></a>           
            </div>
        </div>
    </div>

    <div id="aboutus" class="wrapper-1">
        <div class="title-container">Who we are</div>
        <div class="container">
            <div class="row clearfix">
                <div class="col-md-6">
                    <div class="left-wrapper-1">
                        <div class="paragraph-container">
                            {!! get_content($shop_theme_info, "home", "home_about_us_info") !!}
                        </div>
                        <div class="button-container">
                            <button>More</button>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="image-container">
                        <img src="{{ get_content($shop_theme_info, "home", "home_About_Us_Image") }}">
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div id="whytojoin" class="wrapper-2">
        <div class="title-container">Why Join in NICEnterprises</div>
        <div class="container">
            <div class="row-no-padding clearfix">
                <div class="col-md-4">
                    <div class="image-container">
                        <img src="{{ get_content($shop_theme_info, "home", "home_why_join_nice_image1") }}">
                        <div class="title-image-container">{!! get_content($shop_theme_info, "home", "home_why_join_nice_title1") !!}</div>
                        <div class="info-container">{!! get_content($shop_theme_info, "home", "home_why_join_nice_description1") !!}</div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="image-container center">
                        <img src="{{ get_content($shop_theme_info, "home", "home_why_join_nice_image2") }}">
                        <div class="title-image-container">{!! get_content($shop_theme_info, "home", "home_why_join_nice_title2") !!}</div>
                        <div class="info-container">{!! get_content($shop_theme_info, "home", "home_why_join_nice_description2") !!}</div>
                    </div> 
                </div>
                <div class="col-md-4">
                    <div class="image-container">
                        <img src="{{ get_content($shop_theme_info, "home", "home_why_join_nice_image3") }}">
                        <div class="title-image-container">{!! get_content($shop_theme_info, "home", "home_why_join_nice_title3") !!}</div>
                        <div class="info-container">{!! get_content($shop_theme_info, "home", "home_why_join_nice_description3") !!}</div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div id="packages" class="wrapper-3">
        <div class="title-container">Membership Packages</div>
        <div class="container">
            <div class="row clearfix">
                <div class="col-md-4">
                    <div class="border-container">
                        <div class="border-background-container">
                            <div class="package-container">{!! get_content($shop_theme_info, "home", "home_member_package_title1") !!}<br>{!! get_content($shop_theme_info, "home", "home_member_package_price1") !!}</div>
                            <div class="details-container">
                                {!! get_content($shop_theme_info, "home", "home_member_package_details1") !!}
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="border-container">
                        <div class="border-background-container">
                            <div class="package-container">{!! get_content($shop_theme_info, "home", "home_member_package_title2") !!}<br>{!! get_content($shop_theme_info, "home", "home_member_package_price2") !!}</div>
                            <div class="details-container">
                                 {!! get_content($shop_theme_info, "home", "home_member_package_details2") !!}
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="border-container">
                        <div class="border-background-container">
                            <div class="package-container">{!! get_content($shop_theme_info, "home", "home_member_package_title3") !!}<br>{!! get_content($shop_theme_info, "home", "home_member_package_price3") !!}</div>
                            <div class="details-container">
                                 {!! get_content($shop_theme_info, "home", "home_member_package_details3") !!}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div id="partners" class="wrapper-4">
        <div class="container">
            <div class="wrapper-4-top">
                <div class="title-container">Our Partners</div>
                <div class="container">
                    <div class="row clearfix">
                        <div class="col-md-3">
                            <div class="image-holder">
                                    <img src="{{ get_content($shop_theme_info, "home", "home_partner1") }}">
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="image-holder">
                                    <img src="{{ get_content($shop_theme_info, "home", "home_partner2") }}">
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="image-holder">
                                    <img src="{{ get_content($shop_theme_info, "home", "home_partner3") }}">
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="image-holder">
                                    <img src="{{ get_content($shop_theme_info, "home", "home_partner4") }}">
                            </div>
                        </div>
                    </div>

                </div>
            </div>
            <div class="wrapper-4-bottom">
                <div class="title-container">Become a Business Partner</div>
                <div class="details-container">{!! get_content($shop_theme_info, "home", "home_become_business_partner_description") !!}</div>
                <div class="button-container">
                    <a href="#">Become a Member</a>
                </div>
            </div>
        </div>    
    </div>

    <div id="contactus" class="wrapper-5">
        <div class="container">
            <div class="row clearfix">
                <div class="col-md-6">
                    <div class="register-container">
                        <div class="title-container">Get In Touch With Us</div>
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
                                    <textarea type="text" class="form-control text-message" placeholder="Message"></textarea>
                                </div>
                            </div>
                        </div>
                        <div class="button-container">
                            <a href="#">SEND</a>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="location-container">
                        <div class="title-container">Location</div>
                        <div class="title-container">{!! get_content($shop_theme_info, "home", "home_main_office_branch_name") !!}</div>
                        <div class="desc-title">{!! get_content($shop_theme_info, "home", "home_main_office_branch_address") !!}</div>
                        <div class="contact-container">
                            <span class="icon"><i class="fa fa-mobile" aria-hidden="true"></i></span>
                            <span>{!! get_content($shop_theme_info, "home", "home_contact_number") !!}</span>
                        </div>
                         <div class="contact-container">
                            <span class="icon"><i class="fa fa-envelope-o" aria-hidden="true"></i></span>
                            <span>{!! get_content($shop_theme_info, "home", "home_email_address") !!}</span>
                        </div>
                        <div class="title-container">BUSINESS HOURS</div>
                        <span class="icon"><i class="fa fa-clock-o" aria-hidden="true"></i></span>
                        <span class="business-hours">{!! get_content($shop_theme_info, "home", "home_business_hours") !!}</span>
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

@endsection