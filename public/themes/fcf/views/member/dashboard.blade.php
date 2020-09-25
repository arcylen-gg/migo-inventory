@extends("member.member_layout")
@section("member_content")

<input type="hidden" name="_mode" class="_mode" value="{{ $mode }}">
<input type="hidden" name="_token" class="_token" value="{{ csrf_token() }}">
@if(!$mlm_member)
	<div class="dashboard">
	    <!-- TOP DASHBOARD-->
	    <div class="dashboard-top">
	        <div class="row clearfix">
	            <div class="col-md-8">
	                <div class="img-container">
	                    <img src="/themes/{{ $shop_theme }}/img/brown-img1.png">
	                </div>
	            </div>
	            <div class="col-md-4">
	                <div class="join-container">
	                    <div class="btn btn-text">
	                        <div class="text-header1">Join the Movement!</div>
	                        <div class="text-header2">Enroll now and become one of us!</div>
	                    </div>
	                    <div class="btn-container">
	                        <a href="#" id="btn-buy-a-kit"><button class="btn-buy-a-kit">Buy a Kit</button></a><br>
	                        <img src="/themes/{{ $shop_theme }}/img/or-1.png"><br>
	                        <a href="#" id="btn-enter-a-code"><button class="btn-enter-a-code">Enter a Code</button></a>
	                    </div>
	                </div>
	            </div>
	        </div>
	    </div>

	    <!-- BOTTOM DASHBOARD -->
	    <div class="dashboard-bottom">
	        <div class="text-header">Profile Information</div>
	        <div class="row clearfix">
	            <div class="col-md-4">
	                <div class="profile-info-container pic1 match-height">
	                    <div class="icon-container">
	                        <div class="col-md-2">
	                            <img src="/themes/{{ $shop_theme }}/img/brown-personal-info.png">
	                        </div>
	                        <div class="col-md-10">
	                            <div class="prof-info-text-header">Personal Information</div>
	                        </div>
	                        
	                    </div>
	                    <div class="personal-info-container">
	                        <div><label>Name </label><span>Lorem Ipsum Dolor</span></div>
	                        <div><label>Email </label><span>Lorem Ipsum Dolor</span></div>
	                        <div><label>Birthday </label><span>Lorem Ipsum Dolor</span></div>
	                        <div><label>Contact </label><span>Lorem Ipsum Dolor</span></div>
	                    </div>
	                </div>
	            </div>
	            <div class="col-md-4">
	                <div class="profile-info-container pic2 match-height">
	                    <div class="icon-container">
	                        <div class="col-md-2">
	                            <img src="/themes/{{ $shop_theme }}/img/brown-default-shipping.png">
	                        </div>
	                        <div class="col-md-10">
	                            <div class="prof-info-text-header">Default Shipping Address</div>
	                        </div>
	                    </div>
	                    <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Recusandae similique nulla amet illum labore nostrum sapiente fugiat, pariatur ipsa distinctio.</p>
	                </div>
	            </div>
	            <div class="col-md-4">
	                <div class="profile-info-container pic3 match-height">
	                    <div class="icon-container">
	                        <div class="col-md-2">
	                            <img src="/themes/{{ $shop_theme }}/img/brown-default-billing.png">
	                        </div>
	                        <div class="col-md-10">
	                            <div class="prof-info-text-header">Default Billing Address</div>
	                        </div>
	                    </div>
	                    <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Eos quibusdam nesciunt, dolor culpa architecto enim ratione error ipsum, animi sunt.</p>
	                </div>
	            </div>
	        </div>
	    </div>
	</div>
@else
	<div class="dashboard">
		<div class="row clearfix">
			<div class="col-md-6">
				<div class="title"><i class="align-icon brown-icon-bar-chart"></i> Wallet Summary</div>
				<div class="sub-container">
					<div class="table-holder">
						<div class="chart-legend">
							<div class="holder">
								<div class="color" style="background-color: #76b6ec"></div>
								<div class="name"><span>Current Wallet</span> {{ $wallet->display_current_wallet }}</div>
							</div>
							<div class="holder">
								<div class="color" style="background-color: #8E5EA2"></div>
								<div class="name"><span>Total Pay-out</span> {{ $wallet->display_total_payout }}</div>
							</div>
							<div class="chart-holder">
								<canvas id="income_summary" style="max-width: 150px;" width="400" height="400"></canvas>
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

						<table class="table hidden">
							<thead>
								<tr>
									<th width="33.3333333333%">Level</th>
									<th width="33.3333333333%">Count</th>
									<th width="33.3333333333%">Percentage</th>
								</tr>
							</thead>
							<tbody class="table-body">
								<tr style="background: linear-gradient(to right, rgb(220, 220, 220) 100%, rgb(237, 237, 237) 100%);">
									<td>1</td>
									<td>2/2</td>
									<td>100%</td>	
								</tr>
								<tr style="background: linear-gradient(to right, rgb(220, 220, 220) 100%, rgb(237, 237, 237) 100%);">
									<td>2</td>
									<td>4/4</td>
									<td>100%</td>	
								</tr>
								<tr style="background: linear-gradient(to right, rgb(220, 220, 220) 100%, rgb(237, 237, 237) 100%);">
									<td>4</td>
									<td>8/8</td>
									<td>100%</td>	
								</tr>
								<tr style="background: linear-gradient(to right, rgb(220, 220, 220) 100%, rgb(237, 237, 237) 100%);">
									<td>5</td>
									<td>16/16</td>
									<td>100%</td>	
								</tr>
								<tr style="background: linear-gradient(to right, rgb(220, 220, 220) 98.875%, rgb(237, 237, 237) 98.875%);">
									<td>6</td>
									<td>31/32</td>
									<td>98.875%</td>	
								</tr>
								<tr style="background: linear-gradient(to right, rgb(220, 220, 220) 40.625%, rgb(237, 237, 237) 40.625%);">
									<td>7</td>
									<td>26/64</td>
									<td>40.625%</td>	
								</tr>
								<tr style="background: linear-gradient(to right, rgb(220, 220, 220) 28.90625%, rgb(237, 237, 237) 28.90625%);">
									<td>8</td>
									<td>37/128</td>
									<td>28.90625%</td>	
								</tr>
								<tr style="background: linear-gradient(to right, rgb(220, 220, 220) 20.3125%, rgb(237, 237, 237) 20.3125%);">
									<td>9</td>
									<td>52/256</td>
									<td>20.3125%</td>	
								</tr>
								<tr style="background: linear-gradient(to right, rgb(220, 220, 220) 13.4765625%, rgb(237, 237, 237) 13.4765625%);">
									<td>10</td>
									<td>69/512</td>
									<td>13.4765625%</td>	
								</tr>
								<tr style="background: linear-gradient(to right, rgb(220, 220, 220) 4.1015625%, rgb(237, 237, 237) 4.1015625%);">
									<td>11</td>
									<td>42/1024</td>
									<td>4.1015625%</td>	
								</tr>
								<tr style="background: linear-gradient(to right, rgb(220, 220, 220) 1.953125%, rgb(237, 237, 237) 1.953125%);">
									<td>12</td>
									<td>40/2048</td>
									<td>1.953125%</td>	
								</tr>
							</tbody>
						</table>
					</div>
				</div>
			</div>

			<div class="col-md-6">
				<div class="title"><i class="fa fa-table"></i> Reward Summary</div>
				<div class="sub-container">
					<div class="chart-legend">
						<div class="holder">
							<div class="color"></div>
							<div class="name"><span>Pairing Reward</span> {{ $wallet->display_complan_triangle }}</div>
						</div>
						<div class="holder">
							<div class="color"></div>
							<div class="name"><span>Direct Referral Bonus</span> {{ $wallet->display_complan_direct }}</div>
						</div>
						<div class="holder">
							<div class="color"></div>
							<div class="name"><span>Builder Reward</span> {{ $wallet->display_complan_builder }}</div>
						</div>
						<div class="holder">
							<div class="color"></div>
							<div class="name"><span>Leader Reward</span> {{ $wallet->display_complan_leader }}</div>
						</div>
					</div>

				</div>

				<div class="title"><i class="align-icon brown-icon-gift"></i> Reward Points</div>
				<div class="sub-container">
					<div class="chart-legend" style="min-height: 117px; max-height: auto;">
						<div class="holder">
							<div class="color"></div>
							<div class="name"><span>Builder Point(s)</span> {{ $points->display_brown_builder_points }}</div>
						</div>
						<div class="holder">
							<div class="color"></div>
							<div class="name"><span>Reward Point(s)</span> {{ $points->display_brown_leader_points }}</div>
						</div>
					</div>
				</div>
			</div>
		</div>
		<div class="row clearfix">

			<div class="col-md-12">
				<div class="unilevel-holder">
					<div class="title"><i class="align-icon brown-icon-star"></i> My Slot(s) <a href="javascript:" class="title-button pull-right btn-enter-a-code">Add New Slot</a></div>
					<div class="sub-container">
						@foreach($_slot as $slot)
						<div class="holder">
							<div class="row clearfix">
								<div class="col-sm-4 text-center">
									<div class="label2">{{ $slot->slot_no }}</div>
									<div class="label3">{{ $slot->display_total_earnings }}</div>
									<div class="label3">{{ $slot->current_direct }} / {{ $slot->brown_next_rank_current }}</div>
								</div>
								<div class="col-sm-8 text-center" style="margin-bottom: 5px;">ROAD TO <b>{{ $slot->brown_next_rank }}</b></div>
								<div class="col-sm-4">
									@if($slot->brown_next_rank != "NO NEXT RANK")
										@if($slot->current_direct >= $slot->required_direct)
											<div class="progress2" style="background: linear-gradient(to right, rgb(220, 220, 220) 100%, rgb(237, 237, 237) 100%);">DIRECT <b>QUALIFIED</b></div>
										@else
											<div class="progress2" style="background: linear-gradient(to right, rgb(220, 220, 220) {{ $slot->brown_direct_rank_percentage }}%, rgb(237, 237, 237) {{ $slot->brown_direct_rank_percentage }}%);">DIRECT ({{ $slot->current_direct }}/{{ $slot->required_direct }})</div>
										@endif
									@else
									<div class="progress2" style="background: linear-gradient(to right, rgb(220, 220, 220) 100%, rgb(237, 237, 237) 40%);">NO MORE <b> NEXT RANK</b></div>
									@endif
								</div>
								<div class="col-sm-4">
									@if($slot->brown_next_rank != "NO NEXT RANK")
										@if($slot->brown_next_rank_current >= $slot->brown_next_rank_requirements)
											<div class="progress2" style="background: linear-gradient(to right, rgb(220, 220, 220) 100%, rgb(237, 237, 237) 100%);">GROUP <b>QUALIFIED</b></div>
										@else
											<div class="progress2" style="background: linear-gradient(to right, rgb(220, 220, 220) {{ $slot->brown_rank_rank_percentage }}%, rgb(237, 237, 237) {{ $slot->brown_rank_rank_percentage }}%);">GROUP ({{ $slot->brown_next_rank_current }}/{{ $slot->brown_next_rank_requirements }})</div>
										@endif
									@else
									<div class="progress2" style="background: linear-gradient(to right, rgb(220, 220, 220) 100%, rgb(237, 237, 237) 40%);">NO MORE <b> NEXT RANK</b></div>
									@endif
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
				<div class="title"><i class="align-icon brown-icon-globe"></i> Newest Enrollee(s) Sponsored</div>
				<div class="sub-container border-holder">
					<div class="clearfix wow hidden">
						<div class="badge right">6 New Members</div>
					</div>
					@foreach($_direct as $direct)
					<div class="holder">
						<div class="color">
							<img src="{{ $profile_image }}">
						</div>	
						<div class="text">
							<div class="pull-left">
								<div class="name">{{ $direct->first_name }} {{ $direct->last_name }}</div>
								<div class="email">{{ $direct->slot_no }}</div>
								<div class="date">{{ $direct->time_ago }}</div>
							</div>
						</div>
						<div class="action pull-right">
							@if($direct->distributed == 1)
								<button class="btn btn-default"><i class="fa fa-star"></i> VIEW INFO</button>
							@else
								<button class="btn btn-danger place_slot_btn" place_slot_id="{{$direct->slot_id}}"><i class="fa fa-warning"></i> PLACE THIS SLOT</button>
							@endif
						</div>
					</div>
					@endforeach
				</div>
			</div>
			<div class="col-md-6">
				<div class="match-height">
					<div class="title"><i class="align-icon brown-icon-money"></i> Recent Rewards <a href="javascript:" class="title-button pull-right" onclick="location.href='/members/report'">View All Rewards</a></div>
					<div class="sub-container">
						<div class="activities">
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
	                        <div class="text-caption">You are now officially enrolled to<br><b>Brown & Proud</b> movement</div>
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
        <div class="modal-sm modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                    <h4 class="modal-title"><i class="fa fa-star"></i> SPONSOR</h4>
                </div>
                <div class="modal-body">
                    <form method="post" class="submit-verify-sponsor">
                        <div class="labels">Enter <b>Nickname of Sponsor</b> or <b>Slot Number</b></div>
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
// var ctx = document.getElementById("income_summary").getContext('2d');

// // And for a doughnut chart
// var myDoughnutChart = new Chart(ctx, {
//     type: 'doughnut',
//     data: {
//         labels: ["Red", "Blue"],
//         datasets: [{
//             label: '# of Votes',
//             data: [1.00, 1.00],
//             backgroundColor: [
//                 'rgba(142, 94, 162, 1)',
//                 'rgba(62, 149, 205, 1)'
//             ],
//             borderColor: [
//                 'rgba(142, 94, 162, 1)',
//                 'rgba(62, 149, 205, 1)'
//             ],
//             borderWidth: 1
//         }]
//     },
//     options: 
//     {
//       legend: 
//       {
//         responsive: true,
//         display: false,
//       },
//       tooltips: 
//       {
//         callbacks: 
//         {
//           label: function(tooltipItems, data) 
//           {
//             var sum = data.datasets[0].data.reduce(add, 0);
//             function add(a, b) {
//               return a + b;
//             }

//             return data.datasets[0].data[tooltipItems.index] + ' %';
//           },
//           // beforeLabel: function(tooltipItems, data) {
//           //   return data.datasets[0].data[tooltipItems.index] + ' hrs';
//           // }
//         }
//       }
//     }
// });

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