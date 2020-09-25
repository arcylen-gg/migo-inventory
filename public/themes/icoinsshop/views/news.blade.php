@extends("layout")
@section("content")
<!-- NEWS AND ANNOUNCEMENT -->
<div class="content">
	<div class="main-container">
		<div class="container">
			<div class="row clearfix">
				<!-- NEWS CONTENT -->
				<div class="col-md-8">
					<div class="news-container">
						<div class="image-holder">
							<img src="{{ $main_news->post_image }}">
						</div>
						<div class="title-container">
							<div class="title">{{ $main_news->post_title }}</div>
							<span class="by">{{ $main_news->post_excerpt }}</span>
						</div>
						<div class="details-container">
							<p>{!! $main_news->post_content !!}</p>
						</div>
					</div>
				</div>
				<!-- OTHER NEWS -->
				<div class="col-md-4">
					<div class="other-news-container">
						<div class="header">Other News</div>
						<div class="other-news-container">
						@if(count(get_front_news($shop_id)) > 0)
							@foreach(limit_foreach(get_front_news($shop_id), 4) as $news)
								<a class="{{ $news->post_id == $main_news->post_id ? 'hide' : '' }}" href="/news?id={{ $news->post_id }}">
									<div class="other-news-per-container row-no-padding clearfix">
										<div class="col-md-4">
											<div class="news-img"><img src="{{ $news->post_image }}"></div>
										</div>
										<div class="col-md-8">
											<div class="news-details">
												<div class="news-title">{{ $news->post_title }}</div>
												{{-- <div class="news-content">Lorem ipsum dolor sit amet, consectetur adipisicing elit. Praesentium explicabo ab natus optio fuga officiis unde ullam quae, provident adipisci nulla quasi dolorem.</div> --}}
												{{-- <div class="read-more">READ MORE</div> --}}
											</div>
										</div>							
									</div>
									{{-- <div class="other-news-per-container row-no-padding clearfix">
										<div class="col-md-4">
											<div class="news-img"><img src="/themes/{{ $shop_theme }}/img/news-img-1.jpg"></div>
										</div>
										<div class="col-md-8">
											<div class="news-details">
												<div class="news-title">Bitcoin</div>
												<div class="news-content">Lorem ipsum dolor sit amet, consectetur adipisicing elit. Praesentium explicabo ab natus optio fuga officiis unde ullam quae, provident adipisci nulla quasi dolorem.</div>
												<div class="read-more">READ MORE</div>
											</div>
										</div>							
									</div>
									<div class="other-news-per-container row-no-padding clearfix">
										<div class="col-md-4">
											<div class="news-img"><img src="/themes/{{ $shop_theme }}/img/news-img-2.jpg"></div>
										</div>
										<div class="col-md-8">
											<div class="news-details">
												<div class="news-title">The Rise of Ico</div>
												<div class="news-content">Lorem ipsum dolor sit amet, consectetur adipisicing elit. Praesentium explicabo ab natus optio fuga officiis unde ullam quae, provident adipisci nulla quasi dolorem.</div>
												<div class="read-more">READ MORE</div>
											</div>
										</div>							
									</div>
									<div class="other-news-per-container row-no-padding clearfix">
										<div class="col-md-4">
											<div class="news-img"><img src="/themes/{{ $shop_theme }}/img/announcement-3.jpg"></div>
										</div>
										<div class="col-md-8">
											<div class="news-details">
												<div class="news-title">Investing in Bitcoin Throughout 2017 – is it too Late?</div>
												<div class="news-content">Lorem ipsum dolor sit amet, consectetur adipisicing elit. Praesentium explicabo ab natus optio fuga officiis unde ullam quae, provident adipisci nulla quasi dolorem.</div>
												<div class="read-more">READ MORE</div>
											</div>
										</div>							
									</div> --}}
								</a>
							@endforeach
						@else
							<span>Coming Soon!</span>
						@endif
						</div>
					</div>
				</div>
			</div>
		</div>
		<!-- SCROLL TO TOP -->
		<div class="scroll-up"><img src="/themes/{{ $shop_theme }}/img/scroll-up.png"></div>
	</div>
</div>
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
});	

</script>

@endsection

@section("css")
<link rel="stylesheet" type="text/css" href="/themes/{{ $shop_theme }}/css/announcement.css">
@endsection

