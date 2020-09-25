@extends("layout")
@section("content")
<div class="content">
	<div class="top-1-container">
		<div class="container">
			<div class="title-container">
				Promos
				<div class="line-bot"></div>
			</div>
			<div class="promo-container row clearfix">
				<!-- PER PROMO -->
				@if(is_serialized(get_content($shop_theme_info, "promo", "promo_maintenance")))
					@foreach(unserialize(get_content($shop_theme_info, "promo", "promo_maintenance")) as $key => $promo)
					<a href="/promo_view?id={{ $key }}">
						<div class="col-md-3">
							<div class="per-promo-container">
								<div class="promo-img-container">
									<img src="{{ $promo["image"] }}">
								</div>
								<div class="promo-title text-overflow">{{ $promo["title"] }}</div>
								<div class="promo-date">{{ date("F d, Y", strtotime($promo["posted"])) }}</div>
								<div class="promo-button">Learn More</div>
							</div>
						</div>
					</a>
					@endforeach
				@endif
				{{-- <a href="/promo_view">
					<div class="col-md-3">
						<div class="per-promo-container">
							<div class="promo-img-container">
								<img src="/themes/{{ $shop_theme }}/img/promo-img.png">
							</div>
							<div class="promo-title text-overflow">Race Up Promo Race Up Promo Race Up Promo Race Up Promo</div>
							<div class="promo-date">June 14, 2017</div>
							<div class="promo-button">Learn More</div>
						</div>
					</div>
				</a>
				<a href="/promo_view">
					<div class="col-md-3">
						<div class="per-promo-container">
							<div class="promo-img-container">
								<img src="/themes/{{ $shop_theme }}/img/promo-img.png">
							</div>
							<div class="promo-title text-overflow">Race Up Promo Race Up Promo Race Up Promo Race Up Promo</div>
							<div class="promo-date">June 14, 2017</div>
							<div class="promo-button">Learn More</div>
						</div>
					</div>
				</a>
				<a href="/promo_view">
					<div class="col-md-3">
						<div class="per-promo-container">
							<div class="promo-img-container">
								<img src="/themes/{{ $shop_theme }}/img/promo-img.png">
							</div>
							<div class="promo-title text-overflow">Race Up Promo Race Up Promo Race Up Promo Race Up Promo</div>
							<div class="promo-date">June 14, 2017</div>
							<div class="promo-button">Learn More</div>
						</div>
					</div>
				</a>
				<a href="/promo_view">
					<div class="col-md-3">
						<div class="per-promo-container">
							<div class="promo-img-container">
								<img src="/themes/{{ $shop_theme }}/img/promo-img.png">
							</div>
							<div class="promo-title text-overflow">Race Up Promo Race Up Promo Race Up Promo Race Up Promo</div>
							<div class="promo-date">June 14, 2017</div>
							<div class="promo-button">Learn More</div>
						</div>
					</div>
				</a> --}}
			</div>
		</div>
	</div>
	<!-- SCROLL TO TOP -->
	<div class="scroll-up"><img src="/themes/{{ $shop_theme }}/img/scroll-up.png"></div>
</div>
@endsection

@section("css")
<link rel="stylesheet" type="text/css" href="/themes/{{ $shop_theme }}/css/promos.css">
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