@extends("layout")
@section("content")
<div class="content">
	<div class="background-container">
		<div class="container">
		    <div class="row clearfix">
		    	<div class="col-md-6">
		    		<div class="signup-left-container">
		    			<span class="desc-red">Press-IQ</span><span class="desc-black"> is a intelligent results- driven platform for</span><span class="desc-red"> PR Professionals</span><span class="desc-black"> and</span><span class="desc-red"> Marketers</span><span class="desc-black"> for targeted distribution of your press release. We provide you with access to the most updated and extensive database of media journalist</span>
		    		</div>
		    	</div>
		    	<div class="col-md-6">
		    		<div class="background-border">
			    		<div class="signup-rght-container">
			    			<div class="title-container">Sign Up below, it's easy</div>
			    			<div class="description">Press IQ makes it easier for you to create, manage, and distribute your press release</div>
			    			<div class="border"></div>
			    			<form method="post" enctype="multipart/form-data">
			    				{{csrf_field()}}
			    			@if(session()->has('message'))
							<div class="details">
							<span style="color: red;">
								<strong>Error!</strong> {{ session('message') }}<br>
							</span>
							</div>
							@endif
			    			<div class="register-form">
			    				<input type="text" name="user_first_name" id="user_first_name" placeholder="First Name">
			    			</div>
			    			<div class="register-form">
			    				<input type="text" name="user_last_name" id="user_last_name" placeholder="Last Name">
			    			</div>
			    			<div class="register-form">
			    				<input type="email" name="user_email" id="user_email" placeholder="Email">
			    			</div>
			    			<div class="register-form">
			    				<input type="password" name="user_password" id="user_password" placeholder="Password">
			    			</div>
			    			<div class="register-form">
			    				<input type="password" name="user_password_confirmation" id="user_password_confirmation" placeholder="Confirm Password">
			    			</div>
			    			<div class="register-form">
			    				<input type="text" name="user_company_name" id="user_company_name" placeholder="Company">
			    			</div>
			    			<div class="register-form">
			    				<input type="file" name="user_company_image" id="user_company_image">
			    			</div>

			    			<div class="button-container">
			    			    <button type="submit" href="">REGISTER NOW</button>
			    			</div>
			    			</form>
			    			<div class="details">We respect your privacy and will never share your information.</div>
			    		</div>
		    		</div>
		    	</div>
		    </div>
		</div>
	</div>
</div>
@endsection

@section("css")
<link rel="stylesheet" type="text/css" href="/themes/{{ $shop_theme }}/css/sign_up.css">
@endsection

@section("script")

<script type="text/javascript">

</script>

@endsection