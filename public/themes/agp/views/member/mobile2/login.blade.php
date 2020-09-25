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
    <input type="hidden" id="_token" value="{{csrf_token()}}">
    <meta name="description" content="">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="apple-touch-icon" href="apple-touch-icon.png">
    <!-- FONTS -->
    <link href="https://fonts.googleapis.com/css?family=Rubik:300,400,500" rel="stylesheet">
    <link rel="stylesheet" href="/themes/{{ $shop_theme }}/assets/initializr/css/bootstrap.min.css">
    <link rel="stylesheet" href="/themes/{{ $shop_theme }}/assets/initializr/css/bootstrap-theme.min.css">
    <link rel="stylesheet" href="/themes/{{ $shop_theme }}/assets/initializr/css/main.css">
    <script src="/themes/{{ $shop_theme }}/assets/initializr/js/vendor/modernizr-2.8.3-respond-1.4.2.min.js"></script>
    <!-- EXTERNAL CSS -->
    <link rel="stylesheet" type="text/css" href="/themes/{{ $shop_theme }}/assets/font-awesome/css/font-awesome.min.css">
    <link rel="stylesheet" type="text/css" href="/themes/{{ $shop_theme }}/assets/slick/slick.css">
    <link rel="stylesheet" type="text/css" href="/themes/{{ $shop_theme }}/assets/slick/slick-theme.css">
    <link rel="stylesheet" type="text/css" href="/assets/member/css/loader.css">
    <!-- CSS -->
    <link rel="stylesheet" type="text/css" href="/themes/{{ $shop_theme }}/assets/mobile/css/login.css">
    <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
    <link rel="stylesheet" type="text/css" href="/themes/{{ $shop_theme }}/css/drawer.css">
    <!-- Brown Custom Icon -->
    <link rel="stylesheet" type="text/css" href="/themes/{{ $shop_theme }}/assets/brown-icon/styles.css">
</head>
<body>
    <!--[if lt IE 8]>
    <p class="browserupgrade">You are using an <strong>outdated</strong> browser. Please <a href="http://browsehappy.com/">upgrade your browser</a> to improve your experience.</p>
    <![endif]-->
    <div class="main-container">
        <div class="logo"><img src="/themes/{{ $shop_theme }}/assets/mobile/img/app-logo.png"></div>
        <form method="post">
            {{ csrf_field() }}

            @if (session("error"))
                <div class="alert" style="color: #fff; border: 0;">
                    {!! session("error") !!}
                </div>
            @endif

            @if ($errors->any())
                <div class="alert" style="color: #fff; border: 0;">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <div class="form-group email">
                <input type="text" name="email" class="form-control input-lg" placeholder="Email or Slot Code">
            </div>
            <div class="form-group password">
                <input type="password" name="password" class="form-control input-lg" placeholder="Password">
            </div>
            <div class="form-group action">
                <button class="btn" type="submit">Continue</button>
                <div class="register-label">Don't have an account yet?</div>
                <a href="/members/register" class="register">Sign Up Here</a>
            </div>
            <div class="social-button">
              <!--   <a href="{{$fb_login_url or '#'}}" class="holder fb">
                    <div class="name"><i class="fa fa-facebook" aria-hidden="true"></i> Sign in with Facebook</div>
                </a> -->
                <a href="javascript:" class="holder gp" id="customBtn">
                    <div class="name "><i class="fa fa-google-plus" aria-hidden="true"></i> Sign in with Google+</div>
                </a>
            </div>
        </form>
    </div>
    <script src="//ajax.googleapis.com/ajax/libs/jquery/1.11.2/jquery.min.js"></script>
    <script>window.jQuery || document.write('<script src="/themes/{{ $shop_theme }}/assets/initializr/js/vendor/jquery-1.11.2.min.js"><\/script>')</script>
    <script src="/themes/{{ $shop_theme }}/assets/initializr/js/vendor/bootstrap.min.js"></script>
    <script src="https://apis.google.com/js/platform.js" async defer></script>
    <script src="https://apis.google.com/js/api:client.js"></script>
    <script type="text/javascript" src="/assets/member/js/google_script/google_script_auth.js"></script>
    <script>startApp();</script>
    <!-- BEGIN JIVOSITE CODE -->
    <script type='text/javascript'>
    (function(){ var widget_id = 'OcvyPjoHBr';var d=document;var w=window;function l(){ var s = document.createElement('script'); s.type = 'text/javascript'; s.async = true; s.src = '//code.jivosite.com/script/widget/'+widget_id; var ss = document.getElementsByTagName('script')[0]; ss.parentNode.insertBefore(s, ss);}if(d.readyState=='complete'){l();}else{if(w.attachEvent){w.attachEvent('onload',l);}else{w.addEventListener('load',l,false);}}})();
    </script>
    <!-- END JIVOSITE CODE -->
</body>
</html>