@extends("member.member_layout")
@section("member_content")

<input type="hidden" name="_mode" class="_mode" value="{{ $mode }}">
<input type="hidden" name="_token" class="_token" value="{{ csrf_token() }}">
@if($mlm_member)
	@if(isset($_notification))
	<div class="alert alert-danger">
		<div class="message-warning text-center">
			<b>Warning!</b> You are receiving this notification because ...
			<br>
			{!! $_notification->remarks !!}
		</div>
		<div class="text-center">
			<a class="mark-as-read-click" notif-id="{{$_notification->notification_id}}"><small>Mark as Read</small></a>
		</div>
	</div>
	@endif
@endif
@if(!$mlm_member)
	<div class="dashboard">
		@if(isset($check_unused_code))
			<!--  CONGRATULATION -->
			<div class="popup-notification">
			    <div id="popup-notification-modal">
		            <div class="modal-content">
		                <!--<div class="modal-header">-->
		                <!--    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>-->
		                <!--    <h4 class="modal-title"><i class="fa fa-star"></i> CONGRATULATION</h4>-->
		                <!--</div>-->
		                <div class="modal-body">
		                	<div class="congrats-holder">
			                	<div class="title">CONGRATULATIONS!</div>
			                    <div class="img">
			                    </div>
			                    <div class="desc">You are one step away from your membership!</div>
			                    <div class="btn-container">
			                        <button id="btn-notification" class="btn-verify-notification btn-congratulation btn-notification" type="button">Continue</button>
			                    </div>
		                	</div>
		                </div>
		            </div>
		        </div>
			</div>
    	@else
	    <!-- TOP DASHBOARD-->
	    <div class="dashboard-top">
	        <div class="row clearfix">
	            <div class="col-md-8">
	                <div class="embed-responsive embed-responsive-16by9">
					  <!-- <iframe class="embed-responsive-item" src="https://www.youtube.com/embed/sy655Z-7TZE?autoplay=1" frameborder="0" allowfullscreen></iframe> -->
					  <iframe src="{{ get_content($shop_theme_info, "non-member-video", "nonmember_video_link") }}?autoplay=1" frameborder="0" webkitallowfullscreen mozallowfullscreen allowfullscreen></iframe>
					</div>
	            </div>
	            <div class="col-md-4">
	                <div class="join-container">
	                    <div class="btn btn-text">
	                        <div class="text-header1">Become a member!</div>
	                        <!-- <div class="text-header2">Enroll now and become one of us!</div> -->
	                    </div>
	                    <div class="btn-container">
	                        <button class="btn-buy-a-kit" type="button">Activate My Account</button>
	                        <!-- <br> -->
	                        <!-- <img src="/themes/{{ $shop_theme }}/img/or-1.png"><br>
	                        <a href="#" id="btn-enter-a-code"><button style="margin-top: 0;" onclick="action_load_link_to_modal('members/enter-code')" class="btn-enter-a-code">ENTER A CODE</button></a> -->
	                    </div>
	                </div>
	            </div>
	        </div>
	    </div>
	    @endif
	    
	@else
		<div class="dashboard">
			<div class="row clearfix">
				<div class="col-md-6">
					<div class="title">Wallet Summary <a href="javascript:" class="title-button btn-enter-a-code"><div>Create New Slot</div></a></div>
					<div class="sub-container">
						<div class="table-holder">
							<div class="chart-legend">
								<div class="holder">
									<div class="color cw" style="background-color: #3E95CD;"></div>
									<div class="name"><span>Current Wallet</span> <div class="name cw-text">{{ $wallet->display_current_wallet }}</div></div>
								</div>
								<div class="holder">
									<div class="color tp" style="background-color: #8E5EA2;"></div>
									<div class="name"><span>Total Pay-out</span> <div class="name tp-text">{{ $wallet->display_total_payout }}</div></div>
								</div>
								<div class="chart-holder">
									<canvas id="income_summary" class="chart-income" wallet="{{ $wallet->current_wallet }}"  payout="{{ $wallet->total_payout }}" style="max-width: 150px;" width="400" height="400"></canvas>
								</div>
								<div class="holder">
									<div class="color cs"></div>
									<div class="name"><span>Current Slot(s)</span> <div class="name cs-text">{{ $customer_summary["display_slot_count"] }}</div></div>
								</div>
								<div class="holder">
									<div class="color tr"></div>
									<div class="name"><span>Total Reward</span> <div class="name tr-text">{{ $wallet->display_total_earnings }}</div></div>
								</div>
							</div>
						</div>
					</div>
				</div>

				<div class="col-md-6">
					<div class="title">Reward Summary</div>
					<div class="sub-container">
						<div class="chart-legend">
							@foreach($_wallet_plan as $plan)

								@if($plan->label == "Bank Remittance")
									<div class="holder">
										<div class="color bk"></div>
										<div class="name"><span>{{ $plan->label }}</span> <div class="name bk-text">{{ $wallet->{ "display_" . $plan->string_plan } }}</div></div>
									</div>
								@elseif($plan->label == "Coinsph")
									<div class="holder">
										<div class="color cph"></div>
										<div class="name"><span>{{ $plan->label }}</span> <div class="name cph-text">{{ $wallet->{ "display_" . $plan->string_plan } }}</div></div>
									</div>
								@elseif($plan->label == "Direct")
									<div class="holder">
										<div class="color dir"></div>
										<div class="name"><span>{{ $plan->label }}</span> <div class="name dir-text">{{ $wallet->{ "display_" . $plan->string_plan } }}</div></div>
									</div>
								@elseif($plan->label == "Direct Pass Up")
									<div class="holder">
										<div class="color dpu"></div>
										<div class="name"><span>{{ $plan->label }}</span> <div class="name dpu-text">{{ $wallet->{ "display_" . $plan->string_plan } }}</div></div>
									</div>
								@elseif($plan->label == "Indirect")
									<div class="holder">
										<div class="color ind"></div>
										<div class="name"><span>{{ $plan->label }}</span> <div class="name ind-text">{{ $wallet->{ "display_" . $plan->string_plan } }}</div></div>
									</div>
								@elseif($plan->label == "Palawan Express")
									<div class="holder">
										<div class="color palex"></div>
										<div class="name"><span>{{ $plan->label }}</span> <div class="name palex-text">{{ $wallet->{ "display_" . $plan->string_plan } }}</div></div>
									</div>
								@else
									<div class="holder">
										<div class="color"></div>
										<div class="name"><span>{{ $plan->label }}</span> {{ $wallet->{ "display_" . $plan->string_plan } }}</div>
									</div>
								@endif
							@endforeach
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

					@if($shop_id == "90")
						<div class="title">Buy a Kit</div>
						<div class="sub-container">
							<div class="btn-container">
								<button class="btn-buy-a-kit" type="button">Buy</button>
							</div>
						</div>
					@endif

				</div>
			</div>

			<div class="row clearfix">
				<div class="col-md-6">
					<div class="title">Newest Direct Referrals</div>
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
									<div class="col-md-12">
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
									{{-- <div class="action" style="text-align: center;">
										@if($direct->distributed == 1)
											<button onclick="action_load_link_to_modal('/members/slot-info?slot_no={{ Crypt::encrypt($direct->slot_id) }}&key={{ md5($direct->slot_id . $direct->slot_no) }}')" class="btn btn-default"><i class="fa fa-star"></i> VIEW INFO</button>
										@else
											<button class="btn btn-danger place_slot_btn" place_slot_id="{{$direct->slot_id}}"><i class="fa fa-warning"></i> PLACE THIS SLOT</button>
										@endif
									</div> --}}
								</div>
							</div>
							@endforeach
						@else
							<div class="text-center" style="padding: 20px">You don't have any direct referral yet.</div>
						@endif
					</div>
				</div>
				<div class="col-md-6">
					<div class="match-height">
						<div class="title">Recent Rewards <a href="javascript:" class="title-button" onclick="location.href='/members/report'"><div>View All Rewards</div></a></div>
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
		                        {{-- <div><img src="/themes/{{ $shop_theme }}/img/brown-done-img.png"></div> --}}
		                        <div class="text-header">Done!</div>
		                        <div class="text-caption">You are now an official member of <br><b>Unity Wealth</b></div>
		                    </div>
		                </div>
		            </div>
		        </div>
		    </div>
		</div>
	@endif

    <style type="text/css">
    .unity-kit .kit-holder .name
    {
    	font-size: 14px;
    	font-weight: 700;
    	margin-bottom: 15px;
    }
    .unity-kit .kit-holder .btn
    {
    	width: 100%;
    }
    </style>

	<!-- Modal -->
	<div id="unity_kit" class="modal fade unity-kit" role="dialog">
		<div class="modal-dialog modal-sm">
			<!-- Modal content-->
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal">&times;</button>
					<h4 class="modal-title">Buy a Kit</h4>
				</div>
				<div class="modal-body">
					<div class="row clearfix">
						@foreach($item_kit as $key => $kit)
						<div class="col-md-12 text-center">
							<div class="kit-holder">
								<div class="name match-height">{{ $key }}</div>
								<div class="btn-holder"><button type="button" class="btn btn-custom-primary" onClick="location.href='/cartv2/buy_kit_mobile/{{ $kit }}'">BUY</button></div>
							</div>
						</div>
						@endforeach
					</div>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-custom-white" data-dismiss="modal">Close</button>
				</div>
			</div>
		</div>
	</div>
</div>

@endsection

@section("member_script")
<script type="text/javascript" src="/assets/member/js/non_member.js"></script>
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

<script>
$(document).ready(function()
{
	$wallet = $(".chart-income").attr("wallet");
	$payout = $(".chart-income").attr("payout");

	if (document.getElementById("income_summary") != null) 
	{
		var ctx = document.getElementById("income_summary").getContext('2d');

		var myDoughnutChart = new Chart(ctx,
		{
		    type: 'doughnut',
		    data: {
		        labels: ["Red", "Blue"],
		        datasets: [{
		            label: '# of Votes',
		            data: [$payout, $wallet],
		            backgroundColor: [
		                'rgba(142, 94, 162, 1)',
		                'rgba(62, 149, 205, 1)'
		            ],
		            borderColor: [
		                'rgba(142, 94, 162, 1)',
		                'rgba(62, 149, 205, 1)'
		            ],
		            borderWidth: 1
		        }]
		    },
		    options: 
		    {
		      legend: 
		      {
		        responsive: true,
		        display: false,
		      },
		      tooltips: 
		      {
		        callbacks: 
		        {
		          label: function(tooltipItems, data) 
		          {
		            var sum = data.datasets[0].data.reduce(add, 0);
		            function add(a, b) {
		              return a + b;
		            }

		            return data.datasets[0].data[tooltipItems.index];
		          },
		        }
		      }
		    }
		});
	}
});

// And for a doughnut chart


$(document).ready(function()
{
	if($("._mode").val() == "success")
	{
		$("#success-modal").modal("show");
	}


	$(".place_slot_btn").click(function()
	{
		$(".message-return-slot-placement-verify").empty();
		$(".chosen_slot_id").val($(this).attr("place_slot_id"));
		$("#slot-placement-modal").modal("show");
	});

	add_event_click_buy_kit();
	mark_as_read_function();
});
function mark_as_read_function()
{
	$('.mark-as-read-click').unbind('click');
	$('.mark-as-read-click').bind('click', function()
	{
		$(this).html("Please wait...");
		var notif_id = $(this).attr('notif-id');
		$.ajax({
			url : '/members/read-notification',
			type : 'get',
			data : {notif_id : notif_id},
			success : function(data)
			{
				$('.alert.alert-danger').remove();
			}
		});
	});
}
function add_event_click_buy_kit()
{
	$(".btn-buy-a-kit").off("click");
	$(".btn-buy-a-kit").on("click", function()
	{
		action_click_buy_kit();
	});
}
function action_click_buy_kit()
{
	$("#unity_kit").modal();
}



</script>
@endsection
@section("member_css")
<link rel="stylesheet" type="text/css" href="/themes/{{ $shop_theme }}/css/member_dashboard.css">
<link rel="stylesheet" type="text/css" href="/themes/{{ $shop_theme }}/css/nonmember_dashboard.css">
<link rel="stylesheet" type="text/css" href="/themes/{{ $shop_theme }}/css/atomic_color.css">
<style type="text/css">

input:-webkit-autofill {
    -webkit-box-shadow: 0 0 0 30px white inset;
}

/* PLACEMENT VERIFIER */
.popup-verify-placement {
  background-color: #EEEEEE;
  font-family: "Arimo", sans-serif; }
  .popup-verify-placement .modal-sm {
    width: 100%;
    max-width: 500px; }
  .popup-verify-placement .modal-content {
    background-color: #eee; }
    .popup-verify-placement .modal-content .modal-header {
      background-color: #693d28;
      border-top-left-radius: 3px;
      border-top-right-radius: 3px; }
      .popup-verify-placement .modal-content .modal-header .close {
        color: #FFF; }
      .popup-verify-placement .modal-content .modal-header .modal-title {
        font-weight: 600;
        color: #FFF;
        font-size: 15px; }
    .popup-verify-placement .modal-content .modal-body {
      font-weight: 600;
      margin: 0px 20px 0px 20px; }
      .popup-verify-placement .modal-content .modal-body .message {
        text-align: center;
        margin-bottom: 10px;
        color: red;
        font-size: 14px;
        text-transform: uppercase; }
      .popup-verify-placement .modal-content .modal-body .input {
        width: 100%;
        font-size: 16px;
        border: none;
        border: 1px solid #693d28;
        margin: 5px 0px;
        margin-bottom: 15px;
        padding: 5px;
        border-radius: 2px; }
      .popup-verify-placement .modal-content .modal-body label {
        font-size: 13px;
        text-align: center !important;
        margin: 10px 0px;
        padding: 10px; }
      .popup-verify-placement .modal-content .modal-body .labeld {
        color: #693d28;
        text-align: center;
        text-transform: uppercase; }
      .popup-verify-placement .modal-content .modal-body select {
        width: 100%;
        font-size: 16px;
        border: none;
        border: 1px solid #693d28;
        margin: 5px 0px;
        margin-bottom: 15px;
        padding: 5px;
        border-radius: 2px; }
      .popup-verify-placement .modal-content .modal-body .btn-container {
        text-align: center;
        font-family: "Arimo", sans-serif;
        font-weight: 600;
        font-size: 14px;
        text-transform: uppercase;
        padding-bottom: 20px; }
        .popup-verify-placement .modal-content .modal-body .btn-container .btn-verify-placement {
          color: #693d28;
          background-color: #fff;
          padding: 10px 40px;
          border: 2px solid #693d28;
          border-radius: 2px;
          opacity: 0.9;
          -webkit-transition: all 0.2s ease-in-out;
          -moz-transition: all 0.2s ease-in-out;
          -o-transition: all 0.2s ease-in-out;
          transition: all 0.2s ease-in-out;
          width: 100%;
          margin-top: 20px;
          text-transform: uppercase; }
        .popup-verify-placement .modal-content .modal-body .btn-container .btn-verify-placement:hover {
          color: #fff;
          background-color: #693d28;
          opacity: 1.0; }
</style>
@endsection