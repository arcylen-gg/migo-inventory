@extends("press_user.member")
@section("pressview")
	<div class="row-no-padding clearfix" style="margin-top: 30px;">
		<div class="col-md-9">
			<div class="press-view-holder">
				@foreach ($pr as $prs)
				<div class="title-container"><a href="/pressuser/mypressrelease">{{$prs->pr_headline}}</a></div>
				<div class="date-container">{{$prs->pr_date_sent}}</div>
				<div class="details-container"><p>{!! str_replace('../', '/', $prs->pr_content); !!}</p></div>
				<div class="border"></div>
				<div class="title-about-container"><p>About {{$prs->pr_co_name}}</p><p>{{$prs->pr_type}}</p></div>
				<div class="details-container">{!! str_replace('../', '/', $prs->pr_boiler_content); !!}</div>
				@endforeach
			</div>
		</div>
		<div class="col-md-3">
			<div class="press-others-holder">
			    <div class="logo-holder">
			    	@foreach ($pr as $prs)
			        <img src="{{$prs->pr_co_img}}">
			        @endforeach
			    </div>
				<div class="header">Other Releases:</div>
					@foreach($opr as $prs)
					<div class="title-container"><a href="/pressuser/mypressrelease/pressrelease/view/{{$prs->pr_id}}">{{$prs->pr_headline}}</a></div>
					<div class="date-container"><span><i class="fa fa-clock-o" aria-hidden="true"></i></span><span>{{$prs->pr_date_sent}}</span></div>
					<div class="border"></div>
					@endforeach
					
			</div>
			<div class="press-others-holder" style="display: none;" >
            	{!! $pr->render() !!}
         	</div>
		</div>
	</div>
@endsection

@section("css")
<link rel="stylesheet" type="text/css" href="/themes/{{ $shop_theme }}/css/pressrelease_view.css">
@endsection

@section("script")

@endsection