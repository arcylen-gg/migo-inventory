@extends("layout")
@section("content")
<div class="contact">
    <header class="header" style="background-image: url('{{ get_content($shop_theme_info, "contact", "contact_banner") }}');">
        <div class="container">
        	<div class="first">{{ get_content($shop_theme_info, "contact", "contact_banner_caption1") }}</div>
        	<div class="second">{{ get_content($shop_theme_info, "contact", "contact_banner_caption2") }}</div>
        </div>
    </header>
    <section>
    	<div class="bread-crumb">
    		<div class="container">
    			<div class="holder">Home</div>
	    		<div class="holder">/</div>
	    		<div class="holder">Get in touch</div>
    		</div>
    	</div>
    </section>
    <section>
    	<div class="container">
    		<div class="row clearfix">
    			<div class="col-md-6">
    				<div class="title">Send Message To Us</div>
    				<form>
    					<div class="row clearfix">
    						<div class="col-md-6">
    							<div class="form-group">
		    						<label>Full Name *</label>
		    						<input type="text" class="form-control" name="">
		    					</div>
    						</div>
    						<div class="col-md-6">
    							<div class="form-group">
		    						<label>Email *</label>
		    						<input type="email" class="form-control" name="">
		    					</div>
    						</div>
    						<div class="col-md-6">
    							<div class="form-group">
		    						<label>Phone</label>
		    						<input type="text" class="form-control" name="">
		    					</div>
    						</div>
    						<div class="col-md-6">
    							<div class="form-group">
		    						<label>Subject *</label>
		    						<input type="text" class="form-control" name="">
		    					</div>
    						</div>
    						<div class="col-md-12">
    							<div class="form-group">
    								<label>How can we help you?</label>
    								<textarea class="form-control"></textarea>
    							</div>
    						</div>
    						<div class="col-md-12">
    							<button class="btn btn-primary" style="width: 150px; margin-bottom: 25px;">Submit</button>
    						</div>
    					</div>
    				</form>
    			</div>
    			<div class="col-md-6">
    				<div id="map"></div>
    				<div class="info-holder">
    					<div class="title"><img src="/themes/{{ $shop_theme }}/img/icon/location.png"> Find Us</div>
    					<div class="desc">{{ get_content($shop_theme_info, "contact", "contact_address") }}</div>
    				</div>
    				<div class="info-holder">
    					<div class="title"><img src="/themes/{{ $shop_theme }}/img/icon/phone.png"> Call us today</div>
    					<div class="desc">{{ get_content($shop_theme_info, "contact", "contact_number") }}</div>
    				</div>
    				<div class="info-holder">
    					<div class="title"><img src="/themes/{{ $shop_theme }}/img/icon/mail.png"> Email us now</div>
    					<div class="desc">{{ get_content($shop_theme_info, "contact", "contact_email") }}</div>
    				</div>
    			</div>
    		</div>
    	</div>
    </section>
    <!-- SCROLL TO TOP -->
    <div class="scroll-up"><img src="/themes/{{ $shop_theme }}/img/scroll-up.png"></div>
</div>
@endsection

@section("css")
<link rel="stylesheet" type="text/css" href="/themes/{{ $shop_theme }}/css/contact.css">
@endsection

@section("script")
<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBxUPGcHnXCbz4npefm3cHzEcikYCKkcUI"></script>
<script type="text/javascript">
	var contentString = '<div class="info-window">' +
		                '<h3>Info Window Content</h3>' +
		                '<div class="info-content">' +
		                '<p>Pellentesque habitant morbi tristique senectus et netus et malesuada fames ac turpis egestas. Vestibulum tortor quam, feugiat vitae, ultricies eget, tempor sit amet, ante. Donec eu libero sit amet quam egestas semper. Aenean ultricies mi vitae est. Mauris placerat eleifend leo.</p>' +
		                '</div>' +
		                '</div>';
	var markerImage = '/themes/{{ $shop_theme }}/img/icon/map-location.png';
</script>
<script type="text/javascript" src="/themes/{{ $shop_theme }}/js/contact.js"></script>
@endsection