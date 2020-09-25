@extends("layout")
@section("content")
<div class="content">
    <!-- Media Slider -->
    <div class="slider-wrapper" style="background-image: url('/themes/{{ $shop_theme }}/img/home-banner.jpg')">
        <div class="container">
            <div class="caption-container">
                <span class="title-caption-red">Smart</span><span class="title-caption-black"> and</span><span class="title-caption-blue"> cost effective</span><span class="title-caption-black"> press release distribution platform</span>
            </div>
             <div class="button-container smoth-scroll" href="#requestdemo">
                <a class="smoth-scroll" href="#requestdemo">REQUEST A DEMO</a>
            </div>
        </div>
    </div>

    <div class="wrapper-1">
        <div class="container">
            <div class="title-container">Create great Press Releases</div>
            <div class="subtitle-container">Distribute your content to the right people in the right place.</div>
            <div class="border-container"></div>
            <div class="row clearfix">
                <div class="col-md-6">
                    <div class="image-holder"><img src="/themes/{{ $shop_theme }}/img/wrapper1-image.png"></div>
                </div>
                <div class="col-md-6">
                    <div class="caption-container">
                        <span class="caption-black">Distribute your News using</span><span class="caption-red"> Our Intelligent</span><span class="caption-black"> and most updated media database</span>
                    </div>
                    <div class="details-container">
                        <span class="details-blue">Contacts of media journalist and editors</span><span class="details-black"> across all regions including Asia, North America, Europe and the Middle East</span>
                    </div>
                    <div class="details-container">
                        <span class="details-blue">Easy to use</span><span class="details-black"> media release builder with ability to add rich media images or videos to your release</span>
                    </div>
                    <div class="details-container">
                        <span class="details-blue">Media monitoring</span><span class="details-black"> receive a full report of coverage after your distribution campaign</span>
                    </div>
                    <div class="details-container">
                        <span class="details-blue">Real-time</span><span class="details-black"> reporting and measurable results</span>
                    </div>
                    <div class="details-container">
                        <span class="details-blue">Advanced segmentation</span><span class="details-black"> by Audience, media, demographic, and Category</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div id="requestdemo" class="wrapper-2" style="background-image: url('/themes/{{ $shop_theme }}/img/wrapper2-image.jpg')">
        <div class="container">
                @if (session('Demo_message'))
                    <div class="alert alert-success">
                        {{ session('Demo_message') }}
                    </div>
                 @endif
            <div class="title-container">
                <span class="title">Request a free demo of Press IQ!</span><span class="icon"><img src="/themes/{{ $shop_theme }}/img/wrapper-2-icon.png"></span>
            </div>
            <form action="Post"> 
            <div class="row clearfix">
                <div class="col-md-6">
                    <div class="form-group">
                        <div class="title">Name: *</div>
                        <input type="text" class="form-control" id="name" name="name" required>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <div class="title">Company: *</div>
                        <input type="text" class="form-control" id="company" name="company" required>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <div class="title">Email: *</div>
                        <input type="Email" class="form-control" id="email" name="email" required>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <div class="title">Phone Number: *</div>
                        <input type="text" class="form-control" id="number" name="number" required>
                    </div>
                </div>
                <div class="col-md-12">
                    <div class="form-group">
                        <div class="title">Message: *</div>
                        <textarea type="text" class="form-control text-message" id="message" name="message"></textarea>
                    </div>
                </div>
                <div class="button-container">
                    <button type="submit" formaction="/demo/send">SEND</button>
                </div>
            </div>
            </form>
        </div>
    </div>

    {{-- <div class="wrapper-3">
        <div class="container">
            <div class="row clearfix">
                <div class="col-md-3">
                    <div class="image-holder">
                        <img src="/themes/{{ $shop_theme }}/img/wrapper3-image1.png">
                    </div>    
                </div>
                 <div class="col-md-3">
                    <div class="image-holder">
                        <img src="/themes/{{ $shop_theme }}/img/wrapper3-image2.png">
                    </div>    
                </div>
                 <div class="col-md-3">
                    <div class="image-holder">
                        <img src="/themes/{{ $shop_theme }}/img/wrapper3-image3.png">
                    </div>    
                </div>
                 <div class="col-md-3">
                    <div class="image-holder">
                        <img src="/themes/{{ $shop_theme }}/img/wrapper3-image4.png">
                    </div>    
                </div>

            </div>
        </div>
    </div> --}}

    <!-- SCROLL TO TOP -->
    <div class="scroll-up"><img src="/themes/{{ $shop_theme }}/img/scroll-up.png"></div>
</div>
@endsection

@section("css")
<link rel="stylesheet" type="text/css" href="/themes/{{ $shop_theme }}/css/home.css">
@endsection

@section("script")

<script type="text/javascript">
/*$(document).ready(function($) {

        //START MISSION AND VISION
        $(".title-vision").click(function()
        {
            $("#vision").removeClass("hide");
            $("#mission").addClass("hide");
            $(".title-vision").addClass("highlighted");
            $(".title-mission").removeClass("highlighted");
            
        });
        $(".title-mission").click(function()
        {
            $("#vision").addClass("hide");
            $("#mission").removeClass("hide");
            $(".title-mission").addClass("highlighted");
            $(".title-vision").removeClass("highlighted");
        });
        //END MISSION ANF VISION
});*/
</script>

@endsection