@extends("layout")
@section("content")
<div class="about">
	<div class="container">
		<div class="about-title">Serving the Filipinos</br> for 70 years</div>
		<div class="about-desc">Serving the Filipinos for 70 years with responsible and pioneering businesses.</div>
		<div class="about-title-2">Lets Introduce About Us</div>
		<div class="about-desc">70 years of vision, fidelity and trust was all Joseph Lim had when he open his first electronic shop almost 70 years ago. It grew and became solid corporation in 1967 that believes in the dream and limitless possibilities the Filipino people especially the youth, with its innovators and entrepreneurs can achive through their talent, hardwork and entrepreneurial instinct through hardwork and with an eye for entrepreneurship with Elena Lim his wife and its head it became the start of the company. solid group incorporated a publicly listed company with a tradition of responsible and pioneering way of doing business. The house electronics built the company focus first in the electronic industry bringing in world trusted electronic brands to the country. Sony, Samsung, Aiwa, TCL, Sanyo, and establishing nationwide markets for them. Destiny Cable the first broadband cable services in the country, semented the company's tradition of pioneering in the emerging industry in the fields of electronics and communication started by Elena Lim in 1976 when she managed to convince sony corporation to allow Filipinos to lead its production and management departments in the country. An era of change in 2007, the telecommunications market in the country change by the introduction of the pioneer Filipino brand myphone its all Filipino designed and manufactured phones at the affordable price serving as the first challenge to the then established and more expensive foreign mobile phone brands. After 70 years SGI continues to inspire and empower Filipinos.</div>
	</div>

	<div class="quote">
		<div class="text-holder">
			<div class="text">Gaining the trust of Filipinos with good values</br> and fidelity</div>
			<div class="text-sub">Solid Group Inc. - Publicly Listed Company</div>
		</div>
	</div>

	<div class="here-opacity"></div>

	<div class="container">
		<div class="brand">
			<div class="img"><img src="/themes/{{ $shop_theme }}/img/solid-group.jpg"></div>
			<div class="text">We are reinveting and transforming the young</br> creative entrepreneurs</div>
			<div class="text-sub">Brown is under MySolid Techonologies and Devices Corporation, the</br> flagship company of Solid Group Inc. (SGI)</div>
		</div>
		<div class="logo">
			<div class="text">Building world-trusted brands and industries</div>
			<div class="holder-container">
				<div class="holder"><img src="/themes/{{ $shop_theme }}/img/logo-1.jpg"></div>
				<div class="holder"><img src="/themes/{{ $shop_theme }}/img/logo-2.jpg"></div>
				<div class="holder"><img src="/themes/{{ $shop_theme }}/img/logo-3.jpg"></div>
				<div class="holder"><img src="/themes/{{ $shop_theme }}/img/logo-4.jpg"></div>
				<div class="holder"><img src="/themes/{{ $shop_theme }}/img/logo-5.jpg"></div>
				<div class="holder"><img src="/themes/{{ $shop_theme }}/img/logo-6.jpg"></div>
			</div>
		</div>
	</div>
	
</div>
@endsection
@section("css")
<link rel="stylesheet" type="text/css" href="/themes/{{ $shop_theme }}/css/about.css">
@endsection
@section("script")
<script type="text/javascript">
/* SCROLL TEXT FADE OUT */
$(window).scroll(function()
{
	$(".text-holder").css("opacity", 1 - $(window).scrollTop() / $('.here-opacity').offset().top);
});
</script>
@endsection