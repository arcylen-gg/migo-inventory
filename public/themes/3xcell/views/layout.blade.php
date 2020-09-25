<!doctype html>
<!--[if lt IE 7]>      <html class="no-js lt-ie9 lt-ie8 lt-ie7" lang=""> <![endif]-->
<!--[if IE 7]>         <html class="no-js lt-ie9 lt-ie8" lang=""> <![endif]-->
<!--[if IE 8]>         <html class="no-js lt-ie9" lang=""> <![endif]-->
<!--[if gt IE 8]><!--> <html class="no-js" lang=""> <!--<![endif]-->
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
        <title>{{ ucfirst($shop_info->shop_key) }} | {{ $page or '' }}</title>
        <meta name="description" content="">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <!-- <link rel="apple-touch-icon" href="apple-touch-icon.png"> -->
        <link rel="icon" href="/themes/{{ $shop_theme }}/img/3xcell-icon.png"" type="image/png"/>
        <!-- GOOGLE FONT -->
        <link href="https://fonts.googleapis.com/css?family=Roboto:400,500,700" rel="stylesheet">
        <link href="https://fonts.googleapis.com/css?family=Fjalla+One" rel="stylesheet">
        <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,400,600,700,800" rel="stylesheet">
        <link rel="stylesheet" type="text/css" href="/assets/member/plugin/toaster/toastr.css">
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
                <div class="row-no-padding clearfix">
                    <div class="col-xs-12">
                        <div class="divider"><span>BROWSE</span></div>
                    </div>
                </div>

                <ul class="links">
                    {{-- @if($customer_info) --}}
                    <li><a href="/"><i class="fa fa-home" aria-hidden="true"></i> Home</a></li>
                    {{-- @else
                    <li><a href="/members" id="home">MY ACCOUNT</a></li>
                    @endif --}}
                    <li class="product-mobile-dropdown">
                        <a href="javascript:"><i class="fa fa-list-ul" aria-hidden="true"></i> PRODUCTS <span class="pull-right"><i class="fa-change fa fa-angle-down" aria-hidden="true"></i></span></a>
                    </li>
                        @if(count($_categories) > 0)
                            <ul class="product-mobile-dropdown-list">
                                @foreach($_categories as $categories)
                                    <li>
                                        <a href="/product?type={{ $categories["type_id"] }}">
                                            {{ $categories["type_name"] }}
                                        </a>
                                    </li>
                                @endforeach
                            </ul>
                        @endif
                    <li> <a href="/promos"><i class="fa fa-percent" aria-hidden="true"></i> PROMOS</a></li>
                    <li class="company-mobile-dropdown">
                        <a href="javascript:"><i class="fa fa-info-circle" aria-hidden="true"></i> COMPANY <span class="pull-right"><i class="fa-change fa fa-angle-down" aria-hidden="true"></i></span></a>
                    </li>
                        <ul class="company-mobile-dropdown-list">
                            <li><a href="/history"><i class="fa fa-history" aria-hidden="true"></i> Our History</a></li>
                            <li><a href="/how_to_join"><i class="fa fa-question-circle-o" aria-hidden="true"></i> How to Join</a></li>
                            <li><a href="/events"><i class="fa fa-calendar" aria-hidden="true"></i> Company Events</a></li>
                        </ul>
                    <li> <a href="/gallery"><i class="fa fa-picture-o" aria-hidden="true"></i> GALLERY</a> </li>
                    <li> <a href="/contact"><i class="fa fa-phone" aria-hidden="true"></i> CONTACT US</a> </li>
                </ul>
                
                <div class="space2"></div>
                <div class="row-no-padding clearfix">
                    <div class="col-xs-12">
                        <div class="divider"><span>MEMBER'S AREA</span></div>
                    </div>
                </div>

                <ul class="links">
                    <li> <a href="/members"><i class="fa brown-icon-dashboard" aria-hidden="true"></i> Dashboard</a></li>
                    <li> <a href="/members/profile"><i class="fa brown-icon-profile" aria-hidden="true"></i> Profile</a></li>
                    @if($mlm_member)
                    <li> <a href="/members/genealogy?mode=sponsor"><i class="fa brown-icon-flow-tree" area-hidden="true"></i> Genealogy</a></li>
                    <li> <a href="/members/report"><i class="fa fa-bar-chart" aria-hidden="true"></i> Reports</a></li>
                    <li> <a href="/members/report-points"><i class="fa fa-bar-chart" aria-hidden="true"></i> Reports (Points)</a></li>
                    <li> <a href="/members/wallet-encashment"><i class="fa brown-icon-wallet" aria-hidden="true"></i> Wallet Encashment</a></li>
                        @if($customer)
                            <li class="user-logout"> <a href="/members/logout"><i class="fa fa-sign-out" aria-hidden="true"></i> Logout{{-- &nbsp;<i class="fa fa-long-arrow-right" aria-hidden="true"></i> --}}</a></li>
                        @endif
                    @else
                    @endif
                </ul>
                @else
                    <div class="space1"></div>
                    <div class="space1"></div>
                    <div class="row-no-padding clearfix">
                        <div class="col-xs-12">
                            <div class="divider"><span>BROWSE</span></div>
                        </div>
                    </div>
                    <ul class="links">
                        {{-- @if($customer_info) --}}
                        <li> <a href="/"><i class="fa fa-home" aria-hidden="true"></i> Home</a></li>
                        {{-- @else
                        <li><a href="/members" id="home">MY ACCOUNT</a></li>
                        @endif --}}
                        <li class="product-mobile-dropdown">
                            <a href="javascript:"><i class="fa fa-list-ul" aria-hidden="true"></i> PRODUCTS <span class="pull-right"><i class="fa-change fa fa-angle-down" aria-hidden="true"></i></span></a>
                        </li>
                            @if(count($_categories) > 0)
                                <ul class="product-mobile-dropdown-list">
                                    @foreach($_categories as $categories)
                                        <li>
                                            <a href="/product?type={{ $categories["type_id"] }}">
                                                {{ $categories["type_name"] }}
                                            </a>
                                        </li>
                                    @endforeach
                                </ul>
                            @endif
                        <li> <a href="/promos"><i class="fa fa-percent" aria-hidden="true"></i> PROMOS</a></li>
                        <li class="company-mobile-dropdown"> 
                            <a href="javascript:"><i class="fa fa-info-circle" aria-hidden="true"></i> COMPANY <span class="pull-right"><i class="fa-change fa fa-angle-down" aria-hidden="true"></i></span></a> 
                        </li>
                            <ul class="company-mobile-dropdown-list">
                                <li><a href="/history"><i class="fa fa-history" aria-hidden="true"></i> Our History</a></li>
                                <li><a href="/how_to_join"><i class="fa fa-question-circle-o" aria-hidden="true"></i> How to Join</a></li>
                                <li><a href="/events"><i class="fa fa-calendar" aria-hidden="true"></i> Company Events</a></li>
                            </ul>
                        <li> <a href="/gallery"><i class="fa fa-picture-o" aria-hidden="true"></i> GALLERY</a></li>
                        <li> <a href="/contact"><i class="fa fa-phone" aria-hidden="true"></i> CONTACT US</a></li>
                        
                    </ul>
                @endif
            </nav>
        </div>

        {{-- BLURED WHEN SIDENAV WAS CLICKED --}}
        <div class="blur-me">

            <div class="loader hide">
              <span><img src="/resources/assets/frontend/img/loader.gif"></span>
            </div>

            <!-- HEADER -->
            <div class="subheader-container">
                <div class="container" style="position: relative;">
                    <div class="social-mob">
                        <a href="https://www.facebook.com/3xcell.ph/">
                            <span>
                                <i class="fa fa-facebook-square" aria-hidden="true"></i>
                            </span>
                        </a>
                        <a href="https://twitter.com/3xcell">
                            <span>
                                <i class="fa fa-twitter-square" aria-hidden="true"></i>
                            </span>
                        </a>  
                        <a href="https://www.instagram.com/3xcell/?hl=en">
                            <span>
                                <i class="fa fa-instagram" aria-hidden="true"></i>
                            </span>
                        </a>             
                        {{-- <span>
                            <i class="fa fa-google-plus-square" aria-hidden="true"></i>
                        </span> --}}
                    </div>
                   <div class="button-container">
                        <div class="social-media-container">
                            <a href="https://www.facebook.com/3xcell.ph/">
                                <span>
                                    <i class="fa fa-facebook-square" aria-hidden="true"></i>
                                </span>
                            </a>
                            <a href="https://twitter.com/3xcell">
                                <span>
                                    <i class="fa fa-twitter-square" aria-hidden="true"></i>
                                </span>
                            </a>  
                            <a href="https://www.instagram.com/3xcell/?hl=en">
                                <span>
                                    <i class="fa fa-instagram" aria-hidden="true"></i>
                                </span>
                            </a> 
                            {{-- <span>
                                <i class="fa fa-google-plus-square" aria-hidden="true"></i>
                            </span> --}}
                        </div> 
                       @if($customer)
                       <div class="login-container">
                           <div class="login-button">
                               <span>&nbsp;&nbsp;|&nbsp;&nbsp;<i class="fa fa-user" aria-hidden="true"></i></span><span>&nbsp;<a href="/members">MY ACCOUNT</a></span>
                           </div>
                       </div>
                      <div class="join-us-container">
                           <a href="/members/logout">
                               <div class="join-us-button">
                                   <span>LOGOUT</span>
                               </div>
                           </a>
                       </div>
                       @else
                        <div class="login-container">
                           <div class="login-button">
                               <span>&nbsp;&nbsp;|&nbsp;&nbsp;<i class="fa fa-user" aria-hidden="true"></i></span><span>&nbsp;<a href="/members/login">LOGIN</a></span>
                           </div>
                       </div>
                       <div class="join-us-container">
                           <a href="/members/register">
                               <div class="join-us-button">
                                   <img src="/themes/{{ $shop_theme }}/img/button-icon1.png"><span>&nbsp;&nbsp;JOIN US TODAY</span>
                               </div>
                           </a>
                       </div>
                       @endif
                   </div>
                </div>
            </div>

            <div class="header-container">
                <div class="container">
                    <div class="row clearfix">
                        <div class="col-md-3 cox-sm-12 col-xs-12">
                            <div id="nav_list" onclick="on()"><i class="fa fa-bars hamburger"></i></div>
                            <div class="image-logo-holder">
                                <a class="clearfix" href="/"><img src="/themes/{{ $shop_theme }}/img/company-logo.png"></a>    
                                <!-- <div class="menu-nav">
                                       <span></span>
                                       <span></span>
                                       <span></span>
                                   </div> -->                   
                            </div>

                            <div class="image-logo-mob">
                                <a class="clearfix" href="/"><img src="/themes/{{ $shop_theme }}/img/company-logo.png"></a>
                            </div>
                        </div>
                        <div class="col-md-9">
                        <!-- NAVIGATION -->
                            <nav class="navirino clearfix">
                                <ul>
                                    {{-- @if($customer_info) --}}
                                    <li><a href="/" class="head-button link-nav {{ Request::segment(1) == '' ? 'active' : '' }}" id="home">HOME</a></li>
                                    {{-- @else
                                    <li><a href="/members" class="head-button link-nav {{ Request::segment(1) == 'members' ? 'active' : '' }}" id="home">MY ACCOUNT</a></li>
                                    @endif --}}
                                    <li class="product-hover">
                                        <a class="head-button link-nav">PRODUCTS</a>

                                        <!--MINIMIZE VERSION STARTS HERE -->
                                        <div class="minimize-product-holder">                                
                                            @if(count($_categories) > 0)
                                                @foreach($_categories as $categories)
                                                <a href="/product?type={{ $categories["type_id"] }}">
                                                    <div class="minimize-tabs">{{ $categories["type_name"] }}</div>
                                                </a>
                                                @endforeach
                                            @else
                                                <a href="">
                                                    <div class="minimize-tabs"></div>
                                                </a>   
                                            @endif
                                        </div>
                                        <!-- ENDS HERE -->

                                        <!-- PRODUCT DROPDOWN -->
                                        <div class="product-dropdown" style="display: none;">
                                            @if(count($_categories) > 0)
                                                @foreach($_categories as $categories)
                                                <div class="cat-container">
                                                    <a href="/product?type={{ $categories["type_id"] }}">
                                                        <div class="per-cat">
                                                            <div class="cat-img-container"><img style="width: 124px; height: 100px; object-fit: cover;" src="{{ $categories["type_image"] }}"></div>
                                                            <div class="cat-name">{{ $categories["type_name"] }}</div>
                                                        </div>
                                                    </a>
                                                </div>
                                                @endforeach
                                            @else
                                                <div class="cat-container">
                                                    <a href="/product">
                                                        <div class="per-cat">
                                                            <div class="cat-img-container"><img src="/themes/{{ $shop_theme }}/img/beauty-prod.png"></div>
                                                            <div class="cat-name">BEAUTY SKIN CARE</div>
                                                        </div>
                                                    </a>
                                                </div>
                                                <div class="cat-container">
                                                    <a href="/product">
                                                        <div class="per-cat">
                                                            <div class="cat-img-container"><img src="/themes/{{ $shop_theme }}/img/supplement.png"></div>
                                                            <div class="cat-name">FOOD SUPPLEMENT</div>
                                                        </div>
                                                    </a>
                                                </div>
                                                <div class="cat-container">
                                                    <a href="/product">
                                                        <div class="per-cat">
                                                            <div class="cat-img-container"><img src="/themes/{{ $shop_theme }}/img/healthy-drinks.png"></div>
                                                            <div class="cat-name">HEALTHY DRINKS</div>
                                                        </div>
                                                    </a>
                                                </div>
                                                <div class="cat-container">
                                                    <a href="/product">
                                                        <div class="per-cat">
                                                            <div class="cat-img-container"><img src="/themes/{{ $shop_theme }}/img/p-a.png"></div>
                                                            <div class="cat-name">HEALTHY PACKAGES</div>
                                                        </div>
                                                    </a>
                                                </div>
                                                <div class="cat-container">
                                                    <a href="/product">
                                                        <div class="per-cat">
                                                            <div class="cat-img-container"><img src="/themes/{{ $shop_theme }}/img/p-b.png"></div>
                                                            <div class="cat-name">RETAIL PACKAGES</div>
                                                        </div>
                                                    </a>
                                                </div>
                                            @endif
                                        </div>
                                    </li>
                                    <li><a href="/promos" class="head-button link-nav">PROMOS</a></li>
                                    <li class="company-hover">
                                        <a class="head-button link-nav">COMPANY</a>

                                        <!--MINIMIZE VERSION STARTS HERE -->
                                        <div class="minimize-cat-holder">
                                            <a href="/history">
                                                <div class="cat-name minimize-tabs">OUR HISTORY</div>
                                            </a>
                                            <a href="/how_to_join">
                                                <div class="cat-name minimize-tabs">HOW TO JOIN</div>
                                            </a>
                                            <a href="/events">
                                                <div class="cat-name minimize-tabs">COMPANY EVENTS</div>
                                            </a>
                                        </div>
                                        <!-- ENDS HERE -->

                                        <!-- COMPANY DROPDOWN -->
                                        <div class="company-dropdown" style="display: none;">
                                            <a href="/history">
                                                <div class="cat-container">
                                                    <div class="per-cat">
                                                        <div class="cat-img-container"><img src="/themes/{{ $shop_theme }}/img/history-thumb.png"></div>
                                                        <div class="cat-name">OUR HISTORY</div>
                                                    </div>
                                                </div>
                                            </a>
                                            <a href="/how_to_join">
                                                <div class="cat-container">
                                                    <div class="per-cat">
                                                        <div class="cat-img-container"><img src="/themes/{{ $shop_theme }}/img/how-to-join-thumb.png"></div>
                                                        <div class="cat-name">HOW TO JOIN</div>
                                                    </div>
                                                </div>
                                            </a>
                                            <a href="/events">
                                                <div class="cat-container">
                                                    <div class="per-cat">
                                                        <div class="cat-img-container"><img src="/themes/{{ $shop_theme }}/img/events-calendar-thumb.png"></div>
                                                        <div class="cat-name">COMPANY EVENTS</div>
                                                    </div>
                                                </div>
                                            </a>
                                        </div>
                                    </li>
                                    <li><a href="/gallery" class="head-button link-nav">GALLERY</a></li>
                                    <li><a href="/contact" class="head-button link-nav">CONTACT US</a></li>
                                    <li class="cart-hover">
                                        <a class="link-nav popup" link="/cartv2" size="lg"><span><img class="cart-header" src="/themes/{{ $shop_theme }}/img/cart-header.png"></span></a>
                                        <!-- CART DROPDOWN -->
                                        {{-- <div class="cart-dropdown" style="display: none;">
                                            
                                        </div> --}}
                                    </li>
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
                    <div class="upper row clearfix">
                        <div class="col-md-4 col-sm-4 col-xs-12 footer-holder">
                            <div class="footer-img-container">
                                <img class="footer-img" src="/themes/{{ $shop_theme }}/img/footer-img.png">
                                <p class="description">
                                    3xcell-E Sales & Marketing Inc. is composed of five dynamic individuals who share the same motivation and common values strengthened and lead by their principal incorporator.
                                </p>
                            </div>
                        </div>
                        <div class="col-md-4 col-sm-4 col-xs-12 footer-holder">
                            <div class="upper-mid">
                                <div class="upper-mid-title">INFORMATION</div>
                                <div class="upper-mid-link-container">
                                    <a href="/"><div class="upper-mid-link">HOME</div></a>
                                    <a href="/product"><div class="upper-mid-link">PRODUCTS</div></a>
                                    <a href="/how_to_join"><div class="upper-mid-link">OPPORTUNITY</div></a>
                                    <a href="/gallery"><div class="upper-mid-link">GALLERY</div></a>
                                    <a href="/contact"><div class="upper-mid-link">CONTACT US</div></a>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4 col-sm-4 col-xs-12 footer-holder">
                            <div class="upper-right">
                                <div class="upper-mid-title">CONTACT US TODAY</div>
                                <div class="upper-mid-title-2">PRINCIPAL OFFICE 2</div>
                                <div class="upper-mid-link">
                                    <p>
                                        Vicar's Bldg. #31 Visayas Avenue Corner Road 1 Vasra, 
                                        Quezon City
                                    </p>
                                </div>
                                <div class="upper-mid-title-2">GENSAN BRANCH OFFICE</div>
                                <div class="upper-mid-link">
                                    <p>
                                        Door #2 Perla Compania de Seguros Bldg.
                                        Jp. Laurel East, Corner Sampaguita Street,
                                        General Santos City 
                                    </p>
                                </div>
                                <div class="upper-mid-title-2"><span><img src="/themes/{{ $shop_theme }}/img/footer-mail.png"></span><span>sales@3xcell.com</span></div>
                                <div class="upper-mid-title-2"><span><img src="/themes/{{ $shop_theme }}/img/footer-phone.png"></span><span>+63 2 518 8637</span></div>
                            </div>
                        </div>
                    </div>
                    <div class="bottom">                           
                        <div class="ftr-title">© 2017 3XCELL E-SALES & MARKETING, INC. All Rights Reserved.</div>
                        <div class="ftr-title-2">Powered By: DIGIMA WEB SOLUTIONS, Inc.</div>
                    </div>
                </div>
            </footer>

         </div>

        @include("frontend.gfoot")
        <!-- FB WIDGET -->
        <div id="fb-root"></div>
        <script type="text/javascript" src="/themes/{{ $shop_theme }}/js/theme_custom.js"></script>
        <script type="text/javascript" src="/assets/member/plugin/toaster/toastr.min.js"></script>

        @yield("js")
    </body>
</html>
