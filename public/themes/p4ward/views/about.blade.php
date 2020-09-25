@extends("layout")
@section("content")
<div class="content">
    <div class="container">
        <div class="main-container">
            <div class="title-container">
                <span class="icon-container"><img src="/themes/{{ $shop_theme }}/img/p4ward-icon-blue.png"></span><span class="title-blue">Our </span><span class="title-orange">History</span>
            </div>
            <div class="details-container">
                {!! get_content($shop_theme_info, "about", "about_history") !!}
            </div>
            <div class="p4ward_partner_container">
                <p>May you live a life of significance.</p>
                <p>Your P4ward Partner,</p>
                <div class="image-holder">
                    <img src="{{ get_content($shop_theme_info, "about", "about_author_img") }}">
                </div>
                <p style="font-weight: 700;">Chris Sarmiento</p>
            </div>
        </div>
    </div>
    <!-- SCROLL TO TOP -->
    <div class="scroll-up"><img src="/themes/{{ $shop_theme }}/img/scroll-up.png"></div>

</div>
@endsection

@section("css")
<link rel="stylesheet" type="text/css" href="/themes/{{ $shop_theme }}/css/about.css">
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