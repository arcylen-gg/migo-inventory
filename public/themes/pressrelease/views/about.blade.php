@extends("layout")
@section("content")
<div class="content">
    <!-- Media Slider -->
    <div class="main-wrapper">
        <div class="container">
            <div class="row clearfix">
                <div class="col-md-6">
                    <div class="title-container">About Press IQ</div>
                    <div class="about-container">
                        <span class="desc-black">Press-IQ</span><span class="desc-black"> is a intelligent results- driven platform for</span><span class="desc-black"> PR Professionals</span><span class="desc-black"> and</span><span class="desc-black"> Marketers</span><span class="desc-black"> for targeted distribution of your press release. We provide you with access to the most updated and extensive database of media journalist</span>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="image-holder"><img src="/themes/{{ $shop_theme }}/img/about-image.jpg"></div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section("css")
<link rel="stylesheet" type="text/css" href="/themes/{{ $shop_theme }}/css/about.css">
@endsection
