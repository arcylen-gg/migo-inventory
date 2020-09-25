<!doctype html>
<!--[if lt IE 7]>      <html class="no-js lt-ie9 lt-ie8 lt-ie7" lang=""> <![endif]-->
<!--[if IE 7]>         <html class="no-js lt-ie9 lt-ie8" lang=""> <![endif]-->
<!--[if IE 8]>         <html class="no-js lt-ie9" lang=""> <![endif]-->
<!--[if gt IE 8]><!--> <html class="no-js" lang=""> <!--<![endif]-->
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
        <title>{{ ucfirst($shop_info->shop_key) }} |  {{ isset($page) ? $page : 'Home' }}</title>
        <meta name="description" content="">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name='B-verify' content='8b63efb2920a681d6f877a59a414659d09831140' />
        <meta property="og:image" content="http://philtechglobalinc.com/themes/philtech/img/social-thumb.jpg" />
        
      
        <!-- <link rel="apple-touch-icon" href="apple-touch-icon.png"> -->
        {{-- PHILTECH ICON --}}
        <link rel="icon" href="/themes/{{ $shop_theme }}/img/icon/philtech-icon.png" type="image/png"/>

        <!-- GOOGLE FONT -->
        <link href="https://fonts.googleapis.com/css?family=Rubik:300,400,500" rel="stylesheet">

        <!-- GLOBAL CSS -->
        @include("frontend.ghead")

        <link rel="stylesheet" type="text/css" href="/themes/{{ $shop_theme }}/css/global.css?version=7">
        <link rel="stylesheet" type="text/css" href="/themes/{{ $shop_theme }}/css/push_sidenav.css">
        <link rel="stylesheet" type="text/css" href="/assets/member/css/loader.css">
        
        <!-- OTHER CSS -->
        @yield("css")

        <link rel="stylesheet" type="text/css" href="/themes/{{ $shop_theme }}/css/responsive.css?version=2">
        
        <script src="/themes/{{ $shop_theme }}/assets/initializr/js/vendor/modernizr-2.8.3-respond-1.4.2.min.js"></script>
    </head>
    <body>
        <div class="loader" style="display: none;">
          <span><img src="/resources/assets/frontend/img/loader.gif"></span>
        </div>
        <!--[if lt IE 8]>
            <p class="browserupgrade">You are using an <strong>outdated</strong> browser. Please <a href="http://browsehappy.com/">upgrade your browser</a> to improve your experience.</p>
        <![endif]-->
        <!-- HEADER -->
        <div class="header-nav clearfix">
            <div class="header-nav-top">
                <div class="container">
                    <div class="holder"><a href="/about">COMPANY</a></div>
                    @if($mlm_member)
                    <div class="holder">
                        <div class="dropdown">
                          <a class="">BUSINESS PRESENTATION</a>
                          <div class="dropdown-content">
                            <a href="https://drive.google.com/file/d/1Q4Tr0k5kpKalUzV-vODb6mU_tNyPMQmD">MILLIONAIRE SYSTEM</a>
                            <a href="https://drive.google.com/open?id=0B_zVgtlTtv89ZzlKSnI3ckpxd1k">PRIVILEGE CARD PRESENTATION</a>
                            <a href="https://drive.google.com/file/d/1gxsG3wL8emRvzxhG4Rriwz9FgnB7D84o">FRANCHISE BUSINESS PRESENTATION</a>
                          </div>
                        </div>
                    </div>
                    @else
                    <div class="holder"><a href="https://drive.google.com/file/d/1gxsG3wL8emRvzxhG4Rriwz9FgnB7D84o">BUSINESS PRESENTATION</a></div>
                    @endif
                    <div class="holder"><a href="javascript:" onClick="alert('Under Development')">NEWS</a></div>

                    @if($customer)
                    <div class="wata"></div>
                    <div class="holder"><a href="/members">MY ACCOUNT</a></div>
                    <div class="holder"><div class="linya"></div></div>
                    <div class="holder"><a href="/members/logout">LOGOUT</a></div>
                    @else
                    <div class="wata"></div>
                    <div class="holder"><a href="/members/login">LOGIN</a></div>
                    <div class="holder"><div class="linya"></div></div>
                    <div class="holder"><a href="/members/register">REGISTER</a></div>
                    @endif

                </div>
            </div>
            <div class="header-nav-middle">
                <div class="container">
                    <div class="clearfix">
                        <div class="logo-holder">
                            <img onClick="location.href='/'" style="cursor: pointer;" class="img-responsive logo" src="/themes/{{ $shop_theme }}/img/logo.png">            
                        </div>
                        <div class="search-bar-holder">

                            {{-- Search Bar --}}                          
                            <div class="search-bar">
                                <form action="/product" method="get" id="form-search">
                                    <div class="input-group">
                                         <input onkeydown="javascript: if(event.keyCode == 13) onSearch();" type="text" class="form-control" name="search" id="keyword1" aria-describedby="sizing-addon1" placeholder="Type the item you're looking for...">
                                         <span class="input-group-addon search-button" id="sizing-addon1">
                                            <a href="" onclick="onSearch(1);" id="submit_link"><img src="/themes/{{ $shop_theme }}/img/search-icon.png"></a>                          
                                         </span>
                                    </div>
                                </form>
                            </div>
                            {{-- End Search Bar --}}

                        </div>
                        <div class="woaw">
                            <div class="holder">
                                <img src="/themes/{{ $shop_theme }}/img/header/cashback.png">
                            </div>
                            <div class="holder">
                                <img src="/themes/{{ $shop_theme }}/img/header/delivery.png">
                            </div>
                            <div class="holder">
                                <img src="/themes/{{ $shop_theme }}/img/header/card.png">
                            </div>
                            <div class="shopping-cart-container text-center popup" link="/cartv2" size="lg">
                                <div class="shopping-cart">
                                    <img src="/themes/{{ $shop_theme }}/img/header/cart-icon.png">
                                    <span class="badge mini-cart-quantity quantity-item-holder" style="width: 23px; height: 23px; padding-left: 0; padding-right: 0;">0</span>
                                </div>
                                <div class="container-cart mini-cart">
                                    <div class="text-center"><span class="cart-loader text-center"><img style="height: 50px; margin: auto;" src="/assets/front/img/loader.gif"></span></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- NAVIGATION -->
        <nav class="navbar navbar-default">
          <div class="container sticky-hide">
            <!-- Brand and toggle get grouped for better mobile display -->
            <div class="navbar-header">
              <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1" aria-expanded="false">
                <span class="sr-only">Toggle navigation</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
              </button>
            </div>
            <!-- Collect the nav links, forms, and other content for toggling -->
            <div class="collapse navbar-collapse navbar-center" id="bs-example-navbar-collapse-1">
                <ul class="nav navbar-nav">
                    <li class="nav-border {{ Request::segment(1) == '' ? 'active' : '' }}"><a href="/">HOME</a></li>
                    <li class="nav-border {{ Request::segment(1) == 'partners' ? 'active' : '' }}"><a href="/partners">OUR MERCHANTS</a></li>
                    <li class="nav-border"><a href="https://loadcentral.net">ELOADING BUSINESS</a></li> 
                    <li class="nav-border"><a href="https://philtechglobalinc.vmoney.com">E-MONEY</a></li>
                    <li class="nav-border"><a href="javascript:" onClick="alert('Under Development');">CAREER</a></li>
                    <li class="nav-border"><a href="javascript:" onClick="alert('Under Development');">EVENTS</a></li>
                    <li class="nav-border {{ Request::segment(1) == 'legalities' ? 'active' : '' }}"><a href="/legalities">LEGALITIES</a></li>
                    <li class="nav-border {{ Request::segment(1) == 'contact' ? 'active' : '' }}"><a href="/contact">CONTACT US</a></li> 
                </ul>
            </div><!-- /.navbar-collapse -->
          </div><!-- /.container-fluid -->
          <div class="sticky-show">
              <div class="container">
                  <div class="holder category-holder">
                      <div class="icon-bar-holder">
                        <span class="sr-only">Toggle navigation</span>
                        <span class="icon-bar" style="margin-top: 0;"></span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                      </div>
                      <div class="category-label">CATEGORIES</div>
                  </div>
                  <div class="holder">
                    <div class="search-bar-holder">
                        {{-- Search Bar --}}
                        <div class="search-bar">
                            <form action="/product" method="get" id="form-search">
                                <div class="input-group">
                                    <input onkeydown="javascript: if(event.keyCode == 13) onSearch();" type="text" class="form-control" name="search" id="keyword1" aria-describedby="sizing-addon1">
                                    <span class="input-group-addon search-button" id="sizing-addon1">
                                        <a href="javascript:" onclick="onSearch(1);" id="submit_link"><img src="/themes/{{ $shop_theme }}/img/search-icon.png"></a>
                                    </span>
                                </div>
                            </form>
                        </div>
                        {{-- End Search Bar --}}
                    </div>
                  </div>
                  <div class="holder ft">
                      <img class="img-responsive" src="/themes/{{ $shop_theme }}/img/invert/cashback.png">
                  </div>
                  <div class="holder ft">
                      <img class="img-responsive" src="/themes/{{ $shop_theme }}/img/invert/delivery.png">
                  </div>
                  <div class="holder ft">
                      <img class="img-responsive" src="/themes/{{ $shop_theme }}/img/invert/privilege.png">
                  </div>
                  <div class="holder">
                    <div class="shopping-cart-container text-center popup" link="/cartv2" size="lg">
                        <div class="shopping-cart">
                            <img src="/themes/{{ $shop_theme }}/img/header/cart-icon.png">
                            <span class="badge mini-cart-quantity quantity-item-holder" style="width: 23px; height: 23px; padding-left: 0; padding-right: 0;">0</span>
                        </div>
                        <div class="container-cart mini-cart">
                            <div class="text-center"><span class="cart-loader text-center"><img style="height: 50px; margin: auto;" src="/assets/front/img/loader.gif"></span></div>
                        </div>
                    </div>
                  </div>
              </div>
          </div>
        </nav>

        <!-- MOBILE HEADER -->
        <div class="mob-nav-wrap sticky">
            <!-- PUSH MENU NAVIGATION -->
            <nav class="pushmenu pushmenu-left">
                @if($customer)
                    <div class="space1"></div>
                    <span>CATEGORIES</span>
                    @if(isset($_categories))
                        @foreach($_categories as $category)
                            <div class="links">                   
                                @if($category['subcategory'])
                                    <div class="shop-container">
                                        {{ $category['type_name'] }}
                                    </div>
                                    <div class="subshop-container">
                                        @foreach($category['subcategory'] as $subcategory)
                                            <ul>
                                                <a href="/product?type={{ $subcategory['type_id'] }}">
                                                    <li>
                                                        {{ $subcategory['type_name'] }}
                                                    </li>
                                                </a>
                                            </ul>
                                        @endforeach
                                    </div>
                                @else
                                    <a href="/product?type={{ $category['type_id'] }}">
                                        <div class="shop-container">
                                            {{ $category['type_name'] }}
                                        </div>
                                    </a>
                                @endif
                            </div>
                        @endforeach
                    @endif
                    <div class="space2"></div>
                    <span>BROWSE</span>
                    <ul class="links">
                            <a href="/"><li>HOME</li></a>
                            <a href="/partners"><li>OUR MERCHANTS</li></a>
                            <a href="https://loadcentral.net"><li>E-LOADING BUSINESS</li></a>
                            <a href="https://philtechglobalinc.vmoney.com"><li>E-MONEY</li></a>
                            <a href="javascript:" onClick="alert('Under Development');"><li>CAREER</li></a>
                            <a href="javascript:" onClick="alert('Under Development');"><li>EVENTS</li></a>
                            <a href="/legalities"><li>LEGALITIES</li></a>
                            <a href="/contact"><li>CONTACT US</li></a>
                        </ul>
                    <div class="space2"></div>
                    <span>MEMBER'S AREA</span>
                    <ul class="links">
                        <a href="/members"><li class="{{ Request::segment(1) == "members" ? "active" : "" }}" >DASHBOARD</li></a> 
                        <a href="/members/profile"><li>PROFILE</li></a>
                        @if($mlm_member)
                        <a href="/members/genealogy?mode=binary"><li class="{{ Request::segment(2) == "genealogy" ? "active" : "" }}">GENEALOGY</li></a>
                        <a href="/members/report"><li class="{{ Request::segment(2) == "report" ? "active" : "" }}">REPORTS</li></a>
                        <a href="/members/network"><li class="{{ Request::segment(2) == "report" ? "active" : "" }}">NETWORK LIST</li></a>
                        <a href="/members/lead-list"><li class="{{ Request::segment(2) == "report" ? "active" : "" }}">LEAD LIST</li></a>
                        <a href="/members/redeemable"><li class="{{ Request::segment(2) == "report" ? "active" : "" }}">REDEEMABLE</li></a> 
                        <a href="/members/wallet-encashment"><li class="{{ Request::segment(2) == "wallet-encashment" ? "active" : "" }}">WALLET</li></a> 
                            @if($customer)
                                <a href="/members/logout"><li class="user-logout">Logout &nbsp;<i class="fa fa-long-arrow-right" aria-hidden="true"></i></li>
                                </a>
                            @endif
                        @else
                        @endif
                    </ul>
                @else
                    <div class="space1"></div>
                    <span>CATEGORIES</span>
                    @if(isset($_categories))
                        @foreach($_categories as $category)
                            <div class="links">
                                <div class="shop-container">
                                    {{ $category['type_name'] }}
                                </div>
                                @if($category['subcategory'])
                                    <div class="subshop-container">
                                        @foreach($category['subcategory'] as $subcategory)
                                            <ul>
                                                <a href="/product?type={{ $subcategory['type_id'] }}">
                                                    <li>
                                                        {{ $subcategory['type_name'] }}
                                                    </li>
                                                </a>
                                            </ul>
                                        @endforeach
                                    </div>
                                @endif
                            </div>
                        @endforeach
                    @endif
                    <div class="space2"></div>
                    <span>BROWSE</span>
                    <ul class="links">
                        <a href="/"><li>HOME</li></a>
                        <a href="/partners"><li>OUR MERCHANTS</li></a>
                        <a href="https://loadcentral.net"><li>E-LOADING BUSINESS</li></a>
                        <a href="https://philtechglobalinc.vmoney.com"><li>E-MONEY</li></a>
                        <a href="javascript:" onClick="alert('Under Development');"><li>CAREER</li></a>
                        <a href="javascript:" onClick="alert('Under Development');"><li>EVENTS</li></a>
                        <a href="/legalities"><li>LEGALITIES</li></a>
                        <a href="/contact"><li>CONTACT US</li></a>
                    </ul>
                @endif
            </nav>
            <div id="overlay" onclick="off()"></div>
            <div class="subheader">
                <div class="container">
                    @if($customer)
                    <div class="right-container">                        
                        <span style="cursor: pointer;" onClick="location.href='/members'">MY ACCOUNT&nbsp;&nbsp;&nbsp;&nbsp;</span>
                        <span style="cursor: pointer;" onClick="location.href='/members/logout'">LOGOUT</span>
                    </div>
                    @else
                    <div class="right-container">
                        <span style="cursor: pointer;" onClick="location.href='/members/login'">LOGIN&nbsp;&nbsp;</span>
                        <span style="cursor: pointer;" onClick="location.href='/members/register'">&nbsp;&nbsp;REGISTER</span>
                    </div>
                    @endif
                </div>
            </div>  
            <div class="main-header">
                <div class="container">
                    <div id="nav_list" onclick="on()"><i class="fa fa-bars hamburger"></i></div>
                    <div class="mob-cart-container popup" size="lg" link="/cartv2"><img src="/themes/{{ $shop_theme }}/img/cart-mob.png"></div>
                    <div class="mob-logo-container"><a href="/"><img src="/themes/{{ $shop_theme }}/img/logo.png"></a></div>
                </div>
            </div>
            <!-- SEARCH BAR -->
            @if(Request::segment(1)!="members")
                <div class="mobile-search">
                    <div class="search-bar">
                        <form action="/product" method="get" id="form-search">
                            <div class="input-group">
                                 <input onkeydown="javascript: if(event.keyCode == 13) onSearch();" type="text" class="form-control" style="border: none;" name="search" id="keyword2" aria-describedby="sizing-addon1" placeholder="Type the item you're looking for...">
                                 <span class="input-group-addon search-button mob-search" id="sizing-addon1">
                                    <a href="javascript:" onclick="onSearch(2);" id="submit_link"><img src="/themes/{{ $shop_theme }}/img/search-icon.png"></a>                          
                                 </span>
                            </div>
                        </form>
                    </div>
                </div>
            @endif
        </div>

        <div id="scroll-to" class="clearfix">
           @yield("content")
        </div>

        <!-- FOOTER -->
        <footer>
            <div class="container ftr">
                <div class="row clearfix">
                    <div class="col-md-4 col-sm-6 match-height">
                        <div class="img-footer">
                            <img class="img-responsive" src="/themes/{{ $shop_theme }}/img/philtech.jpg">
                        </div>
                        <p>PHILTECH, INC. “Business and Benefits”
offers New Franchise Business Model and New Rewards System using New Generation of Advanced Technology. PHILTECH, INC. is a pioneer...<a style="color: #fff" href="/about">See more</a></p>
                    </div> 
                    <div class="col-md-2 col-sm-6 match-height">
                        <div class="btm-title">INFORMATION</div>
                        <ul>
                            <li class="{{ Request::segment(1) == 'partners' ? 'active' : '' }}"><a href="/partners">Our Partner Merchants</a></li>
                            <li><a href="https://loadcentral.net">E-loading Business</a></li>
                            {{-- <li><a href="http://tour.philtechglobalinc.com">Airline Ticketing</a></li>
                            <li><a href="http://202.54.157.7/PhilTechInc/BKWLTOlogin.aspx">Travel and Tours</a></li> --}}
                            <li><a href="https://philtechglobalinc.vmoney.com/">E-money</a></li>
                            <li><a href="javascript:" onClick="alert('Under Development');">Career</a></li>
                            <li><a href="javascript:" onClick="alert('Under Development');">Events</a></li>
                            <li class="{{ Request::segment(1) == 'legalities' ? 'active' : '' }}"><a href="/legalities">Legalities</a></li>
                            <li><a href="{{ get_content($shop_theme_info, "legalities", "business_presentation") }}">Business Presentation</a></li>
                            <li><a href="javascript:" onClick="alert('Under Development');">News</a></li>
                            <li><a href="/return_policy">Return Policy</a></li>
                            <li><a href="/privacy_policy">Privacy Policy</a></li>
                            {{-- <li><a href="/franchise_terms_and_condition">Franchise Terms and Conditions</a></li> --}}
                            <li><a href="/general_terms_and_condition">General Terms and Conditions</a></li>
                        </ul>
                    </div>
                    <div class="col-md-2 col-sm-6 match-height">
                        <div class="btm-title">SHOP</div>
                        <ul>
                            <li><a href="javascript:">DTH Products</a></li>
                            <li><a href="javascript:">Prepaid Cards</a></li>
                            <li><a href="javascript:">Gadgets</a></li>
                            <li><a href="javascript:">Electronics</a></li>
                            <li><a href="javascript:">Services</a></li>
                            <li><a href="javascript:">Entertainment</a></li>
                            <li><a href="javascript:">Apparel</a></li>
                            <li><a href="javascript:">Accessories</a></li>
                            <li><a href="javascript:">Health & Wellness</a></li>
                        </ul>
                    </div>
                    <div class="col-md-4 col-sm-6 match-height">
                        <div class="btm-title">CONTACT US</div>
                        <table>
                            <tr>
                                <td><img src="/themes/{{ $shop_theme }}/img/icon/location.png"></td>
                                <td>PhilTECH Building, Gallera Road, Paseo de<br>Putik, Guiwan, Zamboanga City, Philippines</td>
                            </tr>
                            <tr>
                                <td><img src="/themes/{{ $shop_theme }}/img/icon/phone.png"></td>
                                <td>Tel No. (062) 310-2256, Cel No. 0917-542- 2614<br>Tel No. (062) 310-2256, Cel No. 0917-542- 2614</td>
                            </tr>
                            <tr>
                                <td><img src="/themes/{{ $shop_theme }}/img/icon/email.png"></td>
                                <td>
                                    philtechglobal@yahoo.com<br>philtechglobalmainoffice@gmail.com
                                </td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>
        </footer>

        <footer id="bottom-footer">
            <div class="container bottom">
                <div class="clearfix">                
                    <div class="ftr-title pull-left">© {{ isset($company_info['company_name']) ? $company_info['company_name']->value : 'Your Name' }} {{ date('Y') }}</div>
                    <div class="ftr-title pull-right">Powered By: Digima Web Solutions, Inc.</div>
                </div>
            </div>
        </footer>

        <!-- Modal -->
        <div id="shopping_cart" class="modal fade global-modal shopping-cart-modal" role="dialog">
          <div class="modal-dialog">
            <!-- Modal content-->
            <div class="modal-content">
              
            </div>
          </div>
        </div>
        @include("frontend.gfoot")
        <script src="/themes/{{ $shop_theme }}/js/custom_theme.js?version=4"></script>

        @yield("js")
    </body>
</html>
