@extends("layout")
@section("content")
<!-- OUR VALUES -->
<div class="content">
	<div class="container-fluid">
		<!-- TOP BANNER -->
		<div class="top-bg-container">
			<img src="/themes/{{ $shop_theme }}/img/job-bg.png">
			<div class="top-bg-detail-container">
				<img src="/themes/{{ $shop_theme }}/img/jobs-logo.png">
			</div>
		</div>
		<!-- CONTENT -->
		<div class="row clearfix">
			<!-- LEFT COLUMN -->
			<div class="col-md-8">
				<div class="left-top-container">
					<div class="top-title">{{ $job["job_title"] }}</div>
					<div class="row clearfix">
						<div class="col-md-6">
							<div class="job-details-container">
								<p>
									{{ $job["job_caption_post"] }}
								</p>
								<div class="min-title">
									Job Description
								</div>
								<p>
									{!! $job["job_description"] !!}
								</p>
							</div>
						</div>
						<div class="col-md-6">
							<div class="job-image-container">
								<img src="{{ $job["job_image_(433x327)"] }}">
							</div>
						</div>
					</div>
				</div>
				<div class="row-no-padding clearfix" style="background-color: #fff;">
					<div class="col-md-6">
						<div class="left-top-container container-min">
							<div class="min-title">
								Application Details
							</div>
							<p>
								{!! $job["application_details"] !!}
							</p>
						</div>
					</div>
					<div class="col-md-6">
						<div class="left-top-container container-min">
							<div class="min-title">
								Company Compensation
							</div>
							<p>
								{!! $job["company_compensation"] !!}
							</p>
						</div>
					</div>
				</div>
			</div>
			<!-- RIGHT COLUMN -->
			<div class="col-md-4">
				<div class="left-top-container">
					<!-- PER ICON -->
					<div class="right-col-content row clearfix">
						<div class="col-md-2">
							<div class="icon-holder">
								<img src="/themes/{{ $shop_theme }}/img/icon-bg.png">
								<i class="fa fa-money" aria-hidden="true"></i>
							</div>
						</div>
						<div class="col-md-10">
							<div class="icon-caption">
								<span style="color: #004b6e; font-weight: bold;">Salary Range:&nbsp;</span><span><span>PHP&nbsp;</span>{{ number_format($job["job_salary_range_from"], 2) }}</span>&nbsp;-&nbsp;<span>PHP&nbsp;</span><span>{{ number_format($job["job_salary_range_to"], 2) }}</span>
							</div>
						</div>
					</div>
					<div class="right-col-content row clearfix">
						<div class="col-md-2">
							<div class="icon-holder">
								<img src="/themes/{{ $shop_theme }}/img/icon-bg.png">
								<i class="fa fa-briefcase" aria-hidden="true"></i>
							</div>
						</div>
						<div class="col-md-10">
							<div class="icon-caption">
								<span style="color: #004b6e; font-weight: bold;">Work Experience:&nbsp;</span><span>{{ $job["job_experience_from"] }}</span><span>&nbsp;to&nbsp;</span><span>{{ $job["job_experience_to"] }}</span>&nbsp;<span>years of experience</span>
							</div>
						</div>
					</div>
					<div class="right-col-content row clearfix">
						<div class="col-md-2">
							<div class="icon-holder">
								<img src="/themes/{{ $shop_theme }}/img/icon-bg.png">
								<i class="fa fa-map-marker" aria-hidden="true"></i>
							</div>
						</div>
						<div class="col-md-10">
							<div class="icon-caption">
								<span style="color: #004b6e; font-weight: bold;">Work Location:&nbsp;</span><span>{{ $job["job_location"] }}</span>
							</div>
						</div>
					</div>	
				</div>
				<!-- APPLY BUTTON -->
				<div class="left-top-container apply-container row-no-padding clearfix">
					<div class="col-md-6">
						<span>This Job suits me.</span>
					</div>
					<div class="col-md-6">
						<a href="#popup1"><div class="apply-button">APPLY NOW</div></a>
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
<link rel="stylesheet" type="text/css" href="/themes/{{ $shop_theme }}/css/job.css">
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
