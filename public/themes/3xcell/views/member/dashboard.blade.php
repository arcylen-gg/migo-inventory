@extends("member.member_layout")
@section("member_content")

<input type="hidden" name="_mode" class="_mode" value="{{ $mode }}">
<input type="hidden" name="_token" class="_token" value="{{ csrf_token() }}">
@if(!$mlm_member)
	<div class="dashboard">
	    <div class="dashboard-top">
	        <div class="row clearfix">
	            <div class="col-md-12">
	            	<div class="title">NON-MEMBER | <span>DASHBOARD</span> </div>
	                <div class="join-container" style="background-image: url('/themes/{{ $shop_theme }}/img/nonmember-bg.png');">
	                    <div class="btn btn-text">
	                        <div class="text-header1">Become A Member Now!</div>
	                        <div class="text-header2">Opportunity awaits you.</div>
	                    </div>
	                    <div class="btn-container">
	                        {{-- <a href="#" id="btn-buy-a-kit"><button class="btn-buy-a-kit">BUY A KIT</button></a>
	                        <img src="/themes/{{ $shop_theme }}/img/or-1.png"><br>
	                        <span class="or">OR</span> --}}
	                        <a href="#" id="btn-enter-a-code"><button class="btn-enter-a-code">ENTER A CODE</button></a>
	                        @if(isset($company_head_id))
	                        	</br></br>
	                        	<span style="color:white">or</span>
	                        	</br></br>
	                        	<a href="#" id="btn-enter-a-code"><button class="btn-enter-a-code no_sponsor_code" company_head_id="{{$company_head_id->slot_no}}">NO SPONSOR?</button></a>
	                    	@endif
	                    </div>
	                </div>
	            </div>
	        </div>
	    </div>
	</div>
@else
	<div class="dashboard">
		<div class="row clearfix">
			<div class="col-md-6">
				<div class="square-container">
					<div class="title"><i class="align-icon brown-icon-bar-chart"></i> Wallet Summary</div>
					<div class="sub-container">
						<div class="table-holder">
							<div class="chart-legend">
								<div class="holder">
									<div class="color cw"></div>
									<div class="name"><span>Current Wallet</span> <div class="name cw-text">{{ $wallet->display_current_wallet }}</div></div>
								</div>
								<div class="holder">
									<div class="color tp"></div>
									<div class="name"><span>Total Pay-out</span> <div class="name tp-text">{{ $wallet->display_total_payout }}</div></div>
								</div>
								{{-- <div class="holder">
									<div class="color"></div>
									<div class="name"><span>Current Slot(s)</span> {{ $customer_summary["display_slot_count"] }}</div>
								</div> --}}
								<div class="holder">
									<div class="color tr"></div>
									<div class="name"><span>Total Reward</span> <div class="name tr-text">{{ $wallet->display_total_earnings }}</div></div>
								</div>
							</div>
						</div>
					</div>
				</div>
				<div class="square-container">
					<div class="title"><i class="align-icon brown-icon-gift"></i> Reward Points</div>
					<div class="sub-container">
						<div class="chart-legend" style="min-height: 117px; max-height: auto;">
							<div class="holder">
								<div class="color rpv"></div>
								<div class="name"><span>Rank PV</span> <div class="name rpv-text">{{ number_format($points->rank_pv + $points->rank_gpv,2) }} POINT(S)</div></div>
							</div>
							{{-- <div class="holder">
								<div class="color"></div>
								<div class="name"><span>Rank Group PV</span><span class="value">{{ $points->display_rank_gpv }}</span></div>
							</div> --}}
							<div class="holder">
								<div class="color pv"></div>
								<div class="name"><span>Personal Volume</span> <div class="name pv-text">{{ $points->display_stairstep_pv }}</div></div>
							</div>
							<div class="holder">
								<div class="color gs"></div>
								<div class="name"><span>Group Sales PV</span> <div class="name gs-text">{{ $points->display_stairstep_gpv }}</div></div>
							</div>
						</div>
					</div>
				</div>
			</div>

			<div class="col-md-6">
				<div class="square-container">
					<div class="title"><i class="fa fa-table"></i> Reward Summary</div>
					<div class="sub-container">
						<div class="chart-legend">
							<div class="holder">
								<div class="color wsc"></div>
								<div class="name"><span>Wholesale Commission</span><div class="name wsc-text">{{ $wallet->display_complan_direct }}</div></div>
							</div>
						</div>
						<div class="chart-legend">
							<div class="holder">
								<div class="color pr"></div>
								<div class="name"><span>Personal Rebates</span><div class="name pr-text">{{ $wallet->display_complan_rank_repurchase_cashback }}</div></div>
							</div>
						</div>
						<div class="chart-legend">
							<div class="holder">
								<div class="color pc"></div>
								<div class="name"><span>Performance Commission</span><div class="name pc-text">{{ $wallet->display_complan_stairstep }}</div></div>
							</div>
						</div>
					</div>
				</div>
				@if($first_slot->membership_restricted == 1)
				<div class="square-container">
					<div class="title">Upgrade slot</div>
					<div class="sub-container">
						<div class="chart-legend text-center">
							<button class="btn btn-blue" data-toggle="modal" data-target="#upgrade-slot-modal">Use Upgrade Code</button>
						</div>
					</div>
				</div>
				@endif
				<div class="square-container">
					<div class="title">Enter Product Code</div>
					<div class="sub-container">
						<div class="chart-legend text-center">
							<button class="btn btn-blue" onClick="action_load_link_to_modal('/members/slot-useproductcode', 'md')">Use Product Code</button>
						</div>
					</div>
				</div>
			</div>
		</div>
		<div class="col-md-12">
			<div class="title">Replicated Link</div>
			<div class="sub-container">
					@foreach($_slot as $slot)
					<div class="holder">
						<div class="row clearfix">
							<div class="col-sm-12 text-center">
								<div class="label2">{{ $slot->slot_no }}</div>
								<div> <a href="javascript:" onclick="action_load_link_to_modal('/members/lead?slot_no={{ urlencode($slot->slot_no) }}','md')"> VIEW LEAD LINK</a></b></div>
							</div>
						</div>
					</div>
					@endforeach
			</div>
		</div>
		<div class="row clearfix">
			<div class="col-md-6">
				<div class="square-container">
					<div class="title"><i class="align-icon brown-icon-globe"></i> Newest Direct Referrals</div>
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
												<button onclick="action_load_link_to_modal('/members/slot-info?slot_no={{ Crypt::encrypt($direct->slot_id) }}&key={{ md5($direct->slot_id . $direct->slot_no) }}')" class="btn btn-blue"><i class="fa fa-star"></i> VIEW INFO</button>
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

	    <!-- Success -->
	    <div class="popup-success">
	        <div id="success-modal" class="modal success-modal fade">
	            <div class="modal-md modal-dialog">
	                <div class="modal-content">
	                    <div class="modal-body">
	                        <div><img src="/themes/{{ $shop_theme }}/img/done-img.png"></div>
	                        <div class="text-header">Done!</div>
	                        <div class="text-caption">You are now officially a member of<br><b>3xcell</b>.</div>
	                    </div>
	                </div>
	            </div>
	        </div>
	    </div>
	</div>
@endif

<!--  Upgrade Slot -->
<div class="popup-proceed2">
    <div id="upgrade-slot-modal" class="modal fade">
        <div class="modal-sm modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                    <h4 class="modal-title"><i class="fa fa-shield"></i> CODE UPGRADE</h4>
                </div>
                <div class="modal-body">
                    <div class="message message-return-code-verify"></div>
                    <form class="upgrade-slot-verification-form">
                        <div>
                            <div class="labeld">Pin Code</div>
                            <input class="input upgrade-pin text-center" name="pin" type="text">
                        </div>
                        <div>
                            <div class="labeld">Activation</div>
                            <input class="input upgrade-activation text-center" name="activation" type="text">
                        </div>
                        <div class="btn-container">
                            <button id="btn-upgrade" type="button" class="btn-upgrade" onClick="check_upgrade_code()"><i class="fa fa-angle-double-right"></i> Proceed</button>
                        </div>
                    </form>
                </div>
              </div>
        </div>
    </div>
</div>

<!--  Enter a code -->
<div class="popup-enter-a-code">
    <div id="enter-a-code-modal" class="modal fade">
        <div class="modal-md modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                    <h4 class="modal-title"><i class="fa fa-star"></i> SPONSOR</h4>
                </div>
                <div class="modal-body">
                    <form method="post" class="submit-verify-sponsor">
                        <div class="labels"><b>Enter a Slot Number</b></div>
                        <input required="required" class="input-verify-sponsor text-center" name="verify_sponsor" type="text" placeholder="">
                        <div class="output-container">
                            
                        </div>
                        <div class="btn-container">
                            <button id="btn-verify" class="btn-verify btn-verify-sponsor"><i class="fa fa-check"></i> VERIFY SPONSOR</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Proceed 1 -->
<div class="popup-proceed1">
    <div id="proceed-modal-1" class="modal fade">
        <div class="modal-sm modal-dialog">
            <div class="modal-content load-final-verification">
            </div>
        </div>
    </div>
</div>

<!-- Proceed 2 -->
<div class="popup-proceed2">
    <div id="proceed-modal-2" class="modal fade">
        <div class="modal-sm modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                    <h4 class="modal-title"><i class="fa fa-shield"></i> CODE VERIFICATION</h4>
                </div>
                <div class="modal-body">
                    <div class="message message-return-code-verify"></div>
                    <form class="code-verification-form">
                        <div>
                            <div class="labeld">Pin Code</div>
                            <input class="input input-pin text-center" name="pin" type="text">
                        </div>
                        <div>
                            <div class="labeld">Activation</div>
                            <input class="input input-activation text-center" name="activation" type="text">
                        </div>
                        <div class="btn-container">
                            <button id="btn-proceed-2" class="btn-proceed-2"><i class="fa fa-angle-double-right"></i> Proceed</button>
                        </div>
                    </form>
                </div>
              </div>
          </div>
      </div>
  </div>

<!-- MANUAL PLACING OF SLOT -->
<div class="popup-verify-placement">
    <div id="slot-placement-modal" class="modal fade">
        <div class="modal-sm modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                    <h4 class="modal-title"><i class="fa fa-shield"></i> MANUAL PLACEMENT</h4>
                </div>
                <div class="modal-body">
                    <div class="message message-return-slot-placement-verify"></div>
                    <form class="slot-placement-form">
                        <div>
                            <div class="labeld">Slot Placement</div>
                            <input class="input input-slot-placement text-center" name="slot_placement" type="text">
                            <input class="chosen_slot_id" name="chosen_slot_id" type="hidden">
                        </div>
                        <div>
                            <div class="labeld">Slot Position</div>
                            <select class="input input-slot-position text-center" name="slot_position" type="text" style="text-align-last:center;">
                            	<option value="left">LEFT</option>
                            	<option value="right">RIGHT</option>
                            </select>
                        </div>
                        <div class="btn-container">
                            <button id="check_placement" class="btn-verify-placement">VERIFY</button>
                        </div>
                    </form>
                </div>
              </div>
          </div>
      </div>
  </div>
  
@endsection

@section("member_script")
<script type="text/javascript" src="/themes/{{ $shop_theme }}/js/non_member.js"></script>
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

<script type="text/javascript">
	function check_upgrade_code()
	{
		var form_data = {};
		form_data._token = $("._token").val();
		form_data.pin = $(".upgrade-pin").val();
		form_data.activation = $(".upgrade-activation").val();

		/* START LOADING AND DISABLE FORM */
		$(".code-verification-form").find(".btn-proceed-2").html('<i class="fa fa-spinner fa-pulse fa-fw"></i> VERIFYING').attr("disabled", "disabled");
		$(".code-verification-form").find("select").attr("disabled", "disabled");

		$.ajax(
		{
			url:"/members/slot-upgrade-code",
			data:form_data,
			type:"post",
			success: function(data)
			{
				$(".message-return-code-verify").html(data);
				if(data == "")
				{
					toastr.success("Upgrade success");
					window.location.replace("/members");
				}
			},
			error: function(data)
			{
				alert("An ERROR occurred. Please contact administrator.");
				$(".code-verification-form").find(".btn-proceed-2").html('<i class="fa fa-angle-double-right"></i> PROCEED').removeAttr("disabled");
			}
		});

	}
</script>

@endsection

@section("member_css")
<link rel="stylesheet" type="text/css" href="/themes/{{ $shop_theme }}/css/member_dashboard.css">
<link rel="stylesheet" type="text/css" href="/themes/{{ $shop_theme }}/css/nonmember_dashboard.css">
<link rel="stylesheet" type="text/css" href="/themes/{{ $shop_theme }}/css/atomic_color.css">
@endsection

<style type="text/css">
#btn-upgrade
{
    color: #fff;
    background-color: #2161C8;
    padding: 10px 40px;
    border-radius: 2px;
    border: 0px !important;
    -webkit-transition: all 0.2s ease-in-out;
    -moz-transition: all 0.2s ease-in-out;
    -o-transition: all 0.2s ease-in-out;
    transition: all 0.2s ease-in-out;
    width: 100%;
    margin-top: 20px;
    text-transform: uppercase;
}	
</style>