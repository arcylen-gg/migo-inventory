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

        <link rel="stylesheet" type="text/css" href="/themes/{{ $shop_theme }}/css/3xcell_signup.css">
        <link rel="stylesheet" type="text/css" href="/assets/member/plugin/toaster/toastr.css">

        <script src="/themes/{{ $shop_theme }}/assets/initializr/js/vendor/modernizr-2.8.3-respond-1.4.2.min.js"></script>
    </head>
    <body>
    <div class="loader hide">
      <span><img src="/resources/assets/frontend/img/loader.gif"></span>
    </div>

    <div class="content">
		<div class="top-1-container">
			<div class="container">
				<div class="title-container">Member Register</div>
			</div>
		</div>
		<div class="top-2-container">
			<div class="container">
				<div class="fill-up-container row clearfix">
					<form method="post" action="/mlm/register" class="global-submit" autocomplete="on"> 
                    <input type="hidden" class="token" name="_token" value="{{ csrf_token() }}">
						<div class="col-md-6">
							<div class="title-container">Account Details</div>
							<div class="account-details">
								<input required type="text" name="first_name" placeholder="Your First Name">
								<input required type="text" name="last_name" placeholder="Your Last Name">
								<input type="text" name="company" placeholder="Company (Optional)">
								<input required type="email" name="email" placeholder="Email Address">
								<input required type="tel" name="customer_mobile" placeholder="" value="+63">
								<input type="text" name="tinnumber" placeholder="TIN (Optional)">
								<input required type="text" name="username" placeholder="Username">
								<input required type="password" name="pass" placeholder="Password">
								<input required type="password" name="pass2" placeholder="Repeat Password">
							</div>
						</div>
						<div class="col-md-6">
							<div class="title-container">Location Details</div>
							<div class="location-details">
								<input required type="text" name="customer_street" placeholder="Street">
								<input required type="text" name="customer_city" placeholder="City">
								<input required type="text" name="customer_state" placeholder="Province">
								<input required type="text" name="customer_zipcode" placeholder="Zipcode">
								<!-- COUNTRY -->
								<select required class="country" name="country">
									@foreach($country as $value)
	                                    <option value="{{$value->country_id}}" @if($value->country_id == 420) selected @endif >{{$value->country_name}}</option>
	                                @endforeach
								</select>

								@if($lead == null)
                                <p>
                                    <input id="username" name="membership_code" onchange="get_sponsor_info_via_membership_code(this)"  type="text" placeholder="Membership Code of Sponsor (Optional) "/>
                                </p>
                                <p class="sponsor-info " id="sponsor_info_get">
                                    
                                </p>
                                @else
                                <p>
                                    <center>Sponsor</center>
                                    <select class="form-control select_country" name="membership_code" style="">
                                        @foreach($lead_code as $value)
                                            <option value="{{$value->membership_activation_code}}" >{{$value->membership_activation_code}} (Slot {{$value->slot_no}})</option>
                                        @endforeach
                                    </select>
                                </p>
                                <p class="sponsor-info" id="sponsor_info_get" >
                                        @if(isset($customer_info)){!! $customer_info !!}@endif
                                </p>
                                @endif

							</div>
							<div class="button-container">
								<button type="submit" style="border: 0;" class="register-button butonn_register">SIGN UP</button>
							</div>
						</div>
					</form>
				</div>			
			</div>
		</div>

		<!-- SCROLL TO TOP -->
		<div class="scroll-up"><img src="/themes/{{ $shop_theme }}/img/scroll-up.png"></div>
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
        function click_submit_button(ito)
        {
            $('.global-submit').submit();
            $('.butonn_register').attr("disabled", true);
        }
        
        function submit_done(data)
        {
            $('.butonn_register').prop("disabled", false);
            // $('.butonn_register').removeAttr("disabled");
            console.log(data);
            if(data.type == 'error')
            {
                toastr.error(data.message);
                $('.butonn_register').attr("disabled", false);
                $('.butonn_register').removeAttr("disabled");
            }
            else
            {
                $('.butonn_register').attr("disabled", true);
                toastr.success(data.message);

                @if(Request::input("checkout") == 1)
	            	location.href = '/checkout';
	            @else
	            	location.href = '/mlm';
	            @endif
            }
        }
        function get_sponsor_info_via_membership_code(ito)
        {
            var membership_code = $(ito).val();
            get_customer_view(membership_code);
        }
        function get_customer_view(membership_code)
        {
            $('#sponsor_info_get').load('/mlm/register/get/membership_code/' + membership_code);
        }
        </script>
    </body>
</html>