@extends("layout")
@section("content")
<div class="content">
	<div class="background-container" style="background-image: url('/themes/{{ $shop_theme }}/img/home-banner.jpg')">
		<div class="container">
			<div class="row clearfix">
				<div class="col-md-12">
			    	<div class="background-border">
				   		<div class="signin-container">
				   			<div class="title-container">Login</div>
				   			<form method="post">
				    				{{csrf_field()}}
				    			<div class="border"></div>
				    			<div class="register-form">
				    				<input type="text" name="user_email" id="user_email" placeholder="Email">
				    			</div>
				    			<div class="register-form">
				    				<input type="password" name="user_password" id="user_password" placeholder="Password">
				    			</div>
				    			@if(session()->has('message'))
								<span class="register-form" style="color: red;">
									 <strong>Error!</strong> {{ session('message') }}<br>
								</span>
								<div class="forgot-password"><a href="">Forgot Password?</a></div> 
				    			<div class="button-container">
				    			<button type="submit" href="">Login</button>
								@else
				    			<div class="forgot-password"><a href="">Forgot Password?</a></div> 
				    			<div class="button-container">
				    			<button type="submit" href="">Login</button>
				    			@endif
				    		</form>
				    	</div>
			    	</div>
				</div>
			</div>
		</div>
	</div>
</div>
@endsection

@section("css")
<link rel="stylesheet" type="text/css" href="/themes/{{ $shop_theme }}/css/sign_in.css">
@endsection

@section("script")

<script type="text/javascript">

</script>

@endsection