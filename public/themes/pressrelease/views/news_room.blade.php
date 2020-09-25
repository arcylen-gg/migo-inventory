@extends("layout")
@section("content")

<div class="content">
    <!-- Media Slider -->
<div class="background-container">
    <div class="container">
        <div class="border-container" id="show_newsroom">

            <div class="background-border-container" id="show_newsroom">
                <div class="row clearfix">
                    <div class="col-md-12">
                        <div class="search-container">
                            <input type="text" placeholder="Search News" name="search_newsroom" id="search_newsroom">
                            <button type="button" id="search_newsroom_btn" name="search_newsroom_btn" aria-hidden="true" her><i class="fa fa-search"></i></button>
                        </div>
                    </div>
                </div>
                
                <div class="main-container">
                    @foreach ($pr as $prs)
                    <div class="news-title-container" >
                        <div class="title">
                            <a href="/newsroom/view/{{$prs->pr_id}}">{{$prs->pr_headline}}</a>
                        </div>
                    </div>  
                    <div class="details-container">
                        <p>{!!$prs->pr_content!!}</p>
                    </div>
                    <div class="button-container">
                        <button onclick="window.location.href='/newsroom/view/{{$prs->pr_id}}'">Read More &raquo;</button>
                    </div>
                    @endforeach
                    <div class="button-container" style="border-bottom: none;" >
                    {!! $pr->render() !!}
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>

    <!-- SCROLL TO TOP -->
    <div class="scroll-up"><img src="/themes/{{ $shop_theme }}/img/scroll-up.png"></div>
</div>

@endsection

@section("css")
<link rel="stylesheet" type="text/css" href="/themes/{{ $shop_theme }}/css/news_room.css">
@endsection

@section("script")
<script  src="/assets/js/news_room.js"></script>
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