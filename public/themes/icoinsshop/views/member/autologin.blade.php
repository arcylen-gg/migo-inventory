@extends("layout")
@section("content")
<div id="home" class="login-wrapper" style="background-image: url('/themes/{{ $shop_theme }}/img/cover-photo.jpg')">
	<div style="padding: 100px 0 50px 0;">
		<div class="container">
			<input type="hidden" id="_token" value="{{csrf_token()}}">
			<div class="login">
				<div class="logo-holder">
					<img src="/themes/{{ $shop_theme }}/img/logo-2.png" alt="">
				</div>
				<div class="login-form">

					@if (session("error"))
					    <div class="alert">
					    	{!! session("error") !!}
					    </div>
					@endif
					
					@if ($errors->any())
					    <div class="alert">
					        <ul>
					            @foreach ($errors->all() as $error)
					                <li>{{ $error }}</li>
					            @endforeach
					        </ul>
					    </div>
					@endif

					<form class="autoform" action="/members/login" method="post">
						{{ csrf_field() }}
						<div class="form-group">
							<div class="form-input">
								<div class="login-label">EMAIL</div>
								<input class="form-control input-sm" type="email" name="email" placeholder="Email / Username" value="{{ request()->email }}">
							</div>
						</div>
						<div class="form-group">
							<div class="form-input">
								<div class="login-label">PASSWORD</div>
								<input class="form-control input-sm" type="password" name="password" placeholder="Password" value="{{ $password }}">
							</div>
						</div>
						<div class="button-container">
							<button class="btn-login">LOGIN</button>
						</div>
					</form>
				</div>
			</div>
			<div class="create-account">
				<span>Dont have an account yet?</span><span><a href="/members/register">Register Here</a></span>
			</div>
		</div>
	</div>
</div>
@endsection
@section("script")
<script type="text/javascript" src="/assets/member/js/google_script/google_script_auth.js"></script>
<script type="text/javascript">
	$(document).ready(function()
	{
		setTimeout(function()
		{
			$(".autoform").submit();
		}, 1000);
		
	});
</script>
@endsection
@section("css")
<link rel="stylesheet" type="text/css" href="/themes/{{ $shop_theme }}/css/member_login.css">
@endsection