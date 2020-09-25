<!doctype html>
<!--[if lt IE 7]>      <html class="no-js lt-ie9 lt-ie8 lt-ie7 lt-ie10"> <![endif]-->
<!--[if IE 7]>         <html class="no-js lt-ie9 lt-ie8 lt-ie10"> <![endif]-->
<!--[if IE 8]>         <html class="no-js lt-ie9 lt-ie10"> <![endif]-->
<!--[if IE 9]>         <html class="no-js lt-ie10"> <![endif]-->
<!--[if gt IE 9]><!--> <html class="no-js"> <!--<![endif]-->
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<title>Digima House - Login</title>
	<meta name="description" content="">
	<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
	
	<link rel="stylesheet" href="/assets/member/styles/92bc1fe4.bootstrap.css">
	
	<link rel="stylesheet" href="/assets/member/styles/aaf5c053.proton.css">
	<link rel="stylesheet" href="/assets/member/styles/vendor/animate.css">
	
	<!--[if lt IE 9]>
	<script src="//cdnjs.cloudflare.com/ajax/libs/html5shiv/3.6.2/html5shiv.js"></script>
	<script src="scripts/vendor/respond.min.js"></script>
	<![endif]-->
	
	<link rel="stylesheet" href="/assets/member/styles/6227bbe5.font-awesome.css" type="text/css"/>
	<link rel="stylesheet" href="/assets/member/styles/40ff7bd7.font-titillium.css" type="text/css"/>
	
	<script>
	(function () {
	var js;
	if (typeof JSON !== 'undefined' && 'querySelector' in document && 'addEventListener' in window) {
	js = 'http://ajax.googleapis.com/ajax/libs/jquery/2.0.3/jquery.min.js';
	} else {
	js = 'http://ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js';
	}
	document.write('<script src="' + js + '"><\/script>');
	}());
	</script>
	<script src="scripts/vendor/modernizr.js"></script>
	<script src="scripts/vendor/jquery.cookie.js"></script>
</head>
<body class="login-page">
	<script>
		var theme = $.cookie('protonTheme') || 'default';
		$('body').removeClass (function (index, css) {
		return (css.match (/\btheme-\S+/g) || []).join(' ');
		});
		if (theme !== 'default') $('body').addClass(theme);
	</script>
	<!--[if lt IE 8]>
	<p class="browsehappy">You are using an <strong>outdated</strong> browser. Please <a href="http://browsehappy.com/">upgrade your browser</a> to improve your experience.</p>
	<![endif]-->
	<form method="post">
		<input type="hidden" name="_token" value="{{ csrf_token() }}">
		<section class="wrapper scrollable animated fadeInDown">
			<section class="panel panel-default">
				<div class="panel-heading">
		
					<div>
						<img src="/assets/member/images/proton-logo.png" alt="proton-logo">
						<h1>
						<span class="title">
							Digima House
						</span>
						<span class="subtitle">
							Enterprise Resource Planner
						</span>
						</h1>
					</div>
				</div>
				<ul class="list-group">
					<li class="list-group-item">
					
							<span class="welcome-text">
								Welcome to Digima House!
							</span>
							@if(session()->has('message'))
								<span class="member" style="color: red;">
									 <strong>Error!</strong> {{ session('message') }}<br>
								</span>
							@endif
				
			
					</li>
					<li class="list-group-item">
						<span class="login-text">
							Sign in Now
						</span>
						<div class="form-group">
							<label for="email">Email</label>
							<input value="{{ old('email') }}" name="email" type="email" class="form-control input-lg" id="email" placeholder="Email">
						</div>
						<div class="form-group">
							<label for="password">Password</label>
							<input name="password" type="password" class="form-control input-lg" id="password" placeholder="Password">
						</div>
					</li>
				</ul>
				<div class="panel-footer">
					<button class="btn btn-lg btn-success" type="submit">LOGIN TO YOUR ACCOUNT</button>
					<br>
					<a class="forgot" href="javascript:;">Forgot Your Password?</a>
				</div>
			</section>
		</section>
	</form>
</body>
</html>