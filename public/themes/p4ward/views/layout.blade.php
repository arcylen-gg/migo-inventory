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
        <link rel="icon" href="/themes/{{ $shop_theme }}/img/favicon.png" type="image/jpg" />
    
        <!-- GOOGLE FONT -->
        <link href="https://fonts.googleapis.com/css?family=Ubuntu:400,700" rel="stylesheet">
        <link href="https://fonts.googleapis.com/css?family=Open+Sans:400,700" rel="stylesheet">
        <link href="https://fonts.googleapis.com/css?family=Lato:400,700" rel="stylesheet"> 
        <link href="https://fonts.googleapis.com/css?family=Cantarell:400,700i" rel="stylesheet">

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
                <li> <a onclick="off()" href="/#home"><i class="fa fa-home" aria-hidden="true"></i> HOME</a></li>
                <li> <a onclick="off()" href="/#aboutus"><i class="fa fa-info" aria-hidden="true"></i> ABOUT US</a></li>
                <li> <a onclick="off()" href="/#product"><i class="fa fa-list-ul" aria-hidden="true"></i> PRODUCT</a></li>
                <li> <a onclick="off()" href="/#testimonials"><i class="fa fa-quote-left" aria-hidden="true"></i> TESTIMONIALS</a></li>
                <li> <a onclick="off()" href="/#contactus"><i class="fa fa-phone" aria-hidden="true"></i> CONTACT US</a></li>
                <li> <a onclick="off()" href="https://drive.google.com/file/d/0B9TqTDu5OK_3Mm5qdGoyZ2huRFl2ZTd1SS01Q1c3d1EyY1lJ/view" target="_blank"><i class="fa fa-users" aria-hidden="true"></i> NETGIVING</a></li>
            </ul>
            
            <div class="space2"></div>
            <span>MEMBER'S AREA</span>
            <ul class="links">
                <li> <a href="/members"><i class="fa brown-icon-dashboard" aria-hidden="true"></i> Dashboard</a></li>
                <li> <a href="/members/profile"><i class="fa brown-icon-profile" aria-hidden="true"></i> Profile</a></li>
                @if($mlm_member)
                <!-- <li> <a href="/members/genealogy?mode=sponsor">Genealogy</a> </li> -->
                <li class="genealogy-mobile-dropdown"> 
                    <a href="javascript:"><i class="fa brown-icon-flow-tree" area-hidden="true"></i> Genealogy <span class="pull-right"><i class="fa-change fa fa-angle-down" aria-hidden="true"></i></span></a> 
                </li>
                    <ul class="genealogy-mobile-dropdown-list">
                        <li><a href="/members/genealogy?mode=binary">Binary Tree</a></li>
                        <li><a href="/members/genealogy?mode=sponsor">Sponsor Tree</a></li>
                    </ul>

                <li> <a href="/members/report"><i class="fa fa-bar-chart" aria-hidden="true"></i> Reports</a></li>
                <li> <a href="/members/report-points"><i class="fa brown-icon-flow-tree" area-hidden="true"></i> Network List</a></li>
                <li> <a href="/members/wallet-encashment"><i class="fa brown-icon-wallet" aria-hidden="true"></i> Wallet Encashment</a></li>
                    @if($customer)
                        <li class="user-logout"> <a href="/members/logout">Logout &nbsp;<i class="fa fa-long-arrow-right" aria-hidden="true"></i></a> </li>
                    @endif
                @endif
            </ul>
            @else
                <div class="space1"></div>
                <span>BROWSE</span>
                <ul class="links">
                    <li> <a onclick="off()" href="/#home"><i class="fa fa-home" aria-hidden="true"></i> HOME</a></li>
                    <li> <a onclick="off()" href="/#aboutus"><i class="fa fa-info" aria-hidden="true"></i> ABOUT US</a></li>
                    <li> <a onclick="off()" href="/#product"><i class="fa fa-list-ul" aria-hidden="true"></i> PRODUCT</a></li>
                    <li> <a onclick="off()" href="/#testimonials"><i class="fa fa-quote-left" aria-hidden="true"></i> TESTIMONIALS</a></li>
                    <li> <a onclick="off()" href="/#contactus"><i class="fa fa-phone" aria-hidden="true"></i> CONTACT US</a></li>
                    <li> <a onclick="off()" href="https://drive.google.com/file/d/0B9TqTDu5OK_3Mm5qdGoyZ2huRFl2ZTd1SS01Q1c3d1EyY1lJ/view" target="_blank"><i class="fa fa-users" aria-hidden="true"></i> NETGIVING</a></li>
                </ul>
            @endif
        </nav>
    </div>

    <div class="blur-me">
        <div class="loader hide">
          <span><img src="/resources/assets/frontend/img/loader.gif"></span>
        </div>

        <!-- HEADER -->
        <div id="home" class="subheader-container">
            <div class="container">
                @if($customer)
                <div class="left-container">
                    <span><i class="fa fa-envelope" aria-hidden="true"></i></span>
                    <span>admin@p4wardph.com</span>
                    <span><i class="fa fa-phone" aria-hidden="true"></i></span>
                    <span>Call us 028995519</span>
                </div>
                <div class="right-container">
                    <span><a href="/members">MY ACCOUNT</a></span>
                    <span>|</i></span>
                    <span><a href="/members/logout">LOGOUT</a></i></span>
                </div>
                @else
                <div class="left-container">
                    <span><i class="fa fa-envelope" aria-hidden="true"></i></span>
                    <span>admin@p4wardph.com</span>
                    <span><i class="fa fa-phone" aria-hidden="true"></i></span>
                    <span>Call us 028995519</span>
                </div>
                <div class="right-container">
                    <span><a href="/members/register">REGISTER</a></span>
                    <span>|</i></span>
                    <span><a href="/members/login">LOGIN</a></i></span>
                </div>
                @endif
            </div>
        </div>
        <div class="header-container">
            <div class="container">
                <div class="row clearfix">
                    <div class="col-md-2">

                        <div id="nav_list" style="display: none;"><i class="fa fa-bars hamburger" onclick="on()"></i></div>

                        <div class="image-logo-holder">
                            <a class="clearfix" href="/">
                                <img src="/themes/{{ $shop_theme }}/img/header-logo.png">
                            </a>                       
                        </div>
                        {{-- <div class="menu-nav">
                            <span></span>
                            <span></span>
                            <span></span>
                        </div> --}}
                    </div>
                    <div class="col-md-10">
                    <!-- NAVIGATION -->
                        <nav class="navirino">
                            <ul>
                                @if(Request::segment(1)=="members")
                                    <li><a class="smoth-scroll" href="/#home">HOME</a></li>
                                    <li><a class="smoth-scroll" href="/#aboutus">ABOUT US</a></li>
                                    <li><a class="smoth-scroll" href="/#product">PRODUCT</a></li>
                                    <li><a class="smoth-scroll" href="/#testimonials">TESTIMONIALS</a></li>
                                    <li><a class="smoth-scroll" href="/#contactus">CONTACT</a></li>
                                    <li><a href="https://drive.google.com/file/d/0B9TqTDu5OK_3Mm5qdGoyZ2huRFl2ZTd1SS01Q1c3d1EyY1lJ/view" target="_blank">NETGIVING</a></li>
                                @elseif(Request::segment(1)=="product")
                                    <li><a class="smoth-scroll" href="/#home">HOME</a></li>
                                    <li><a class="smoth-scroll" href="/#aboutus">ABOUT US</a></li>
                                    <li><a class="smoth-scroll" href="/#product">PRODUCT</a></li>
                                    <li><a class="smoth-scroll" href="/#testimonials">TESTIMONIALS</a></li>
                                    <li><a class="smoth-scroll" href="/#contactus">CONTACT</a></li>
                                    <li><a href="https://drive.google.com/file/d/0B9TqTDu5OK_3Mm5qdGoyZ2huRFl2ZTd1SS01Q1c3d1EyY1lJ/view" target="_blank">NETGIVING</a></li>
                                @elseif(Request::segment(1)=="product2")
                                    <li><a class="smoth-scroll" href="/#home">HOME</a></li>
                                    <li><a class="smoth-scroll" href="/#aboutus">ABOUT US</a></li>
                                    <li><a class="smoth-scroll" href="/#product">PRODUCT</a></li>
                                    <li><a class="smoth-scroll" href="/#testimonials">TESTIMONIALS</a></li>
                                    <li><a class="smoth-scroll" href="/#contactus">CONTACT</a></li>
                                    <li><a href="https://drive.google.com/file/d/0B9TqTDu5OK_3Mm5qdGoyZ2huRFl2ZTd1SS01Q1c3d1EyY1lJ/view" target="_blank">NETGIVING</a></li>
                                @elseif(Request::segment(1)=="about")
                                    <li><a class="smoth-scroll" href="/#home">HOME</a></li>
                                    <li><a class="smoth-scroll" href="/#aboutus">ABOUT US</a></li>
                                    <li><a class="smoth-scroll" href="/#product">PRODUCT</a></li>
                                    <li><a class="smoth-scroll" href="/#testimonials">TESTIMONIALS</a></li>
                                    <li><a class="smoth-scroll" href="/#contactus">CONTACT</a></li>
                                    <li><a href="https://drive.google.com/file/d/0B9TqTDu5OK_3Mm5qdGoyZ2huRFl2ZTd1SS01Q1c3d1EyY1lJ/view" target="_blank">NETGIVING</a></li>
                                @else
                                    <li><a class="smoth-scroll" href="#home">HOME</a></li>
                                    <li><a class="smoth-scroll" href="#aboutus">ABOUT US</a></li>
                                    <li><a class="smoth-scroll" href="#product">PRODUCT</a></li>
                                    <li><a class="smoth-scroll" href="#testimonials">TESTIMONIALS</a></li>
                                    <li><a class="smoth-scroll" href="#contactus">CONTACT</a></li>
                                    <li><a href="https://drive.google.com/file/d/0B9TqTDu5OK_3Mm5qdGoyZ2huRFl2ZTd1SS01Q1c3d1EyY1lJ/view" target="_blank">NETGIVING</a></li>
                                @endif
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
        <footer id="bottom-footer">
            <div class="container">
                <div class="footer-container">
                    <div class="upper row clearfix">
                        <div class="col-md-3">
                            <div class="footer-icon-container">
                                <img src="\themes\p4ward\img\p4ward-icon-footer.png">
                            </div>
                            <div><p>P4ward Global Marketing started through the concept of giving. On December 22, 2016, a tragedy hit our family. An accident happened in the apartment of my brother which started a fire that engulfed the entire flat including his body...</div>
                            <div class="button-container"><a href="/about">READ MORE</a></div>
                        </div>
                        <div class="col-md-3">
                             <div class="footer-title-container">
                                <p class="footer-title">NAVIGATION</p>
                            </div>

                            @if(Request::segment(1)=="members")
                                <div class="nav-list"><a class="smoth-scroll" href="/#home">HOME</a></div>
                                <div class="nav-list"><a class="smoth-scroll" href="/#aboutus">ABOUT US</a></div>
                                <div class="nav-list"><a class="smoth-scroll" href="/#product">PRODUCT</a></div>
                                <div class="nav-list"><a class="smoth-scroll" href="/#testimonials">TESTIMONIALS</a></div>
                                <div class="nav-list"><a class="smoth-scroll" href="/#contactus">CONTACT</a></div>
                                <div class="nav-list"><a href="https://drive.google.com/file/d/0B9TqTDu5OK_3Mm5qdGoyZ2huRFl2ZTd1SS01Q1c3d1EyY1lJ/view" target="_blank">NETGIVING</a></div>
                            @elseif(Request::segment(1)=="product")
                                <div class="nav-list"><a class="smoth-scroll" href="/#home">HOME</a></div>
                                <div class="nav-list"><a class="smoth-scroll" href="/#aboutus">ABOUT US</a></div>
                                <div class="nav-list"><a class="smoth-scroll" href="/#product">PRODUCT</a></div>
                                <div class="nav-list"><a class="smoth-scroll" href="/#testimonials">TESTIMONIALS</a></div>
                                <div class="nav-list"><a class="smoth-scroll" href="/#contactus">CONTACT</a></div>
                                <div class="nav-list"><a href="https://drive.google.com/file/d/0B9TqTDu5OK_3Mm5qdGoyZ2huRFl2ZTd1SS01Q1c3d1EyY1lJ/view" target="_blank">NETGIVING</a></div>
                            @elseif(Request::segment(1)=="product2")
                                <div class="nav-list"><a class="smoth-scroll" href="/#home">HOME</a></div>
                                <div class="nav-list"><a class="smoth-scroll" href="/#aboutus">ABOUT US</a></div>
                                <div class="nav-list"><a class="smoth-scroll" href="/#product">PRODUCT</a></div>
                                <div class="nav-list"><a class="smoth-scroll" href="/#testimonials">TESTIMONIALS</a></div>
                                <div class="nav-list"><a class="smoth-scroll" href="/#contactus">CONTACT</a></div>
                                <div class="nav-list"><a href="https://drive.google.com/file/d/0B9TqTDu5OK_3Mm5qdGoyZ2huRFl2ZTd1SS01Q1c3d1EyY1lJ/view" target="_blank">NETGIVING</a></div>
                            @elseif(Request::segment(1)=="about")
                                <div class="nav-list"><a class="smoth-scroll" href="/#home">HOME</a></div>
                                <div class="nav-list"><a class="smoth-scroll" href="/#aboutus">ABOUT US</a></div>
                                <div class="nav-list"><a class="smoth-scroll" href="/#product">PRODUCT</a></div>
                                <div class="nav-list"><a class="smoth-scroll" href="/#testimonials">TESTIMONIALS</a></div>
                                <div class="nav-list"><a class="smoth-scroll" href="/#contactus">CONTACT</a></div>
                                <div class="nav-list"><a href="https://drive.google.com/file/d/0B9TqTDu5OK_3Mm5qdGoyZ2huRFl2ZTd1SS01Q1c3d1EyY1lJ/view" target="_blank">NETGIVING</a></div>
                            @else
                                <div class="nav-list"><a class="smoth-scroll" href="#home">HOME</a></div>
                                <div class="nav-list"><a class="smoth-scroll" href="#aboutus">ABOUT US</a></div>
                                <div class="nav-list"><a class="smoth-scroll" href="#product">PRODUCT</a></div>
                                <div class="nav-list"><a class="smoth-scroll" href="#testimonials">TESTIMONIALS</a></div>
                                <div class="nav-list"><a class="smoth-scroll" href="#contactus">CONTACT</a></div>
                                <div class="nav-list"><a href="https://drive.google.com/file/d/0B9TqTDu5OK_3Mm5qdGoyZ2huRFl2ZTd1SS01Q1c3d1EyY1lJ/view" target="_blank">NETGIVING</a></div>
                            @endif

                        </div>
                        <div class="col-md-3">
                            <div class="footer-title-container">
                                <p class="footer-title">PRODUCT PORTFOLIO</p>
                            </div>
                            <div class="product-list"><a href="/product">DON ORGANICS COFFEE SCRUB</a></div>
                            <div class="product-list"><a href="/product2">DON ORGANICS RED RICE SCRUB</a></div>
                        </div>
                        <div class="col-md-3">
                            <div class="footer-title-container">
                                <p class="footer-title">FOLLOW US ON</p>
                            </div>
                            <div class="social-icon-container">
                                <a href="https://facebook.com/p4ward" target="_blank"><span><i class="fa fa-facebook-square"></i></span></a>
                                <a href="https://mobile.twitter.com/P4wardph" target="_blank"><span><i class="fa fa-twitter-square"></i></span></a>
                                <a href="https://www.instagram.com/p4wardph/" target="_blank"><span><i class="fa fa-instagram"></i></span></a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </footer>
        <div class="container">
            <div class="bottom">                           
                <div class="ftr-title">© P4ward. All Rights Reserved</div>
                <div class="ftr-title-2">Powered By: DIGIMA WEB SOLUTIONS, Inc.</div>
            </div>
        </div>
    </div>

    @include("frontend.gfoot")
    <script type="text/javascript" src="/themes/{{ $shop_theme }}/js/global.js"></script>
    <script type="text/javascript" src="/themes/{{ $shop_theme }}/js/theme_custom.js"></script>
    
    <!-- FB WIDGET -->
    <div id="fb-root"></div>

     @yield("script")
    </body>
</html>
