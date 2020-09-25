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
    <link rel="icon" href="/themes/{{ $shop_theme }}/img/favicon.png" type="image/png"/>
    <!-- GOOGLE FONT -->
    <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,400,600,700" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css?family=Ubuntu:300,400,500" rel="stylesheet">
    <!-- GLOBAL CSS -->
    <link rel="stylesheet" type="text/css" href="/themes/{{ $shop_theme }}/css/global.css">
    <link rel="stylesheet" type="text/css" href="/themes/{{ $shop_theme }}/css/push_sidenav.css">
    <link rel="stylesheet" type="text/css" href="/themes/{{ $shop_theme }}/css/mobile_res.css">
    
    @include("frontend.ghead")
    <!-- OTHER CSS -->
    @yield("css")
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
                <li> <a onclick="off()" href="/#about"><i class="fa fa-info" aria-hidden="true"></i> ABOUT</a></li>
                <li> <a onclick="off()" href="/#missionvision"><i class="fa fa-rocket" aria-hidden="true"></i> MISSION AND VISION</a></li>
                <li> <a onclick="off()" href="/#howitworks"><i class="fa fa-question" aria-hidden="true"></i> HOW IT WORKS</a></li>
                <li> <a onclick="off()" href="/#products"><i class="fa fa-list-ul" aria-hidden="true"></i> PRODUCTS</a></li>
                <li> <a onclick="off()" href="/#news"><i class="fa fa-newspaper-o" aria-hidden="true"></i> NEWS</a></li>
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
                    {{-- <li><a href="/members/genealogy?mode=binary">Binary Tree</a></li> --}}
                    <li><a href="/members/genealogy?mode=sponsor">Unilevel Tree</a></li>
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
                <li> <a onclick="off()" href="/#home"><i class="fa fa-home" aria-hidden="true"></i> HOME</a></li>
                <li> <a onclick="off()" href="/#about"><i class="fa fa-info" aria-hidden="true"></i> ABOUT</a></li>
                <li> <a onclick="off()" href="/#missionvision"><i class="fa fa-rocket" aria-hidden="true"></i> MISSION AND VISION</a></li>
                <li> <a onclick="off()" href="/#howitworks"><i class="fa fa-question" aria-hidden="true"></i> HOW IT WORKS</a></li>
                <li> <a onclick="off()" href="/#products"><i class="fa fa-list-ul" aria-hidden="true"></i> PRODUCTS</a></li>
                <li> <a onclick="off()" href="/#news"><i class="fa fa-newspaper-o" aria-hidden="true"></i> NEWS</a></li>
                <li> <a onclick="off()" href="/members/login"><i class="fa fa-sign-in" aria-hidden="true"></i> SIGNIN</a></li>
                <li> <a onclick="off()" href="/members/register"><i class="fa fa-pencil-square-o" aria-hidden="true"></i> REGISTER</a></li>
            </ul>
            @endif
        </nav>
    </div>
    <div class="blur-me">
        <div class="loader hide">
            <span><img src="/resources/assets/frontend/img/loader.gif"></span>
        </div>
        <!-- HEADER -->
        <header class="header-container">
            <div class="nav-holder">
                <div class="container">
                    <div class="row-no-padding clearfix">
                        <div class="center-logo-on-mobile col-md-2">
                            <div id="nav_list" onclick="on()"><i class="fa fa-bars hamburger"></i></div>
                            <div class="image-logo-holder">
                                <a class="clearfix logo-1" href="/"><img src="/themes/{{ $shop_theme }}/img/icoins-logo.png"></a>
                                <a class="clearfix logo-2" href="/"><img src="/themes/{{ $shop_theme }}/img/logo-2.png"></a>
                            </div>
                        </div>
                        <div class="remove-on-mobile col-md-7">
                            <nav class="navigation">
                                <ul>
                                    @if(Request::segment(1) == "members")
                                    <li><a class="navigation__link" href="/#home">HOME</a></li>
                                    <li><a class="navigation__link" href="/#about">ABOUT</a></li>
                                    <li><a class="navigation__link" href="/#missionvision">MISSION AND VISION</a></li>
                                    <li><a class="navigation__link" href="/#howitworks">HOW IT WORKS</a></li>
                                    <li><a class="navigation__link" href="/#products">PRODUCTS</a></li>
                                    <li><a class="navigation__link" href="/#news">NEWS</a></li>
                                    @elseif(Request::segment(1) == "news")
                                    <li><a class="navigation__link" href="/#home">HOME</a></li>
                                    <li><a class="navigation__link" href="/#about">ABOUT</a></li>
                                    <li><a class="navigation__link" href="/#missionvision">MISSION AND VISION</a></li>
                                    <li><a class="navigation__link" href="/#howitworks">HOW IT WORKS</a></li>
                                    <li><a class="navigation__link" href="/#products">PRODUCTS</a></li>
                                    <li><a class="navigation__link" href="/#news">NEWS</a></li>
                                    @else
                                    <li><a class="navigation__link" href="#home">HOME</a></li>
                                    <li><a class="navigation__link" href="#about">ABOUT</a></li>
                                    <li><a class="navigation__link" href="#missionvision">MISSION AND VISION</a></li>
                                    <li><a class="navigation__link" href="#howitworks">HOW IT WORKS</a></li>
                                    <li><a class="navigation__link" href="#products">PRODUCTS</a></li>
                                    <li><a class="navigation__link" href="#news">NEWS</a></li>
                                    @endif
                                </ul>
                            </nav>
                        </div>
                        <div class="remove-on-mobile col-md-3">
                            <div class="nav">
                                <ul>
                                    @if($customer)
                                    <li><a class="pr {{ Request::segment(1) == "members" ? "active" : "" }}" href="/members">MY ACCOUNT</a></li>
                                    <li><a href="/members/logout"><button class="btn-signin">LOGOUT</button></a></li>
                                    @else
                                    <li><a class="{{ Request::segment(2) == "login" ? "active" : "" }}" href="/members/login"><button class="btn-signin">SIGNIN</button></a></li>
                                    <li style="margin-left: 10px;"><a class="{{ Request::segment(2) == "register" ? "active" : "" }}" href="/members/register"><button class="btn-signin">REGISTER</button></a></li>
                                    @endif
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </header>
    <!-- CONTENT -->
    <div id="scroll-to" class="clearfix">
        @yield("content")
    </div>
    <!-- FOOTER -->
    <footer>
        <div class="container">
            <div class="border"></div>
            <div class="row clearfix">
                <div class="col-md-6 col-xs-6">
                    <div class="bottom-text pull-left">Copyright © 2018 Icoinsshop. All Rights Reserved.</div>
                </div>
                <div class="col-md-6 col-xs-6">
                    <div class="bottom-text pull-right">Powered By: DIGIMA WEB SOLUTIONS, Inc.</div>
                </div>
            </div>
        </div>
    </footer>
</div>

@include("frontend.gfoot")
{{-- GLOBALS --}}
<script type="text/javascript" src="/themes/{{ $shop_theme }}/js/theme_custom.js"></script>
<script type="text/javascript" src="/themes/{{ $shop_theme }}/js/smooth-scroll.js"></script>

<!-- FB WIDGET -->
<div id="fb-root"></div>
@yield("script")
</body>
</html>