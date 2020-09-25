@extends("layout")
@section("content")
<!-- <div class="bts-popup" role="alert">
    <div class="bts-popup-container">
		
		<div class="button-container">
			<div class="link-btn"><a href="">ENROLL NOW</a></div>
		</div>
    </div>
</div> -->
<div class="content">
	<div class="wrapper-1">
		<div class="fullscreen background parallax" style="background-image: url('/themes/{{ $shop_theme }}/img/brown-banner.jpg');" data-img-width="1600" data-img-height="907" data-diff="100">
			<div class="container">
				<div class="row clearfix">
					<div class="col-md-8 left-container">
						<div class="head-caption" style="display: none;">www.brown.com.ph</div>
						<!-- <video autoplay class="animated zoomInDown">
							<source src="/themes/{{ $shop_theme }}/img/intro2.mp4" type="video/mp4">
						</video> -->
						<!-- 16:9 aspect ratio -->
						<div style="max-width: 803px; margin: auto;">
							<div class="embed-responsive embed-responsive-16by9 animated zoomInDown" style="margin-top: 25px;">
							  <!-- <div class="overlay"></div> -->
					        	<iframe class="embed-responsive-item" src="https://www.youtube.com/embed/I7kIfi2RlcE?autoplay=1&showinfo=0&controls=0&loop=1&disablekb=1&modestbranding=1&playlist=DglLgQYkQX4&mute=0"></iframe>
					        	
							</div>
						</div>
						<h1>Turn Your Spending Into Earnings!</h1>
						<div onclick="location.href='/members/register'" class="join-button animated fadeInUp">JOIN THE MOVEMENT</div>	
					</div>
					<div class="col-md-4 right-container">
						<img style="cursor: pointer;" onclick="location.href='/members/register'" src="/themes/{{ $shop_theme }}/img/left-ads.png">
						<div class="join-button animated fadeInUp hidden">
							<!-- <span>ENROLL</span><span>&nbsp;&nbsp;<i class="fa fa-chevron-circle-right" aria-hidden="true"></i>
							</span> -->
							VISIT ACADEMY
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
	<!-- INTRODUCING BROWN -->
	<div class="wrapper-2">
		<div class="container">
			<div class="title-container wow fadeInLeft"><span class="text-1">BROWN</span>&nbsp;<span class="text-2">PRODUCTS</span></div>
			<div class="single-slide">
				<div class="per-slide-container row clearfix">
					<div class="col-md-6">
						<div class="bp-container">
							<img src="/themes/{{ $shop_theme }}/img/bp-1.jpg">
						</div>
					</div>
					<div class="col-md-6">
						<div class="caption-container">
							<p>Introducing</p>
							<h1>THE BROWN EDITION</h1>
							<h2>A PHONE FILIPINOS CAN BE PROUD OF</h2>
							<p>Brown promotes a future that is mindful of the needs of every Filipino.</p>
							<!-- <div class="button-brown">SHOP NOW</div> -->
						</div>
					</div>
				</div>
				<div class="per-slide-container row clearfix">
					<div class="col-md-6">
						<div class="bp-container">
							<img src="/themes/{{ $shop_theme }}/img/bp-2.jpg">
						</div>
					</div>
					<div class="col-md-6">
						<div class="caption-container">
							<h1>THE BROWN SPEAKER</h1>
							<h2>UNLEASH THE NATIVE BASS!</h2>
							<p>Brown proudly designed a unique style of speakers that mirrors the culture of a true filipino.</p>
							<!-- <div class="button-brown">SHOP NOW</div> -->
						</div>
					</div>
				</div>
				<div class="per-slide-container row clearfix">
					<div class="col-md-6">
						<div class="bp-container">
							<img src="/themes/{{ $shop_theme }}/img/bp-3.jpg">
						</div>
					</div>
					<div class="col-md-6">
						<div class="caption-container">
							<h1>BROWN'S DTV DONGLES</h1>
							<h2>THE FUTURE STYLE REVEALED</h2>
							<p>Let your passion comes to our culture. Brown's DTV Dongles promotes a native looks that embraces Filipino Culture</p>
							<!-- <div class="button-brown">SHOP NOW</div> -->
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
	<!-- FEATURES -->
	<div class="wrapper-3 row-no-padding clearfix">
		<div class="col-md-6 video-holder match-height">
			<div class="detail-container">
				<div class="title-container wow fadeInDown" data-wow-offset="10" data-wow-delay="0.2s"><span class="text-1">BROWN</span>&nbsp;<span>PHONES</span></div>
				<p class="wow fadeInLeft" data-wow-delay="0.3s">
					The Brown phone is your portal to a new world full of creativity and opportunities, bringing you closer to artists and entrepreneurs, while keeping you updated on the latest news, hottest trends, and innovative products and services, making your life better and more inspiring.
				</p>
			</div>
		</div>
		<div class="col-md-6">
			<video class="wow fadeInRight" data-wow-delay="0.3s" width="" height="" muted autoplay="" controls="" loop data-wow-offset="10">
				<source src="/themes/{{ $shop_theme }}/img/features.m4v" type="video/mp4">
			</video>
			<!--<div class="embed-responsive embed-responsive-16by9 animated zoomInDown">-->
			<!--  <div class="overlay"></div>-->
			<!--  <iframe class="embed-responsive-item" src="https://www.youtube.com/embed/DglLgQYkQX4?autoplay=1&showinfo=0&controls=0&loop=1&disablekb=1&modestbranding=1&playlist=DglLgQYkQX4&mute=1"></iframe>-->
			<!--</div>-->
		</div>
	</div>
	<!-- EVENTS -->
	<div class="wrapper-4">
		<div class="container">
			<div class="title-container wow fadeInLeft"><span class="text-1">UPCOMING</span>&nbsp;<span class="text-2">EVENTS</span></div>	
			<div class="event-holder row clearfix">
				<!-- PER EVENT -->
				<div class="col-md-6 col-xs-12">
					<div class="per-event row clearfix">
						@if(count($_event) > 0)
						@foreach($_event as $event)
						<div class="col-md-4">
							<div class="event-img-container wow fadeInUp" data-wow-delay="0.2s">
								<a href="javascript:" class="popup" size="lg" link="/events/view_details?id={{$event->event_id}}"><img src="{{$event->event_thumbnail_image}}"></a>
							</div>
						</div>
						<div class="col-md-8">
							<div class="event-detail-container wow fadeInRight" data-wow-delay="0.3s">
								<a href="javascript:" class="popup" size="lg" link="/events/view_details?id={{$event->event_id}}"><div class="title max-lines-title">{{strtoupper($event->event_title)}}</div></a>
								<div class="date"><span><i class="fa fa-calendar-o" aria-hidden="true"></i></span>&nbsp;&nbsp;<span>{{strtoupper(date('F d, Y', strtotime($event->event_date)))}}</span></div>
								<div class="desc">
									<div class="max-lines-desc">
										{!! $event->event_description !!}
									</div>
								</div>
							</div>
						</div>
						@endforeach
						@else
						<p class="no-event">Events are coming soon! See you there...</p>
						@endif
					</div>
				</div>
			</div>
		</div>
	</div>
	<!-- BROWN ARTICLES -->
	<div class="wrapper-5">
		<div class="container">
			<div class="row clearfix">
				<div class="col-md-3 col-sm-6 wow fadeInUp" data-wow-offset="10" data-wow-delay="0.2s">
					<div class="holder">
						<div class="img">
							<img src="/themes/{{ $shop_theme }}/img/greensun.png">
						</div>
						<div class="title-2">Who Are We</div>
						<div class="caption-container">
							<p>Brown is under MySolid Technologies and Devices Corporation, the flagship company of Solid Group Inc. (SGI) Serving the Filipinos for 70 years with responsible and pioneering​ businesses.</p>
						</div>	
					</div>
				</div>
				<div class="col-md-3 col-sm-6 wow fadeInUp" data-wow-offset="10" data-wow-delay="0.2s">
					<div class="holder">
						<div class="img">
							<img src="/themes/{{ $shop_theme }}/img/holder-1.png">
						</div>
						<div class="title-2">Our Philosophy</div>
						<div class="caption-container">
							<p>Filipinos work hard, dream big, and create with their hearts. And through bayanihan, we create opportunities to enable the world to patronize everything beautiful about being Filipino. </p>
						</div>
					</div>
				</div>
				<div class="col-md-3 col-sm-6 wow fadeInUp" data-wow-offset="10" data-wow-delay="0.3s">
					<div class="holder">
						<div class="img">
							<img src="/themes/{{ $shop_theme }}/img/holder-2.png">
						</div>
						<div class="title-2">Our Culture</div>
						<div class="caption-container">
							<p class="more">We curate emerging Filipino talents and artists-always on the lookout for the latest and the best in fashion, music, business, and other lifestyles that are relevant to your daily life. Through our technology, we celebrate the diversity of the artists and the potential in their craft. </p>
						</div>
					</div>
				</div>
				<div class="col-md-3 col-sm-6 wow fadeInUp" data-wow-offset="10" data-wow-delay="0.4s">
					<div class="holder">
						<div class="img">
							<img src="/themes/{{ $shop_theme }}/img/holder-3.png">
						</div>
						<div class="title-2">The Brown Movement</div>
						<div class="caption-container">
							<p class="more">When culture and technology meet, opportunities are opened and resources are created to help every Filipino pursue their dreams while building the nation. With us at Brown, all it takes is to be proud of what we have, a phone, and others who wll work with us in inspiring the world with works of creativity and entrepreneurship. </p>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
@endsection
@section("script")
<script type="text/javascript" src="/themes/{{ $shop_theme }}/js/home.js"></script>
<script>
	new WOW().init();
</script>

<script type="text/javascript">
	$(document).ready(function()
	{
		$('.single-slide').slick({
		  infinite: true,
		  slidesToShow: 1,
		  slidesToScroll: 1,
		  autoplay: true,
	  	  autoplaySpeed: 2500,
	  	  arrows: true,
		  prevArrow:"<img class='a-left control-c prev slick-prev' src='/themes/{{ $shop_theme }}/img/chev-left.png'>",
		  nextArrow:"<img class='a-right control-c next slick-next' src='/themes/{{ $shop_theme }}/img/chev-right.png'>",
	  	  speed: 500,
		  fade: true,
		  cssEase: 'linear',
		  pauseOnHover: false
		});


		/*SHOW MORE JS*/
		var showChar = 130;  // How many characters are shown by default
	    var ellipsestext = "...";
	    var moretext = "Show more >";
	    var lesstext = "Show less";
	    

	    $('.more').each(function() {
	        var content = $(this).html();
	 
	        if(content.length > showChar) {
	 
	            var c = content.substr(0, showChar);
	            var h = content.substr(showChar, content.length - showChar);
	 
	            var html = c + '<span class="moreellipses">' + ellipsestext+ '&nbsp;</span><span class="morecontent"><span>' + h + '</span>&nbsp;&nbsp;<a href="" class="morelink">' + moretext + '</a></span>';
	 
	            $(this).html(html);
	        }
	 
	    });
	 
	    $(".morelink").click(function(){
	        if($(this).hasClass("less")) {
	            $(this).removeClass("less");
	            $(this).html(moretext);
	        } else {
	            $(this).addClass("less");
	            $(this).html(lesstext);
	        }
	        $(this).parent().prev().toggle();
	        $(this).prev().toggle();
	        return false;
	    });


	});
</script>

<!-- BTS POPUP -->
<script>
// $(document).ready(function($){
  
//   window.onload = function (){
//     $(".bts-popup").delay(1000).addClass('is-visible');
// 	}
  

// 	$('.bts-popup-trigger').on('click', function(event){
// 		event.preventDefault();
// 		$('.bts-popup').addClass('is-visible');
// 	});
	

// 	$('.bts-popup').on('click', function(event){
// 		if( $(event.target).is('.bts-popup-close') || $(event.target).is('.bts-popup') ) {
// 			event.preventDefault();
// 			$(this).removeClass('is-visible');
// 		}
// 	});

// 	$(document).keyup(function(event){
//     	if(event.which=='27'){
//     		$('.bts-popup').removeClass('is-visible');
// 	    }
//     });
// });

</script>


@endsection
@section("css")
<link rel="stylesheet" type="text/css" href="/themes/{{ $shop_theme }}/css/home.css?v=1.0">
<style type="text/css">
.now-height
{
	max-height: none !important;
	height: auto !important;
}
</style>
@endsection