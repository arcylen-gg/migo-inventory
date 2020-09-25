<!doctype html>
<!--[if lt IE 7]>      <html class="no-js lt-ie9 lt-ie8 lt-ie7" lang=""> <![endif]-->
<!--[if IE 7]>         <html class="no-js lt-ie9 lt-ie8" lang=""> <![endif]-->
<!--[if IE 8]>         <html class="no-js lt-ie9" lang=""> <![endif]-->
<!--[if gt IE 8]><!--> <html class="no-js" lang=""> <!--<![endif]-->
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
        <title>3xCELL</title>
        <meta name="description" content="">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="apple-touch-icon" href="apple-touch-icon.png">
        <!-- GOOGLE FONT -->
        <link href="https://fonts.googleapis.com/css?family=Roboto:400,500,700" rel="stylesheet">
        <link href="https://fonts.googleapis.com/css?family=Fjalla+One" rel="stylesheet">
        <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,400,600,700" rel="stylesheet">  
        <!-- BOOTSTRAP -->
        <link rel="stylesheet" href="/themes/{{ $shop_theme }}/assets/initializr/css/bootstrap.min.css">
        <link rel="stylesheet" href="/themes/{{ $shop_theme }}/assets/initializr/css/bootstrap-theme.min.css">
        <!-- FONT AWESOME -->
        <link rel="stylesheet" type="text/css" href="/themes/{{ $shop_theme }}/assets/font-awesome/css/font-awesome.min.css">
        <!-- SLICK CSS -->
        <link rel="stylesheet" type="text/css" href="/themes/{{ $shop_theme }}/assets/slick/slick.css">
        <link rel="stylesheet" type="text/css" href="/themes/{{ $shop_theme }}/assets/slick/slick-theme.css">
        <!-- GLOBAL CSS -->
        <link rel="stylesheet" type="text/css" href="/themes/{{ $shop_theme }}/css/global.css">
        <link rel="stylesheet" type="text/css" href="/assets/front/css/loader.css">
        <!-- PARALLAX -->
        <link rel="stylesheet" type="text/css" href="/themes/{{ $shop_theme }}/css/parallax.css">
        
        <!-- LIGHTBOX -->
        <link rel="stylesheet" type="text/css" href="/themes/{{ $shop_theme }}/assets/lightbox/css/lightbox.css">

        <link rel="stylesheet" type="text/css" href="/themes/{{ $shop_theme }}/css/3xcell_login.css">
        <link rel="stylesheet" type="text/css" href="/assets/member/plugin/toaster/toastr.css">

        <script src="/themes/{{ $shop_theme }}/assets/initializr/js/vendor/modernizr-2.8.3-respond-1.4.2.min.js"></script>
    </head>
    <body>
    <div class="loader hide">
      <span><img src="/resources/assets/frontend/img/loader.gif"></span>
    </div>
    
    <div class="login-wrapper">
		<div class="container">
			<div class="login-holder">
				<div class="logo-container">
					<img src="/themes/{{ $shop_theme }}/img/logo-lg.png">
					<h1>BORN TO SUCCEED.</h1>
				</div>
				<div class="login-field-container">
					<h1>
						Login
					</h1>
					<form method="post" action="/mlm/login" class="global-submit" autocomplete="on">
                    	<input type="hidden" class="token" name="_token" value="{{ csrf_token() }}">
						<input class="text-field" type="text" name="user" placeholder="Username">
						<input class="text-field" type="password" name="pass" placeholder="Password">
						<button style="border: 0; margin-left: 0;" type="submit" class="login-button">LOGIN</button>
						<h2>Don't have an account yet?</h2>
						<a href="/mlm/register{{ Request::input("checkout") ? "?checkout"."=".Request::input("checkout") : "" }}"><h3>SIGN UP HERE</h3></a>
					</form>
				</div>
			</div>
		</div>
	</div>
    
    <script src="//ajax.googleapis.com/ajax/libs/jquery/1.11.2/jquery.min.js"></script>
    <script>window.jQuery || document.write('<script src="/themes/{{ $shop_theme }}/assets/initializr/js/vendor/jquery-1.11.2.min.js"><\/script>')</script>
    <script src="/themes/{{ $shop_theme }}/assets/initializr/js/vendor/bootstrap.min.js"></script>
    <script type="text/javascript" src="/themes/{{ $shop_theme }}/js/match-height.js"></script>
    <script type="text/javascript" src="/themes/{{ $shop_theme }}/js/fit-text.js"></script>
    <script type="text/javascript" src="/themes/{{ $shop_theme }}/assets/slick/slick.min.js"></script>
    <script type="text/javascript" src="/assets/front/js/jquery.keep-ratio.min.js"></script>
    <script type="text/javascript" src="/assets/front/js/globalv2.js"></script>
    <script src="/themes/{{ $shop_theme }}/js/global.js"></script>
    <script type="text/javascript" src="/themes/{{ $shop_theme }}/js/figuesslider.js"></script>
    <script type="text/javascript" src="/themes/{{ $shop_theme }}/js/parallax.js"></script>
    <script type="text/javascript" src="/themes/{{ $shop_theme }}/assets/lightbox/js/lightbox.js"></script>

    <script type="text/javascript" src="/assets/member/global.js"></script>
    <script type="text/javascript" src="/assets/member/plugin/toaster/toastr.min.js"></script>
    <script type="text/javascript">
    function submit_done(data)
    {
        console.log(data);
        if(data.type == 'error')
        {
            toastr.error(data.message);
        }
        else
        {
            toastr.success(data.message);

            @if(Request::input("checkout") == 1)
            	location.href = '/checkout';
            @else
            	location.href = '/mlm';
            @endif
        }
    }
    </script>
    </body>
</html>
