@extends("member.member_layout")
@section("member_content")

<input type="hidden" name="_mode" class="_mode" value="{{ $mode }}">
<input type="hidden" name="_token" class="_token" value="{{ csrf_token() }}">


@if(!$mlm_member)
	<!-- NON-MLM MEMBER -->
	<div class="dashboard">
	    <div class="dashboard-top">
	        <div class="row clearfix">
	            <div class="col-md-12">
	            	<div class="title">NON-MEMBER | <span>DASHBOARD</span> </div>
	                <div class="join-container" style="background-image: url('/themes/{{ $shop_theme }}/img/nonmember-bg.png');">
	                    <div class="btn btn-text">
	                        <div class="text-header1">Become A Member Now!</div>
	                        <div class="text-header2">Opportunity awaits you. Lorem ipsum dolor sit amet, consectetuer adipiscing elit.<br>Aenean commodo ligula eget dolor.</div>
	                    </div>
	                    <div class="btn-container">
	                        <!-- <a href="#" id="btn-buy-a-kit"><button class="btn-buy-a-kit">BUY A KIT</button></a> -->
	                        <!-- <img src="/themes/{{ $shop_theme }}/img/or-1.png"><br> -->
	                        <!-- <span class="or">OR</span> -->
	                        <a href="javascript:" onclick="action_load_link_to_modal('/members/enter-code')" id="btn-enter-a-code"><button class="btn-enter-a-code">ENTER A CODE</button></a>
	                    </div>
	                </div>
	            </div>
	        </div>
	    </div>
	</div>
@else
	<!-- MLM MEMBER -->
	<div class="dashboard">
		<div class="row clearfix">
			<div class="col-md-12">
				<h4><img src="/themes/{{ $shop_theme }}/img/p-card.png"> Privilage Card Holder</h4>
			</div>
		</div>
		<div class="row clearfix">
			<div class="col-md-2">
			</div>
			<div class="col-md-8 content-wrapper">
				<div class="sub-container text">
					@if(count($_point_plan) > 0)
					<div class="chart-legend" style="min-height: 117px; max-height: auto;">
						@foreach($_point_plan as $plan)
						<div class="holder">
							
							<div class="name" style="width: 100%;"><div class="color pull-left middle-pos"></div><span>{{ $plan->label }}</span> <span class="p-text">{{ $points->{ "display_" . $plan->string_plan } }}</span></div>
						</div>
						@endforeach
					</div>
					@else
						<div class="text-center" style="padding: 20px">You don't have any points yet.</div>
					@endif
				</div>
				<div class="title left-side">Enter Product Code</div>
				<div class="sub-container">
					<div class="chart-legend text-center">
						<button class="btn btn-lblue" onClick="action_load_link_to_modal('/members/slot-useproductcode', 'md')">Use Product Code</button>
					</div>
				</div>
			</div>
		</div>
	    <!-- Success -->
	    <div class="popup-success">
	        <div id="success-modal" class="modal success-modal fade">
	            <div class="modal-md modal-dialog">
	                <div class="modal-content">
	                    <div class="modal-body">
	                        <div><img src="/themes/{{ $shop_theme }}/img/brown-done-img.png"></div>
	                        <div class="text-header">Done!</div>
	                        <div class="text-caption">You are now officially enrolled to<br><b>Philtech Global, Inc.</b></div>
	                    </div>
	                </div>
	            </div>
	        </div>
	    </div>
	</div>
@endif
@endsection

@section("member_script")
<script type="text/javascript" src="assets/member/js/non_member.js"></script>
<script type="text/javascript" src='/assets/chartjs/Chart.bundle.min.js'></script>
@endsection

@section("member_css")
<link rel="stylesheet" type="text/css" href="/themes/{{ $shop_theme }}/css/member_dashboard.css">
<link rel="stylesheet" type="text/css" href="/themes/{{ $shop_theme }}/css/nonmember_dashboard.css">
@endsection