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
        <link rel="icon" href="/themes/{{ $shop_theme }}/img/icon/icon-logo.png" type="image/png"/>
        <!-- GOOGLE FONT -->
        <link href="https://fonts.googleapis.com/css?family=Rubik:300,400,500,700" rel="stylesheet"> 
        <link href="https://fonts.googleapis.com/css?family=Roboto:100,300,400,500,700" rel="stylesheet">
        @include("frontend.ghead")
        <!-- GLOBAL CSS -->
        <link rel="stylesheet" type="text/css" href="/themes/{{ $shop_theme }}/css/global.css">
        <!-- OTHER CSS -->
        @yield("css")
        {{-- RESPONSIVE CSS --}}
        <link rel="stylesheet" type="text/css" href="/themes/{{ $shop_theme }}/css/responsive.css">
        <link rel="stylesheet" type="text/css" href="/themes/{{ $shop_theme }}/css/push_sidenav.css">

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
                    <li> <a onclick="off()" href="/"><i class="fa fa-home" aria-hidden="true"></i> Home</a></li>
                    <li> <a onclick="off()" href="/about"><i class="fa fa-building-o" aria-hidden="true"></i> Company</a></li>
                    <li> <a onclick="off()" href="/product"><i class="fa fa-list-ul" aria-hidden="true"></i> Products</a> </li>
                    <li> <a onclick="off()" href="/contact"><i class="fa fa-envelope" aria-hidden="true"></i> Get in touch</a></li>
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
                            <li><a href="/members/genealogy?mode=sponsor">Unilevel Tree</a></li>
                        </ul>
                    <li> <a href="/members/report"><i class="fa fa-bar-chart" aria-hidden="true"></i> Reports</a></li>
                    <li> <a href="/members/report-points"><i class="fa brown-icon-flow-tree" area-hidden="true"></i> Network List</a></li>
                    <li> <a href="/members/wallet-encashment"><i class="fa brown-icon-wallet" aria-hidden="true"></i> Wallet Encashment</a></li>

                    @else
                    @endif
                    <li> <a href="/members/order"><i class="fa brown-icon-bag" aria-hidden="true"></i> Orders</a></li>
                    @if($customer)
                        <li class="user-logout"> <a href="/members/logout">Logout &nbsp;<i class="fa fa-long-arrow-right" aria-hidden="true"></i></a></li>
                    @endif
                </ul>
                @else
                    <div class="space1"></div>
                    <span>BROWSE</span>
                    <ul class="links">
                        <li> <a onclick="off()" href="/"><i class="fa fa-home" aria-hidden="true"></i> Home</a></li>
                        <li> <a onclick="off()" href="/about"><i class="fa fa-building-o" aria-hidden="true"></i> Company</a></li>
                        <li> <a onclick="off()" href="/product"><i class="fa fa-list-ul" aria-hidden="true"></i> Products</a> </li>
                        <li> <a onclick="off()" href="/contact"><i class="fa fa-envelope" aria-hidden="true"></i> Get in touch</a></li>
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
                        <span><i class="fa fa-envelope-o" aria-hidden="true"></i></span>
                        <span>yourcompany.email</span>
                    </div>
                    <div class="right-container">
                        <span style="cursor: pointer;" onClick="location.href='/members/logout'">LOGOUT</span>
                        <span style="cursor: pointer;" onClick="location.href='/members'">MY ACCOUNT</span></div>
                    @else
                    <div class="left-container">
                        <span><i class="fa fa-envelope-o" aria-hidden="true"></i></span>
                        <span>yourcompany.email</span>
                    </div>
                    <div class="right-container">
                        <span class="smoth-scroll" style="cursor: pointer;" onClick="location.href='/members/register'">REGISTER</span>
                        <span class="smoth-scroll sign" style="cursor: pointer;" onClick="location.href='/members/login'">SIGN IN</span>
                    </div>
                    @endif
                </div>
            </div>

            <div class="header-container">
                <div class="container">
                    <div class="row clearfix">
                        <div class="col-md-2 col-sm-12">
                            
                            <div id="nav_list" onclick="on()"><i class="fa fa-bars hamburger"></i></div>
                            
                            <div class="image-logo-holder clearfix">
                                <a class="clearfix" href="/"><img src="/themes/{{ $shop_theme }}/img/shift-logo.png"></a>
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
                                    <li class="{{ Request::segment(1) == "" ? "active" : "" }}"><a href="/">HOME</a></li>
                                    <li class="{{ Request::segment(1) == "about" ? "active" : "" }}"><a href="/about">COMPANY</a></li>
                                    <li class="{{ Request::segment(1) == "product" ? "active" : "" }}"><a href="/product">PRODUCTS</a></li>
                                    <li class="{{ Request::segment(1) == "contact" ? "active" : "" }}"><a href="/contact">GET INTOUCH</a></li>
                                </ul>
                            </nav>
                        </div>
                    </div>
                </div>
            </div> 
            
            <!-- CONTENT -->
            <div id="scroll-to" class="clearfix">
                <div class="content">
                    @yield("content")
                </div>
            </div>

            <!-- FOOTER -->
            <footer id="bottom-footer">
                <div class="container">
                    <div class="footer-container">
                        <div class="upper row clearfix">
                            <div class="col-md-4 col-sm-6">
                                <div class="inner-content">
                                    <img src="/themes/{{ $shop_theme }}/img/footer-banner.jpg">
                                </div>
                            </div>
                             <div class="col-md-4 col-sm-6">
                                 <div class="inner-content">
                                     <div class="inner-content-title">Information</div>
                                     <p>FAQ</p>
                                     <p>Downloadables</p>
                                     <p>Get In Touch</p>
                                     <p>Company</p>
                                 </div>
                             </div> 
                             <div class="col-md-4 col-sm-12">
                                 <div class="inner-content">
                                     <div class="inner-content-title">Contact Information</div>
                                     <p>Lorem ipsum dolor sit amet, consectetuer adipiscing elit. Aenean commodo ligula eget dolor. Aenean massa. </p>
                                     <p>042 0000</p>
                                     <p>youremailhere@gmail.com</p>
                                 </div>
                             </div>
                        </div>
                    </div>
                </div>
            </footer>
            
            <div class="bottom clearfix">      
                <div class="container">                     
                    <div class="ftr-title">© {{ date("Y") }} SHIFT Business Corporation. All Right Reserved</div>
                    <div class="ftr-title-2">Powered By: DIGIMA WEB SOLUTIONS, Inc.</div>
                </div>
            </div>
        </div>

    @include("frontend.gfoot")

    <script src="/themes/{{ $shop_theme }}/js/global.js"></script>

    @yield("script")

    </body>
</html>
