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
            
        </div>

        {{-- BLURED WHEN SIDENAV WAS CLICKED --}}
        <div class="blur-me">

            <div class="loader hide">
              <span><img src="/resources/assets/frontend/img/loader.gif"></span>
            </div>

            <!-- HEADER -->
            <div class="subheader-container">
                <div class="container" style="position: relative;">
                    @if($customer)
                    <div class="left-container">
                        <span class="about"><a href="#">About Us</a></span>
                        <span class="divider">|</span>
                        <span>Follow Us On</span>
                        <span><i class="fa fa-facebook-square" aria-hidden="true"></i></span>
                    </div>
                    <div class="right-container">
                        <span><a href="/members">My Account</a></span>
                        <span class="divider">|</i></span>
                        <span><a href="/members/logout">Logout</a></i></span>
                    </div>
                    @else
                    <div class="left-container">
                        <span class="about"><a href="#">About Us</a></span>
                        <span class="divider">|</span>
                        <span>Follow Us On</span>
                        <span><i class="fa fa-facebook-square" aria-hidden="true"></i></span>
                    </div>
                    <div class="right-container">
                        <span><a href="/members/register">Sign Up</a></span>
                        <span class="divider">|</i></span>
                        <span><a href="/members/login">Login</a></i></span>
                    </div>
                    @endif
                </div>
            </div>

            <div class="header-container">
                <div class="container">
                    <div class="row clearfix">
                        <div class="col-md-4">
                            <div class="categories-container">
                                {{-- <a onclick="myFunction()"><i class="fa fa-bars" aria-hidden="true"></i></a><span>Categories</span> --}}
                                <div class="mini-submenu">
                                    <span class="icon-bar"></span>
                                    <span class="icon-bar"></span>
                                    <span class="icon-bar"></span>
                                </div>
                                <ul id="menu">
                                    <div class="categories-header">
                                        <span class="title">CATEGORIES</span><i id="close-menu" class="fa fa-times" aria-hidden="true"></i>
                                    </div>
                                    
                                    @if(isset($_category))
                                        <li><a href="/product">All</a></li>
                                        <div class="divider"></div>
                                        @foreach($_category as $category)
                                            <li><a href="/product?type={{ $category['type_id'] }}">{{ $category['type_name'] }}</a>
                                                @if($category['subcategory'])
                                                    <ul class="sub-menu">
                                                        @foreach($category['subcategory'] as $subcategory)
                                                        <li><a href="/product?type={{ $subcategory['type_id'] }}">{{ $subcategory['type_name'] }}</a></li>
                                                        <div class="divider"></div>
                                                        @endforeach
                                                    </ul>
                                                @endif
                                            </li>
                                            <div class="divider"></div>
                                        @endforeach
                                    @else
                                        <li><a href="#">Men's Apparel</a>
                                            <ul class="sub-menu">
                                                <li><a href="#">Mobile and Gadget</a></li>
                                                <div class="divider"></div>
                                                <li><a href="#">Consumer Electronic</a></li>
                                                <div class="divider"></div>
                                                <li><a href="#">Home and Living</a></li>
                                                <div class="divider"></div>
                                            </ul>
                                        </li>
                                        <div class="divider"></div>
                                        <li><a href="#">Mobile and Gadget</a>
                                            <ul class="sub-menu">
                                                <li><a href="#">Consumer Electronic</a></li>
                                                <div class="divider"></div>
                                                <li><a href="#">Home and Living</a></li>
                                                <div class="divider"></div>
                                                <li><a href="#">Men's Accessories</a></li>
                                                <div class="divider"></div>
                                                <li><a href="#">Men's Shoes</a></li>
                                                <div class="divider"></div>
                                                <li><a href="#">Foods</a></li>
                                                <div class="divider"></div>
                                                <li><a href="#">Hobbies and Stationery</a></li>
                                            </ul>
                                        </li>
                                        <div class="divider"></div>
                                        <li><a href="#">Consumer Electronic</a></li>
                                        <div class="divider"></div>
                                        <li><a href="#">Home and Living</a></li>
                                        <div class="divider"></div>
                                        <li><a href="#">Men's Accessories</a></li>
                                        <div class="divider"></div>
                                        <li><a href="#">Men's Shoes</a></li>
                                        <div class="divider"></div>
                                        <li><a href="#">Foods</a></li>
                                        <div class="divider"></div>
                                        <li><a href="#">Hobbies and Stationery</a></li>
                                        <div class="divider"></div>
                                        <li><a href="#">Women's Apparel</a></li>
                                        <div class="divider"></div>
                                        <li><a href="#">Healthy and Beauty</a></li>
                                        <div class="divider"></div>
                                        <li><a href="#">Toys, Babies and Kids</a></li>
                                        <div class="divider"></div>
                                        <li><a href="#">Bags</a></li>
                                        <div class="divider"></div>
                                        <li><a href="#">Women's Accessories</a></li>
                                    @endif
                                </ul>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="image-logo-holder">
                                <a href="/#home">
                                    <img src="/themes/{{ $shop_theme }}/img/header-logo.jpg">
                                </a>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <input type="text" placeholder="Search...">
                            <span>
                                <i class="fa fa-search" aria-hidden="true"></i>
                            </span>
                            
                            <span class="my-cart">My Cart</span>
                            <a class="popup" link="/cartv2" size="lg" href="javascript:" style="padding: 0;">
                                <span><img src="/themes/{{ $shop_theme }}/img/my-cart-logo.png"></span>
                            </a>
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
                    
                    <div class="bottom">                           
                        <div class="ftr-title">© 2018 koloretemarketing. All Rights Reserved.</div>
                        <div class="ftr-title-2"><span class="kolorete-policies"><a href="/terms_and_conditions">kolorete marketing Policies</a></span><span class="divider">|</span><span>Powered By: DIGIMA WEB SOLUTIONS, Inc.</span></div>
                    </div>
                </div>
            </footer>

         </div>

        @include("frontend.gfoot")
        <!-- FB WIDGET -->
        <div id="fb-root"></div>
        <script type="text/javascript" src="/themes/{{ $shop_theme }}/js/theme_custom.js"></script>
        <script type="text/javascript" src="/assets/member/plugin/toaster/toastr.min.js"></script>
        {{-- <script type="text/javascript">
            function myFunction() {
                var x = document.getElementById("menu");
                if (x.style.display === "block") {
                    x.style.display = "none";
                } else {
                    x.style.display = "block";
                }
            }
        </script> --}}
        <script type="text/javascript">
            $(function()
            {

                $('#close-menu').on('click',function() 
                {                 
                    $(this).closest('#menu').toggle(500,function(){
                    $('.mini-submenu').fadeIn();    
                });
            });
                $('.mini-submenu').on('click',function()
                {       
                    $(this).next('#menu').toggle(500);
                    $('.mini-submenu').hide();
                })
            })
        </script>
        @yield("js")
    </body>
</html>
