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
        <link href="https://fonts.googleapis.com/css?family=PT+Serif:400,700" rel="stylesheet">
        <link href="https://fonts.googleapis.com/css?family=PT+Sans:400,700" rel="stylesheet">   

        <!-- GLOBAL CSS -->
        <link rel="stylesheet" type="text/css" href="/themes/{{ $shop_theme }}/css/global.css">

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
    <div class="loader hide">
      <span><img src="/resources/assets/frontend/img/loader.gif"></span>
    </div>

    <!-- HEADER -->
    <div id="home" class="subheader-container">
        <div class="container">
            <div class="left-container">
                <span><i class="fa fa-phone" aria-hidden="true"></i></span>
                <span>Call Us {!! get_content($shop_theme_info, "contact_details", "contact_company_contact_number") !!}</span>
                <span><i class="fa fa-envelope" aria-hidden="true"></i></span>
                <span>{!! get_content($shop_theme_info, "contact_details", "contact_company_email_address") !!}</span>
            </div>
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
                    <div class="menu-nav">
                        <span></span>
                        <span></span>
                        <span></span>
                    </div>
                </div>
                <div class="col-md-10">
                <!-- NAVIGATION -->
                    <nav class="navirino">
                        <ul>
                            @if(Request::segment(1)=="members")
                                <li><a class="smoth-scroll" href="#home">HOME</a></li>
                                <li><a class="smoth-scroll" href="#about">ABOUT US</a></li>
                                <li><a class="smoth-scroll" href="#process">PROCESS</a></li>
                                <li><a class="smoth-scroll" href="#product">PRODUCT</a></li>
                                <li><a class="smoth-scroll" href="#contact">CONTACT</a></li>
                            @else
                                <li><a class="smoth-scroll" href="#home">HOME</a></li>
                                <li><a class="smoth-scroll" href="#about">ABOUT US</a></li>
                                <li><a class="smoth-scroll" href="#process">PROCESS</a></li>
                                <li><a class="smoth-scroll" href="#product">PRODUCT</a></li>
                                <li><a class="smoth-scroll" href="#contact">CONTACT</a></li>
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
                        <div class="footer-icon-container">
                            <img src="\themes\living-water\img\header-logo.png">
                        </div>
                        <div>
                        <p>LIVINGWATER has been in the business of supplying healthy drinking water for almost three (3) years. Given its vast marketing experiences and a solid development foundation, our company has carved for itself a formidable position in the supply of healthy drinking water in the Philippines.</p>
                        </div>
                    </div>
                    <div class="col-md-4">
                         <div class="footer-title-container">
                            <p class="footer-title">INFORMATION</p>
                        </div>
                        <div class="info-list"><a class="smoth-scroll" href="#home">HOME</a></div>
                        <div class="info-list"><a class="smoth-scroll" href="#about">ABOUT US</a></div>
                        <div class="info-list"><a class="smoth-scroll" href="#process">PROCESS</a></div>
                        <div class="info-list"><a class="smoth-scroll" href="#product">PRODUCTS</a></div>
                        <div class="info-list"><a class="smoth-scroll" href="#contact">CONTACT US</a></div>
                    </div>
                    <div class="col-md-4">
                        <div class="footer-title-container">
                            <p class="footer-title">FOLLOW US ON</p>
                        </div>
                        <div class="social-icon-container">
                            <a href="#"><span><i class="fa fa-facebook-square"></i></span></a>
                            <a href="#"><span><i class="fa fa-twitter-square"></i></span></a>
                            <a href="#"><span><i class="fa fa-instagram"></i></span></a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </footer>
    <div class="container">
        <div class="bottom">                           
            <div class="ftr-title">© Living Water, All Rights Reserved</div>
            <div class="ftr-title-2">Powered By: DIGIMA WEB SOLUTIONS, Inc.</div>
        </div>
    </div>
    
    @include("frontend.gfoot")
    <script type="text/javascript" src="/themes/{{ $shop_theme }}/js/global.js"></script>
    <script type="text/javascript" src="/themes/{{ $shop_theme }}/js/theme_custom.js"></script>
    
    <!-- HEADER FIXED -->
    <script type="text/javascript">
        $window = $(window);
        $window.scroll(function() {
          $scroll_position = $window.scrollTop();
            if ($scroll_position > 100) { 
                $('.header-container').addClass('header-fixed');

                header_height = $('.your-header').innerHeight();
                $('body').css('padding-top' , header_height);
            } else {
                $('.header-container').removeClass('header-fixed');
                $('body').css('padding-top' , '0');
            }
         });
    </script>

    <!-- FB WIDGET -->
    <div id="fb-root"></div>

     @yield("script")
     <script> 
       $(window).load(function() { 
         $.fn.lightspeedBox(); 
       }); 
     </script> 
    </body>
</html>
