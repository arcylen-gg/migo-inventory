@extends("layout")
@section("content")
<div class="content">
    <!-- Media Slider -->
    <div class="slider-wrapper">
        <div class="container">
            <div class="row clearfix mob-vid-wrapper">
                {{-- <div class="media-wrapper">
                    <div class="embed-responsive embed-responsive-16by9">
                        <iframe class="embed-responsive-item" src="https://www.youtube.com/embed/I7kIfi2RlcE?ecver=1&modestbranding=1&rel=0&autohide=1&showinfo=0&controls=0" controls="0" frameborder="0" allowfullscreen>
                        </iframe>
                    </div>
                    <a href="/members/register"><div class="btn-container animated fadeInDown watt">GET FREE ACCESS</div></a>
                </div> --}}
                <div class="col-md-6">
                	<div class="media-wrapper">
                	    <div class="embed-responsive embed-responsive-16by9">
                	        <iframe class="embed-responsive-item" src="https://www.youtube.com/embed/gcVHATfMQns?ecver=1&modestbranding=1&rel=0&autohide=1&showinfo=0&controls=0" controls="0" frameborder="0" allowfullscreen>
                	        </iframe>
                	    </div>
                	    {{-- <a href="/members/register"><div class="btn-container animated fadeInDown watt">GET FREE ACCESS</div></a> --}}
                	</div>
                </div>
                <div class="col-md-6">
                	<div class="media-wrapper mob-vid">
                	    <div class="embed-responsive embed-responsive-16by9">
                	        <iframe class="embed-responsive-item" src="https://www.youtube.com/embed/eHtKQ2TSr8c?ecver=1&modestbranding=1&rel=0&autohide=1&showinfo=0&controls=0" controls="0" frameborder="0" allowfullscreen>
                	        </iframe>
                	    </div>
                	    
                	</div>
                </div>                
            </div>
            <a href="/members/register"><div class="btn-container animated fadeInDown watt">GET FREE ACCESS</div></a>
        </div>
    </div>
</div>
@endsection

@section("css")
<link rel="stylesheet" type="text/css" href="/themes/{{ $shop_theme }}/css/replicated.css?version=2.1">
<style>
	header
	{
		display: none;
	}
	.sticky-nav
	{
		display: none;
	}
	.footer-bottom
	{
		display: none;
	}
	.figues
	{
		display: none;
	}
	#main
	{
		margin-top: 0 !important;
	}
	body
	{
		background-image: url('/themes/{{ $shop_theme }}/img/brown-banner.jpg');
		background-size: cover;
	}
	.watt
	{
		background-color: transparent !important;
		border-radius: 0;
		border: 2px #fff solid;
	}
</style>
@endsection

@section("script")

@endsection
