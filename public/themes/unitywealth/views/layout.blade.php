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
    {{-- <link rel="apple-touch-icon" href="apple-touch-icon.png"> --}}
    <link rel="icon" href="/themes/{{ $shop_theme }}/img/unity-wealth-icon.png" type="image/png" />
    <!-- GOOGLE FONT -->
    <link href="https://fonts.googleapis.com/css?family=Raleway:300,400,500,700" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css?family=Ubuntu:300,400,500" rel="stylesheet">
    <!-- GLOBAL CSS -->
    <link rel="stylesheet" type="text/css" href="/themes/{{ $shop_theme }}/css/global.css">
    <link rel="stylesheet" type="text/css" href="/themes/{{ $shop_theme }}/css/push_sidenav.css?version=2">
    <!-- VIDEO LIGHTBOX -->
    <link rel="stylesheet" type="text/css" media="all" href="/themes/{{ $shop_theme }}/assets/lity/assets/prism.css">
    <link rel="stylesheet" href="/themes/{{ $shop_theme }}/assets/lity/dist/lity.css">
    
    <!-- <link rel="stylesheet" type="text/css" href="/themes/{{ $shop_theme }}/css/new_sidenav.css"> -->
    <!-- COUNTDOWN TIMER -->
    <link rel="stylesheet" href="/themes/{{ $shop_theme }}/assets/css/animate.css">
    {{-- <link rel="stylesheet" href="/themes/{{ $shop_theme }}/assets/css/form-elements.css"> --}}
    <link rel="stylesheet" href="/themes/{{ $shop_theme }}/assets/css/style.css">
    <link rel="stylesheet" href="/themes/{{ $shop_theme }}/assets/css/media-queries.css">
    
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
        <!-- MOBILE PUSH MENU -->
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
                <li class="{{ Request::segment(2) == "" ? "active" : "" }}"> <a href="/"><i class="fa fa-home" aria-hidden="true"></i> Home</a> </li>
                <li onclick="off()"> <a class="smoth-scroll" href="/#company-profile"><i class="fa fa-rocket" aria-hidden="true"></i> Company Profile</a> </li>
                {{-- <li> <a href="javascript:">Mission & Vision</a> </li> --}}
                <li onclick="off()"> <a href="javascript:"><i class="fa fa-phone" aria-hidden="true"></i> Contact Us</a> </li>
            </ul>
            
            <div class="space2"></div>
            <span>MEMBER'S AREA</span>
            <ul class="links">
                <li class="{{ Request::segment(1) == "members" ? "active" : "" }}" > <a href="/members"><i class="fa brown-icon-dashboard" aria-hidden="true"></i> Dashboard</a> </li>
                <li> <a href="/members/profile"><i class="fa brown-icon-profile" aria-hidden="true"></i> Profile</a> </li>
                @if($mlm_member)
                {{-- <li> <a href="javascript:">Videos</a> </li> --}}
                <li class="{{ Request::segment(2) == "products" ? "active" : "" }}"> <a href="/members/ebooks"><i class="fa fa-book" aria-hidden="true"></i> Ebooks</a> </li>
                <li class="{{ Request::segment(2) == "products" ? "active" : "" }}"> <a href="/members/videos"><i class="fa fa-play" aria-hidden="true"></i> Products</a> </li>
                <li class="{{ Request::segment(2) == "certificate" ? "active" : "" }}"> <a href="/members/certificate"><i class="fa fa-certificate" aria-hidden="true"></i> Certificate</a> </li>
                <li class="{{ Request::segment(2) == "genealogy" ? "active" : "" }}"> <a href="/members/genealogy?mode=sponsor"><i class="fa brown-icon-flow-tree" aria-hidden="true"></i> Genealogy</a> </li>
                <li class="{{ Request::segment(2) == "report" ? "active" : "" }}"> <a href="/members/report"><i class="fa brown-icon-bar-chart" aria-hidden="true"></i> Reports</a> </li>
                <li class="{{ Request::segment(2) == "lead-list" ? "active" : "" }}"> <a href="/members/lead-list"><i class="fa brown-icon-heart" aria-hidden="true"></i> Lead List</a> </li>
                <li class="{{ Request::segment(2) == "wallet-encashment" ? "active" : "" }}"> <a href="/members/wallet-encashment"><i class="fa brown-icon-wallet" aria-hidden="true"></i> Wallet</a> </li>
                    @if($customer)
                        <li class="user-logout"> <a href="/members/logout">Logout &nbsp;<i class="fa fa-long-arrow-right" aria-hidden="true"></i></a> </li>
                    @endif
                @else
                @endif
            </ul>
            @else
                <div class="space1"></div>
                <span>BROWSE</span>
                <ul class="links">
                    <li> <a href="/"><i class="fa fa-home" aria-hidden="true"></i> Home</a> </li>
                    <li onclick="off()"> <a class="smoth-scroll" href="/#company-profile"><i class="fa fa-rocket" aria-hidden="true"></i> Company Profile</a> </li>
                    {{-- <li> <a href="javascript:">Mission & Vision</a> </li> --}}
                    <li onclick="off()"> <a href="javascript:"><i class="fa fa-phone" aria-hidden="true"></i> Contact Us</a> </li>
                </ul>
            @endif
        </nav>
    </div>

    <div class="blur-me">
        <div class="loader hide">
            <span><img src="/resources/assets/frontend/img/loader.gif"></span>
        </div>
        <!-- HEADER -->
        <!-- <ul class="navigation">
            <li class="nav-item"><a href="#">Home</a></li>
            <li class="nav-item"><a href="#">Portfolio</a></li>
            <li class="nav-item"><a href="#">About</a></li>
            <li class="nav-item"><a href="#">Blog</a></li>
            <li class="nav-item"><a href="#">Contact</a></li>
        </ul>
        
        <input type="checkbox" id="nav-trigger" class="nav-trigger" />
        <label for="nav-trigger"></label> -->
        <div id="home" class="subheader-container">
            <div class="container">
                @if($customer)
                <!-- <div class="left-container"><span><i class="fa fa-envelope-o" aria-hidden="true"></i></span><span>company-email.here</span></div> -->
                <div class="right-container"><span style="cursor: pointer;" onClick="location.href='/members/logout'">LOGOUT</span><span style="cursor: pointer;" onClick="location.href='/members'">MY ACCOUNT</span></div>
                @else
                <!-- <div class="left-container">
                    <span><i class="fa fa-envelope-o" aria-hidden="true"></i></span>
                    <span>company-email.here</span>
                </div> -->
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
                    <div class="col-md-3">
                        @if(request()->segment(1) != "replicated")
                        <div id="nav_list" onclick="on()"><i class="fa fa-bars hamburger"></i></div>
                        @endif

                        <div class="image-logo-holder">
                            <a class="clearfix" href="/">
                                <img src="/themes/{{ $shop_theme }}/img/header-logo.png">
                            </a>
                        </div>
                        @if(request()->segment(1) != "replicated")
                        <!-- <div class="menu-nav">
                            <span></span>
                            <span></span>
                            <span></span>
                        </div> -->
                        @endif
                    </div>
                    <div class="col-md-9">
                        <!-- NAVIGATION -->
                        <nav class="navirino">
                            <ul>
                                @if(Request::segment(1)=="members")
                                <li><a class="smoth-scroll" href="/">HOME</a></li>
                                <li><a class="smoth-scroll" href="/#company-profile">COMPANY PROFILE</a></li>
                                {{-- <li><a class="smoth-scroll" href="/#mission-vision">MISSION & VISION</a></li> --}}
                                <li><a class="smoth-scroll" href="/#contact">CONTACT US</a></li>
                                @else
                                <li><a class="smoth-scroll" href="#home">HOME</a></li>
                                <li><a class="smoth-scroll" href="#company-profile">COMPANY PROFILE</a></li>
                                {{-- <li><a class="smoth-scroll" href="#mission-vision">MISSION & VISION</a></li> --}}
                                <li><a class="smoth-scroll" href="#contact">CONTACT US</a></li>
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
                        <div class="col-md-4">
                            <div class="footer-img-container"><img src="/themes/{{ $shop_theme }}/img/logo-inverted.png"></div>
                            <div class="footer-profile-content">
                                <p>
                                    {{ get_content($shop_theme_info, "footer-details", "footer_company_profile") }} 
                                </p>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="footer-title">
                                NAVIGATION
                            </div>
                            <div class="footer-details">
                                <ul>
                                    <a href="/"><li>HOME</li></a>
                                    <a class="smoth-scroll" href="/#company-profile"><li>COMPANY</li></a>
                                    <a href=""><li>MISSION & VISION</li></a>
                                    <a href=""><li>CONTACT US</li></a>
                                </ul>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="footer-title">
                                CONTACT INFORMATION
                            </div>
                            <div class="footer-details">
                                <p>
                                   <span></span> 
                                </p>
                                <p>
                                   <span>Phone:&nbsp;&nbsp;</span><span>{{ get_content($shop_theme_info, "footer-details", "footer_phone_number") }}</span> 
                                </p>
                                <p>
                                    <span>Email Address:&nbsp;&nbsp;</span><span>{{ get_content($shop_theme_info, "footer-details", "footer_email_address") }}</span>
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </footer>
        <div class="container">
            <div class="bottom">
                <div class="ftr-title">© UnityWealth. All Right Reserved</div>
                <div class="ftr-title-2">Powered By: DIGIMA WEB SOLUTIONS, Inc.</div>
            </div>
        </div>
    </div>
    
    @include("frontend.gfoot")
    {{-- GLOBALS --}}
    <script type="text/javascript" src="/themes/{{ $shop_theme }}/js/theme_custom.js?version=2"></script>
    <!-- COUNTDOWN TIMER -->
    {{-- <script src="/themes/{{ $shop_theme }}/assets/countdown/jquery.backstretch.min.js"></script>
    <script src="/themes/{{ $shop_theme }}/assets/countdown/jquery.countdown.min.js"></script>
    <script src="/themes/{{ $shop_theme }}/assets/countdown/wow.min.js"></script>
    <script src="/themes/{{ $shop_theme }}/assets/countdown/scripts.js?version=1"></script> --}}

    <!-- LITY -->
    {{-- <script src="/themes/{{ $shop_theme }}/assets/lity/vendor/jquery.js"></script> --}}
    <script src="/themes/{{ $shop_theme }}/assets/lity/dist/lity.js"></script>
    <script src="/themes/{{ $shop_theme }}/assets/lity/assets/prism.js"></script>
    
    <!-- FB WIDGET -->
    <div id="fb-root"></div>
    @yield("script")
</body>
</html>