<!doctype html>
<!--[if lt IE 7]>      <html class="no-js lt-ie9 lt-ie8 lt-ie7" lang=""> <![endif]-->
<!--[if IE 7]>         <html class="no-js lt-ie9 lt-ie8" lang=""> <![endif]-->
<!--[if IE 8]>         <html class="no-js lt-ie9" lang=""> <![endif]-->
<!--[if gt IE 8]><!--> <html class="no-js" lang=""> <!--<![endif]-->
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
        <title>BROWN</title>
        <meta name="description" content="">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <base href="{{ URL::to('/') }}">
        <link rel="apple-touch-icon" href="apple-touch-icon.png">
        <link href="https://fonts.googleapis.com/css?family=Roboto:700" rel="stylesheet">
        <link rel="stylesheet" href="assets/initializr/css/bootstrap.min.css">
        <link rel="stylesheet" href="assets/initializr/css/bootstrap-theme.min.css">
        <link rel="stylesheet" type="text/css" href="assets/mlm/css/register-global.css">
        <link rel="stylesheet" type="text/css" href="assets/member/plugin/toaster/toastr.css">
        @yield("css")
        <style type="text/css">
            #load{
            width:100%;
            height:100%;
            position:fixed;
            z-index:9999;
            background:url("assets/mlm/loading.gif") no-repeat center center rgba(0,0,0,0.25)
        }
        </style>
        <script src="assets/initializr/js/vendor/modernizr-2.8.3-respond-1.4.2.min.js"></script>
        <!-- polyfiller file to detect and load polyfills -->
        <script src="http://cdn.jsdelivr.net/webshim/1.12.4/polyfiller.js"></script>
        <script>
          webshims.setOptions('waitReady', false);
          webshims.setOptions('forms-ext', {types: 'date'});
          webshims.polyfill('forms forms-ext');
        </script>
    </head>
    <body>
    <div id="load" class="hide a"></div>
        <!--[if lt IE 8]>
            <p class="browserupgrade">You are using an <strong>outdated</strong> browser. Please <a href="http://browsehappy.com/">upgrade your browser</a> to improve your experience.</p>
        <![endif]-->
    
    <div class="content">
      @yield("content")
    </div>
    
    <script src="//ajax.googleapis.com/ajax/libs/jquery/1.11.2/jquery.min.js"></script>
    <script>window.jQuery || document.write('<script src="assets/initializr/js/vendor/jquery-1.11.2.min.js"><\/script>')</script>
    <script src="assets/initializr/js/vendor/bootstrap.min.js"></script>
    <script type="text/javascript" src="assets/member/plugin/toaster/toastr.min.js"></script>
    <script type="text/javascript" src="assets/mlm/js/match-height.js"></script>
    @yield("script")
    </body>
</html>
