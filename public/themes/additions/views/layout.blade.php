<!doctype html>
<!--[if lt IE 7]>      <html class="no-js lt-ie9 lt-ie8 lt-ie7" lang=""> <![endif]-->
<!--[if IE 7]>         <html class="no-js lt-ie9 lt-ie8" lang=""> <![endif]-->
<!--[if IE 8]>         <html class="no-js lt-ie9" lang=""> <![endif]-->
<!--[if gt IE 8]><!--> <html class="no-js" lang=""> <!--<![endif]-->
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
        <title>Additions PH</title>
        <meta name="description" content="">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="apple-touch-icon" href="apple-touch-icon.png">

        <link rel="stylesheet" href="/themes/{{ $shop_theme }}/assets/initializr/css/bootstrap.min.css">
        <link rel="stylesheet" href="/themes/{{ $shop_theme }}/assets/initializr/css/bootstrap-theme.min.css">
        <link rel="stylesheet" href="/themes/{{ $shop_theme }}/assets/initializr/css/main.css">
        <script src="/themes/{{ $shop_theme }}/assets/initializr/js/vendor/modernizr-2.8.3-respond-1.4.2.min.js"></script>
        {{-- FONT --}}
        <link rel="stylesheet" type="text/css" href="/themes/{{ $shop_theme }}/font/berling/styles.css">
        <!-- EXTERNAL CSS -->  
        <link rel="stylesheet" type="text/css" href="/themes/{{ $shop_theme }}/assets/font-awesome/css/font-awesome.min.css">
        <link rel="stylesheet" type="text/css" href="/themes/{{ $shop_theme }}/assets/slick/slick.css">
        <link rel="stylesheet" type="text/css" href="/themes/{{ $shop_theme }}/assets/slick/slick-theme.css">
        <!-- CSS -->
        <link rel="stylesheet" type="text/css" href="/themes/{{ $shop_theme }}/assets/front/css/global.css">
        <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
        @yield("css")
    </head>
    <body>
        <!--[if lt IE 8]>
            <p class="browserupgrade">You are using an <strong>outdated</strong> browser. Please <a href="http://browsehappy.com/">upgrade your browser</a> to improve your experience.</p>
        <![endif]-->
    <div class="loader-container hide">
      <div class="loader">
        <img src="/themes/{{ $shop_theme }}/assets/front/img/loader.gif">
      </div>
    </div>
    <!--<div class="banner-header">-->
    <!--  <div class="container holda">-->
    <!--    <div class="row clearfix" style="min-height: 50px">-->
          <!-- <div class="col-md-6">
    <!--        <div class="row clearfix">-->
    <!--          <div class="col-md-7 col-md-offset-5">-->
    <!--            <div class="text-holder-container"> -->
    <!--              <div class="text-holder">-->
    <!--                <div class="small-f">UP TO</div>-->
    <!--                <div class="big-f">30%</div>-->
    <!--              </div>-->
    <!--              <div class="text-holder text-center">-->
    <!--                <div class="big-f ss">DISCOUNT</div>-->
    <!--                <div class="small-f">IN STORES & ONLINE</div>-->
    <!--              </div>-->
    <!--            </div>-->
    <!--          </div>-->
    <!--        </div>-->
    <!--      </div>-->
    <!--      <div class="col-md-6">-->
    <!--        <div class="divider"></div>-->
    <!--        <div class="desc">Lorem ipsum dolor sit amet, consectetuer adipiscing elit. Aenean commodo ligula eget dolor.</div>-->
    <!--      </div> -->
    <!--    </div>-->
    <!--  </div>-->
    <!--</div> -->
    <div class="header-nav">
      <div class="clearfix container">
        <div class="pull-left">
          <div class="nav-holder"><a href="https://www.facebook.com/" target="_blank"><i class="fa fa-facebook" aria-hidden="true"></i></a></div>
          <div class="nav-holder"><a href="https://www.twitter.com/" target="_blank"><i class="fa fa-twitter" aria-hidden="true"></i></a></div>
          <div class="nav-holder"><a href="https://www.pinterest.com/" target="_blank"><i class="fa fa-pinterest-p" aria-hidden="true"></i></a></div>
        </div>
        <div class="pull-right margin-left-10">
          
          <div class="dropdown">
            
            <div class="dropdown-toggle" data-toggle="dropdown">MY ACCOUNT
            <span class="caret"></span></div>
            <ul class="dropdown-menu">
              {{-- @if(!$hasuser) --}}
              <li><a href="/mlm/login">LOGIN</a></li>
              <li><a href="/mlm/register">CREATE ACCOUNT</a></li>
              <li><a href="/mlm/login/forgot_password">FORGOT PASSWORD</a></li>
              {{-- @else
              <li><a href="/profile">PROFILE</a></li>
              <li><a href="/logout">LOG OUT</a></li>
              @endif --}}
            </ul>
          </div>
        </div>
        {{-- <div class="pull-right margin-left-10">
          <div class="dropdown">
            <div class="dropdown-toggle track-record">TRACK MY ORDER | </div>
            <div class="dropdown-menu-custom dropdown-tracking">
              <form class="form-horizontal" method="GET" action="/trackOrder">
              <div class="margin-tb-20">
                <div class="col-md-12">
                  <span for="">Please confirm you email:</span>
                  <input type="email" name="track_email" class="form-control" required/>
                </div>
                <div class="col-md-12 margin-bottom-20">
                  <span>Your order number:</span>
                  <div class="input-group">
                    <input type="text" name="track_number" class="form-control" required/>
                    <span class="input-group-btn">
                      <button class="btn btn-custom-pink" type="submit"><i class="fa fa-chevron-right"></i></button>
                    </span>
                  </div>
                  
                </div> 
              </div>
              </form>
            </div>
          </div>
        </div> --}}
      </div>
    </div>
    <div class="img-nav">
      <div class="clearfix container">
        <div class="logo">
          <a class="" href="javascript:" style="height: auto;"><img src="/themes/{{ $shop_theme }}/assets/front/img/logo.png"></a>
        </div>
      </div>
    </div>
    
    <div class="navbar">
    <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
            <span class="sr-only">Toggle navigation</span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
          </button>
      <div id="navbar" class="navbar-collapse collapse">
        <div class="clearfix container">
          <ul class="nav navbar-nav navbar-left">
            <li class="{{ Request::segment(1) == '' ? 'active' : '' }}"><a href="/">HOME</a></li>
            <li class="{{ Request::segment(1) == 'product' ? 'active' : '' }}"><a href="/product">CLOTHES</a></li>
            <!--<li class="{{ Request::segment(1) == 'store' ? 'active' : '' }}"><a href="/product">STORES</a></li>-->
            <li class="{{ Request::segment(1) == 'about' ? 'active' : '' }}"><a href="/about">ABOUT US</a></li>
            <li class="{{ Request::segment(1) == 'contact' ? 'active' : '' }}"><a href="/contact">CONTACT US</a></li>
          </ul>
          <ul class="nav navbar-nav navbar-right">
            <li class="search-combine-holder">
              <div class="search-combine clearfix">
                <input type="text" class="form-control search" id="search_field" val="{{Request::input('search') ? Request::input('search') : ''}}">
                <i class="fa fa-search"></i>
              </div>
            </li>
            <li class="cart-contain">
              <div class="cart-holder cart-quantity">0</div>
            </li>
          </ul>
       </div><!--/.navbar-collapse -->
      </div>
    </div>
    @yield("content")
    <footer>
      <div class="container">
        <div class="row clearfix">
          <div class="col-md-3 col-sm-6 match-height">
            <div class="title">ABOUT US</div>
            <div class="content">
              <p>Our main business office is Taytay, Rizal and we have branches in all leading Department Stores nationwide.From all of our locations, we want to provide superior products and the best customer service. </p>            
            </div>
          </div>
          <div class="col-md-3 col-sm-6 match-height">
            <div class="title">SITE MAP</div>
            <div class="content">
              <ul>
                <li><a href="/">HOME</a></li>
                <li><a href="/product">CLOTHES</a></li>
                <!--<li><a href="javascript:">STORES</a></li>-->
                <li><a href="/about">ABOUT US</a></li>
                <li><a href="/contact">CONTACT US</a></li>
              </ul>
            </div>
          </div>
          <div class="col-md-3 col-sm-6 match-height">
            <div class="title">CONTACT US</div>
            <div class="content">
              <ul>
                {{-- @foreach($contactInfo->_contact as $contact) --}}
                  <li><p><i class="fa fa-envelope" aria-hidden="true"> </i>&nbsp;&nbsp;sample@email.com</p></li>
                {{-- @endforeach --}}
              </ul>
            </div>
          </div>
          <div class="col-md-3 col-sm-6 match-height">
            <div class="pull-right">
              <div class="title">PAYMENT METHOD</div>
              <div class="content">
                <img class="img-responsive" src="/themes/{{ $shop_theme }}/front/paypal.png" style="margin: auto;">
              </div>
            </div>
          </div>
        </div>
      </div>
      
    </footer>
    <!-- Modal -->
    <div id="account_modal" class="modal fade account-modal" role="dialog">
      <div class="modal-dialog">
        <!-- Modal content-->
        <div class="modal-content">
          <div class="modal-body">
            <button type="button" class="close" data-dismiss="modal">&times;</button>
            <div class="account-content" type="login">
              <div class="login-logo">
                <img src="/themes/{{ $shop_theme }}/assets/front/img/logo.png">
              </div>
              <div class="form-group">
                <input type="text" class="form-control input-lg" id="login_username" placeholder="USER NAME">
              </div>
              <div class="form-group">
                <input type="password" class="form-control input-lg" id="login_password" placeholder="PASSWORD">
              </div>
              <div class="login-error"></div>
              <div class="form-group text-right">
                <button class="btn btn-orange btn-lg btn-signin">SIGN IN</button>
              </div>
              <div class="form-group create-label">
                <p>Don't have an account yet?</p>
                <p class="create-account account-modal-button" style="cursor: pointer;" type="register">CREATE AN ACCOUNT</p>
              </div>
            </div>
            <div class="account-content" type="register">
              <div class="form-group register-title">
                <p>CREATE ACCOUNT</p>
              </div>
              <div class="form-group">
                <input type="text" class="form-control input-lg" placeholder="FIRST NAME" id="new_firstname" name="">
              </div>
              <div class="form-group">
                <input type="text" class="form-control input-lg" placeholder="LAST NAME" id="new_lastname" name="">
              </div>
             <!--  <div class="form-group">
                <input type="text" class="form-control input-lg" id="new_username" placeholder="USER NAME">
              </div> -->
              
              <div class="form-group">
                <input type="text" class="form-control input-lg date-picker" id="new_bday" placeholder="BIRTHDATE">
              </div>
             

              <div class="form-group">
                <select class="form-control input-lg" style="text-align-last: center !important;" id="new_country">
                  <option value="">Select Country</option>
                  {{-- @foreach($_country as $country)
                    <option value="{{$country->country_id}}">{{$country->country_name}}</option>
                  @endforeach --}}
                </select>
              </div>
               <div class="form-group">
                <input type="text" class="form-control input-lg" id="new_province" placeholder="PROVINCE">
              </div>
               <div class="form-group">
                <input type="text" class="form-control input-lg" id="new_city" placeholder="CITY">
              </div>
               <div class="form-group">
                <input type="text" class="form-control input-lg" id="new_zip_code" placeholder="ZIP CODE">
              </div>
               <div class="form-group">
                <input type="text" class="form-control input-lg" id="new_address" placeholder="STREET">
              </div>
              <div class="form-group">
                <input type="text" class="form-control input-lg" id="new_number" placeholder="CONTACT NUMBER">
              </div>
              <div class="form-group">
                <input type="text" class="form-control input-lg" id="new_email" placeholder="ACCOUNT EMAIL ADDRESS">
              </div>
              <div class="form-group">
                <input type="password" class="form-control input-lg" id="new_password" placeholder="PASSWORD">
              </div>
              <div class="form-group">
                <input type="password" class="form-control input-lg" id="new_cpassword" placeholder="CONFIRM PASSWORD">
              </div>
              <div class="error-div-create"></div>
             
              <div class="form-group text-right register-account">
                <button class="btn btn-orange btn-lg btn-create-acount" type="button">CREATE</button>
              </div>
            </div>
            <div class="account-content" type="cart">
              <div class="login-logo">
                <img src="/themes/{{ $shop_theme }}/assets/front/img/logo.png">
              </div>
              <form method="POST" action="/product/addtocart">
              <input type="hidden" name="_token" id="_token" value="{{ csrf_token() }}">
              <div class="form-group">
                <input type="text" class="form-control input-lg" name="quantity" id="quantity" placeholder="QUANTITY" autocomplete="off">
                <input type="hidden" name="variant_id" id="variant_id" class="variant_id" value="">
              </div>
              <div class="form-group text-right">
                <button class="btn btn-orange btn-lg">ADD TO BAG</button>
              </div>
              </form>
            </div>
            <div class="account-content hide" type="register1">
              <div class="form-group">
                <input type="text" class="form-control input-lg" placeholder="FULL NAME">
              </div>
              <div class="form-group">
                <input type="text" class="form-control input-lg" placeholder="USER NAME">
              </div>
              <div class="form-group">
                <input type="text" class="form-control input-lg" placeholder="ACCOUNT EMAIL ADDRESS">
              </div>
              <div class="form-group">
                <select class="form-control input-lg">
                  <option>BIRTHDAY</option>
                </select>
              </div>
              <div class="form-group">
                <input type="text" class="form-control input-lg" placeholder="CONTACT NUMBER">
              </div>
              <div class="form-group">
                <input type="password" class="form-control input-lg" placeholder="PASSWORD">
              </div>
              <div class="form-group">
                <input type="password" class="form-control input-lg" placeholder="REPEAT PASSWORD">
              </div>
              <div class="form-group text-right">
                <button class="btn btn-orange btn-lg">CREATE</button>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
    <div id="cart_modal" class="modal fade cart-modal" role="dialog">
      <div class="modal-dialog">
        <!-- Modal content-->
        <div class="modal-content">
          <div class="modal-body">
            <div class="cart-load">
              
            </div>
          </div>
        </div>
      </div>
    </div>
    <script src="//ajax.googleapis.com/ajax/libs/jquery/1.11.2/jquery.min.js"></script>
    <script>window.jQuery || document.write('<script src="/themes/{{ $shop_theme }}/assets/initializr/js/vendor/jquery-1.11.2.min.js"><\/script>')</script>
    <script src="/themes/{{ $shop_theme }}/assets/initializr/js/vendor/bootstrap.min.js"></script>
    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
    <script src="/themes/{{ $shop_theme }}/assets/initializr/js/main.js"></script>
    <script type="text/javascript" src="/themes/{{ $shop_theme }}/assets/slick/slick.min.js"></script>
    <script type="text/javascript" src="/themes/{{ $shop_theme }}/assets/front/js/match-height.js"></script>
    <script type="text/javascript" src="/themes/{{ $shop_theme }}/assets/front/js/global.js"></script>
    <script type="text/javascript" src="/themes/{{ $shop_theme }}/assets/front/js/layout.js"></script>
    <script type="text/javascript" src='/themes/{{ $shop_theme }}/assets/front/js/add_to_cart.js'></script>
    
    <script type="text/javascript" src="/assets/front/js/jquery.keep-ratio.min.js"></script>
    <script type="text/javascript" src="/themes/{{ $shop_theme }}/js/global.js"></script>

    <script type="text/javascript">
      $(".date-picker").datepicker({
        dateFormat:"yy-mm-dd"
      });
    </script>
    @yield("script")
    </body>
</html>
