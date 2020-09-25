@extends("layout")
@section("content")
<div class="content">
	<!-- CONTENT -->
	<div class="mid-content">
		<div class="container">
			<!-- PER DETAIL -->
			<div class="detail-container row clearfix">
				<div class="col-md-6">
					<div class="description-container">
						<div class="title-container">About Red Fruit
						</div>
						<div class="description-content">
							{!! get_content($shop_theme_info, "about-red-fruit", "about_red_fruit") !!}
						</div>
					</div>
				</div>
				<div class="col-md-6">
					<div class="img-container">
						<img src="{{ get_content($shop_theme_info, "about-red-fruit", "about_red_fruit_image") }}">
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
<link rel="stylesheet" type="text/css" href="/themes/{{ $shop_theme }}/css/about_red_fruit.css">
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