@extends("layout")
@section("content")
<div class="content">
    <!-- Media Slider -->
    <div class="slider-wrapper">
        <div class="container">
            <div class="row clearfix">
                <div class="media-wrapper">
                    <div class="embed-responsive embed-responsive-16by9">
                        <!-- <iframe class="embed-responsive-item" src="https://www.youtube.com/embed/sy655Z-7TZE?autoplay=1&showinfo=0&controls=0" controls="0" allowfullscreen="" frameborder="0">                            
                        </iframe> -->
                        <!-- <iframe class="embed-responsive-item" src="https://www.youtube.com/embed/rzyKBUX18Wc?ecver=1&modestbranding=1&rel=0&autohide=1&showinfo=0&controls=0" controls="0" frameborder="0" allowfullscreen>
                        </iframe> -->
                        <iframe class="embed-responsive-item" src="{{ get_content($shop_theme_info, "replicated-link", "replicated_video_link") }}?ecver=1&modestbranding=1&rel=0&autohide=1&showinfo=0&controls=0" controls="0" frameborder="0" allowfullscreen>
                        </iframe>
                    </div>
                    <a href="/members/register"><div class="btn-container animated fadeInDown">GET FREE ACCESS</div></a>
                </div>
            </div>
        </div>
    </div>
    <!-- SCROLL TO TOP -->
    <div class="scroll-up"><img src="/themes/{{ $shop_theme }}/img/scroll-up.png"></div>
</div>
@endsection

@section("css")
<link rel="stylesheet" type="text/css" href="/themes/{{ $shop_theme }}/css/replicated.css">
@endsection

@section("script")

<script type="text/javascript">
$(document).ready(function($) {
    
        /*TEXT FADEOUT*/
        $(window).scroll(function(){
                $(".caption-container, .caption-logo-container").css("opacity", 1 - $(window).scrollTop() / 250);
        });

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
});
</script>

@endsection
