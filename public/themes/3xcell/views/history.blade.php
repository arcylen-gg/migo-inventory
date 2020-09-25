@extends("layout")
@section("content")
<div class="content">
	<div class="top-1-container">
		<div class="container">
			<div class="title-container">{{ get_content($shop_theme_info, "history", "history_banner_title") }}</div>
		</div>
	</div>
	<div class="top-2-container">
		<div class="container">
			<div class="top-2-content-container row clearfix">
				<div class="col-md-8 col-sm-8 col-xs-8 about-holder">
					<div class="title-container">{{ get_content($shop_theme_info, "history", "history_content_title") }}</div>
					<div class="top-2-content">
						<p>
							{!!get_content($shop_theme_info, "history", "history_content_description") !!}
						</p>
					</div>
				</div>
				<div class="col-md-4 col-sm-4 col-xs-4 image-holder">
					<div class="img-container">
						<img src="{{ get_content($shop_theme_info, "history", "history_content_img") }}">
					</div>
				</div>
			</div>
		</div>
	</div>
	<!-- VISION MISION -->
	<div class="mid-container" style="background-image: url('/themes/{{ $shop_theme }}/img/vision-mission-img.png')">
		<div class="container">
			<div class="mid-content row clearfix">
				<div class="col-md-6 col-sm-6 col-xs-6 vision-holder">
					<div class="mid-content-single">
						<div class="title-container row clearfix">
							<div class="col-sm-4 col-xs-4"><i class="fa fa-binoculars" aria-hidden="true" style="color: white; font-size: 35px;"></i></div>
							<div class="col-sm-8 col-xs-8">
								<h1>VISION</h1>
								<h2>Major Views</h2>
							</div>
						</div>
						<div class="detail-container">
							{{ get_content($shop_theme_info, "history", "history_vision_description") }}
						</div>			
					</div>
				</div>
				<div class="col-md-6 col-sm-6 col-xs-6 mission-holder">
					<div class="mid-content-single">
						<div class="title-container row clearfix">
							<div class="col-sm-4 col-xs-4"><i class="fa fa-rocket" aria-hidden="true" style="color: white; font-size: 35px;"></i></div>
							<div class="col-sm-8 col-xs-8">
								<h1>MISSION</h1>
								<h2>Our Aims</h2>
							</div>
						</div>
						<div class="detail-container">
							{{ get_content($shop_theme_info, "history", "history_mission_description") }}
						</div>			
					</div>
				</div>
			</div>
		</div>
	</div>
	<!-- 3XCELL PERMITS -->
	<div class="bot-container">
		<div class="container">
			<div class="title-container">
				3XCELL Permits
				<div class="line-bot"></div>
			</div>
			<div class="bot-content row clearfix">
				@if(loop_content_condition($shop_theme_info, "history", "history_permit"))
					@foreach(loop_content_get($shop_theme_info, "history", "history_permit") as $permit)
						<div class="col-md-2 col-sm-2 col-xs-6">
							<div class="img-container">
								<a href="{{ $permit }}" data-title="" data-lightbox="company-gallery">
									<img src="{{ $permit }}">
								</a>
							</div>
						</div>
					@endforeach
				@else
					<div class="col-md-2 col-sm-2 col-xs-2 permit-holder">
						<div class="img-container">
							<a href="/themes/{{ $shop_theme }}/img/permit1.png" data-title="" data-lightbox="company-gallery">
								<img src="/themes/{{ $shop_theme }}/img/permit1.png">
							</a>
						</div>
					</div>
					<div class="col-md-2 col-sm-2 col-xs-2 permit-holder">
						<div class="img-container">
							<a href="/themes/{{ $shop_theme }}/img/permit2.png" data-title="" data-lightbox="company-gallery">
								<img src="/themes/{{ $shop_theme }}/img/permit2.png">
							</a>
						</div>
					</div>
					<div class="col-md-2 col-sm-2 col-xs-2 permit-holder">
						<div class="img-container">
							<a href="/themes/{{ $shop_theme }}/img/permit3.png" data-title="" data-lightbox="company-gallery">
								<img src="/themes/{{ $shop_theme }}/img/permit3.png">
							</a>
						</div>
					</div>
					<div class="col-md-2 col-sm-2 col-xs-2 permit-holder">
						<div class="img-container">
							<a href="/themes/{{ $shop_theme }}/img/permit4.png" data-title="" data-lightbox="company-gallery">
								<img src="/themes/{{ $shop_theme }}/img/permit4.png">
							</a>
						</div>
					</div>
					<div class="col-md-2 col-sm-2 col-xs-2 permit-holder">
						<div class="img-container">
							<a href="/themes/{{ $shop_theme }}/img/permit5.png" data-title="" data-lightbox="company-gallery">
								<img src="/themes/{{ $shop_theme }}/img/permit5.png">
							</a>
						</div>
					</div>
				@endif
			</div>
		</div>
	</div>

	<!-- SCROLL TO TOP -->
	<div class="scroll-up"><img src="/themes/{{ $shop_theme }}/img/scroll-up.png"></div>
</div>
@endsection

@section("css")
<link rel="stylesheet" type="text/css" href="/themes/{{ $shop_theme }}/css/history.css">
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

    /*LIGHTBOX*/
    lightbox.option({
      'disableScrolling': true,
      'wrapAround': true
    });
});
</script>
@endsection