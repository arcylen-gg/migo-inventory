@extends("layout")
@section("content")
<div class="content">
	<div class="container">
		<div class="top-1-container row clearfix">
			<div class="col-md-8">
				<div class="promo-view-container">
					<div class="promo-img-container">
						<img src="{{ unserialize(get_content($shop_theme_info, "promo", "promo_maintenance"))[Request::input("id")]["image"] }}">
					</div>
					<div class="promo-details-container">
						<div class="promo-title">{{ unserialize(get_content($shop_theme_info, "promo", "promo_maintenance"))[Request::input("id")]["title"] }}</div>
						<div class="promo-date">{{ date("F d, Y", strtotime(unserialize(get_content($shop_theme_info, "promo", "promo_maintenance"))[Request::input("id")]["posted"])) }}</div>
						<div class="promo-description">
							<p>{!! unserialize(get_content($shop_theme_info, "promo", "promo_maintenance"))[Request::input("id")]["description"] !!}</p>
						</div>
					</div>
				</div>
			</div>
			<div class="col-md-4"></div>
		</div>
	</div>
	<!-- SCROLL TO TOP -->
	<div class="scroll-up"><img src="/themes/{{ $shop_theme }}/img/scroll-up.png"></div>
</div>
@endsection

@section("css")
<link rel="stylesheet" type="text/css" href="/themes/{{ $shop_theme }}/css/promo_view.css">
<style type="text/css">
.promo-description img
{
	max-width: 100% !important;
	max-height: 100% !important;
}
</style>
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