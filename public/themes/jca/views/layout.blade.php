<!doctype html>
<!--[if lt IE 7]>      <html class="no-js lt-ie9 lt-ie8 lt-ie7" lang=""> <![endif]-->
<!--[if IE 7]>         <html class="no-js lt-ie9 lt-ie8" lang=""> <![endif]-->
<!--[if IE 8]>         <html class="no-js lt-ie9" lang=""> <![endif]-->
<!--[if gt IE 8]><!--> <html class="no-js" lang=""> <!--<![endif]-->
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">

        <title>{{ ucfirst($shop_info->shop_key) }} | {{ isset($page) ? $page : "" }}</title>
        <meta name="description" content="">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <!-- <link rel="apple-touch-icon" href="apple-touch-icon.png"> -->
        <link rel="icon" href="/themes/{{ $shop_theme }}/img/jca-icon.png"" type="image/png"/>

        <!-- GOOGLE FONT -->
        <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,400,600,700" rel="stylesheet">
        <link href="https://fonts.googleapis.com/css?family=Raleway:300,400,500,700" rel="stylesheet">   
        <!-- GLOBAL CSS -->
        <link rel="stylesheet" type="text/css" href="/themes/{{ $shop_theme }}/css/global.css">
        <link rel="stylesheet" type="text/css" href="/themes/{{ $shop_theme }}/css/push_sidenav.css">

        @include("frontend.ghead")
        
        <!-- OTHER CSS -->
        @yield("css")
        <style type="text/css">
        .content
        {
            background-color: transparent;
        }
        .navbar.sticky
        {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            z-index: 100;
        }
        </style>
        <script src="/themes/{{ $shop_theme }}/assets/initializr/js/vendor/modernizr-2.8.3-respond-1.4.2.min.js"></script>
    </head>
    <body>

        <div id="overlay" onclick="off()"></div>

        <div class="side-nav">
            
            <nav class="pushmenu pushmenu-left">

                @if($customer)
                <div class="space1"></div>
                <a href="/members/profile">
                   <div class="profile-img-container">
                        <div class="row-no-padding clearfix">
                            {{-- <div class="col-xs-3">
                                <div class="profile-img"><img src="{{ $profile_image }}"></div>
                            </div>
                            <div class="col-xs-9">
                                <div class="text-holder">
                                    <div class="name-text text-overflow">{{ $customer->first_name }} {{ $customer->middle_name }} {{ $customer->last_name }}</div>
                                    <div class="subtext text-overflow">{{ $customer->email }}</div>
                                </div>
                            </div> --}}
                            <div class="col-xs-12">
                                <div class="profile-img"><img src="{{ $profile_image }}"></div>
                                <div class="text-holder">
                                    <div class="name-text text-overflow">{{ $customer->first_name }} {{ $customer->middle_name }} {{ $customer->last_name }}</div>
                                    <div class="subtext text-overflow">{{ $customer->email }}</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </a>
                <div class="space1"></div>
                <span>BROWSE</span>
                <ul class="links">
                    <li class="{{ Request::segment(2) == "" ? "active" : "" }}"> <a href="/"><i class="fa fa-home" aria-hidden="true"></i> Home</a></li>
                    <li onclick="off()"> <a class="smoth-scroll" href="/#aboutus"><i class="fa fa-info" aria-hidden="true"></i> ABOUT US</a></li>
                    <li onclick="off()"> <a class="smoth-scroll" href="/#mission-vision"><i class="fa fa-rocket" aria-hidden="true"></i> MISSION & VISION</a></li>
                    <li onclick="off()"> <a class="smoth-scroll" href="/#products"><i class="fa fa-list-ul" aria-hidden="true"></i> PRODUCTS</a></li>
                </ul>
                
                <div class="space2"></div>
                <span>MEMBER'S AREA</span>
                <ul class="links">
                    <li class="{{ Request::segment(1) == "members" ? "active" : "" }}" > <a href="/members"><i class="fa brown-icon-dashboard" aria-hidden="true"></i> DASHBOARD</a> </li>
                    <li> <a href="/members/profile"><i class="fa brown-icon-profile" aria-hidden="true"></i> PROFILE</a></li>
                    @if($mlm_member)
                    <li class="{{ Request::segment(2) == "genealogy" ? "active" : "" }}"> <a href="/members/genealogy?mode=binary"><i class="fa brown-icon-flow-tree" area-hidden="true"></i> GENEALOGY</a></li>
                    <li class="{{ Request::segment(2) == "report" ? "active" : "" }}"> <a href="/members/report"><i class="fa fa-bar-chart" aria-hidden="true"></i> REPORTS</a></li>
                    <li class="{{ Request::segment(2) == "wallet-encashment" ? "active" : "" }}"> <a href="/members/wallet-encashment"><i class="fa brown-icon-wallet" aria-hidden="true"></i> WALLET</a></li>
                        @if($customer)
                            <li class="user-logout"> <a href="/members/logout">Logout &nbsp;<i class="fa fa-long-arrow-right" aria-hidden="true"></i></a></li>
                        @endif
                    @else
                    @endif
                </ul>
                @else
                    <div class="space1"></div>
                    <span>BROWSE</span>
                    <ul class="links">
                        <li> <a href="/"><i class="fa fa-home" aria-hidden="true"></i> HOME</a></li>
                        <li onclick="off()"> <a class="smoth-scroll" href="/#aboutus"><i class="fa fa-info" aria-hidden="true"></i> ABOUT US</a></li>
                        <li onclick="off()"> <a class="smoth-scroll" href="/#mission-vision"><i class="fa fa-rocket" aria-hidden="true"></i> MISSION & VISION</a></li>
                        <li onclick="off()"> <a class="smoth-scroll" href="/#products"><i class="fa fa-list-ul" aria-hidden="true"></i> PRODUCTS</a></li>
                    </ul>
                @endif
            </nav>
        </div>

        {{-- BLUR IN MOBILE VIEW --}}
        <div class="blur-me">

            <div class="loader hide">
              <span><img src="/resources/assets/frontend/img/loader.gif"></span>
            </div>

            <!-- HEADER -->
            <div id="home" class="subheader-container">
                <div class="container">
                    @if($customer)
                    {{-- <div class="left-container">
                        @if(!$mlm_member)
                            <span><i class="fa fa-heart-o" aria-hidden="true"></i></span>
                            <span>BECOME A MEMBER</span>
                        @endif
                    </div> --}}
                    <div class="right-container"><span style="cursor: pointer;" onClick="location.href='/members/logout'">LOGOUT</span><span style="cursor: pointer;" onClick="location.href='/members'">MY ACCOUNT</span></div>
                    @else
                    {{-- <div class="left-container">
                        <span><i class="fa fa-heart-o" aria-hidden="true"></i></span>
                        <span>BECOME A MEMBER</span>
                    </div> --}}
                    <div class="right-container">
                        <span class="smoth-scroll" style="cursor: pointer;" onClick="location.href='/members/login'">LOGIN</span>
                        <span class="smoth-scroll" style="cursor: pointer;" onClick="location.href='/members/register'">REGISTER</span>
                    </div>
                     @endif 
                </div>
            </div>

            <div class="header-container">
                <div class="container">
                    <div class="row clearfix">
                        <div class="col-md-2">
                            <!-- <div class="menu-nav">
                                <span></span>
                                <span></span>
                                <span></span>
                            </div> -->
                            <div id="nav_list" onclick="on()"><i class="fa fa-bars hamburger"></i></div>
                            <div class="image-logo-holder">
                                <a class="clearfix" href="/"><img src="/themes/{{ $shop_theme }}/img/header-logo.png"></a>                       
                            </div>
                        </div>
                        <div class="col-md-10">
                        <!-- NAVIGATION -->
                            <nav class="navirino">
                                <ul>
                                    @if(Request::segment(1)=="members")
                                        <li><a class="smoth-scroll" href="/#home">HOME</a></li>
                                        <li><a class="smoth-scroll" href="/#aboutus">ABOUT US</a></li>
                                        <li><a class="smoth-scroll" href="/#mission-vision">MISSION & VISION</a></li>
                                        <li><a class="smoth-scroll" href="/#products">PRODUCTS</a></li>
                                    @elseif(Request::segment(1)=="terms_and_conditions")
                                        <li><a class="smoth-scroll" href="/#home">HOME</a></li>
                                        <li><a class="smoth-scroll" href="/#aboutus">ABOUT US</a></li>
                                        <li><a class="smoth-scroll" href="/#mission-vision">MISSION & VISION</a></li>
                                        <li><a class="smoth-scroll" href="/#products">PRODUCTS</a></li>    
                                    @else
                                        <li><a class="smoth-scroll" href="#home">HOME</a></li>
                                        <li><a class="smoth-scroll" href="#aboutus">ABOUT US</a></li>
                                        <li><a class="smoth-scroll" href="#mission-vision">MISSION & VISION</a></li>
                                        <li><a class="smoth-scroll" href="#products">PRODUCTS</a></li>
                                    @endif
                                    <!-- SHOPPING CART ICON -->
                                    <!-- <li>
                                        <a href="javascript:" class="popup cart-holder" link="/cartv2" size="lg"><i class="fa fa-shopping-cart" aria-hidden="true"></i> <span class="badge quantity-item-holder">0</span></a>
                                    </li> -->
                                </ul>
                            </nav>
                        </div>
                    </div>
                </div>
            </div> 
            
            <!-- CONTENT -->
            <div id="scroll-to" class="clearfix">
               @yield("content")
            </div>

            <!-- FOOTER -->
            <footer id="bottom-footer" class="blur-me">
                <div class="container">
                    <div class="footer-container">
                        <div class="upper row clearfix">
                            <div class="col-md-4">
                                <div class="reach-us-holder">
                                    <div class="jca-footer-title-container">
                                        <p class="footer-title">Reach Us</p>
                                    </div>
                                    <div class="jca-footer-details-container">
                                        <div class="icon-holder">
                                            <img class="jca-footer-icon" src="/themes/{{ $shop_theme }}/img/location-logo.png">
                                        </div>
                                        <p class="footer-details">{{ get_content($shop_theme_info, "footer_details", "footer_details_address") }}</p>
                                    </div>
                                    <div class="jca-footer-details-container">
                                        <div class="icon-holder">
                                            <img class="jca-footer-icon" src="/themes/{{ $shop_theme }}/img/telephone-logo.png">
                                        </div>
                                        <p class="footer-details">{{ get_content($shop_theme_info, "footer_details", "footer_details_number") }}</p>
                                    </div>
                                    <div class="jca-footer-details-container">
                                        <div class="icon-holder">
                                            <img class="jca-footer-icon" src="/themes/{{ $shop_theme }}/img/mail-logo.png">
                                        </div>
                                        <p class="footer-details">{{ get_content($shop_theme_info, "footer_details", "footer_email_address") }}</p>
                                    </div>
                                </div>
                            </div>
                            <!-- <div class="col-md-2">
                                <div class="jca-footer-title-container">
                                    <p class="footer-title">Quick Links</p>
                                </div>
                                <div class="jca-footer-details-container">
                                    <a href="javascript:">
                                        <p class="footer-detail-list">Dealer’s Policy</p>
                                    </a>
                                    <a href="javascript:">
                                        <p class="footer-detail-list">Disclaimer</p>
                                    </a>
                                    <a href="javascript:">
                                       <p class="footer-detail-list">Terms & Condition</p> 
                                    </a>
                                    <a href="javascript:">
                                        <p class="footer-detail-list">Privacy Policy</p>
                                    </a>
                                    <a href="javascript:">
                                        <p class="footer-detail-list">Product Policy</p>
                                    </a>
                                </div>
                            </div> -->
                            <div class="col-md-4">
                                <div class="jca-footer-title-container">
                                    <p class="footer-title">Overview</p>
                                </div>
                                <div class="jca-footer-details-container">

                                    <a href="{{ get_content($shop_theme_info, "footer_details", "footer_marketing_plan") }}" class="lsb-preview"> 
                                        <p class="footer-detail-list">Marketing Plan</p>
                                    </a>
                                    
                                    <a href="{{ get_content($shop_theme_info, "footer_details", "footer_packages") }}" class="lsb-preview"> 
                                        <p class="footer-detail-list">Packages</p>
                                    </a>
                                    
                                    <a href="{{ get_content($shop_theme_info, "footer_details", "footer_service_packages") }}" class="lsb-preview"> 
                                        <p class="footer-detail-list">Service Packages</p>
                                    </a>

                                    <a href="{{ get_content($shop_theme_info, "footer_details", "footer_product_packages") }}" class="lsb-preview"> 
                                        <p class="footer-detail-list">Product Packages</p>
                                    </a>
                                    
                                    <a href="{{ get_content($shop_theme_info, "footer_details", "footer_sales_commission") }}" class="lsb-preview"> 
                                        <p class="footer-detail-list">Sales Commission</p>
                                    </a>

                                    <a href="{{ get_content($shop_theme_info, "footer_details", "footer_override_sales_commission") }}" class="lsb-preview"> 
                                        <p class="footer-detail-list">Overide Sales Commission</p>
                                    </a>

                                    <a href="/terms_and_conditions"> 
                                        <p class="footer-detail-list">General Terms and Conditions</p>
                                    </a>

                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="jca-footer-title-container">
                                    <p class="footer-title">Product Portfolio</p>
                                </div>
                                <div class="jca-footer-details-container">
                                    <a href="{{ get_content($shop_theme_info, "footer_details", "footer_product_portfolio1") }}" class="lsb-preview"> 
                                        <p class="footer-detail-list">Swiss Apple Stem Cell Serum</p>
                                    </a>
                                    <a href="{{ get_content($shop_theme_info, "footer_details", "footer_product_portfolio2") }}" class="lsb-preview"> 
                                        <p class="footer-detail-list">Swiss Apple Stem Cell Soap with Glutathione and Collagen</p>
                                    </a>
                                    <a href="{{ get_content($shop_theme_info, "footer_details", "footer_product_portfolio3") }}" class="lsb-preview"> 
                                        <p class="footer-detail-list">Stem Cell Therapy- The Anti-Aging and Rejuvenation Therapy</p>
                                    </a>
                                </div>
                            </div> 
                        </div>
                    </div>
                </div>
            </footer>

            <div class="container">
                <div class="bottom">                           
                    <div class="ftr-title">© JCA Wellness International Corp. All Right Reserved</div>
                    <div class="ftr-title-2">Powered By: DIGIMA WEB SOLUTIONS, Inc.</div>
                </div>
            </div>
        </div>
        
        @include("frontend.gfoot")
        <script type="text/javascript" src="/themes/{{ $shop_theme }}/js/global.js"></script>
        <script type="text/javascript" src="/themes/{{ $shop_theme }}/js/theme_custom.js"></script>

        @yield("script")
        <script> 
          $(window).load(function() { 
            $.fn.lightspeedBox(); 
          }); 
        </script> 
    </body>
</html>
