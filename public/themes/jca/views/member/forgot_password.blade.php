@extends("layout")
@section("content")
<div class="container">
	<div class="register">
		<table>
			<tbody>
				<tr>
					<td class="c1">
						<div class="register-side">
							<img src="/themes/{{ $shop_theme }}/img/forgot_password.jpg">
						</div>
					</td>
					<td class="c2">
						<form action="/members/forgot-password-submit" method="post">
							<input type="hidden" value="{{csrf_token()}}" name="_token">
							<div class="register-form">
								<div class="form-group">
										<div class="choice">

											<h2 class ="h2-Forgot">Forgot Password</h2>
											<h2 class="h2-Lost">Lost Password</h2>
											<p class="p-Follow">Follow these simple steps to reset your password</p>
											<p class="p-Enter">1. Enter your <a style="text-decoration: none">{{$_SERVER['SERVER_NAME']}}</a> E-mail Address</p>
											<p class="p-Wait">2. Wait for your recovery details to be sent</p>
											<p class="p-Follow-instruction">3. Follow instruction to login your account again	</p>
											<div class="form-input alert-message">
												@if(Session::has('success'))
												<div class="alert alert-success">
													{!! Session::get('success') !!}
												</div>
												@endif
												@if(Session::has('danger'))
												<div class="alert alert-danger">
													{!! Session::get('danger') !!}
												</div>
												@endif
											</div>
											<div class="register-label">EMAIL</div>

											<div class="form-input">
												<input class="form-control input-sm" type="email" name="email" placeholder="Type Your Email Here">
											</div>
										<div class="holder">
											<button type="submit" class="btn btn-brown">Get New Password</button>
										</div>
									</div>
								</div>
							</div>
						</form>
					</td>
				</tr>
			</tbody>
		</table>	
	</div>	
</div>
@endsection
@section("script")
<!-- <script type="text/javascript">
	function submit_done(data)
	{
		type = data.status;
		html_data = '<div class="alert alert-'+type+'">'+
			data.status_message
			+'</div>';
	}
</script> -->
@endsection
@section("css")
<link rel="stylesheet" type="text/css" href="/themes/{{ $shop_theme }}/css/forgot_password.css">
@endsection