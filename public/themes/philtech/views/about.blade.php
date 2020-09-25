@extends("layout")
@section("content")
<!-- OUR VALUES -->
<div class="container">

	<div class="values">
		<div class="holder message">
			<div class="row clearfix">
				<div class="col-md-8">
					<div class="greet">GREETINGS!</div>
					<div class="welcome">WELCOME to the FRANCHISE BUSINESS OPPORTUNITY!</div>
					<p>
						This is a very unique business model that would make all of us realize and achieve our DREAMS. My greatest vision and passion have been to help more Filipinos to own an affordable and profitable BUSINESS and be truly successful holistically in their lives, thus the PhilTECH company was founded.<br><br> 

						For me, SUCCESS is not just about FINANCIAL FREEDOM, it is also about the TOTAL LIFE TRANSFORMATION: FINANCIALLY, EMOTIONALLY AND SPIRITUALLY. To MENTOR, MOTIVATE and INSPIRE other people regardless of age, gender, socio-economic status and educational background. <br><br>

						PhilTECH, INC. has been established to provide the right vehicle to ride on to earn limitless passive income and to become MILLIONAIRE in the future. PhilTECH, INC. is soaring at its highest potential to offer the FRANCHISE BUSINESS PACKAGES to all FILIPINOS anywhere in the world. It is one of my dreams that many people will be successful for the rest of their lives. <br><br>

						Again, I would like to welcome you all to this Life-Changing- Opportunity!<br><br>

						ARNOLD A. ARBILLERA<br>
						President &amp; CEO
					</p>
				</div>
				<div class="col-md-4">
					<img style="width: 250px;" src="/themes/{{ $shop_theme }}/img/greeting.jpg">
				</div>
			</div>
		</div>
	</div>

	<!-- TOP CONTENT -->
	<div class="top-content">
		<div class="clearfix">
			<div class="col-md-6">
				<div class="top-content-image">
					<img src="{{ get_content($shop_theme_info, 'about', 'about_image', '/themes/'. $shop_theme .'/img/about-content-image.jpg') }}">
				</div>
			</div>
		
			<div class="col-md-6">
				<div class="top-title">
					<span class="welcome">{{ get_content($shop_theme_info, "about", "about_title", "Welcome to Shopshoshop.com") }}</span>
				</div>
				<div class="paragraph">
					<div style="white-space: pre-wrap;">{{ get_content($shop_theme_info, "about", "about_content", "Lorem ipsum dolor sit amet, consectetuer adipiscing elit. Aenean commodo ligula eget dolor. Aenean massa. Cum sociis natoque penatibus et magnis dis parturient montes, nascetur ridiculus mus. Donec quam felis, ultricies nec, pellentesque eu, pretium quis, sem. Nulla consequat massa quis enim. Donec pede justo, fringilla vel, aliquet nec, vulputate eget, arcu. In enim justo, rhoncus ut, imperdiet a, venenatis vitae, justo.") }}</div>
				</div>
			</div>
		</div>
	</div>


	<!-- BOTTOM CONTENT -->
	<div class="bottom-content">
		
		<div class="clearfix">

			<div class="bottom-image"><img src="{{ get_content($shop_theme_info, 'about', 'about_mission_image', '/themes/'. $shop_theme .'/img/mission.png') }}"></div>

			<div class="title">
				<div>{{ get_content($shop_theme_info, "about", "about_mission_title", "Our Mission") }}</div>
			</div>

			<div class="bottom-par" style="white-space: pre-wrap;">{{ get_content($shop_theme_info, "about", "about_mission_content", "Lorem ipsum dolor sit amet, consectetuer adipiscing elit. Aenean commodo ligula eget dolor. Aenean massa. Cum sociis natoque penatibus et magnis dis parturient montes, nascetur ridiculus mus. Donec quam felis, ultricies nec, pellentesque eu, pretium quis, sem. Nulla consequat massa quis enim. Donec pede justo, fringilla vel, aliquet nec, vulputate eget, arcu. In enim justo, rhoncus ut, imperdiet a, venenatis vitae, justo.") }}</div>

			<div class="bottom-image">
				<img src="{{ get_content($shop_theme_info, 'about', 'about_vision_image', '/themes/'. $shop_theme .'/img/vision.png') }}">
			</div>

			<div class="title">
				<div>{{ get_content($shop_theme_info, "about", "about_vision_title", "Our Vision") }}</div>
			</div>

			<div class="bottom-par bottom-par-two" style="white-space: pre-wrap;">{{ get_content($shop_theme_info, "about", "about_vision_content", "Lorem ipsum dolor sit amet, consectetuer adipiscing elit. Aenean commodo ligula eget dolor. Aenean massa. Cum sociis natoque penatibus et magnis dis parturient montes, nascetur ridiculus mus. Donec quam felis, ultricies nec, pellentesque eu, pretium quis, sem. Nulla consequat massa quis enim. Donec pede justo, fringilla vel, aliquet nec, vulputate eget, arcu. In enim justo, rhoncus ut, imperdiet a, venenatis vitae, justo.") }}</div>
		</div>
			
	</div>

	<div class="bottom-content values">
		<div class="holder">
			<img class="img-responsive" style="margin: auto;" src="/themes/{{ $shop_theme }}/img/corevalues.png">
			<div class="title" style="margin-top: 50px; margin-bottom: 50px;">
				<div>CORE VALUES</div>
			</div>
			<img class="img-responsive" style="margin: auto;" src="/themes/{{ $shop_theme }}/img/core-values.jpg">
		</div>
	</div>
	<!-- SCROLL TO TOP -->
	<div class="scroll-up"><img src="/themes/{{ $shop_theme }}/img/scroll-up.png"></div>
</div>
@endsection

@section("css")
<link rel="stylesheet" type="text/css" href="/themes/{{ $shop_theme }}/css/aboutus.css">
@endsection
