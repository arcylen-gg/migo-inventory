@extends("layout")
@section("content")
<!-- OUR VALUES -->
<div class="content">
	<div class="container-fluid">
		<!-- TOP BANNER -->
		<div class="top-bg-container">
			<img src="{{ get_content($shop_theme_info, "jobs", "jobs_banner_img") }}">
			<div class="top-bg-detail-container">
				<img src="/themes/{{ $shop_theme }}/img/jobs-logo.png">
			</div>
		</div>
		<!-- CONTENT -->
		<div class="row clearfix content">
			<!-- LEFT COLUMN -->
			<div class="col-md-8">
				<div class="content-title">
					{{ get_content($shop_theme_info, "jobs", "jobs_head_text") }}
				</div>
				<div class="title-caption">
					<p>
						{{ get_content($shop_theme_info, "jobs", "jobs_head_context") }}
					</p>
				</div>
				<div class="list-title-container">
					<img src="/themes/{{ $shop_theme }}/img/list-title-bg.png">
					<div class="list-title">Job List</div>
				</div>
				<!-- LIST CONTAINER -->
				<div class="list-container-holder">
					<div class="list-container">
						@if( loop_content_condition( $shop_theme_info, "job", "job_maintenance" ) )
							@foreach( unserialize( get_content($shop_theme_info, "job", "job_maintenance") ) as $key => $jobs )
							<div class="per-list">
								<div class="per-list-title"><a href="/job?id={{ $key }}">{{ $jobs["job_title"] }}</a></div>
								<div class="per-list-description">
									{{ $jobs["job_caption_post"] }}
								</div>
								<div class="per-list-details">Date Posted: {{ date("F d, Y", strtotime($jobs["job_date_posted"])) }}</div>
							</div>
							@endforeach
						@else
							<div class="default-notif-container">
								<img src="/themes/{{ $shop_theme }}/img/miniature2.png">
								<div class="span-container">
									<span style="font-size: 18px; font-weight: bold;">There are</span><br>
									<span style="font-size: 32px; font-weight: bold;">No Vacancies</span><br>
									<span style="font-size: 18px; font-weight: bold;">at this time.</span>
								</div>
							</div>
						@endif
						
					</div>
				</div>
			</div>
			<!-- RIGHT COLUMN -->
			<div class="col-md-4">
				<!-- ATTACHED RESUME -->
				<div class="right-per-container">
					<div class="title-container">
						<div class="title">{{ get_content($shop_theme_info, "jobs", "jobs_right_tab_1_head_text") }}</div>
						<div class="details">{{ get_content($shop_theme_info, "jobs", "jobs_right_tab_1_subhead_text") }}</div>
					</div>
					<div class="img-container">
						<img src="{{ get_content($shop_theme_info, "jobs", "jobs_right_tab_1_image") }}">
						<a href="#popup1"><div class="submit-button">SUBMIT RESUME</div></a>
					</div>
				</div>
				<!-- COMPANY PROFILE -->
				<div class="right-per-container">
					<div class="right-tab">
						<div class="title">{{ get_content($shop_theme_info, "jobs", "jobs_right_tab_2_head_text") }}</div>
						<div class="details">{{ get_content($shop_theme_info, "jobs", "jobs_right_tab_2_subhead_text") }}</div>
					</div>
					<div class="right-tab-content">
						<p>
							{{ get_content($shop_theme_info, "jobs", "jobs_right_tab_2_subhead_context") }}
						</p>
					</div>
				</div>
				<!-- MAP -->
				<div class="right-per-container">
					<div class="title-container">
						<div class="title">Head Office Location</div>
						<div class="details">Walk-in applicatns are welcomed</div>
					</div>
					<div class="img-container">
						<img src="/themes/{{ $shop_theme }}/img/head-office.png">
						<a href="https://www.google.com.ph/maps/place/FCF+Minerals+Corporation+%2F+Metal+Exploration+Public+Limied+Company/@14.5615752,121.0156273,17z/data=!3m1!4b1!4m5!3m4!1s0x3397c909a1ec69eb:0x25b39d0024fa6ae!8m2!3d14.56157!4d121.017816" target="_blank"><div class="location-map-button">LOCATION MAP</div></a>
					</div>
				</div>
			</div>
		</div>
	</div>
	<!-- SCROLL TO TOP -->
	<div class="scroll-up"><img src="/themes/{{ $shop_theme }}/img/scroll-up.png"></div>
</div>
@endsection

@section("css")
<link rel="stylesheet" type="text/css" href="/themes/{{ $shop_theme }}/css/jobs.css">
@endsection

@section("js")
<script type="text/javascript">

$(document).ready(function()
{
	/*scroll up*/
	$(window).scroll(function () {
        if ($(this).scrollTop() > 600) {
            $('.scroll-up').fadeIn();
        } else {
            $('.scroll-up').fadeOut();
        }
    });

    $('.scroll-up').click(function () {
        $("html, body").animate({
            scrollTop: 0
        }, 600);
        return false;
    });

    $(".per-img-container").click(function()
	{
		var source = $(this).find("img").attr("src");
		$(".lightbox-target").find("img").attr("src", source);
	})


});	

</script>
@endsection
