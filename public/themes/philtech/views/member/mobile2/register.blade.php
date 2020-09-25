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
    <link rel="stylesheet" type="text/css" href="/themes/{{ $shop_theme }}/assets/mobile/css/register.css">
    <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
    <link rel="stylesheet" type="text/css" href="/themes/{{ $shop_theme }}/css/drawer.css">
    <!-- Brown Custom Icon -->
    <link rel="stylesheet" type="text/css" href="/themes/{{ $shop_theme }}/assets/brown-icon/styles.css">
</head>
<body>
    <!--[if lt IE 8]>
    <p class="browserupgrade">You are using an <strong>outdated</strong> browser. Please <a href="http://browsehappy.com/">upgrade your browser</a> to improve your experience.</p>
    <![endif]-->
    <div class="main-container clearfix">
        <div class="holder" style="cursor: pointer;" onClick="location.href='/members/login'">
            <div class="lock">
                <img src="/themes/{{ $shop_theme }}/assets/mobile/img/lock.jpg">
                <div class="text">Already have an account?</div>
                <div class="right"><i class="fa fa-angle-right" aria-hidden="true"></i></div>
            </div>
        </div>
        <div style="margin-top: 10px; ">
            <div class="social-button">
              <!--   <a href="{{$fb_login_url or '#'}}" class="holder fb">
                    <div class="name"><i class="fa fa-facebook" aria-hidden="true"></i> Sign in with Facebook</div>
                </a> -->
                <a href="javascript:" class="holder gp" id="customBtn" style="display: block; color: font-size: 15px; font-weight: 500; color: #333">
                    <div class="name "><i class="fa fa-google-plus" aria-hidden="true" style="color: #DE5245; font-size: 25px; vertical-align: middle; padding-right: 15px;"></i> Sign up with Google+</div>
                </a>
            </div>
        </div>
        <div class="holder">
            <form method="post">
            {{ csrf_field() }}
            @if(Session::has('error'))
                <div class="alert alert-danger">
                    <ul>
                        <li>{!! session('error') !!}</li>
                    </ul>
                </div>
            @endif

            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
                <div class="form-group">
                    <label>First Name</label>
                    <input class="form-control" type="text" name="first_name" value="{{ old('first_name') }}">
                </div>
                <div class="form-group">
                    <label>Middle Name</label>
                    <input class="form-control" type="text" name="middle_name" value="{{ old('middle_name') }}">
                </div>
                <div class="form-group">
                    <label>Last Name</label>
                    <input class="form-control" type="text" name="last_name" value="{{ old('last_name') }}">
                </div>
                <div class="form-group">
                    <label>Date of Birth</label>
                    <div class="form-input">
                        <div class="date-holder">
                            <select name="b_month" class="form-control">
                                @for($ctr = 1; $ctr <= 12; $ctr++)
                                <option {{ old('b_month') == $ctr ? 'selected' : '' }} value="{{ $ctr }}">{{ date("F", strtotime($ctr . "/01/17")) }}</option>
                                @endfor
                            </select>
                        </div>
                        <div class="date-holder">
                            <select name="b_day" class="form-control">
                                @for($ctr = 1; $ctr <= 31; $ctr++)
                                <option {{ old('b_day') == $ctr ? 'selected' : '' }} value="{{ $ctr }}">{{ $ctr }}</option>
                                @endfor
                            </select>
                        </div>
                        <div class="date-holder">
                            <select name="b_year" class="form-control">
                                @for($ctr = date("Y"); $ctr >= (date("Y")-100); $ctr--)
                                <option {{ old('b_year') == $ctr ? 'selected' : '' }} value="{{ $ctr }}">{{ $ctr }}</option>
                                @endfor
                            </select>
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <label>Gender</label>
                    <select name="gender" class="form-control">
                        <option value="male">Male</option>
                        <option value="female">Female</option>
                    </select>
                </div>
                <div class="form-group">
                    <label>Contact</label>
                    <input class="form-control" type="number" name="contact" value="{{ old('contact') }}">
                </div>
                <div class="form-group">
                    <label>E-mail</label>
                    <input class="form-control" type="email" name="email" {{ old('email') }}>
                </div>
                <div class="form-group">
                    <label>Password</label>
                    <input class="form-control" type="password" name="password" value="{{ old('password') }}">
                </div>
                <div class="form-group">
                    <label>Repeat Password</label>
                    <input class="form-control" type="password" name="password_confirmation" value="{{ old('password_confirmation') }}">
                </div>
                <div class="form-group">
                    <div class="agreement">I agree with Brown <a href="javascript:">Terms of Use</a> and <a href="javascript:">Privacy Policy</a></div>
                </div>
                <div class="form-group">
                    <button class="btn" type="submit">Sign Up</button>
                </div>
            </form>
        </div>
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