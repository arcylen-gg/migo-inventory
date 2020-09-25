@extends("layout")
@section("content")
<div class="about-image">
	<img src="{{ get_content($shop_theme_info, "about", "about_image") }}">
</div>
<div class="content">
	<div class="info">
		<div class="about-title">
			<p>{{ get_front_divide_string($shop_theme_info, "about", "about_title", 2, 0) }} <font class="about-title-pink">{{ get_front_divide_string($shop_theme_info, "about", "about_title", 2, 1) }}</font></p>
		</div>
		<div class="container about-desc"><p>{{ get_content($shop_theme_info, "about", "about_description") }}</p></div>
		
		<div class="statement">
			<div class="title">
				<p>{{ get_front_divide_string($shop_theme_info, "about", "mission_title", 2, 0) }} <font class="title-pink">{{ get_front_divide_string($shop_theme_info, "about", "mission_title", 2, 1) }}</font></p>
			</div>
			<div class="container desc">
				<p>{{ get_content($shop_theme_info, "about", "mission_description") }}</p>
			</div>
			<div class="title">
				<p>{{ get_front_divide_string($shop_theme_info, "about", "vision_title", 2, 0) }} <font class="title-pink">{{ get_front_divide_string($shop_theme_info, "about", "vision_title", 2, 1) }}</font></p>
			</div>
			<div class="container desc">
				<p>{{ get_content($shop_theme_info, "about", "vision_description") }}</p>
			</div>
			<div class="title">
				<p>{{ get_front_divide_string($shop_theme_info, "about", "core_title", 2, 0) }} <font class="title-pink">{{ get_front_divide_string($shop_theme_info, "about", "core_title", 2, 1) }}</font></p>
			</div>
			<div class="container desc">{!! get_content($shop_theme_info, "about", "core_description") !!}</div>
		</div>
	</div>
</div>
@endsection
@section("css")
<link rel="stylesheet" type="text/css" href="/themes/{{ $shop_theme }}/assets/front/css/about.css">
@endsection
@section("script")

@endsection