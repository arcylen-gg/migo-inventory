<!doctype html>
<!--[if lt IE 7]>      <html class="no-js lt-ie9 lt-ie8 lt-ie7" lang=""> <![endif]-->
<!--[if IE 7]>         <html class="no-js lt-ie9 lt-ie8" lang=""> <![endif]-->
<!--[if IE 8]>         <html class="no-js lt-ie9" lang=""> <![endif]-->
<!--[if gt IE 8]><!--> <html class="no-js" lang=""> <!--<![endif]-->
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
        <title>Digima House</title>
        <meta name="description" content="">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="apple-touch-icon" href="apple-touch-icon.png">
        <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,400,600,700,800" rel="stylesheet">
        <link rel="stylesheet" href="/assets/initializr/css/bootstrap.min.css">
        <link rel="stylesheet" href="/assets/initializr/css/bootstrap-theme.min.css">
        <!--<link rel="stylesheet" href="/assets/initializr/css/main.css">-->
        <link rel="stylesheet" href="/assets/front/css/global.css">
        @yield("css")
        <script src="/assets/initializr/js/vendor/modernizr-2.8.3-respond-1.4.2.min.js"></script>
    </head>
    <body>
        <!--[if lt IE 8]>
            <p class="browserupgrade">You are using an <strong>outdated</strong> browser. Please <a href="http://browsehappy.com/">upgrade your browser</a> to improve your experience.</p>
        <![endif]-->
    <nav class="navbar navbar-default navbar-fixed-top">
      <div class="container">
        <!-- Brand and toggle get grouped for better mobile display -->
        <div class="navbar-header">
          <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1" aria-expanded="false">
            <span class="sr-only">Toggle navigation</span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
          </button>
          <a class="navbar-brand" href="/">Digima House</a>
        </div>
    
        <!-- Collect the nav links, forms, and other content for toggling -->
        <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
          <ul class="nav navbar-nav">
            <li class="{{ Request::segment(1) == 'pricing' ? 'active' : '' }}"><a href="/pricing">BUSINESS PLAN</a></li>
            <!--<li><a href="#">ADD-ONS</a></li>-->
            <!--<li><a href="#">ACCOUNTANTS</a></li>-->
            <li class="{{ Request::segment(1) == 'support' ? 'active' : '' }}"><a href="/support">SUPPORT</a></li>
          </ul>
          <ul class="nav navbar-nav navbar-right">
            <li><a href="/login" class="login-holder"><button class="btn btn-transparent">Sign In</button></a></li>
          </ul>
        </div><!-- /.navbar-collapse -->
      </div><!-- /.container-fluid -->
    </nav>

    <div class="main-content-holder">
      @yield("content")
    </div>
    
    <!-- FOOTER -->
    <footer>
      <div class="container">
        <div class="row clearfix">
          <div class="col-sm-3">
            <h5>For Small Businesses</h5>
            <ul>
              <li><a href="javascript:">View Digima House Online plans</a></li>
              <li><a href="javascript:">Online Invoicing Software</a></li>
              <li><a href="javascript:">Add-ons</a></li>
            </ul>
          </div>
          <div class="col-sm-3">
            <h5>Features & Benefits</h5>
            <ul>
              <li><a href="javascript:">Balance Sheet Template</a></li>
              <li><a href="javascript:">Profit & Loss Statement</a></li>
              <li><a href="javascript:">Income Statement Template</a></li>
              <li><a href="javascript:">Track Accounts Payable</a></li>
              <li><a href="javascript:">Manage Cash Flow</a></li>
              <li><a href="javascript:">Chart of Accounts</a></li>
              <li><a href="javascript:">Accounts Receivable</a></li>
            </ul>
          </div>
          <div class="col-sm-3">
            <h5>For Accounting Professionals</h5>
            <ul>
              <li><a href="javascript:">Digima House Online Accountant</a></li>
            </ul>
          </div>
          <div class="col-sm-3">
            <h5>Support</h5>
            <ul>
              <li><a href="javascript:">Digima House Online Support</a></li>
              <li><a href="javascript:">FAQs</a></li>
            </ul>
          </div>
        </div>
      </div>
    </footer>
    <div class="copyright">
      <div class="container">
        <div class="row clearfix">
          <div class="col-md-8">
            <div class="note">© 2016 Digima Web Solutions. All rights reserved. Terms and conditions, features, support, pricing, and service options subject to change without notice.</div>
          </div>
          <div class="col-md-4">
            <div class="nav-bot">
              <div class="holder"><a href="javascript:">Legal</a></div>
              <div class="holder">|</div>
              <div class="holder"><a href="javascript:">Privacy</a></div>
              <div class="holder">|</div>
              <div class="holder"><a href="javascript:">Security</a></div>
            </div>
          </div>
        </div>
      </div>
    </div>
    
    <script src="//ajax.googleapis.com/ajax/libs/jquery/1.11.2/jquery.min.js"></script>
    <script>window.jQuery || document.write('<script src="/assets/initializr/js/vendor/jquery-1.11.2.min.js"><\/script>')</script>
    <script src="/assets/initializr/js/vendor/bootstrap.min.js"></script>
    <script type="text/javascript" src="/assets/front/js/global.js"></script>
    <!--<script src="/assets/initializr/js/main.js"></script>-->
    @yield("script")
    </body>
</html>
