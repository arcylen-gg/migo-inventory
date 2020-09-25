@extends("layout")
@section("content")
<div class="content">
	<div class="contact-container">
	    <div class="container">
	        <div class="row clearfix">
	            <div class="col-md-6">
	                <div class="title">{{ get_front_divide_string($shop_theme_info, "contact", "contact_title", 2, 0) }} <font class="intouch-label">{{ get_front_divide_string($shop_theme_info, "contact", "contact_title", 2, 1) }}</font></div>
	                <div class="form-group-container">
	                    <div class="row clearfix">
	                        <div class="col-md-6">
	                            <div class="form-group">
	                                <label>First Name *</label>
	                                <input type="text" class="form-control">
	                            </div>
	                        </div>
	                        <div class="col-md-6">
	                            <div class="form-group">
	                                <label>Last Name *</label>
	                                <input type="text" class="form-control">
	                            </div>
	                        </div>
	                    </div>
	                    <div class="row clearfix">
	                        <div class="col-md-6">
	                            <div class="form-group">
	                                <label>Phone Number *</label>
	                                <input type="text" class="form-control">
	                            </div>
	                        </div>
	                        <div class="col-md-6">
	                            <div class="form-group">
	                                <label>Email Address *</label>
	                                <input type="text" class="form-control">
	                            </div>
	                        </div>
	                    </div>
	                    <div class="row clearfix">
	                        <div class="col-md-6">
	                            <div class="form-group">
	                                <label>Subject *</label>
	                                <input type="text" class="form-control">
	                            </div>
	                        </div>
	                    </div>
	                    <div class="row clearfix">
	                        <div class="col-md-12">
	                            <div class="form-group">
	                                <label>Message *</label>
	                                <textarea class="form-control"></textarea>
	                            </div>
	                        </div>
	                    </div>
	                    <div class="text-right">
	                        <button class="btn btn-primary">Send</button>
	                    </div>
	                </div>
	            </div>
	            <div class="col-md-6">
	                <div class="title">{{ get_content($shop_theme_info, "contact", "location_title") }}</div>
	                <div class="info">
	                    <table>
	                        <tbody>
	                            <tr>
	                                <td class="icon"><i class="fa fa-map-marker" aria-hidden="true"></i></td>
	                                <td class="text">{{ get_content($shop_theme_info, "contact", "location_address") }}</td>
	                            </tr>
	                            <tr>
	                                <td class="icon"><i class="fa fa-mobile" aria-hidden="true"></i></td>
	                                <td class="text">{{ get_content($shop_theme_info, "contact", "location_phone") }}</td>
	                            </tr>
	                            <tr>
	                                <td class="icon"><i class="fa fa-envelope" aria-hidden="true"></i></td>
	                                <td class="text">{{ get_content($shop_theme_info, "contact", "location_email") }}</td>
	                            </tr>
	                        </tbody>
	                    </table>
	                </div>
	                <div class="title">{{ get_front_divide_string($shop_theme_info, "contact", "business_title", 2, 0) }} <font class="business-label">{{ get_front_divide_string($shop_theme_info, "contact", "business_title", 2, 1) }}</font></div>
	                <div class="info">
	                    <table>
	                        <tbody>
	                            <tr>
	                                <td class="icon"><i class="fa fa-clock-o" aria-hidden="true"></i></td>
	                                <td class="text">{{ get_content($shop_theme_info, "contact", "business_hours") }}</td>
	                            </tr>
	                        </tbody>
	                    </table>
	                </div>
	            </div>
	        </div>
	    </div>
	</div>
	<div class="map-title">{{ get_front_divide_string($shop_theme_info, "contact", "map_title", 2, 0) }} <font class="map-title-pink">{{ get_front_divide_string($shop_theme_info, "contact", "map_title", 2, 1) }}</font></div>
	<div class="map-container">
		<div class="container">
			<iframe src="https://www.google.com/maps/embed?pb=!1m14!1m12!1m3!1d3861.007280409033!2d120.97894086009421!3d14.598660949454503!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!5e0!3m2!1sen!2sph!4v1468556209677" width="100%" height="450" frameborder="0" style="border:0" allowfullscreen></iframe>
		</div>
	</div>
</div>
@endsection
@section("css")
<link rel="stylesheet" type="text/css" href="/themes/{{ $shop_theme }}/assets/front/css/contact.css">
@endsection
@section("script")

@endsection