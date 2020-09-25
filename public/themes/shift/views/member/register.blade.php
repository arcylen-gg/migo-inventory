@extends("layout")
@section("content")
<form method="post">
{{ csrf_field() }}
	<div class="container">
	<input type="hidden" id="_token" value="{{csrf_token()}}">
		<div class="register">
			<table>
				<tbody>
					<tr>
						<td colspan="2">
							<div class="register-header clearfix">
								<div class="left">
									<div class="title">Create new customer account</div>
								</div>
								<div class="right">
									<div class="or">
										<img src="/themes/{{ $shop_theme }}/img/or-2.png">
									</div>
									<div class="text-right social-button">
										{{-- <a href="{{$fb_login_url or '#'}}" class="holder fb">
											<div class="name"><i class="fa fa-facebook" aria-hidden="true"></i> Sign up with Facebook</div>
										</a> --}}
										<a href="javascript:" class="holder gp" id="customBtn">
											<div class="name"><i class="fa fa-google-plus" aria-hidden="true"></i> Sign up with Google+</div>
										</a>
									</div>
								</div>
							</div>
						</td>
					</tr>
					<tr>
						<td class="c1">
							<div class="register-form">
								<div class="form-group">
									<div class="register-label">GENDER</div>
									<div class="form-input gender-input">
										<label class="radio-inline"><input checked type="radio" name="gender" value="male">MALE</label>
										<label class="radio-inline"><input type="radio" name="gender" value="female">FEMALE</label>
									</div>
								</div>
								<div class="form-group">
									<div class="register-label">EMAIL</div>
									<div class="form-input">
										<input class="form-control input-sm" type="email" name="email" placeholder="Type Your Email Here" value="{{ old('email') }}">
									</div>
								</div>
								<div class="form-group">
									<div class="register-label">CUSTOMER NAME</div>
									<div class="form-input">
										<input class="form-control input-sm" type="text" name="first_name" placeholder="First Name" value="{{ old('first_name') }}">
									</div>
								</div>
								<div class="form-group">
									<div class="form-input">
										<input class="form-control input-sm" type="text" name="middle_name" placeholder="Middle Name" value="{{ old('middle_name') }}">
									</div>
								</div>
								<div class="form-group">
									<div class="form-input">
										<input class="form-control input-sm" type="text" name="last_name" placeholder="Last Name" value="{{ old('last_name') }}">
									</div>
								</div>
								<div class="form-group">
									<div class="register-label">BIRTHDAY</div>
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
							</div>
						</td>
						<td class="c2">
							
							<div class="register-form">

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
									<div class="register-label">CONTACT</div>
									<div class="form-input">
										<input class="form-control input-sm" type="text" name="contact" value="{{ old('contact') }}">
									</div>
								</div>
								<div class="form-group">
									<div class="register-label">PASSWORD</div>
									<div class="form-input">
										<input class="form-control input-sm" type="password" name="password" value="">
									</div>
								</div>
								<div class="form-group">
									<div class="register-label">REPEAT PASSWORD</div>
									<div class="form-input">
										<input class="form-control input-sm" type="password" name="password_confirmation" value="">
									</div>
								</div>
								<div class="form-group clearfix" style="margin-top: 15px;">
									<div class="checkbox agreement-checkbox">
									  <label><input type="checkbox" value="" required> I agree to the Shift <span>Terms of Use and Privacy Policy</span></label>
									</div>
								</div>
								<div class="form-group">
									<div class="choice">
										<div class="holder">
											<button class="btn btn-custom">Sign Up</button>
										</div>
										<div class="holder"><span class="or">OR</span></div>
										<div class="holder"><a class="login-href" href="/members/login">Login an Account</a></div>
									</div>
								</div>
							</div>
						</td>
					</tr>
				</tbody>
			</table>		
		</div>
	</div>
</form>

<!-- Modal -->
<div class="modal fade modal-agreement" id="modal_agreement" role="dialog">
    <div class="modal-dialog">
        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">Accept Shift Contract</div>
            <div class="modal-body">
                <div class="contract">Lorem ipsum dolor sit amet, consectetuer adipiscing elit. Aenean commodo ligula eget dolor. Aenean massa. Cum sociis natoque penatibus et magnis dis parturient montes, nascetur ridiculus mus. Donec quam felis, ultricies nec, pellentesque eu, pretium quis, sem. 

Nulla consequat massa quis enim. Donec pede justo, fringilla vel, aliquet nec, vulputate eget, arcu. In enim justo, rhoncus ut, imperdiet a, venenatis vitae, justo. Nullam dictum felis eu pede mollis pretium. Integer tincidunt. Cras dapibus. Vivamus elementum semper nisi. 

Aenean vulputate eleifend tellus. Aenean leo ligula, porttitor eu, consequat vitae, eleifend ac, enim. Aliquam lorem ante, dapibus in, viverra quis, feugiat a, tellus. Phasellus viverra nulla ut metus varius laoreet. Quisque rutrum. Aenean imperdiet. Etiam ultricies nisi vel augue. 

Curabitur ullamcorper ultricies nisi. Nam eget dui. Etiam rhoncus. Maecenas tempus, tellus eget condimentum rhoncus, sem quam semper libero, sit amet adipiscing sem neque sed ipsum. 

Nam quam nunc, blandit vel, luctus pulvinar, hendrerit id, lorem. Maecenas nec odio et ante tincidunt tempus.</div>
            </div>
            <div class="modal-footer">
            	<button type="submit" class="btn btn-pure pull-right" data-dismiss="modal">Accept</button>
                <button type="submit" class="btn btn-semi pull-right" data-dismiss="modal">Cancel</button>
            </div>
        </div>
    </div>
</div>
@endsection
@section("script")
<script type="text/javascript" src="/themes/{{ $shop_theme }}/js/member_register.js"></script>
<script type="text/javascript" src="/assets/member/js/google_script/google_script_auth.js"></script>

<script>startApp();</script>
@endsection
@section("css")
<link rel="stylesheet" type="text/css" href="/themes/{{ $shop_theme }}/css/member_register.css">
@endsection