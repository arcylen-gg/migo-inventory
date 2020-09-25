@extends("layout")
@section("content")
<div class="content">
   <div class="container">
        <div class="single-slide">
        @foreach($_company as $cm)
            @if($cm->company_id == Request::input("i"))
            <div class="merchant-wrapper">
                <div class="controls-cont ainer">
                    <p>{{strtoupper($cm->company_name)}}</p>
                    <div class="prev"><i class="fa fa-arrow-circle-o-left" aria-hidden="true"></i></div>
                    <div class="next"><i class="fa fa-arrow-circle-o-right" aria-hidden="true"></i></div>
                </div>
                <div class="merchant-img-container">
                    <img src="{{$cm->company_brochure}}">
                </div>
                <br>
                <br>
                <br>
                <div class="map-address text-center">
                    <h4><strong>{{$cm->company_address}}</strong></h4>
                </div>
                <div class="map-container">
                    <iframe src="https://www.google.com/maps/embed/v1/place?key=AIzaSyCRvE3JLUuCDLRdqAz4ngx61tQDImQjOxQ&q={{$cm->company_address}}" width="600" height="450" frameborder="0" style="border:0" frameborder="0"></iframe>
                </div>
            </div>
            @endif
        @endforeach
        @foreach($_company as $cm)
            @if($cm->company_id != Request::input("i"))
            <div class="merchant-wrapper {{$cm->company_id}}">
                <div class="controls-cont ainer">
                    <p>{{strtoupper($cm->company_name)}}</p>
                    <div class="prev"><i class="fa fa-arrow-circle-o-left" aria-hidden="true"></i></div>
                    <div class="next"><i class="fa fa-arrow-circle-o-right" aria-hidden="true"></i></div>
                </div>
                <div class="merchant-img-container">
                    <img src="{{$cm->company_brochure}}">
                </div>
                <br>
                <br>
                <br>
                <div class="map-address text-center">
                    <h4><strong>{{$cm->company_address}}</strong></h4>
                </div>
                <div class="map-container">
                    <iframe src="https://www.google.com/maps/embed/v1/place?key=AIzaSyCRvE3JLUuCDLRdqAz4ngx61tQDImQjOxQ&q={{$cm->company_address}}" width="600" height="450" frameborder="0" style="border:0" frameborder="0"></iframe>
                </div>
            </div>
            @endif
        @endforeach
        </div>
    </div>
    <!-- SCROLL TO TOP -->
    <div class="scroll-up"><img src="/themes/{{ $shop_theme }}/img/scroll-up.png"></div>    
</div>
@endsection
@section("css")
<link rel="stylesheet" type="text/css" href="/themes/{{ $shop_theme }}/css/partners_views.css">
@endsection
@section('js')
<script type="text/javascript">
    $(document).ready(function()
    {
        $('.single-slide').slick({
          infinite: true,
          slidesToShow: 1,
          slidesToScroll: 1,
          arrows: false,
          speed: 500,
          fade: true,
          cssEase: 'linear'
        });

        $(".prev").click(function()
        {
            $('.single-slide').slick('slickPrev');
        });

        $(".next").click(function()
        {
            $('.single-slide').slick('slickNext');
        });
        
    });
</script>
@endsection

