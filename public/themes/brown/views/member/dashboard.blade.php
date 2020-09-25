@extends("member.member_layout")
@section("member_content")

@if($customer->customer_payout_method == "unset")
	@if($mlm_member)
	<div class="top-message-warning for-payout" onclick="action_load_link_to_modal('/members/payout-setting', 'lg')">
		<div class="message-warning text-center"><b>Warning!</b> You won't be receiving your payout until you setup your <b>payout details</b>. Click here to set it up right away. </div>
	</div>
	@endif
@endif

<input type="hidden" name="_mode" class="_mode" value="{{ $mode }}">
<input type="hidden" name="_token" class="_token" value="{{ csrf_token() }}">
<input type="hidden" name="code" class="check_unused_code" value="{{ $check_unused_code or 0 }}">
<input type="hidden" name="not_placed_yet" class="not_placed_yet" value="{{ $not_placed_yet or 0 }}" link="/members/enter-placement?slot_no={{ Crypt::encrypt($not_placed_slot->slot_id) }}&key={{ md5($not_placed_slot->slot_id . $not_placed_slot->slot_no) }}">
@if(!$mlm_member)
	<div class="dashboard" style="overflow: hidden;">
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
			                    	<img src="/themes/{{ $shop_theme }}/assets/mobile/img/trophy.png">
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
			        <div class="animated fadeInLeft col-md-8">
			        	<!-- <video controls=""> -->
							<div class="embed-responsive embed-responsive-16by9 animated zoomInDown" style="margin-top: 25px;">
							  <!-- <div class="overlay"></div> -->
					        	<iframe class="embed-responsive-item" src="https://www.youtube.com/embed/I7kIfi2RlcE?autoplay=1&showinfo=0&controls=0&loop=1&disablekb=1&modestbranding=1&playlist=DglLgQYkQX4&mute=0"></iframe>
							</div>
						<!-- </video> -->
			        </div>
		            <div class="animated fadeInRight col-md-4">
		                <div class="join-container">
		                    <div class="btn btn-text">
		                        <div class="text-header1">Join the Movement!</div>
		                        <div class="text-header2">Enroll now and become one of us!</div>
		                    </div>
		                    <div class="btn-container">
		                        <!-- <button class="product-add-cart btn-buy-a-kit" item-id="{{$item_kit_id or '54'}}" quantity="1">Enroll Now</button><br> -->
		                        <button class="btn-buy-a-kit popup" link="/members/kit" size="lg">Enroll Now</button><br>
		                        <img src="/themes/{{ $shop_theme }}/img/or-1.png"><br>
		                        <a href="#" id="btn-enter-a-code"><button class="btn-enter-a-code">Enter a Code</button></a>
		                    </div>
		                </div>
		            </div>
		        </div>
		    </div>
		    <!-- BOTTOM DASHBOARD -->
			<div class="dash-bot row clearfix">
				<div class="wow zoomIn col-md-3">
					<img src="/themes/{{ $shop_theme }}/img/nonmember-ad.jpg" style="width: 100%;">
				</div>
				<div class="col-md-9">
					<h1 class="wow fadeInDown">The Brown Phone</h1>
					<p class="wow fadeInRight">The Brown phone is your portal to a new world full of creativity and opportunities, bringing you closer to artists and entrepreneurs, while keeping you updated on the latest news, hottest trends, and innovative products and services, making your life better and more inspiring.</p>
					<h2 class="wow fadeInDown" >With Premium Content and Rewards App</h2>
					<p class="wow fadeInRight"><i class="fa fa-circle" aria-hidden="true"></i>With Brown App and Portal</p>
					<p class="wow fadeInRight"><i class="fa fa-circle" aria-hidden="true"></i>Agila Rewards Ready</p>
				</div>
			</div>
		@endif
	</div>
@else
	<div class="dashboard" style="overflow: hidden;">
		<!-- WALLET SUMMARY -->
		<div class="row clearfix">
			<div class="col-md-3">
				<div class="animated fadeInRight per-summary-container box1 row clearfix">
					<div class="col-md-4">
						<div class="icon-container">
							<img src="/themes/{{ $shop_theme }}/img/wallet-icon.png">
						</div>
					</div>
					<div class="col-md-8">
						<div class="detail-container">
							<h2>{{ $wallet->display_current_wallet }}</h2>
							<h3>Current Wallet</h3>
						</div>
					</div>
				</div>
			</div>
			<div class="col-md-3">
				<div class="animated fadeInRight per-summary-container box2 row clearfix">
					<div class="col-md-4">
						<div class="icon-container">
							<img src="/themes/{{ $shop_theme }}/img/total-payout.png">
						</div>
					</div>
					<div class="col-md-8">
						<div class="detail-container">
							<h2>{{ $wallet->display_total_payout }}</h2>
							<h3>Total Pay-out</h3>
						</div>
					</div>
				</div>
			</div>
			<div class="col-md-3">
				<div class="animated fadeInRight per-summary-container box3 row clearfix">
					<div class="col-md-4">
						<div class="icon-container">
							<img src="/themes/{{ $shop_theme }}/img/current-slots.png">
						</div>
					</div>
					<div class="col-md-8">
						<div class="detail-container">
							<h2>{{ $customer_summary["display_slot_count"] }}</h2>
							<h3>Current Slot(s)</h3>
						</div>
					</div>
				</div>
			</div>
			<div class="col-md-3">
				<div class="animated fadeInRight per-summary-container box4 row clearfix">
					<div class="col-md-4">
						<div class="icon-container">
							<img src="/themes/{{ $shop_theme }}/img/total-rewards.png">
						</div>
					</div>
					<div class="col-md-8">
						<div class="detail-container">
							<!-- <h1>PHP</h1> -->
							<h2>{{ $wallet->display_total_earnings }}</h2>
							<h3>Total Reward</h3>
						</div>
					</div>
				</div>
			</div>
		</div>

		<div class="row clearfix">
			<div class="animated fadeInUp col-md-6">
				<div class="title"><i class="fa fa-table"></i> Reward Summary
					<a href="javascript:" class="title-button pull-right" onclick="action_load_link_to_modal('members/enter-code')">Create New Slot</a>
				</div>
				<div class="sub-container" style="padding-bottom: 46px !important;">
					<div class="chart-legend">
						<div class="holder">
							<div class="color"></div>
							<div class="name"><span>Pairing Reward</span><span class="value">{{ $wallet->display_complan_triangle }}</span></div>
						</div>
						<div class="holder">
							<div class="color"></div>
							<div class="name"><span>Direct Referral Bonus</span><span class="value">{{ $wallet->display_complan_direct }}</span></div>
						</div>
						<div class="holder">
							<div class="color"></div>
							<div class="name"><span>Builder Reward</span><span class="value">{{ $wallet->display_complan_builder }}</span></div>
						</div>
						<div class="holder">
							<div class="color"></div>
							<div class="name"><span>Leader Reward</span><span class="value">{{ $wallet->display_complan_leader }}</span></div>
						</div>
					</div>	
				</div>
				<div class="title"><i class="align-icon brown-icon-gift"></i> Reward Points</div>
				<div class="sub-container">
					<div class="chart-legend" style="min-height: 117px; max-height: auto;">
						<div class="holder">
							<div class="color"></div>
							<div class="name"><span>Builder Point(s)</span><span class="value">{{ $points->display_brown_builder_points }}</span></div>
						</div>
						<div class="holder">
							<div class="color"></div>
							<div class="name"><span>Leader Point(s)</span><span class="value">{{ $points->display_brown_leader_points }}</span></div>
						</div>
					</div>
				</div>


			</div>
			<div class="animated fadeInUp col-md-6">
				<div class="title"><i class="align-icon fa fa-newspaper-o"></i> Upcoming Events</div>
				<div class="sub-container">
					<div class="chart-legend" style="min-height: 310px; max-height: auto;">
						<div class="events-container">
							<div class="event-list">
								@if(isset($_event))
									@if(count($_event) > 0)
										@foreach($_event as $event)
										<div class="event clearfix">
											<div onclick="action_load_link_to_modal('/members/event-details?id={{$event->event_id}}', 'lg')" style="background-image: url('{{$event->event_thumbnail_image}}'); background-size: cover; background-repeat: no-repeat;" class="box overlay black">
												<div class="date">
													<div class="day">{{date('d', strtotime($event->event_date))}}</div>
													<div class="month">{{date('F', strtotime($event->event_date))}}</div>
												</div>
											</div>
											<div class="detail">
												<div class="titles">{{$event->event_title}}</div>
												<div class="description">{{$event->event_sub_title}}</div>
												<div class="action">
													<a style="cursor: pointer;" class="popup" size="lg" link="/members/event-details?id={{$event->event_id}}"><i class="fa fa-check-circle"></i> Details</a>
													@if($event->is_reserved == 0) 
													<a style="cursor: pointer;" class="popup" size="md" link="/members/event-reserve?id={{$event->event_id}}"><i class="fa fa-calendar-check-o"></i> Reserve a Seat</a>
													@else
													<a href="javascript:"><i class="fa fa-calendar-check-o"></i> Reserved</a>
													@endif
												</div>
											</div>
										</div>
										@endforeach
									@else
									<div class="event clearfix text-center">
										<div style="padding: 100px 30px;">NO EVENT POSTED YET</div>
									</div>
									@endif
								@endif
							</div>
						</div>
					</div>
				</div> 
				
			</div>
		</div>

		<div class="row clearfix">
			<div class="animated fadeInUp col-md-12">
				<div class="unilevel-holder">
					<div class="title"><i class="align-icon brown-icon-star"></i> My Slot(s) </div>
					<div class="sub-container">
						@foreach($_slot as $slot)
						<div class="holder">
							<div class="row clearfix">
								<div class="col-sm-4 text-center">
									<div class="label2">{{ $slot->slot_no }}</div>
									<div class="label3"> <a href="javascript:" onclick="action_load_link_to_modal('/members/lead?slot_no={{ $slot->slot_no }}')"> VIEW LEAD LINK</a></b></div>
									<div class="label3">{{ $slot->display_total_earnings }}</div>
									{{-- $slot->current_direct }} / {{ $slot->brown_next_rank_current --}}
								</div>
								<div class="col-sm-8 text-center" style="margin-bottom: 5px;">ROAD TO <b>{{ $slot->brown_next_rank }}</div>
							
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
			<div class="animated fadeInUp col-md-12">
				<div class="unilevel-holder">
					<div class="title"><i class="fa fa-credit-card" aria-hidden="true"></i> Repurchase</div>
					<div class="sub-container">
						<div class="dashboard-top">
				            <div class="join-container" style="border: 0; max-height: none; min-height: auto;">
				                <div class="btn-container" style="padding-top: 0;">
				                    <button class=" btn-buy-a-kit popup" link="/members/kit" size="lg" item-id="{{$item_kit_id or '54'}}" quantity="1">Buy a Kit</button><br>
				                </div>
				            </div>	    
					    </div>
					</div>
				</div>
			</div>
		</div>

		<div class="row clearfix">
			<div class="animated fadeInUp col-md-6">
				<div class="title"><i class="align-icon brown-icon-globe"></i> Newest Enrollee(s) Sponsored</div>
				<div class="sub-container border-holder">
					<div class="clearfix wow hidden">
						<div class="badge right">6 New Members</div>
					</div>
					{{-- <div class="load-direct-referrals-here">
												
					</div> --}}
					@if(count($_direct) > 0)
						@foreach($_direct as $direct)
						<div class="holder">
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
							<div class="action pull-right">
								@if($direct->distributed == 1)
									<button onclick="action_load_link_to_modal('/members/slot-info?slot_no={{ Crypt::encrypt($direct->slot_id) }}&key={{ md5($direct->slot_id . $direct->slot_no) }}')" class="btn btn-brown"><i class="fa fa-star"></i> VIEW INFO</button>
								@else
									<button onclick="action_load_link_to_modal('/members/enter-placement?slot_no={{ Crypt::encrypt($direct->slot_id) }}&key={{ md5($direct->slot_id . $direct->slot_no) }}')" class="btn btn-custom-danger"><i class="fa fa-warning"></i> PLACE THIS SLOT</button>
								@endif
							</div>
						</div>
						@endforeach
					@else
						<div class="text-center" style="padding: 20px">You don't have any direct referral yet.</div>
					@endif
				</div>
			</div>
			<div class="animated fadeInUp col-md-6">
				<div class="match-height">
					<div class="title"><i class="align-icon brown-icon-money"></i> Recent Rewards <a href="javascript:" class="title-button pull-right" onclick="location.href='/members/report'">View All Rewards</a></div>
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

		<!-- Popup Academy -->
	    <div class="popup-academy">
	        <div id="academy-modal" class="modal academy-modal fade">
	            <div class="modal-lg modal-dialog">
	                <div class="modal-content">
	                    
	                    <div class="modal-header">
	                    	<div class="container">
								<div class="row clearfix">
							        <div class="col-md-4">
							        	<div class="logo-container">
							        		Logo Here
							        	</div>
							        </div>

							        <div class="cold-md-8">
							        	<div class="header-container">
							        		<h2>BROWN&PROUD ACADEMY</h2>
							        		<h3>MATERCLASS IN CREATIVE ENTREPRENEURSHIP</h3>
							        	</div>
							        </div>
								</div>
							</div>
					    </div>

						<div class="modal-body">
							
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

@endsection

@section("member_script")
<script type="text/javascript" src="/themes/{{ $shop_theme }}/js/non_member.js?version=2.2"></script>
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
	
	var exist = document.getElementById("income_summary");
	
	if (exist != null) 
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
</script>
@endsection
@section("member_css")
<link rel="stylesheet" type="text/css" href="/themes/{{ $shop_theme }}/css/member_dashboard.css?v=2.2">
<link rel="stylesheet" type="text/css" href="/themes/{{ $shop_theme }}/css/nonmember_dashboard.css?v=2.2">


<style type="text/css">

input:-webkit-autofill
{
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
      background-color: #402A21;
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
        border: 1px solid #402A21;
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
        color: #402A21;
        text-align: center;
        text-transform: uppercase; }
      .popup-verify-placement .modal-content .modal-body select {
        width: 100%;
        font-size: 16px;
        border: none;
        border: 1px solid #402A21;
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
          color: #402A21;
          background-color: #fff;
          padding: 10px 40px;
          border: 2px solid #402A21;
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
          background-color: #402A21;
          opacity: 1.0; }
</style>
@endsection