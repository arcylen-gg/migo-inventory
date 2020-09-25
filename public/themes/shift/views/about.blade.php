@extends("layout")
@section("content")
<div class="about">
    <header class="header" style="background-image: url('{{ get_content($shop_theme_info, "company", "company_banner") }}');">
        <div class="container">
        	<div class="first">{{ get_content($shop_theme_info, "company", "company_banner_caption1") }}</div>
        	<div class="second">{{ get_content($shop_theme_info, "company", "company_banner_caption2") }}</div>
        </div>
    </header>
    <section class="first-row">
    	<div class="container">
    		<div class="row clearfix">
	    		<div class="col-md-4">
	    			<div class="title">Who Are We</div>
	    			<div class="desc">{!! get_content($shop_theme_info, "company", "company_who_are_we_context") !!}</div>
	    		</div>
	    		<div class="col-md-4">
	    			<div class="title">Vision</div>
	    			<div class="desc">{!! get_content($shop_theme_info, "company", "company_vision_context") !!}</div>
	    		</div>
	    		<div class="col-md-4">
	    			<div class="title">Mission</div>
	    			<div class="desc">{!! get_content($shop_theme_info, "company", "company_mission_context") !!}</div>
	    		</div>
	    	</div>
    	</div>
    </section>
    <section class="second-row">
    	<div class="main clearfix">
    		<div class="container">
    			<div class="title">Our <span>Team</span></div>
	    		<div class="img"><img class="img-responsive" src="/themes/{{ $shop_theme }}/img/team/main.png"></div>
	    		<div class="name">Florentino Gemora Garcia</div>
	    		<div class="sub">Vice President</div>
	    		<div class="desc">{!! get_content($shop_theme_info, "company", "company_division2_vp_context") !!}</div>
	    		
    		</div>
    	</div>
    	<div class="other">
    		<div class="container">
    			<div class="row clearfix">
	    			<div class="col-md-3 col-sm-6">
	    				<div class="img"><img class="img-responsive" src="/themes/{{ $shop_theme }}/img/team/1.png"></div>
	    				<div class="name">Mitchel Despi Barawidan</div>
	    				<div class="role">Sales Executive</div>
	    			</div>
	    			<div class="col-md-3 col-sm-6">
	    				<div class="img"><img class="img-responsive" src="/themes/{{ $shop_theme }}/img/team/2.png"></div>
	    				<div class="name">Baltazar Bagolor</div>
	    				<div class="role">Sales Executive</div>
	    			</div>
	    			<div class="col-md-3 col-sm-6">
	    				<div class="img"><img class="img-responsive" src="/themes/{{ $shop_theme }}/img/team/3.png"></div>
	    				<div class="name">Sam Dacullo</div>
	    				<div class="role">Sales Executive</div>
	    			</div>
	    			<div class="col-md-3 col-sm-6">
	    				<div class="img"><img class="img-responsive" src="/themes/{{ $shop_theme }}/img/team/4.png"></div>
	    				<div class="name">Anthony Rodriguez</div>
	    				<div class="role">Sales Executive</div>
	    			</div>
	    			<div class="col-md-3 col-sm-6">
	    				<div class="img"><img class="img-responsive" src="/themes/{{ $shop_theme }}/img/team/5.png"></div>
	    				<div class="name">Abel Echon</div>
	    				<div class="role">Sales Executive</div>
	    			</div>
	    			<div class="col-md-3 col-sm-6">
	    				<div class="img"><img class="img-responsive" src="/themes/{{ $shop_theme }}/img/team/6.png"></div>
	    				<div class="name">Tiffany Bascon</div>
	    				<div class="role">Sales Executive</div>
	    			</div>
	    			<div class="col-md-3 col-sm-6">
	    				<div class="img"><img class="img-responsive" src="/themes/{{ $shop_theme }}/img/team/7.png"></div>
	    				<div class="name">Jefrrey Blando</div>
	    				<div class="role">Sales Executive</div>
	    			</div>
	    			<div class="col-md-3 col-sm-6">
	    				<div class="img"><img class="img-responsive" src="/themes/{{ $shop_theme }}/img/team/8.png"></div>
	    				<div class="name">Julie Baronda</div>
	    				<div class="role">Sales Executive</div>
	    			</div>
	    			<div class="col-md-3 col-sm-6">
	    				<div class="img"><img class="img-responsive" src="/themes/{{ $shop_theme }}/img/team/9.png"></div>
	    				<div class="name">Josie Noble</div>
	    				<div class="role">Sales Executive</div>
	    			</div>
	    			<div class="col-md-3 col-sm-6">
	    				<div class="img"><img class="img-responsive" src="/themes/{{ $shop_theme }}/img/team/10.png"></div>
	    				<div class="name">Oliver Mahilum</div>
	    				<div class="role">Sales Executive</div>
	    			</div>
	    			<div class="col-md-3 col-sm-6">
	    				<div class="img"><img class="img-responsive" src="/themes/{{ $shop_theme }}/img/team/11.png"></div>
	    				<div class="name">Lady Mae Baronca</div>
	    				<div class="role">Sales Executive</div>
	    			</div>
	    			<div class="col-md-3 col-sm-6">
	    				<div class="img"><img class="img-responsive" src="/themes/{{ $shop_theme }}/img/team/12.png"></div>
	    				<div class="name">Ma. Antonia Zabala</div>
	    				<div class="role">Sales Executive</div>
	    			</div>
	    			<div class="col-md-3 col-sm-6">
	    				<div class="img"><img class="img-responsive" src="/themes/{{ $shop_theme }}/img/team/13.png"></div>
	    				<div class="name">Jerome Olanda</div>
	    				<div class="role">Sales Executive</div>
	    			</div>
	    		</div>
    		</div>
    	</div>
    </section>
    <!-- SCROLL TO TOP -->
    <div class="scroll-up"><img src="/themes/{{ $shop_theme }}/img/scroll-up.png"></div>
</div>
@endsection

@section("css")
<link rel="stylesheet" type="text/css" href="/themes/{{ $shop_theme }}/css/about.css">
@endsection

@section("script")

@endsection