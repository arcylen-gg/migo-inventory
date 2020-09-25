@extends("layout")
@section("content")
<div class="content">
	<div class="top-1-container" style="background-image: url('/themes/{{ $shop_theme }}/img/how-to-join-bg.png')">
		<div class="container">
			<div class="top-1-content">
				<h1>{{ get_content($shop_theme_info, "How-To-Join", "howtojoin_banner_title") }}</h1>
				<h2><span>{{ get_content($shop_theme_info, "How-To-Join", "howtojoin_banner_description") }}</span></h2>
			</div>
		</div>
	</div>
	<!-- CONTENT -->
		<div class="mid-content">
			<div class="container">
				<!-- PER DETAIL -->
				<div class="detail-container row clearfix">
					<div class="col-md-6 col-sm-6 col-xs-6 desc-holder">
						<div class="description-container">
							<div class="title-container">{{ get_content($shop_theme_info, "How-To-Join", "howtojoin_division1_title") }}
								<div class="line-bot"></div>
							</div>
							<div class="description-content">
								<p>
									{!! get_content($shop_theme_info, "How-To-Join", "howtojoin_division1_description") !!}
								</p>
							</div>
						</div>
					</div>
					<div class="col-md-6 col-sm-6 col-xs-6 image-in-join-holder">
						<div class="img-container">
							<img src="{{ get_content($shop_theme_info, "How-To-Join", "howtojoin_division1_img") }}">
						</div>
					</div>
				</div>
				<div class="detail-container row clearfix">
					<div class="col-md-6 col-sm-6 col-xs-6 desc-holder">
						<div class="description-container">
							<div class="title-container">{{ get_content($shop_theme_info, "How-To-Join", "howtojoin_division2_title") }}
								<div class="line-bot-2"></div>
							</div>
							<div class="description-content">
								<p>
									{!! get_content($shop_theme_info, "How-To-Join", "howtojoin_division2_description") !!}

								</p>
							</div>
						</div>
					</div>
					<div class="col-md-6 col-sm-6 col-xs-6 image-in-join-holder">
						<div class="img-container">
							<img src="{{ get_content($shop_theme_info, "How-To-Join", "howtojoin_division2_img") }}">
						</div>
					</div>
				</div>
				<div class="detail-container row clearfix">
					<div class="col-md-6 col-sm-6 col-xs-6 desc-holder">
						<div class="description-container">
							<div class="title-container">{{ get_content($shop_theme_info, "How-To-Join", "howtojoin_division3_title") }}
								<div class="line-bot-3"></div>
							</div>
							<div class="description-content">
								<p>									
									{!! get_content($shop_theme_info, "How-To-Join", "howtojoin_division3_description") !!}
								</p>
							</div>
						</div>
					</div>
					<div class="col-md-6 col-sm-6 col-xs-6 image-in-join-holder">
						<div class="img-container">
				
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
<link rel="stylesheet" type="text/css" href="/themes/{{ $shop_theme }}/css/how_to_join.css">
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

    /*SCROLL TEXT FADE OUT*/
	    $(window).scroll(function(){
	    	$(".top-1-content").css("opacity", 1 - $(window).scrollTop() / 250);
	  	});
});
</script>
@endsection