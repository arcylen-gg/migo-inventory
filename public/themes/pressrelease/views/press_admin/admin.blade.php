@extends("layout")
@section("content")
<div class="content">
<div class="background-container" style="background-image: url('/themes/{{ $shop_theme }}/img/home-banner.jpg')">
	<div class="container">
		<!-- <div class="row clearfix row-no-padding">
			<div class="col-md-12">
				<div class="press-member">
					<ul class="nav nav-tabs">
					   <li class="{{ Request::segment(2) == "dashboard" ? "active" : "" }}"><a href="/pressadmin/dashboard">Dashboard</a></li>
					   <li class="{{ Request::segment(2) == "mediacontacts" ? "active" : "" }}"><a href="/pressadmin/mediacontacts">Media Contacts</a></li>Add link
					   <li class="{{ Request::segment(2) == "pressreleases" ? "active" : "" }}"><a href="/pressadmin/pressreleases">Press Releases</a></li>
					   <li class="{{ Request::segment(2) == "email" ? "active" : "" }}"><a href="/pressadmin/email">Email</a></li>
					</ul>

					<div class="tab-content">
						@yield("pressview")
					</div>
				</div>
			</div>
		</div>  -->
		<div id='cssmenu'>
    		<ul>
    		   <li class="{{ Request::segment(2) == "dashboard" ? "active" : "" }}"><a href="/pressadmin/dashboard">Dashboard</a></li>
			   <li class="{{ Request::segment(2) == "mediacontacts" ? "active" : "" }}"><a href="/pressadmin/mediacontacts">Media Contacts</a></li>
			   <li class="{{ Request::segment(2) == "manage_user" ? "active" : "" }}"><a href="/pressadmin/manage_user">Manage User</a></li>
			   <li class="{{ Request::segment(2) == "email" ? "active" : "" }}"><a href="/pressadmin/email">Email</a></li>
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
<link rel="stylesheet" type="text/css" href="/themes/{{ $shop_theme }}/css/admin.css">
@endsection

@section("script")

<script type="text/javascript">

</script>

@endsection