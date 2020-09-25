@extends("layout")
@section("content")
<div class="content">
	<!-- Media Slider -->
	<div class="slider-wrapper single-item">
		@if(loop_content_condition($shop_theme_info, "home", "home_slider"))
			@foreach(loop_content_get($shop_theme_info, "home", "home_slider") as $slider)
			<img src="{{ $slider }}">
			@endforeach
		@else
		<img src="/themes/{{ $shop_theme }}/img/slide-img-2.jpg">
		<img src="/themes/{{ $shop_theme }}/img/slide-img-1.png">
		<img src="/themes/{{ $shop_theme }}/img/slide-img-3.jpg">
		@endif		
	</div>
	<!-- INFORMATIVE -->
	<div class="top-1-container">
		<div class="container">
		<!-- INFO -->
			<div class="info-container row-no-padding clearfix">
				<div class="col-md-4 col-sm-4 col-xs-12">
					<div class="box1-container">
						<div class="title-container">
							<span class="info-icon"><img src="/themes/{{ $shop_theme }}/img/people-icon.png"></span>
							<span class="info-title">{{ get_content($shop_theme_info, "home", "home_top_left_title") }}</span>	
						</div>
						<div class="context-container">
							<p>
								{{ get_content($shop_theme_info, "home", "home_top_left_description") }}
							</p>
						</div>
						<a href="/history"><div class="small-button">Read More</div></a>
					</div>
				</div>
				<div class="col-md-4 col-sm-4 col-xs-12">
					<div class="box2-container">
						<div class="title-container">
							<span class="info-icon"><img src="/themes/{{ $shop_theme }}/img/handshake-icon.png"></span>
							<span class="info-title">Opportunity</span>	
						</div>
						<div class="context-container">							
								{!! get_content($shop_theme_info, "home", "home_top_middle_description") !!}
						</div>
						<a href="/how_to_join"><div class="small-button">Know More</div></a>
					</div>
				</div>
				<div class="col-md-4 col-sm-4 col-xs-12">
					<div class="box3-container">
						<div class="title-container">
							<span class="info-icon"><img src="/themes/{{ $shop_theme }}/img/buah-merah-icon.png"></span>
							<span class="info-title">Buah Merah</span>	
						</div>
						<div class="context-container">
							<p>
								{{ get_content($shop_theme_info, "home", "home_top_right_description") }}

							</p>
						</div>
						<a href="/about_red_fruit"><div class="small-button">Learn More</div></a>
					</div>
				</div>				
			</div>
		</div>
	</div>
	<!-- FEATURED PRODUCTS -->
	<div class="top-2-container">
		<div class="container">
			<div class="featured-prod-container">
				<div class="title-container">
					{{ get_content($shop_theme_info, "home", "home_division_3_title") }}
				</div>
				<div class="prod-container row clearfix">
					<div class="col-md-6 col-sm-6 col-xs-12 prod-holder">
						<div class="prod-cat">
							<div class="hover-detail-container">
								<h3>TAKE IT EVERYDAY</h3>
								<h4>FEEL IT ALL THE WAY</h4>
								<div class="medium-button blue-button" onClick="location.href='/product?type=212'">SHOP NOW</div>								
							</div>
							<div class="hover-bg"></div>
							<img src="{{ get_content($shop_theme_info, "home", "home_division4_img1") }}">
							<div class="bg-overlay">
								<img class="black-overlay" src="/themes/{{ $shop_theme }}/img/black-overlay.png">
							</div>
							<div class="detail-container">
								<span>{{ get_content($shop_theme_info, "home", "home_division_4_img1_Description") }}</span>
							</div>
							<!-- <div class="medium-button">Shop Now</div> -->
						</div>
						<div class="prod-cat">
							<div class="hover-detail-container">
								<h5>FEEL THE</h5>
								<h6 class="h-red">NATURE'S BEAUTY</h6>
								<div class="medium-button red-button" onClick="location.href='/product?type=211'">SHOP NOW</div>								
							</div>
							<div class="hover-bg"></div>
							<img src="{{ get_content($shop_theme_info, "home", "home_division_4_img3") }}">
							<div class="bg-overlay">
								<img class="black-overlay" src="/themes/{{ $shop_theme }}/img/black-overlay.png">
							</div>
							<div class="detail-container">
								<span>{{ get_content($shop_theme_info, "home", "home_division_4_img3_Description") }}</span>
							</div>
							<!-- <div class="medium-button">Shop Now</div> -->
						</div>
					</div>
					<div class="col-md-6 col-sm-6 col-xs-12 prod-holder">
						<div class="prod-cat">
							<div class="hover-detail-container">
								<h5>YOUR EVERYDAY</h5>
								<h6 class="h-green">HEALTHY DRINK</h6>
								<div class="medium-button green-button" onClick="location.href='/product?type=213'">SHOP NOW</div>								
							</div>
							<div class="hover-bg"></div>
							<img src="{{ get_content($shop_theme_info, "home", "home_division4_img2") }}">
							<div class="bg-overlay">
								<img class="black-overlay" src="/themes/{{ $shop_theme }}/img/black-overlay.png">
							</div>
							<div class="detail-container">
								<span>{{ get_content($shop_theme_info, "home", "home_division_4_img2_Description") }} </span>
							</div>
							<!-- <div class="medium-button">Shop Now</div> -->
						</div>
						<div class="row-no-padding clearfix">
							<div class="col-md-6 col-sm-6 col-xs-6">
								<div class="prod-cat">
									<div class="hover-detail-container hover-detail-container-sml">
										<h3 class="h-gray">BUSINESS</h3>
										<h4>PACKAGE</h4>
										<div class="medium-button blue-button gray-button-short" onClick="location.href='/product?type=216'">SHOP NOW</div>								
									</div>
									<div class="hover-bg"></div>
									<img src="{{ get_content($shop_theme_info, "home", "home_division_4_img4") }}">
									<div class="bg-overlay">
										<img class="black-overlay" src="/themes/{{ $shop_theme }}/img/black-overlay.png">
									</div>
									<div class="detail-container">
										<span>{{ get_content($shop_theme_info, "home", "home_division_4_img4_Description") }}</span>
									</div>
									<!-- <div class="medium-button button-short">Shop Now</div> -->
								</div>
							</div>
							<div class="col-md-6 col-sm-6 col-xs-6">
								<div class="prod-cat">
									<div class="hover-detail-container hover-detail-container-sml">
										<h3 class="h-gray">RETAIL</h3>
										<h4>PACKAGE</h4>
										<div class="medium-button green-button gray-button-short" onClick="location.href='/product?type=237'">SHOP NOW</div>								
									</div>
									<div class="hover-bg"></div>
									<img src="{{ get_content($shop_theme_info, "home", "home_division_4_img5") }}">
									<div class="bg-overlay">
										<img class="black-overlay" src="/themes/{{ $shop_theme }}/img/black-overlay.png">
									</div>
									<div class="detail-container">
										<span>{{ get_content($shop_theme_info, "home", "home_division_4_img5_Description") }}</span>
									</div>
									<!-- <div class="medium-button button-short">Shop Now</div> -->
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
	<!-- TRUSTED BRAND -->
	<div class="top-3-container">
		<div class="container">
			<div class="trusted-brand">
				<div class="title-container"></div>
				<div class="brand-container row clearfix">
					<div class="col-md-4 col-sm-4 col-xs-12 per-brand-holder">
						<div class="per-brand">
							<img src="/themes/{{ $shop_theme }}/img/shield-icon.png">
							<h1>{{ get_content($shop_theme_info, "home", "home_division_5_left_title") }}</h1>
							<h2>
								{{ get_content($shop_theme_info, "home", "home_division_5_left_description") }}
							</h2>
						</div>
					</div>
					<div class="col-md-4 col-sm-4 col-xs-12 per-brand-holder">
						<div class="per-brand">
							<img src="/themes/{{ $shop_theme }}/img/quality-icon.png">
							<h1>{{ get_content($shop_theme_info, "home", "home_division_5_middle_title") }} </h1>
							<h2>
								{{ get_content($shop_theme_info, "home", "home_division_5_middle_description") }}
							</h2>
						</div>
					</div>
					<div class="col-md-4 col-sm-4 col-xs-12 per-brand-holder">
						<div class="per-brand">
							<img src="/themes/{{ $shop_theme }}/img/cart-icon.png">
							<h1>{{ get_content($shop_theme_info, "home", "home_division_5_right_title") }}</h1>
							<h2>
								{{ get_content($shop_theme_info, "home", "home_division_5_right_description") }}
							</h2>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
	<!-- COMPANY IMAGES -->
	<div class="mid-container">
		<div class="container">
			<div class="gallery-container">
				<div class="title-container">{{ get_content($shop_theme_info, "home", "home_division_6_title") }}</div>
				<div class="image-container row-no-padding clearfix">
					<div class="col-md-4 col-sm-4 col-xs-4 col-padding-2">
						<div class="per-image-container">
							<a href="/themes/{{ $shop_theme }}/img/gallery-sample1.png" data-title="" data-lightbox="company-gallery">
								<img src="/themes/{{ $shop_theme }}/img/gallery-sample1.png" alt="">
							</a>
						</div>
					</div>
					<div class="col-md-4 col-sm-4 col-xs-4 col-padding-2">
						<div class="per-image-container">
							<a href="/themes/{{ $shop_theme }}/img/gallery-sample2.png" data-title="" data-lightbox="company-gallery">
								<img src="/themes/{{ $shop_theme }}/img/gallery-sample2.png" alt="">
							</a>
						</div>
					</div>
					<div class="col-md-4 col-sm-4 col-xs-4 col-padding-2">
						<div class="per-image-container">
							<a href="/themes/{{ $shop_theme }}/img/gallery-sample3.png" data-title="" data-lightbox="company-gallery">
								<img src="/themes/{{ $shop_theme }}/img/gallery-sample3.png" alt="">
							</a>
						</div>
					</div>
					<div class="col-md-4 col-sm-4 col-xs-4 col-padding-2">
						<div class="per-image-container">
							<a href="/themes/{{ $shop_theme }}/img/gallery-sample4.png" data-title="" data-lightbox="company-gallery">
								<img src="/themes/{{ $shop_theme }}/img/gallery-sample4.png" alt="">
							</a>
						</div>
					</div>
					<div class="col-md-4 col-sm-4 col-xs-4 col-padding-2">
						<div class="per-image-container">
							<a href="/themes/{{ $shop_theme }}/img/gallery-sample5.png" data-title="" data-lightbox="company-gallery">
								<img src="/themes/{{ $shop_theme }}/img/gallery-sample5.png" alt="">
							</a>
						</div>
					</div>
					<div class="col-md-4 col-sm-4 col-xs-4 col-padding-2">
						<div class="per-image-container">
							<a href="/themes/{{ $shop_theme }}/img/gallery-sample6.png" data-title="" data-lightbox="company-gallery">
								<img src="/themes/{{ $shop_theme }}/img/gallery-sample6.png" alt="">
							</a>
						</div>
					</div>
					<div class="col-md-4 col-sm-4 col-xs-4 col-padding-2">
						<div class="per-image-container">
							<a href="/themes/{{ $shop_theme }}/img/gallery-sample7.png" data-title="" data-lightbox="company-gallery">
								<img src="/themes/{{ $shop_theme }}/img/gallery-sample7.png" alt="">
							</a>
						</div>
					</div>
					<div class="col-md-4 col-sm-4 col-xs-4 col-padding-2">
						<div class="per-image-container">
							<a href="/themes/{{ $shop_theme }}/img/gallery-sample8.png" data-title="" data-lightbox="company-gallery">
								<img src="/themes/{{ $shop_theme }}/img/gallery-sample8.png" alt="">
							</a>
						</div>
					</div>
					<div class="col-md-4 col-sm-4 col-xs-4 col-padding-2">
						<div class="per-image-container">
							<a href="/themes/{{ $shop_theme }}/img/gallery-sample9.png" data-title="" data-lightbox="company-gallery">
								<img src="/themes/{{ $shop_theme }}/img/gallery-sample9.png" alt="">
							</a>
						</div>
					</div>
				</div>
				<div class="link-container"><a href="/gallery"><span>View All From The Gallery</span></a></div>
			</div>
		</div>
	</div>
	<!-- EVENTS -->
	<div class="mid-container-2">
		<div class="container">
			<div class="title-container">Events</div>
			<div class="events-container row-no-padding clearfix">
				<div class="col-md-8 col-sm-8 col-xs-12 events-holder">
					<div class="per-event-container">
						@if(count(get_front_news($shop_id)) > 0)
							@foreach(limit_foreach(get_front_news($shop_id), 3) as $news)
									<div class="per-event desk row clearfix">
										<div class="col-md-4 col-sm-4 col-xs-4">
											<div class="event-image-container">
												<a href="/news?id={{ $news->post_id }}">
													<img src="{{ $news->post_image }}">
												</a>
											</div>
										</div>
										<div class="col-md-8 col-sm-8 col-xs-8">
											<div class="event-details-container">
												<h1>
													<a href="/news?id={{ $news->post_id }}">
														{{ $news->post_title }}	
													</a>
												</h1>
												<h2>
													{{ $news->post_excerpt }}
												</h2>
											</div>
										</div>
									</div>
									<!-- MOBILE -->
									<div class="per-event mob row clearfix">
										<div class="col-md-6 col-sm-6 col-xs-6">
											<div class="event-image-container">
												<a href="/news?id={{ $news->post_id }}">
													<img src="{{ $news->post_image }}">
												</a>
											</div>
										</div>
										<div class="col-md-6 col-sm-6 col-xs-6">
											<div class="event-details-container">
												<h1>
													<a href="/news?id={{ $news->post_id }}">
														{{ $news->post_title }}	
													</a>
												</h1>
												<h2>
													{{ $news->post_excerpt }}
												</h2>
											</div>
										</div>
									</div>
							@endforeach
						@else
							<span>Coming Soon!</span>
						@endif
					</div>
					<div class="link-container">
						<a href="/events"><span>View All Events</span></a>
					</div>
				</div>
				<!-- FACEBOOK FANPAGE -->
				<div class="col-md-4 col-sm-4 col-xs-12 fb-fanpage-holder">
					<div class="fb-container">
						<div class="container-title-header">Facebook Fanpage</div>
						<div class="body-container">
							<div class="fb-page" data-href="https://www.facebook.com/3xcell.ph/" data-tabs="timeline" data-small-header="false" data-adapt-container-width="true" data-hide-cover="false" data-show-facepile="true"><blockquote cite="https://www.facebook.com/3xcell.ph/" class="fb-xfbml-parse-ignore"><a href="https://www.facebook.com/3xcell.ph/">3xcell-E Sales &amp; Marketing Inc.</a></blockquote></div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
	<!-- SUBSCRIBE -->	
	<!-- <div class="fullscreen background parallax" style="background-image: url('/themes/{{ $shop_theme }}/img/subscribe-bg3.png');" data-img-width="1366" data-img-height="400" data-diff="100">
		<div class="container">
			<div class="bot-container-1">
				<h1>{{ get_content($shop_theme_info, "home", "home_division_7_banner") }}</h1>
				<div class="input-container">
					<input class="subscribe-input" type="text" name="" placeholder="Email">
					<div class="subscribe-button">Subscribe</div>
				</div>
			</div>
		</div>
	</div> -->
	<!-- LIVE HEALTHY BE WEALTHY -->
	
	<div class="bot-container-2">
		<div class="container">
			<div class="content-container row clearfix">
				<div class="col-md-6 col-sm-6">
					<div class="health-image-container">
						<img class="healthy-people" src="/themes/{{ $shop_theme }}/img/healthy-people.png">
					</div>
				</div>
				<div class="col-md-6 col-sm-6">
					<div class="caption-container">
						<h1>{{ get_content($shop_theme_info, "home", "home_division_7_title") }}</h1>
						<p>
							{{ get_content($shop_theme_info, "home", "home_division_7_description") }}
						</p>
					</div>
					@if(!$mlm_member)
					<a href="/members/register" style="text-decoration: none;">
						<div class="join-button">
							<img src="/themes/{{ $shop_theme }}/img/join-icon.png"><span>JOIN US TODAY</span>
						</div>
					</a>
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
<link rel="stylesheet" type="text/css" href="/themes/{{ $shop_theme }}/css/home.css?version=2">

@endsection

@section("js")

<script type="text/javascript">

	$(document).ready(function()
	{
		$('.single-item').slick({
			prevArrow:"<img class='a-left control-c prev slick-prev' src='/themes/{{ $shop_theme }}/img/arrow-left.png'>",
	      	nextArrow:"<img class='a-right control-c next slick-next' src='/themes/{{ $shop_theme }}/img/arrow-right.png'>",
	      	dots: false,
	      	autoplay: true,
	  		autoplaySpeed: 3000,
		});

	    lightbox.option({
	      'disableScrolling': true,
	      'wrapAround': true
	    });

	});
</script>
@endsection