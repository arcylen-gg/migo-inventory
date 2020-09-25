@extends("layout")
@section("content")

<div class="content">

	<div class="signin-form-container">
		<div class="container">
			<div class="row clearfix">

				<div class="col-md-4">

					<div class="signin-form">
						
						<div class="text signin-text-header">Welcome to Digima House!</div>
						<div class="text signin-subtext-header">Not a Member? <span><a href="#" class="signup">Sign Up >></a></span></div>
						
						<div class="text textbox-label">Signin your Shop Now</div>
						
						<div class="textbox-container">
	                     	<input type="text" class="form-control email-input" placeholder="Email">
                        	<input type="Password" class="form-control password-input" placeholder="Password">
						</div>

					</div>
				</div>

				<div class="col-md-8">
					<img width="510" height="300" src="/themes/{{ $shop_theme }}/img/mlm-adv.png">
				</div>

			</div>

			<div>Digima Web Solutions provides us with critical insight into our business which allows us to make fast decision and ultimately become more dynamic and competitive in marketplace. Visit www.digimaweb.solutions</div>
		</div>
	</div>

</div>

@endsection

@section("css")
<link rel="stylesheet" type="text/css" href="/themes/{{ $shop_theme }}/css/signin.css">
@endsection

@section("js")

<script type="text/javascript">
$(document).ready(function()
{
	
});
</script>
@endsection