@extends("layout")
@section("content")
<div class="container">
	<input type="hidden" id="_token" value="{{csrf_token()}}">
	<div class="register">
		<table>
			<tbody>
				<tr>
					<td class="c1">
						<div class="register-side">
							<img src="/themes/{{ $shop_theme }}/img/login-bg.jpg">
						</div>
					</td>
					<td class="c2">
						<div class="register-form">
							<div class="text-right social-button">
								<a href="{{$fb_login_url or '#'}}" class="holder fb">
									<div class="name"><i class="fa fa-facebook" aria-hidden="true"></i> Sign in with Facebook</div>
								</a>
								<a href="javascript:" class="holder gp" id="customBtn">
									<div class="name "><i class="fa fa-google-plus" aria-hidden="true"></i> Sign in with Google+</div>
								</a>
							</div>

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

							<form method="post">
								{{ csrf_field() }}
								<div class="form-group">
									<div class="register-label">EMAIL</div>
									<div class="form-input">
										<input class="form-control input-sm" type="email" name="email" placeholder="Type Your Email Here" value="">
									</div>
								</div>
								<div class="form-group">
									<div class="register-label">PASSWORD</div>
									<div class="form-input">
										<input class="form-control input-sm" type="password" name="password" placeholder="Type Your Password Here" value="">
									</div>
								</div>
								<div class="form-group">
									<div class="choice">
										<div class="holder">
											<button class="btn btn-brown">Login</button>
										</div>
										<div class="holder"><span class="or">OR</span></div>
										<div class="holder"><a class="login-href" href="/members/register">Create an Account</a></div>
									</div>
								</div>
							</form>

							<div class="form-group text-center">
								<div class="forgot" style="color: #808080; font-weight: 600;">Forgot Password? <a style="color: #4d575e;" href="/members/forgot-password">Click Here</a></div>
							</div>
						</div>
					</td>
				</tr>
			</tbody>
		</table>		
	</div>
</div>
@endsection
@section("script")
<script type="text/javascript" src="/assets/member/js/google_script/google_script_auth.js"></script>

<script>startApp();</script>
@endsection
@section("css")
<link rel="stylesheet" type="text/css" href="/themes/{{ $shop_theme }}/css/member_login.css">
@endsection