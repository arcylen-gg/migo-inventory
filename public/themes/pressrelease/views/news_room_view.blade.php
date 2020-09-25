@extends("layout")
@section("content")
<div class="content">
    <div class="background-container">
        <div class="container">
        	<div class="border-container">
        		<div class="border-background-container">
                    <div class="row-no-padding clearfix">
                    @foreach ($pr as $prs)    
                        <div class="col-md-9">
                            <div class="left-container">
                                <div class="heading-container">{{$prs->pr_headline}}</div>
                                <div class="sender-container"><span class="title-sender">Published by </span><span class="sender-name">{{$prs->pr_sender_name}}</span></div>
                                <div class="date-container">{{$prs->pr_date_sent}}</div>
                                <div class="content-container">{!! str_replace('../', '/', $prs->pr_content); !!}</div>
                                <div class="border"></div>
                                <div class="title-about-container">About {{$prs->pr_co_name}}</div>
                                <div class="title-about-container">{{$prs->pr_type}}</div>
                                <div class="content-container">{!! str_replace('../', '/', $prs->pr_boiler_content); !!}</div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="right-container">
                                <div class="company-name-container">
                                    <div class="company-name">{{$prs->pr_co_name}}</div>
                                </div>
                                <div class="logo-holder">
                                    <img src="{{$prs->pr_co_img}}">
                                </div>
                                <div class="header">Latest releases of industry</div>
                                    @foreach($pr_newsroom as $pr_view)
                                    <div class="release-container">
                                        <div class="title"><a href="/newsroom/view/{{$pr_view->pr_id}}">{{$pr_view->pr_headline}}</a></div>
                                        <div class="date-container"><span><i class="fa fa-clock-o" aria-hidden="true"></i></span><span>{{$pr_view->pr_date_sent}}</span></div>
                                    </div>
                                    @endforeach
                            </div>
                            <div class="right-container" style="display: none;" >
                                {!! $pr_newsroom->render() !!}
                            </div>  
                        </div>
                    @endforeach    
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
<link rel="stylesheet" type="text/css" href="/themes/{{ $shop_theme }}/css/news_room_view.css">
@endsection

@section("script")



@endsection