<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="utf-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
		<meta name="description" content="">
		<meta name="author" content="">
		<link rel="icon" href="../../favicon.ico">
		<title>Sample Website</title>
		<!-- Bootstrap core CSS -->
		<link href="/assets/themes/css/bootstrap.min.css" rel="stylesheet" type="text/css">
		<link href="/themes/{{ $shop_theme }}/css/main.css" rel="stylesheet" type="text/css">
		<!-- THEME COLOR -->
		<link href="/themes/{{ $shop_theme }}/css/{{ $shop_theme_color }}.css" rel="stylesheet" type="text/css">
		<!-- Brown Custom Icon -->
        <link rel="stylesheet" type="text/css" href="/themes/{{ $shop_theme }}/assets/brown-icon/styles.css">
        <!-- FONT AWESOME -->
        <link rel="stylesheet" type="text/css" href="/themes/{{ $shop_theme }}/assets/font-awesome/css/font-awesome.min.css">
        @yield("css")
	</head>
	<body>
		<nav class="navbar" style="background-color: #fff;">
			<div class="container">
				<div class="navbar-header">
					<button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
					<span class="sr-only">Toggle navigation</span>
					<span class="icon-bar"></span>
					<span class="icon-bar"></span>
					<span class="icon-bar"></span>
					</button>
					<a class="navbar-brand" href="#">Sample Website</a>
				</div>
				<!-- NAVIGATION -->
				<div id="navbar" class="navbar-collapse collapse navbar-right">
					<ul class="nav navbar-nav">
						<li class="active"><a href="/members/login">Login</a></li>
						<li><a href="/members/register">Register</a></li>
					</ul>
				</div>
				<!-- END NAVIGATION -->
			</div>
		</nav>
		<!-- START OF CONTENT -->
		@yield('content');
		<!-- END OF CONTENT -->

	    {{-- START GLOBAL MODAL --}}
	    <div id="global_modal" class="modal fade" role="dialog" >
	        <div class="modal-dialog">
	            <!-- Modal content-->
	            <div class="modal-content modal-content-global clearfix">
	            </div>
	        </div>
	    </div>
	    {{-- END GLOBAL MODAL --}}
	    {{-- GLOBAL MULTIPLE MODAL --}}
	    <div class="multiple_global_modal_container"></div>
	    {{-- END GLOBAL MULTIPLE MODAL --}}


		
		<script src="/assets/themes/js/jquery.min.js"></script>
		<script src="/assets/themes/js/bootstrap.min.js"></script>
		<script type="text/javascript" src="/assets/front/js/global_function.js"></script>
		@yield("script")
	</body>
</html>