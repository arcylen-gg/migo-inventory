@extends("layout")
@section("content")
<div class="content">
	<div class="container">
		<!-- EVENT VIEW -->
		<div class="top-1-container row clearfix">
			<div class="col-md-8">
				<div class="events-view-container">
					<div class="event-image-container">
						<img src="{{ $main_news->post_image }}">
					</div>
					<div class="event-details-container">
						<h1>
							{{ $main_news->post_title }}	
						</h1>
						<h2>
							{{ $main_news->post_excerpt }}
						</h2>
						<p>
							{!! $main_news->post_content !!}
						</p>
					</div>	
				</div>
			</div>
			<!-- OTHER EVENTS -->
			<div class="col-md-4">
				<div class="title-container">Other Events</div>
				<div class="per-event-container">
				@if(count(get_front_news($shop_id)) > 0)
					@foreach(limit_foreach(get_front_news($shop_id), 4) as $news)
						<a class="{{ $news->post_id == $main_news->post_id ? 'hide' : '' }}" href="/news?id={{ $news->post_id }}">
							<div class="per-event row-no-padding clearfix">
								<div class="col-md-4">
									<div class="event-image-container">
										<img src="{{ $news->post_image }}">
									</div>
								</div>
								<div class="col-md-8">
									<div class="event-details-container">
										<h1>
											{{ $news->post_title }}	
										</h1>
										<h2>
											{{ $news->post_excerpt }}
										</h2>
									</div>
								</div>
							</div>
						</a>
					@endforeach
				@else
					<span>Coming Soon!</span>
				@endif
				</div>
			</div>
		</div>
	</div>
	<!-- SCROLL TO TOP -->
	<div class="scroll-up"><img src="/themes/{{ $shop_theme }}/img/scroll-up.png"></div>
</div>
@endsection

@section("css")
<link rel="stylesheet" type="text/css" href="/themes/{{ $shop_theme }}/css/news.css">
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