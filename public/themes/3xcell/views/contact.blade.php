@extends("layout")
@section("content")
<div class="content">
	<div class="top-1-container">
		<div class="container">
			<div class="title-container">{{ get_content($shop_theme_info, "contact", "contactus_banner_title") }}</div>
		</div>
	</div>
	<div class="top-2-container">
		<div class="container">
			<div class="content-container row clearfix">
				<div class="col-md-6">
					<div class="title-container">Get In Touch With Us</div>
					<div class="fill-up-container">
						<!-- TEXTFIELD CONTAINER -->
						<div class="textfield-container row clearfix">
							<div class="col-md-6 col-sm-6 col-xs-12 form-holder">
								<input type="text" class="form-control" placeholder="First Name*">
							</div>
							<div class="col-md-6 col-sm-6 col-xs-12 form-holder">
								<input type="text" class="form-control" placeholder="Last Name*">
							</div>
							<div class="col-md-6 col-sm-6 col-xs-12 form-holder">
								<input type="text" class="form-control" placeholder="Phone Number*">
							</div>
							<div class="col-md-6 col-sm-6 col-xs-12 form-holder">
								<input type="text" class="form-control" placeholder="Email Address*">
							</div>
							<div class="col-md-6 col-sm-6 col-xs-12 form-holder">
								<input type="text" class="form-control" placeholder="Subject">
							</div>
						</div>
						<div class="textfield-container">
							<textarea class="form-control message-textarea" placeholder="Message"></textarea>
						</div>
						<div class="textfield-container">
							<button>SEND</button>
						</div>
					</div>
				</div>
				<div class="col-md-6">
					<div class="title-container">Location</div>
					<div class="location-content-container">
						<div class="title-2-container">{{ get_content($shop_theme_info, "contact", "contactus_office1") }}</div>
						<div class="detail-container">
							{{ get_content($shop_theme_info, "contact", "contactus_office1_address") }}
						</div>
						<div class="title-2-container">{{ get_content($shop_theme_info, "contact", "contactus_office2") }}</div>
						<div class="detail-container">
							{{ get_content($shop_theme_info, "contact", "contactus_office2_address") }}
						</div>
						<div class="detail-container">
							<span><i class="fa fa-mobile" aria-hidden="true" style="font-size: 25px; color: #1c1c1c;"></i>&nbsp;&nbsp;</span><span>{{ get_content($shop_theme_info, "contact", "contactus_phone_number") }}</span>
						</div>
						<div class="detail-container">
							<span><i class="fa fa-envelope" aria-hidden="true" style="font-size: 13px; color: #1c1c1c;"></i>&nbsp;&nbsp;</span><span>{{ get_content($shop_theme_info, "contact", "contactus_email") }}</span>
						</div>
						<div class="title-2-container">BUSINESS HOURS</div>
						<div class="detail-container">
							<span><i class="fa fa-clock-o" aria-hidden="true" style="font-size: 15px; color: #1c1c1c;"></i>&nbsp;&nbsp;</span><span>{{ get_content($shop_theme_info, "contact", "contactus_business_hours") }}</span>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
	<div class="bot-container">
		<div class="container">
			<div class="title-container">Find Us On Google Map</div>
			<div class="content-container row clearfix">
				<div class="col-md-6 col-sm-6">
					<div class="map-container">
						<iframe class="map" src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d15439.672607472603!2d121.03694842734843!3d14.660586160660534!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x3397b719dcc15bd1%3A0xfd4fe4f1c0982e9a!2sVicars+Building!5e0!3m2!1sen!2sph!4v1501901215371" width="" height="450" frameborder="0" style="border:0" allowfullscreen></iframe>
					</div>
					<div class="title-container first-title">PRINCIPAL OFFICE</div>
				</div>
				<div class="col-md-6 col-sm-6">
					<div class="map-container">
						<iframe class="map" src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3967.108523067281!2d125.17183721482861!3d6.116090629461948!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x32f79fa64c22dedb%3A0xd952833de2ffdf90!2sPerla+Insurance!5e0!3m2!1sen!2sph!4v1501901631315" width="600" height="450" frameborder="0" style="border:0" allowfullscreen></iframe>
					</div>
					<div class="title-container">GENSAN BRANCH OFFICE</div>
				</div>
			</div>
		</div>
	</div>
	<!-- 3XCELL PERMITS -->
	<div class="bot-container">
		<div class="container">
		</div>
	</div>

	<!-- SCROLL TO TOP -->
	<div class="scroll-up"><img src="/themes/{{ $shop_theme }}/img/scroll-up.png"></div>
</div>
@endsection

@section("css")
<link rel="stylesheet" type="text/css" href="/themes/{{ $shop_theme }}/css/contact.css">
@endsection

@section("js")
<script type="text/javascript">
$(document).ready(function()
{
	/*scroll up*/
	$(window).scroll(function () {
        if ($(this).scrollTop() > 700) {
            $('.scroll-up').fadeIn();
        } else {
            $('.scroll-up').fadeOut();
        }
    });

    $('.scroll-up').click(function () {
        $("html, body").animate({
            scrollTop: 0
        }, 700);
        return false;
    });
});
</script>
@endsection