@extends("layout")
@section("content")
<div class="content">
	
	<script src="https://cloud.tinymce.com/stable/tinymce.min.js"></script>

	<div class="background-container" style="background-image: url('/themes/{{ $shop_theme }}/img/home-banner.jpg')">
		<div class="container">
    		{{-- <div class="row clearfix row-no-padding">
    			<div class="col-md-12">
    				<div class="press-member">
						<ul class="nav nav-tabs">
						   <li class="{{ Request::segment(2) == "dashboard" ? "active" : "" }}"><a href="/pressuser/dashboard">Dashboard</a></li>
						   <li class="{{ Request::segment(2) == "pressrelease" ? "active" : "" }}"><a href="/pressuser/pressrelease">Press Release</a></li><!-- Add link -->
						   <li class="{{ Request::segment(2) == "mypressrelease" ? "active" : "" }}"><a href="/pressuser/mypressrelease">My Press Releases</a></li><!-- add link -->
						   <li class="{{ Request::segment(2) == "analytics" ? "active" : "" }}"><a href="/pressuser/analytics">Analytics</a></li><!-- add link -->
						    <li class="{{ Request::segment(2) == "drafts" ? "active" : "" }}"><a href="/pressuser/drafts">Drafts</a></li><!-- add link -->
						</ul>
						<div class="tab-content">
							@yield("pressview")
						</div>
    				</div>
    			</div>
    		</div> --}}
    		<div id='cssmenu'>
	    		<ul>
	    		   <li class="{{ Request::segment(2) == "dashboard" ? "active" : "" }}"><a href="/pressuser/dashboard">Dashboard</a></li>
				   <li class="{{ Request::segment(2) == "pressrelease" ? "active" : "" }}"><a href="/pressuser/pressrelease">Press Release</a></li><!-- Add link -->
				   <li class="{{ Request::segment(2) == "mypressrelease" ? "active" : "" }}"><a href="/pressuser/mypressrelease">My Press Releases</a></li><!-- add link -->
				   <li class="{{ Request::segment(2) == "analytics" ? "active" : "" }}"><a href="/pressuser/analytics">Analytics</a></li><!-- add link -->
				   <li class="{{ Request::segment(2) == "media_contacts" ? "active" : "" }}"><a href="/pressuser/media_contacts">Media Contacts</a></li><!-- add link -->

				   <li class="{{ Request::segment(2) == "drafts" ? "active" : "" }}"><a href="/pressuser/drafts">Drafts</a></li><!-- add link -->
				</ul>
    		</div>
    		<div class="tab-content">
				@yield("pressview")
			</div>
		</div>
	</div>
</div>

@endsection

@section("css")
<link rel="stylesheet" type="text/css" href="/themes/{{ $shop_theme }}/css/member.css">
@endsection

@section("script")

<script type="text/javascript">
	( function( $ ) {
	$( document ).ready(function() {
	$('#cssmenu').prepend('<div id="bg-one"></div><div id="bg-two"></div><div id="bg-three"></div><div id="bg-four"></div>');
	});
	} )( jQuery );
</script>

@endsection