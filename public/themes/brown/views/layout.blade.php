<!doctype html>
<!--[if lt IE 7]>      <html class="no-js lt-ie9 lt-ie8 lt-ie7" lang=""> <![endif]-->
<!--[if IE 7]>         <html class="no-js lt-ie9 lt-ie8" lang=""> <![endif]-->
<!--[if IE 8]>         <html class="no-js lt-ie9" lang=""> <![endif]-->
<!--[if gt IE 8]><!--> <html class="no-js" lang=""> <!--<![endif]-->
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <title>Brown</title>
    <meta name="google-signin-scope" content="profile email">
    <meta name="google-signin-client_id" content="{{$google_app_id or ''}}">
    <input type="hidden" name="" class="google_app_id" value="{{$google_app_id or ''}}">
    <meta name="description" content="">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="icon" href="/themes/{{ $shop_theme }}/img/icon-logo.png" type="image/png" />

    <!-- FONTS -->
    <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,400,600,700" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css?family=Droid+Sans:400,700" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css?family=Arimo:400,700" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css?family=Rubik:300,400,500,700" rel="stylesheet"> 
    <link href="https://fonts.googleapis.com/css?family=Poppins:400,500,700" rel="stylesheet">

    @include("frontend.ghead")

    <!-- CSS -->
    <link rel="stylesheet" type="text/css" href="/themes/{{ $shop_theme }}/css/global.css?v=5.0">
    <link rel="stylesheet" type="text/css" href="/themes/{{ $shop_theme }}/css/drawer.css?v=5.0">

    @yield("css")
</head>
<body>
    <!--[if lt IE 8]>
    <p class="browserupgrade">You are using an <strong>outdated</strong> browser. Please <a href="http://browsehappy.com/">upgrade your browser</a> to improve your experience.</p>
    <![endif]-->
    {{-- SIDE NAV --}}
    <div id="mySidenav" class="sidenav">
        <a href="javascript:void(0)" class="closebtn" onclick="closeNav()">&times;</a>
        @if($customer)
        <a class="{{ Request::segment(1) == "members" ? "active" : "" }}" href="/members">My Account</a>
        @else
        <a class="{{ Request::segment(1) == "members" ? "active" : "" }}" href="/members/login">Login</a>
        <a class="{{ Request::segment(1) == "members" ? "active" : "" }}" href="/members/register">Register</a>
        @endif
        <a class="{{ Request::segment(1) == "" ? "active" : "" }}" href="/product">Brown</a>
        <!--<a class="{{ Request::segment(1) == "product" ? "active" : "product" }}" href="/product">Phone Accessories</a>-->
        <a href="#">Internet Of Things</a>
        <a href="#">Health Technology</a>
    </div>
    <div class="side-nav-dim" style="display: none;"></div>
    {{-- HEADER --}}
    <header>
        <div class="header-top">
            <div class="container">
                <!--<a class="holder">GET THE APP</a>-->
                {{-- <a href="/about" class="holder">COMPANY</a> --}}
                @if($customer)
                <div style="display: inline-block; vertical-align: middle;" class="dropdown">
                    <a class="holder" data-toggle="dropdown" data-hover="dropdown" data-delay="1000" data-close-others="false" style="text-transform: uppercase; color: #fff;">{{ $customer->first_name }} {{ $customer->last_name }} <span class="caret"></span></a>
                    <ul class="dropdown-menu dropdown-menu-right profile-menu" style="margin-top: -1px;">
                        <li>
                            <div class="profile-pic">
                                <img src="{{ $profile_image }}">
                            </div>
                            <div class="profile-text">
                                <div class="name">{{ $customer->first_name }} {{ $customer->last_name }}</div>
                                <div class="email">{{ $customer->email }}</div>
                                <div class="button-holder">
                                    <div class="clearfix">
                                        <button class="btn btn-brown" type="button" onClick="location.href='/members'">Profile</button>
                                        <button class="btn btn-green" type="button" onClick="location.href='/members/profile'">Settings</button>
                                    </div>
                                </div>
                            </div>
                            <div class="profile-footer">
                                <!-- onClick="location.href='/members/logout'" -->
                                <button type="button" class="btn btn-grey brown-sign-out" onClick="location.href='/members/logout'" >Sign Out</button>
                            </div>
                        </li>
                    </ul>
                </div>
                @else
                <a class="holder" href="/members/register">JOIN THE MOVEMENT</a>
                <a class="holder" href="/members/login">LOGIN</a>
                @endif
            </div>
        </div>
        <div class="header-middle clearfix">
            <div class="container">
                <div class="logo" onClick="location.href='/'">
                    <img src="/themes/{{ $shop_theme }}/img/logo.png">
                </div>
                <div class="search">
                    <div class="holder">
                        <input class="form-control" type="text" name="">
                        <img class="search-icon" src="/themes/{{ $shop_theme }}/img/search.png">
                    </div>
                </div>
            </div>
        </div>
        <div class="header-bottom">
            <nav class="navbar">
                <div class="container">
                    <div class="navbar-header">
                        {{-- <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#myNavbar">
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                        </button> --}}
                        <button type="button" class="navbar-toggle open-side-bar" onClick="openNav();">
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                        </button>
                    </div>
                    <div class="collapse navbar-collapse" id="myNavbar" style="padding-left: 0 !important;">

                        <ul class="nav navbar-nav">

                            @if($customer)
                            <li class="{{ Request::segment(1) == "members" ? "active" : "" }}"><a href="/members">MY ACCOUNT</a></li>
                            @endif

                            <li class="{{ Request::segment(1) == "" ? "active" : "" }}"><a href="/">HOME</a></li>
                            <!--<li class="{{ Request::segment(1) == "product" ? "active" : "product" }}"><a href="/product">Phone Accessories</a></li>-->
                            <!-- <li><a href="">STORE</a></li> -->
                            <!-- <li><a href="">THE ACADEMY</a></li> -->
                            <li><a href="/inspirers">INSPIRERS</a></li>
                            <!-- <li><a href="">INTERNET OF THINGS</a></li> -->
                            <!-- <li><a href="">EVENTS</a></li> -->
                        </ul>
                        <ul class="nav navbar-nav navbar-right">
                            <li class="dropdown">
                                <a href="#" class="notif-holder dropdown-toggle hidden" data-toggle="dropdown">
                                    <div class="brown-icon-bell-o"></div> <span class="badge">500</span>
                                </a>
                                <ul class="dropdown-menu notif">
                                    <li>
                                        <div class="notif-header">Notifications</div>
                                        <div class="notif-container">
                                            <div class="notif-holder">
                                                <div class="first clearfix">
                                                    <div class="notif-id">
                                                        <img src="/themes/{{ $shop_theme }}/img/people.png">
                                                        <div class="number">111133</div>
                                                    </div>
                                                    <div class="notif-date">2017-09-01 03:20:47</div>
                                                </div>
                                                <div class="second">
                                                    <div class="text">Sorry, Your Slot has already reached the max level for matrix. Slot 111133's pairing reward will not be added. </div>
                                                </div>
                                            </div>
                                            <div class="notif-holder">
                                                <div class="first clearfix">
                                                    <div class="notif-id">
                                                        <img src="/themes/{{ $shop_theme }}/img/people.png">
                                                        <div class="number">111133</div>
                                                    </div>
                                                    <div class="notif-date">2017-09-01 03:20:47</div>
                                                </div>
                                                <div class="second">
                                                    <div class="text">Sorry, Your Slot has already reached the max level for matrix. Slot 111133's pairing reward will not be added. </div>
                                                </div>
                                            </div>
                                            <div class="notif-holder">
                                                <div class="first clearfix">
                                                    <div class="notif-id">
                                                        <img src="/themes/{{ $shop_theme }}/img/people.png">
                                                        <div class="number">111133</div>
                                                    </div>
                                                    <div class="notif-date">2017-09-01 03:20:47</div>
                                                </div>
                                                <div class="second">
                                                    <div class="text">Sorry, Your Slot has already reached the max level for matrix. Slot 111133's pairing reward will not be added. </div>
                                                </div>
                                            </div>
                                            <div class="notif-holder">
                                                <div class="first clearfix">
                                                    <div class="notif-id">
                                                        <img src="/themes/{{ $shop_theme }}/img/people.png">
                                                        <div class="number">111133</div>
                                                    </div>
                                                    <div class="notif-date">2017-09-01 03:20:47</div>
                                                </div>
                                                <div class="second">
                                                    <div class="text">Sorry, Your Slot has already reached the max level for matrix. Slot 111133's pairing reward will not be added. </div>
                                                </div>
                                            </div>
                                            <div class="notif-holder see-all">
                                                <a href="javascript:">See all alerts</a>
                                            </div>
                                        </div>
                                    </li>
                                </ul>
                            </li>
                            <li>
                                <a href="#" class="cart-holder popup" link="/cartv2" size="lg">
                                    <div class="brown-icon-shopping-cart"></div> Cart (<span class="quantity-item-holder" style="vertical-align: middle;">0</span> items)
                                </a>
                            </li>
                        </ul>
                    </div>
                </div>
            </nav>
        </div>
    </header>
    <div class="sticky-nav hide-sticky">
        <div class="relative">
            <div class="clearfix container">
                <div class="left">
                    <img style="cursor: pointer;" class="list-category-button" src="/themes/{{ $shop_theme }}/img/list.png">
                    <div class="text">All Categories</div>
                    <div class="list-category hide">
                        <div class="container">
                            <a href="/" class="head-button link-nav {{ Request::segment(1) == '' ? 'active' : '' }}" id="home">Brown</a>
                            <!--<a href="javascript:">Phone Accessories</a>-->
                            <a href="/inspirers">Inspirer Page</a>
                            <a href="javascript:">Health Technology</a>
                        </div>
                    </div>
                </div>
                <div class="right">
                    <div class="dropdown hidden">
                        <a href="#" class="notif-holders dropdown-toggle" data-toggle="dropdown">
                            <div class="brown-icon-bell-o" style="font-size: 20px"></div> <span class="badge">500</span>
                        </a>
                        <ul class="dropdown-menu notif">
                            <li>
                                <div class="notif-header">Notifications</div>
                                <div class="notif-container">
                                    <div class="notif-holder">
                                        <div class="first clearfix">
                                            <div class="notif-id">
                                                <img src="/themes/{{ $shop_theme }}/img/people.png">
                                                <div class="number">111133</div>
                                            </div>
                                            <div class="notif-date">2017-09-01 03:20:47</div>
                                        </div>
                                        <div class="second">
                                            <div class="text">Sorry, Your Slot has already reached the max level for matrix. Slot 111133's pairing reward will not be added. </div>
                                        </div>
                                    </div>
                                    <div class="notif-holder">
                                        <div class="first clearfix">
                                            <div class="notif-id">
                                                <img src="/themes/{{ $shop_theme }}/img/people.png">
                                                <div class="number">111133</div>
                                            </div>
                                            <div class="notif-date">2017-09-01 03:20:47</div>
                                        </div>
                                        <div class="second">
                                            <div class="text">Sorry, Your Slot has already reached the max level for matrix. Slot 111133's pairing reward will not be added. </div>
                                        </div>
                                    </div>
                                    <div class="notif-holder">
                                        <div class="first clearfix">
                                            <div class="notif-id">
                                                <img src="/themes/{{ $shop_theme }}/img/people.png">
                                                <div class="number">111133</div>
                                            </div>
                                            <div class="notif-date">2017-09-01 03:20:47</div>
                                        </div>
                                        <div class="second">
                                            <div class="text">Sorry, Your Slot has already reached the max level for matrix. Slot 111133's pairing reward will not be added. </div>
                                        </div>
                                    </div>
                                    <div class="notif-holder">
                                        <div class="first clearfix">
                                            <div class="notif-id">
                                                <img src="/themes/{{ $shop_theme }}/img/people.png">
                                                <div class="number">111133</div>
                                            </div>
                                            <div class="notif-date">2017-09-01 03:20:47</div>
                                        </div>
                                        <div class="second">
                                            <div class="text">Sorry, Your Slot has already reached the max level for matrix. Slot 111133's pairing reward will not be added. </div>
                                        </div>
                                    </div>
                                    <div class="notif-holder see-all">
                                        <a href="javascript:">See all alerts</a>
                                    </div>
                                </div>
                            </li>
                        </ul>
                    </div>
                    <a href="#" class="cart-holder show-cart hidden" style="text-decoration: none;">
                        <!-- <img style="width: 30px; height: 20px;" src="/themes/{{ $shop_theme }}/img/cart-blur.png"> <span class="badge">500</span> -->
                        <div class="brown-icon-shopping-cart"></div> <span class="badge">500</span>
                    </a>
                    @if($customer)
                    <div style="display: inline-block; vertical-align: middle;" class="dropdown hidden">
                        <a class="holder" data-toggle="dropdown" data-hover="dropdown" data-delay="1000" data-close-others="false" style="text-transform: uppercase; color: #fff;">#272842 <span class="caret"></span></a>
                        <ul class="dropdown-menu dropdown-menu-right slot-picker">
                            <li class="slot-title">Slot Number</li>
                            <li><a href="#">#272841</a></li>
                            <li><a href="#">#272841</a></li>
                            <li><a href="#">#272841</a></li>
                            <li><a href="#">#272841</a></li>
                        </ul>
                    </div>
                    <div style="display: inline-block; vertical-align: middle;" class="dropdown">
                        <a class="holder" data-toggle="dropdown" data-hover="dropdown" data-delay="1000" data-close-others="false" style="text-transform: uppercase; color: #fff;">{{ $customer->first_name }} {{ $customer->last_name }} <span class="caret"></span></a>
                        <ul class="dropdown-menu dropdown-menu-right profile-menu">
                            <li>
                                <div class="profile-pic">
                                    <img src="{{ $profile_image }}">
                                </div>
                                <div class="profile-text">
                                    <div class="name">{{ $customer->first_name }} {{ $customer->last_name }}</div>
                                    <div class="email">{{ $customer->email }}</div>
                                    <div class="button-holder">
                                        <div class="clearfix">
                                            <button class="btn btn-brown" type="button" onClick="location.href='/members'">Profile</button>
                                            <button class="btn btn-green" type="button" onClick="location.href='/members/profile'">Settings</button>
                                        </div>
                                    </div>
                                </div>
                                <div class="profile-footer">
                                    <button class="btn btn-grey" type="button" onClick="location.href='/members/logout'">Sign Out</button>
                                </div>
                            </li>
                        </ul>
                    </div>
                    @else
                    <a class="holder" href="/members/register">JOIN THE MOVEMENT</a>
                    <a class="holder" href="/members/login">LOGIN</a>
                    @endif
                    <button type="button" class="navbar-toggle open-side-bar" onClick="openNav();">
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    </button>
                </div>
            </div>
        </div>
    </div>
    {{-- END HEADER --}}
    <div class="main-content-scroll" id="main">
        @yield("content")
    </div>
    {{-- FOOTER --}}
    <footer>
        <div class="container">
            <div class="row clearfix">
                <div class="col-md-3 col-xs-6 match-height">
                    <div class="holder">
                        <div class="title">Exclusive Offer</div>
                        <div class="desc">Subscribe to brown&proud Newsletter</div>
                        <input class="form-control" type="form-control" name="" placeholder="Your Email Address">
                        <div class="app">
                            <img class="img-responsive" src="/themes/{{ $shop_theme }}/img/download-app.png">
                        </div>
                    </div>
                </div>
                <div class="col-md-3 col-xs-6 match-height">
                    <div class="holder">
                        <div class="title">Customer Care</div>
                        <ul>
                            <li><a href="javascript:">Help Center</a></li>
                            <li><a href="javascript:">Payment</a></li>
                            <li><a href="javascript:">How to Buy</a></li>
                            <li><a href="javascript:">Shipping and Delivery</a></li>
                            <li><a href="javascript:">Questions</a></li>
                        </ul>
                    </div>
                </div>
                <div class="col-md-3 col-xs-6 match-height">
                    <div class="holder">
                        <div class="title">Brown&Proud</div>
                        <ul>
                            <li><a href="javascript:">About Us</a></li>
                            <li><a href="javascript:">Contact Us</a></li>
                            <li><a href="javascript:">Terms and Conditions</a></li>
                            <li><a href="javascript:">Privacy Policy</a></li>
                            <li><a href="javascript:">Store Directory</a></li>
                        </ul>
                    </div>
                </div>
                <div class="col-md-3 col-xs-6 match-height">
                    <div class="holder">
                        <div class="title">Get Intouch With Us</div>
                        <div class="social-logo">
                            <a href="https://www.facebook.com/phbrownandproud/"' target="_blank" class="holder"><i class="fa fa-facebook" aria-hidden="true"></i></a>
                            <a class="holder"><i class="fa fa-twitter" aria-hidden="true"></i></a>
                            <a class="holder"><i class="fa fa-instagram" aria-hidden="true"></i></a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </footer>
    <div class="footer-bottom">
        <div class="container">
            <div class="clearfix">
                <div class="payment-method">
                    <div class="title">Payment Methods</div>
                    <div class="payment-container">
                        <!--<div class="holder"><img src="/themes/{{ $shop_theme }}/img/cod.png"></div>-->
                        <div class="holder"><img src="/themes/{{ $shop_theme }}/img/paymaya.png"></div>
                    </div>
                </div>
                <div class="delivery-services">
                    <div class="title">Delivery Services</div>
                    <div class="payment-container">
                        <div class="holder"><img src="/themes/{{ $shop_theme }}/img/delivery-services.png"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="container figues">
        <div class="copyright">
            <div class="clearfix">
                <div class="copy">&copy; 2017 BROWN&PROUD. All Right Reserved.</div>
                <div class="power">Powered By: DIGIMA WEB SOLUTIONS, Inc.</div>
            </div>
        </div>
    </div>
    {{-- END FOOTER --}}
    
    {{-- START GLOBALS POPUP --}}
    <div class="popup-buy-a-kit">
        <div id="buy-a-kit-modal" class="modal fade shopping-cart">
            <div class="modal-lg modal-dialog">
                <div class="modal-content cart">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                        <h4 class="modal-title"><img src="/themes/{{ $shop_theme }}/img/cart.png"> My Shopping Cart</h4>
                    </div>
                    <div>
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Image</th>
                                    <th>Product Name</th>
                                    <th>Unit Price</th>
                                    <th>Quantity</th>
                                    <th>Total</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td><img src="/themes/{{ $shop_theme }}/img/brown-cart-img1.png"></td>
                                    <td>P 9,500.00</td>
                                    <td>P 9,500.00</td>
                                    <td>
                                        <input class="item-qty" name="quantity" min="1" step="1" value="1"  type="number"></td>
                                        <td>P 9,500.00</td>
                                    </tr>
                                    <tr>
                                        <td><img src="/themes/{{ $shop_theme }}/img/brown-cart-img1.png"></td>
                                        <td>P 9,500.00</td>
                                        <td>P 9,500.00</td>
                                        <td>
                                            <input class="item-qty" name="quantity" min="1" step="1" value="1"  type="number">
                                        </td>
                                        <td>P 9,500.00</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        <div class="modal-footer row clearfix">
                            
                            <div class="col-md-8">
                                <div class="left-btn-container">
                                    <div><i class="fa fa-long-arrow-left" aria-hidden="true">&nbsp;</i>&nbsp;Continue Shopping</div>
                                    <button class="btn-checkout" onclick="location.href='/members/checkout'">Checkout</button>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="total">Total: P 9,500.00</div>
                            </div>
                            
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @include("frontend.gfoot")

    <script type="text/javascript" src="/themes/{{ $shop_theme }}/js/drawer.js"></script>
    <script type="text/javascript" src="/themes/{{ $shop_theme }}/js/theme_custom.js"></script>
    
    @yield("script")
    
    <!-- BEGIN JIVOSITE CODE -->
    <script type='text/javascript'>
    (function(){ var widget_id = 'OcvyPjoHBr';var d=document;var w=window;function l(){ var s = document.createElement('script'); s.type = 'text/javascript'; s.async = true; s.src = '//code.jivosite.com/script/widget/'+widget_id; var ss = document.getElementsByTagName('script')[0]; ss.parentNode.insertBefore(s, ss);}if(d.readyState=='complete'){l();}else{if(w.attachEvent){w.attachEvent('onload',l);}else{w.addEventListener('load',l,false);}}})();
    </script>
    <!-- END JIVOSITE CODE -->
</body>
</html>