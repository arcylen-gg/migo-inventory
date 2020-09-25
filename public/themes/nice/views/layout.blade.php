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
        <link rel="icon" href="/themes/{{ $shop_theme }}/img/nice-icon.png" type="image/png" />
    
        <!-- GOOGLE FONT -->
        <link href="https://fonts.googleapis.com/css?family=Lato:400,700" rel="stylesheet"> 
        <link href="https://fonts.googleapis.com/css?family=PT+Sans:400,700" rel="stylesheet">    

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
                    <li> <a onclick="off()" href="/#home"><i class="fa fa-home" aria-hidden="true"></i> Home</a></li>
                    <li> <a onclick="off()" href="/#aboutus"><i class="fa fa-info" aria-hidden="true"></i> ABOUT US</a></li>
                    <li> <a onclick="off()" href="/#whytojoin"><i class="fa fa-handshake-o" aria-hidden="true"></i> WHY TO JOIN</a></li>
                    <li> <a onclick="off()" href="/#packages"><i class="fa fa-archive" aria-hidden="true"></i> PACKAGES</a></li>
                    <li> <a onclick="off()" href="/#partners"><i class="fa fa-users" aria-hidden="true"></i> OUR PARTNERS</a></li>
                    <li> <a onclick="off()" href="/#contactus"><i class="fa fa-phone" aria-hidden="true"></i> CONTACT US</a></li>
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
                    @else
                    @endif
                </ul>
                @else
                    <div class="space1"></div>
                    <span>BROWSE</span>
                    <ul class="links">
                        <li> <a onclick="off()" href="/#home"><i class="fa fa-home" aria-hidden="true"></i> Home</a></li>
                        <li> <a onclick="off()" href="/#aboutus"><i class="fa fa-info" aria-hidden="true"></i> ABOUT US</a></li>
                        <li> <a onclick="off()" href="/#whytojoin"><i class="fa fa-handshake-o" aria-hidden="true"></i> WHY TO JOIN</a></li>
                        <li> <a onclick="off()" href="/#packages"><i class="fa fa-archive" aria-hidden="true"></i> PACKAGES</a></li>
                        <li> <a onclick="off()" href="/#partners"><i class="fa fa-users" aria-hidden="true"></i> OUR PARTNERS</a></li>
                        <li> <a onclick="off()" href="/#contactus"><i class="fa fa-phone" aria-hidden="true"></i> CONTACT US</a></li>
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
                        <span><i class="fa fa-mobile" aria-hidden="true"></i></span>
                        <span>{!! get_content($shop_theme_info, "home", "home_contact_number") !!}</span>
                        <span><i class="fa fa-envelope-o" aria-hidden="true"></i></span>
                        <span>{!! get_content($shop_theme_info, "home", "home_email_address") !!}</span>
                        <span><i class="fa fa-facebook" aria-hidden="true"></i></span>
                        <span>Facebook</span>
                    </div>
                    <div class="right-container"><span style="cursor: pointer;" onClick="location.href='/members/logout'">LOGOUT</span><span style="cursor: pointer;" onClick="location.href='/members'">MY ACCOUNT</span></div>
                    @else
                    <div class="left-container">
                        <span><i class="fa fa-mobile" aria-hidden="true"></i></span>
                        <span>{!! get_content($shop_theme_info, "home", "home_contact_number") !!}</span>
                        <span><i class="fa fa-envelope-o" aria-hidden="true"></i></span>
                        <span>{!! get_content($shop_theme_info, "home", "home_email_address") !!}</span>
                        <span><i class="fa fa-facebook" aria-hidden="true"></i></span>
                        <span>Facebook</span>
                    </div>
                    <div class="right-container">
                        <span class="smoth-scroll" style="cursor: pointer;" onClick="location.href='/members/register'">REGISTER</span>
                        <span class="smoth-scroll" style="cursor: pointer;" onClick="location.href='/members/login'">
                            <div class="subhead-btn">SIGN IN</div>
                        </span>
                    </div>
                    @endif
                </div>
            </div>
            
            <div class="header-container">
                <div class="container">
                    <div class="row clearfix">
                        <div class="col-md-2">
                            <div id="nav_list" onclick="on()"><i class="fa fa-bars hamburger"></i></div>
                            <div class="image-logo-holder">
                                <a class="clearfix" href="/">
                                    <img src="/themes/{{ $shop_theme }}/img/header-logo.png">
                                </a>                       
                            </div>
                            <!-- <div class="menu-nav">
                                <span></span>
                                <span></span>
                                <span></span>
                            </div> -->
                        </div>
                        <div class="col-md-10">
                        <!-- NAVIGATION -->
                            <nav class="navirino">
                                <ul>
                                    @if(Request::segment(1)=="members")
                                        <li><a class="smoth-scroll" href="/#home">Home</a></li>
                                        <li><a class="smoth-scroll" href="/#aboutus">About Us</a></li>
                                        <li><a class="smoth-scroll" href="/#whytojoin">Why to Join</a></li>
                                        <li><a class="smoth-scroll" href="/#packages">Packages</a></li>
                                        <li><a class="smoth-scroll" href="/#partners">Our Partners</a></li>
                                        <li><a class="smoth-scroll" href="/#contactus">Contact Us</a></li>
                                    @else
                                        <li><a class="smoth-scroll" href="#home">Home</a></li>
                                        <li><a class="smoth-scroll" href="#aboutus">About Us</a></li>
                                        <li><a class="smoth-scroll" href="#whytojoin">Why to Join</a></li>
                                        <li><a class="smoth-scroll" href="#packages">Packages</a></li>
                                        <li><a class="smoth-scroll" href="#partners">Our Partners</a></li>
                                        <li><a class="smoth-scroll" href="#contactus">Contact Us</a></li>
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
                                <div class="footer-title-container">
                                    <p class="footer-title">INFORMATION</p>
                                </div>
                                <a href="/"><p>HOME</p></a>
                                <a href="/#aboutus"><p>ABOUT US</p></a>
                                <a href="/#whytojoin"><p>WHY TO JOIN</p></a>
                                <a href="/#packages"><p>PACKAGES</p></a>
                                <a href="/#partners"><p>OUR PARTNERS</p></a>
                                <a href="/#contactus"><p>CONTACT US</p></a>
                            </div>
                            <div class="col-md-4">
                                <div class="footer-title-container">
                                    <p class="footer-title">NEWS LETTER</p>
                                </div>
                                <p style="text-transform: uppercase; letter-spacing: 0.5px;">
                                    Cum sociis natoque penatibus et magnis dis parturient montes, nascetur ridiculus mus. 
                                </p>
                                <input type="text" placeholder="Enter Your Email Here">
                                <span style="padding-left: 10px;">
                                    <i class="fa fa-paper-plane" aria-hidden="true"></i>
                                </span>
                            </div>
                            <div class="col-md-4">
                                <div class="footer-title-container">
                                    <p class="footer-title">FOLLOW US ON</p>
                                </div>
                                
                                <div class="social-icon-container">
                                    <span><i class="fa fa-facebook-square"></i></span>
                                    <span><i class="fa fa-twitter-square"></i></span>
                                    <span><i class="fa fa-pinterest-square"></i></span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </footer>

            <div class="container">
                <div class="bottom">                           
                    <div class="ftr-title">© Nicenterprises All Right Reserved</div>
                    <div class="ftr-title-2">Powered By: DIGIMA WEB SOLUTIONS, Inc.</div>
                </div>
            </div>
            
        </div>
        
    @include("frontend.gfoot")
    <!-- <script type="text/javascript" src="/themes/{{ $shop_theme }}/js/global.js"></script> -->
    <script type="text/javascript" src="/themes/{{ $shop_theme }}/js/theme_custom.js"></script>
    
    <!-- FB WIDGET -->
    <div id="fb-root"></div>

     @yield("script")
    </body>
</html>
