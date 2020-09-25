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
	                        <div class="text-header2">Opportunity awaits you. Lorem ipsum dolor sit amet, consectetuer adipiscing elit.<br>Aenean commodo ligula eget dolor.</div>
	                    </div>
	                    <div class="btn-container">
	                        <!-- <a href="#" id="btn-buy-a-kit"><button class="btn-buy-a-kit">BUY A KIT</button></a> -->
	                        <!-- <img src="/themes/{{ $shop_theme }}/img/or-1.png"><br> -->
	                        <!-- <span class="or">OR</span> -->
	                        <a href="#" id="btn-enter-a-code"><button class="btn-enter-a-code">ENTER A CODE</button></a>
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
					<div class="title">Wallet Summary <a href="javascript:" class="title-button btn-enter-a-code"><div>Create New Slot</div></a></div>
					<div class="sub-container">
						<div class="table-holder">
							<div class="chart-legend">
								<div class="holder">
									<div class="color" style="background-color: #0D91E3"></div>
									<div class="name"><span>Current Wallet</span> <div class="name w2hpx">{{ $wallet->display_current_wallet }}</div></div>
								</div>
								<div class="holder">
									<div class="color" style="background-color: #8E5EA2"></div>
									<div class="name"><span>Total Pay-out</span> <div class="name w2hpx">{{ $wallet->display_total_payout }}</div></div>
								</div>
								<div class="chart-holder">
									<canvas id="income_summary" class="chart-income" wallet="{{ $wallet->current_wallet }}"  payout="{{ $wallet->total_payout }}" style="max-width: 150px;" width="400" height="400"></canvas>
								</div>
								<div class="holder">
									<div class="color"></div>
									<div class="name"><span>Current Slot(s)</span> <div class="name w2hpx">{{ $customer_summary["display_slot_count"] }}</div></div>
								</div>
								<div class="holder">
									<div class="color"></div>
									<div class="name"><span>Total Reward</span> <div class="name w2hpx">{{ $wallet->display_total_earnings }}</div></div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>

			<div class="col-md-6">

				<div class="square-container">
					<div class="title">Reward Summary</div>
					<div class="sub-container">
						<div class="chart-legend">
							<div class="holder">
								<div class="color"></div>
								<div class="name"><span>Advertisement Bonus</span> <div class="name w2hpx">{{ $wallet->display_complan_advertisement_bonus }}</div></div>
							</div>
							<div class="holder">
								<div class="color"></div>
								<div class="name"><span>Direct</span> <div class="name w2hpx">{{ $wallet->display_complan_direct }}</div></div>
							</div>
							<div class="holder">
								<div class="color"></div>
								<div class="name"><span>Leadership Advertisement Bonus</span> <div class="name w2hpx">{{ $wallet->display_complan_leadership_advertisement_bonus }}</div></div>
							</div>
							<div class="holder">
								<div class="color"></div>
								<div class="name"><span>Rebates Bonus</span> <div class="name w2hpx">{{ $wallet->display_complan_stairstep }}</div></div>
							</div>
						</div>
					</div>
				</div>

				<div class="square-container">
					<div class="title">Reward Points</div>
					<div class="sub-container">
						<div class="chart-legend" style="max-height: auto;">
							<div class="holder">
								<div class="color"></div>
								<div class="name"><span>5th Pair GC</span> <div class="name w2hpx">{{ $points->display_leadership_advertisement_bonus}}</div></div>
							</div>
							<div class="holder">
								<div class="color"></div>
								<div class="name"><span>Advertisement GC</span> <div class="name w2hpx">{{ $points->display_advertisement_bonus}}</div></div>
							</div>
							<div class="holder">
								<div class="color"></div>
								<div class="name"><span>Rank PV</span> <div class="name w2hpx">{{ $points->display_rank_pv }}</div></div>
							</div>
							<div class="holder">
								<div class="color"></div>
								<div class="name"><span>Rebates Points</span> <div class="name w2hpx">{{ $points->display_stairstep_gpv }}</div></div>
							</div>
							<div class="holder">
								<div class="color"></div>
								<div class="name"><span>Reward Points Redemption</span> <div class="name">{{ currency('',$reward_point_redemption) }} POINT(S)</div></div>
							</div>
							<div class="holder">
								<div class="color"></div>
								<div class="name"><span>Remaining Reward Points</span> <div class="name">{{ currency('',$points->leadership_advertisement_bonus+$points->advertisement_bonus+$reward_point_redemption) }} POINT(S)</div></div>
							</div>
						</div>
					</div>
				</div>
				
				<div class="square-container">
					<div class="title">Enter Product Code</div>
					<div class="sub-container">
						<div class="chart-legend text-center">
							<button class="btn btn-shift-use-pcode" onclick="action_load_link_to_modal('/members/slot-useproductcode', 'md')">Use Product Code</button>
						</div>
					</div>
				</div>

			</div>
		</div>

		<div class="row clearfix">
			<div class="col-md-6">
					
				<div class="square-container">
					<div class="title">Newest Direct Referrals</div>
					<div class="sub-container border-holder">
						<div class="clearfix wow hidden">
							<div class="badge right">6 New Members</div>
						</div>
						<div class="load-direct-referrals-here">
							
						</div>
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
	                        <div><img src="/themes/{{ $shop_theme }}/img/done-img.png"></div>
	                        <div class="text-header">Done!</div>
	                        <div class="text-caption">You are now officially enrolled to SHIFT.</div>
	                    </div>
	                </div>
	            </div>
	        </div>
	    </div>
	</div>
@endif

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

<script>
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
</script>

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

	// $(".place_slot_btn").click(function()
	// {
	// 	$(".message-return-slot-placement-verify").empty();
	// 	$(".chosen_slot_id").val($(this).attr("place_slot_id"));
	// 	$("#slot-placement-modal").modal("show");
	// });

	$("body").on('click','.place_slot_btn',function()
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
  /*background-color: #EEEEEE;*/
  font-family: "Arimo", sans-serif; }
  .popup-verify-placement .modal-sm {
    width: 100%;
    max-width: 500px; }
  .popup-verify-placement .modal-content {
    /*background-color: #eee;*/ }
    .popup-verify-placement .modal-content .modal-header {
      background-color: #191919;
      /*border-top-left-radius: 3px;
      border-top-right-radius: 3px; */}
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
        border: 1px solid #191919;
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
        color: #191919;
        text-align: center;
        text-transform: uppercase; }
      .popup-verify-placement .modal-content .modal-body select {
        width: 100%;
        font-size: 16px;
        border: none;
        border: 1px solid #191919;
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
          color: #191919;
          background-color: #fff;
          padding: 10px 40px;
          border: 2px solid #191919;
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
          background-color: #191919;
          opacity: 1.0; }
</style>
@endsection