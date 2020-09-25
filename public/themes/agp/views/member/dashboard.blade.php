@extends("member.member_layout")
@section("member_content")


<input type="hidden" name="_mode" class="_mode" value="{{ $mode }}">
<input type="hidden" name="_token" class="_token" value="{{ csrf_token() }}">
<input type="hidden" name="code" class="check_unused_code" value="{{ $check_unused_code or 0 }}">
<input type="hidden" name="not_placed_yet" class="not_placed_yet" value="{{ $not_placed_yet or 0 }}" link="/members/enter-placement?slot_no={{ Crypt::encrypt($not_placed_slot->slot_id) }}&key={{ md5($not_placed_slot->slot_id . $not_placed_slot->slot_no) }}">

@if(!$mlm_member)
	<div class="dashboard">
	    <div class="dashboard-top">
	        <div class="row clearfix">
	            <div class="col-md-12">
	                <div class="join-container" style="background-image: url('/themes/{{ $shop_theme }}/img/nonmember-dash.jpg')">
	                    <div class="caption-container">
	                        <div class="text-header1">Not yet a Member?</div>
	                        <div class="text-header2">Join Us Now!</div>
	                    </div>
	                    <div class="btn-container">
	                        <a href="#" id="btn-enter-a-code"><button onclick="action_load_link_to_modal('/members/enter-code')" class="btn-enter-a-code">Enter a Code</button></a>
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
					<div class="title">Wallet Summary <a href="javascript:" class="title-button" onclick="action_load_link_to_modal('members/enter-code')"><div>Create New Slot</div></a></div>
					<div class="sub-container">
						<div class="table-holder">
							<div class="chart-legend">
								<div class="holder">
									<div class="color" style="background-color: #019771"></div>
									<div class="name"><span>Current Wallet</span> {{ $wallet->display_current_wallet }}</div>
								</div>
								<div class="holder">
									<div class="color" style="background-color: #8E5EA2"></div>
									<div class="name"><span>Total Pay-out</span> {{ $wallet->display_total_payout }}</div>
								</div>
								<div class="holder">
									<div class="color"></div>
									<div class="name"><span>Current Slot(s)</span> {{ $customer_summary["display_slot_count"] }}</div>
								</div>
								<div class="holder">
									<div class="color"></div>
									<div class="name"><span>Total Reward</span> {{ $wallet->display_total_earnings }}</div>
								</div>
							</div>
						</div>
					</div>
				</div>

				<div class="square-container">
					<div class="title">Wallet Per Slot</div>
					<div class="sub-container">
						<div class="table-holder">
							<div class="chart-legend">
								@foreach($_slot as $slot)
								<div class="holder">
									<div class="color" style="background-color: #019771"></div>
									<div class="name"><span>{{ $slot->slot_no }}</span> {{ $slot->display_current_wallet }}</div>
								</div>
								@endforeach
							</div>
						</div>
					</div>
				</div>

				<div class="square-container">
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

				<div class="square-container">
					<div class="title">Cash Reward Summary</div>
					<div class="sub-container">
						<div class="chart-legend">
							<div class="holder">
								<div class="color"></div>
								<div class="name"><span>Binary Pairing</span><span class="value">{{ isset($wallet->display_complan_binary) ? $wallet->display_complan_binary : 'PHP 0.00' }}</span></div>
							</div>
							<div class="holder">
								<div class="color"></div>
								<div class="name"><span>Direct Referral</span><span class="value">{{ $wallet->display_complan_direct }}</span></div>
							</div>
						</div>
					</div>
				</div>
			</div>

			<div class="col-md-6">

				<div class="square-container">
					<div class="title">Reward Points</div>
					<div class="sub-container">
						<div class="chart-legend" style="max-height: auto;">
							<div class="holder">
								<div class="color"></div>
								<div class="name"><span>5th Pair GC</span><span class="value">{{ $points->display_binary }}</span></div>
							</div>
						</div>
						<div class="chart-legend" style="max-height: auto;">
							<div class="holder">
								<div class="color"></div>
								<div class="name"><span>Direct GC</span><span class="value">{{ $points->display_direct }}</span></div>
							</div>
						</div>
						<div class="chart-legend" style="max-height: auto;">
							<div class="holder">
								<div class="color"></div>
								<div class="name"><span>Maintenance GC</span><span class="value">{{ $points->display_maintenance }}</span></div>
							</div>
						</div>
					</div>
				</div>	

				<div class="square-container">
					<div class="title">Binary Points</div>
					<div class="sub-container">
	                    <div class="table-responsive">
	                        <table style="margin-top: 5px;" class="table table-condensed">
	                            <thead style="text-transform: uppercase">
	                                <tr>
	                                    <th class="text-center">SLOT</th>
	                                    <th class="text-center">POINT (LEFT)</th>
	                                    <th class="text-center">POINT (RIGHT)</th>
	                                </tr>
	                            </thead>
	                            <tbody>
	                            	@foreach($_slot as $slot)
	                                <tr>
	                                    <td class="text-center">{{ $slot->slot_no }}</td>
	                                    <td class="text-center">{{ number_format($slot->slot_binary_left, 2) }}</td>
	                                    <td class="text-center">{{ number_format($slot->slot_binary_right, 2) }}</td>
	                                </tr>
	                                @endforeach
	                            </tbody>
	                        </table>
	                    </div>
					</div>
				</div>
			</div>
		</div>
		<div class="row clearfix">
			<div class="col-md-6">
				<div class="square-container">
					<div class="title">Newest Direct Referrals  <a href="javascript:" class="title-button" onclick="location.href='/members/direct'"><div>View All Direct</div></a></div>
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
									<div class="col-md-8">
										<div class="color">
											<img src="{{ $direct->profile_image }}">
										</div>
										<div class="text">
											<div class="pull-left">
												<div style="max-width: 250px;" class="name">{{ $direct->first_name }} {{ $direct->last_name }}</div>
												<div class="email">{{ $direct->slot_no }}</div>
												<div class="date">{{ $direct->time_ago }}</div>
											</div>
										</div>
									</div>
									<div class="col-md-4">
										<div class="action" style="text-align: center;">
											@if($direct->distributed == 1)
												<button onclick="action_load_link_to_modal('/members/slot-info?slot_no={{ Crypt::encrypt($direct->slot_id) }}&key={{ md5($direct->slot_id . $direct->slot_no) }}')" class="btn btn-default"><i class="fa fa-star"></i> VIEW INFO</button>
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
	                        <div><img src="/themes/{{ $shop_theme }}/img/brown-done-img.png"></div>
	                        <div class="text-header">Done!</div>
	                        <div class="text-caption">You are now officially enrolled to<br><b>Alpha Global Prestige</b></div>
	                    </div>
	                </div>
	            </div>
	        </div>
	    </div>
	</div>
@endif
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
});

</script>
@endsection
@section("member_css")
<link rel="stylesheet" type="text/css" href="/themes/{{ $shop_theme }}/css/member_dashboard.css">
<link rel="stylesheet" type="text/css" href="/themes/{{ $shop_theme }}/css/nonmember_dashboard.css">
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