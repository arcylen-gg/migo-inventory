@extends("layout")
@section("content")
<!-- CONTENT -->
<div class="header" style="background-image: url('/themes/{{ $shop_theme }}/img/contact-header.jpg');">
	<div class="container">Contact Us</div>
</div>
<div class="container content">
	<div class="row clearfix">
		<div class="col-md-12">
			<div class="col-md-7">
				<div id="get-intouch" class="content-title">Get Intouch With Us</div>
				<!-- INPUTS -->
				<form method="post">
				<input type="hidden" name="_token" value="{{ csrf_token() }}">
					<div class="inputs">
						<div class="col-md-6">
							<input type="text" name="first_name" class="form-control" placeholder="First Name*">
						</div>
						<div class="col-md-6">
							<input type="text" name="last_name" class="form-control" placeholder="Last Name*">
						</div>
						<div class="col-md-6">
							<input type="text" name="phone_number" class="form-control" placeholder="Phone Number*">
						</div>
						<div class="col-md-6">
							<input type="text" name="email_address" class="form-control" placeholder="Email Address*">
						</div>
						<div class="col-md-6">
							<input type="text" name="subject" class="form-control" placeholder="Subject">
						</div>
						<div class="col-md-12">
							<textarea class="form-control" name="message" placeholder="Message" style="height: 180px;"></textarea>
							<!-- SEND BUTTON -->
							@if(Request::input("success"))
								<div class="alert alert-success">
								  <strong>{{ Request::input("success") }}</strong>
								</div>
							@elseif(Request::input("fail"))
								<div class="alert alert-danger">
								  <strong>{{ Request::input("fail") }}</strong>
								</div>
							@endif
							<button type="submit">SEND</button>
						</div>
					</div>
				</form>
			</div>
			<div class="col-md-5">
				<div id="location" class="content-title">Location</div>
				<table>
					<tr>
						<td class="icon"><i class="fa fa-map-marker" aria-hidden="true"></i></td>
						<td class="par">{{ isset($company_info['company_address']) ? $company_info['company_address']->value : 'Lorem ipsum dolor sit amet, consectetuer adipiscing elit. Aenean commodo ligula eget dolor. Aenean massa.' }}</td>
					</tr>
					<tr>
						<td class="icon"><i class="fa fa-mobile" aria-hidden="true"></i></td>
						<td class="par">{{ isset($company_info['company_mobile']) ? $company_info['company_mobile']->value : '+44 870 888 88 88' }}</td>
					</tr>
					<tr>
						<td class="icon"><i class="fa fa-envelope" aria-hidden="true"></td>
						<td class="par">{{ isset($company_info['company_email']) ? $company_info['company_email']->value : 'youremailhere@company.com' }}</td>
					</tr>
				</table>
				<div>
					<div class="content-title business-hours">Business Hours</div>
					<table>
						<tr>
							<td class="icon"><i class="fa fa-clock-o" aria-hidden="true"></i></td>
							<td class="par">{{ isset($company_info['company_hour']) ? $company_info['company_hour']->value : 'Monday - Friday at 9:00am - 6:00pm' }}</td>
						</tr>
					</table>
				</div>
			</div>
		</div>
		<!-- <div class="google-map">
			<iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3861.705779275078!2d121.00566831496255!3d14.558810389829077!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x3397c9730adc49fd%3A0xb721aedbb51dd260!2sThe+Linear+Makati+Tower+I!5e0!3m2!1sen!2sph!4v1485887036000" allowfullscreen></iframe>
		</div> -->
	</div>
</div>
<div class="map">
	<div class="map-header">
		<div class="container">Find us on Map</div>
	</div>
	<iframe src="https://www.google.com/maps/embed?pb=!1m14!1m12!1m3!1d30851.535259676984!2d120.93737719999999!3d14.856548099999998!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!5e0!3m2!1sen!2sph!4v1493383619343" height="450" frameborder="0" style="border:0" allowfullscreen></iframe>
</div>
<div class="join">
	<div class="container">
		<div class="holder">
			<div class="row clearfix">
				<div class="col-md-8">
					<div class="text">We offer one of the Strongest Compensations plans in the Industry with Multiple Ways to Earn Weekly and Monthly Income!</div>
				</div>
				<div class="col-md-4 text-center">
					<button class="btn">Join us today</button>
				</div>
			</div>
		</div>
	</div>
</div>
<!-- SCROLL TO TOP -->
<div class="scroll-up"><img src="/themes/{{ $shop_theme }}/img/scroll-up.png"></div>
@endsection

@section("css")
<link rel="stylesheet" type="text/css" href="/themes/{{ $shop_theme }}/css/contactus.css">
@endsection

