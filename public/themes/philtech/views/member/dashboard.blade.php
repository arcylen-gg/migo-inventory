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
	            	<div class="title">Not Yet Activated | <span>DASHBOARD</span> </div>
	                <div class="join-container" style="background-image: url('/themes/{{ $shop_theme }}/img/nonmember-bg.png');">
	                    <div class="btn btn-text">
	                        <div class="text-header1">BE A FRANCHISEE NOW!</div>
	                        <div class="text-header2">Opportunity awaits you. Purchase now and enjoy the perks of being a Philtech VIP.</div>
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
			<div class="col-md-6">
				<div class="square-container">
					<div class="title"><i class="align-icon brown-icon-bar-chart"></i> Wallet Summary <a href="javascript:" style="opacity: 100;" class="title-button" onclick="action_load_link_to_modal('/members/enter-code')"><div>Create New Slot</div></a></div>
					<div class="sub-container">
						<div class="table-holder">
							<div class="chart-legend">
								<div class="holder">
									<div class="color" {{-- style="background-color: #76b6ec" --}}></div>
									<div class="name"><span>Current Wallet</span> <div class="name w2hpx">{{ $wallet->display_current_wallet }}</div></div>
								</div>
								<div class="holder">
									<div class="color" {{-- style="background-color: #8E5EA2" --}}></div>
									<div class="name"><span>Total Pay-out</span> <div class="name w2hpx">{{ $wallet->display_total_payout }}</div></div>
								</div>
								<div class="holder">
									<div class="color"></div>
									<div class="name"><span>Current Slot(s)</span> <div class="name w2hpx">{{ $customer_summary["display_slot_count"] }}</div></div>
								</div>
								<div class="holder">
									<div class="color"></div>
									<div class="name"><span>Total Earnings</span> <div class="name w2hpx">{{ $wallet->display_total_earnings }}</div></div>
								</div>

								<div class="holder">
									<div class="color"></div>
									<div class="name"><span>Total Points</span> <div class="name w2hpx">{{ $total_points }}</div></div>
								</div>
							</div>
						</div>
					</div>
				</div>
				@if($travel_and_tours)
				<div class="square-container" style="border: none !important;">
					<div class="btn-container">
						<a class="btn btn-lblue" style="width: 100%;" role="button" href="{{$link}}">Travel and Tours</a>
					</div>
				</div>
				@endif
				<div class="square-container">
					<div class="title"><i class="align-icon brown-icon-gift"></i> Reward Points</div>
					<div class="sub-container">
						@if(count($_point_plan) > 0)
						<div class="chart-legend" style="min-height: 117px; max-height: auto;">
							@foreach($_point_plan as $plan)
								@if($plan->label == "Repurchase Cashback")
									<!-- <div class="holder">
										<div class="color"></div>
										<div class="name"><span>VIP Cashback</span> {{ $points->{ "display_" . $plan->string_plan } }}</div>
									</div> -->
								@else
									<div class="holder">
										<div class="color"></div>
										<div class="name"><span>{{ $plan->label }}</span> <div class="name w2hpx">{{ $points->{ "display_" . $plan->string_plan } }}</div></div>
									</div>
								@endif
							@endforeach
						</div>
						@else
							<div class="text-center" style="padding: 20px">You don't have any points yet.</div>
						@endif
					</div>
				</div>
			</div>

			<div class="col-md-6">
				<div class="square-container">
					<div class="title"><i class="fa fa-table"></i> Reward Summary</div>
					<div class="sub-container">
						<div class="chart-legend">
							@foreach($_wallet_plan as $plan)
								<div class="holder">

									<div class="color"></div>

									@if($plan->label == "Membership Matching")
										<div class="name"><span>Franchise Matching</span> <div class="name w2hpx">{{ $wallet->{ "display_" . $plan->string_plan } }}</div></div>
									@elseif($plan->label == "Vmoney")
										<div class="name"><span>E-Money</span> <div class="name w2hpx">{{ $wallet->{ "display_" . $plan->string_plan } }}</div></div>
									@else
										<div class="name"><span>{{ $plan->label }}</span> <div class="name w2hpx">{{ $wallet->{ "display_" . $plan->string_plan } }}</div></div>
									@endif

								</div>
							@endforeach
						</div>
					</div>
				</div>
				
				@if(isset($points->{ "display_repurchase_cashback"}))
				<div class="square-container">
					<div class="title"><i class="align-icon"></i> VIP Cashback</div>
					<div class="sub-container">
						<div class="chart-legend" style="min-height: 117px; max-height: auto;">
							<div class="holder">
								<div class="color"></div>
									<div class="name">{{ $points->{ "display_repurchase_cashback"} }}</div>
							</div>
						</div>
					</div>
				</div>
				@endif
				<div class="square-container">
					<div class="title">Enter Product Code</div>
					<div class="sub-container">
						<div class="chart-legend text-center">
							<button class="btn btn-lblue" onclick="action_load_link_to_modal('/members/slot-useproductcode', 'md')">Use Product Code</button>
						</div>
					</div>
				</div>
			</div>
		</div>
		
		<div class="row clearfix">
			<div class="col-md-12">
				<div class="square-container">
					<div class="title">Replicated Link</div>
					<div class="sub-container">
						@foreach($_slot as $slot)
						<div class="holder">
							<div class="row clearfix">
								<div class="col-sm-12 text-center">
									<div class="label2">{{ $slot->slot_no }}</div>
									<div class="label3"> <a href="javascript:" onclick="action_load_link_to_modal('/members/lead?slot_no={{ $slot->slot_no }}')"> VIEW LEAD LINK</a></b></div>
								</div>
							</div>
						</div>
						@endforeach
					</div>
				</div>
			</div>
		</div>

		<div class="row clearfix">
			<div class="col-md-6">
				<div class="square-container">
					<div class="title"><i class="align-icon brown-icon-globe"></i> Newest Direct Franchisee</div>
					<div class="sub-container border-holder">
						<div class="clearfix wow hidden">
							<div class="badge right">6 New Members</div>
						</div>
						{{-- <div class="load-direct-referrals-here">
													
						</div> --}}
						@if(count($_direct) > 0)
							@foreach($_direct as $direct)
							<div class="holder">
								<div class="row clearfix">
									<div class="mob-center col-md-7">
										<div class="color">
											<img src="{{ $direct->profile_image }}">
										</div>	
										<div class="text">
											<div class="pull-left">
												<div class="name">{{ $direct->first_name }} {{ $direct->last_name }}</div>
												<div class="email">{{ $direct->slot_no }}</div>
												<div class="date">{{ $direct->time_ago }}</div>
											</div>
										</div>
									</div>
									<div class="col-md-5">
										<div class="action" style="text-align: center;">
											@if($direct->distributed == 1)
												<button onclick="action_load_link_to_modal('/members/slot-info?slot_no={{ Crypt::encrypt($direct->slot_id) }}&key={{ md5($direct->slot_id . $direct->slot_no) }}')" class="btn btn-lblue"><i class="fa fa-star"></i> VIEW INFO</button>
											@else
												<button class="btn btn-danger place_slot_btn" place_slot_id="{{$direct->slot_id}}"><i class="fa fa-warning"></i> PLACE THIS SLOT</button>
											@endif
										</div>
									</div>
								</div>
							</div>
							@endforeach
						@else
							<div class="text-center" style="padding: 20px">You don't have any direct referral yet.</div>
						@endif
					</div>
				</div>
			</div>
			<div class="col-md-6">
				<div class="square-container">
					<div class="match-height">
						<div class="title"><i class="align-icon brown-icon-money"></i> Recent Rewards <a href="javascript:" class="title-button" onclick="location.href='/members/report'"><div>View All Rewards</div></a></div>
						<div class="sub-container">
							<div class="activities">
								@if(count($_recent_rewards) > 0)
									@foreach($_recent_rewards as $recent_reward)
									<div class="holder">
										<div class="circle-line">
											<div class="circle"><img src="/themes/{{ $shop_theme }}/img/circle.png"></div>
											<div class="line"><img src="/themes/{{ $shop_theme }}/img/line.jpg"></div>
										</div>
										<div class="message">{!! $recent_reward->log !!}</div>
										<div class="row clearfix">
											<div class="col-sm-6">
												<div class="date">{{ $recent_reward->time_ago }}</div>
											</div>
											<div class="col-sm-6">
												<div class="wallet"> EARNED BY {{ $recent_reward->slot_no }}</div>
											</div>
										</div>
									</div>
									@endforeach
								@else
									<div class="text-center" style="padding: 20px">You don't have any reward yet.</div>
								@endif
							</div>
						</div>
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

{{-- <script>
	$(window).on('hashchange', function() {
	    if (window.location.hash) {
	        var page = window.location.hash.replace('#', '');
	        if (page == Number.NaN || page <= 0) {
	            return false;
	        } else {
	            getPosts(page);
	        }
	    }
	});
	$(document).ready(function() {
		getPosts(1);
	    $(document).on('click', '.pagination a', function (e) {
	        getPosts($(this).attr('href').split('page=')[1]);
	        e.preventDefault();
	    });
	});
	function getPosts(page) {
	    $.ajax(
	    {
	        url : '/members/direct-referrals?page=' + page,
	        type: 'get',
	    }).done(function (data) 
	    {
	        $('.load-direct-referrals-here').html(data);
	        location.hash = page;
	    }).fail(function () 
	    {
	        alert('Posts could not be loaded.');
	    });
	}
</script> --}}

@endsection

@section("member_css")
<link rel="stylesheet" type="text/css" href="/themes/{{ $shop_theme }}/css/member_dashboard.css">
<link rel="stylesheet" type="text/css" href="/themes/{{ $shop_theme }}/css/nonmember_dashboard.css">
@endsection