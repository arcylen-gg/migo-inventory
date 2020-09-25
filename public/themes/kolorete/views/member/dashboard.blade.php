@extends("member.member_layout")
@section("member_content")

<input type="hidden" name="_mode" class="_mode" value="{{ $mode }}">
<input type="hidden" name="_token" class="_token" value="{{ csrf_token() }}">
@if(!$mlm_member)
	<div class="dashboard">
	    <!-- TOP DASHBOARD-->

	    <div class="dashboard-top">
	        <div class="row clearfix">
	            <div class="col-md-12">
	            	<div class="title">NON-MEMBER | <span>DASHBOARD</span> </div>
	                <div class="join-container" style="background-image: url('/themes/{{ $shop_theme }}/img/nonmember-bg.jpg');">
	                    <div class="caption-container">
	                        <div class="text-header1">Join Us Now!</div>
	                        <div class="text-header2">Lorem ipsum dolor sit amet, consectetuer adipiscing elit.<br>Aenean commodo ligula eget dolor.</div>
	                    </div>
	                    <div class="btn-container">
	                        <!-- <a href="#" id="btn-buy-a-kit"><button class="btn-buy-a-kit">BUY A KIT</button></a> -->
	                        <!-- <img src="/themes/{{ $shop_theme }}/img/or-1.png"><br> -->
	                        <!-- <span class="or">OR</span> -->
	                        <a href="#" id="btn-enter-a-code"><button onclick="action_load_link_to_modal('members/enter-code')" class="btn-enter-a-code">ENTER A CODE</button></a>
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
					<div class="title">Wallet Summary <a href="javascript:" onclick="action_load_link_to_modal('/members/enter-code')" class="title-button btn-enter-a-code"><div>Create New Slot</div></a></div>
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
								<div class="holder">
									<div class="color cs"></div>
									<div class="name"><span>Current Slot(s)</span> <div class="name cs-text"><a class="hand" onclick="action_load_link_to_modal('/members/slots')">{{ $customer_summary["display_slot_count"] }}</a></div></div>
								</div>
								<div class="holder">
									<div class="color tr"></div>
									<div class="name"><span>Total Reward</span> <div class="name tr-text">{{ $wallet->display_total_earnings }}</div></div>
								</div>
								<div class="holder">
									<div class="color tr"></div>
									<div class="name"><span>Total KROPS</span> <div class="name tr-text">0.00 KROP(S)</div></div>
								</div>
							</div>
						</div>
					</div>
				</div>

				{{-- <div class="square-container">
					<div class="title">Reward Summary</div>
					<div class="sub-container">
						<div class="chart-legend">
							<div class="holder">
								<div class="color bpr"></div>
								<div class="name"><span>Binary Pairing Reward</span> 
									<div class="name bpr-text">
										{{ isset($wallet->display_complan_binary) ? $wallet->display_complan_binary : 'PHP 0.00' }}
									</div>
								</div>
							</div>
						</div>
						<div class="chart-legend">
							<div class="holder">
								<div class="color rc"></div>
								<div class="name"><span>Repurchase Cashback</span> <div class="name rc-text">{{ $wallet->display_complan_repurchase_cashback }}</div></div>
							</div>
						</div>
					</div>
				</div> --}}
				
				<div class="square-containr">
					<div class="title"> Reward Points</div>
					<div class="sub-container">
						<div class="chart-legend" style="max-height: auto;">
							{{-- <div class="holder">
								<div class="color fifth-pgc"></div>
								<div class="name"><span>5th Pair GC</span> <div class="name fifth-pgc-text">{{ $points->display_binary }}</div></div>
							</div> --}}
							<div class="holder">
								<div class="color fifth-pgc"></div>
								<div class="name"><span>KROPS GC</span> <div class="name fifth-pgc-text">{{isset($krops_gc) ? currency('',$krops_gc)." POINT(S)" : '0.00 POINT(S)' }}</div></div>
							</div>
						</div>
					</div>
				</div>
			</div>


			{{-- <div class="col-md-6">

				<div class="square-container">
					<div class="title">Binary Points</div>
					<div class="sub-container" style="min-height: 421px; max-height: 421px; overflow-y: auto;">
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
			</div> --}}

			<div class="col-md-6">
				<div class="square-container">
					<div class="title">Reward Summary</div>
					<div class="sub-container">
						<div class="chart-legend">
							@foreach($_wallet_plan as $plan)
								<div class="holder">

									<div class="color"></div>

									<div class="name"><span>{{ $plan->label." Bonus" }}</span> <div class="name w2hpx">{{ $wallet->{ "display_" . $plan->string_plan } }}</div></div>

								</div>
							@endforeach
							@if(count($_wallet_plan)<1)
								<div class="holder">

									<div class="color"></div>

									<div class="name"><span>Unilevel Bonus</span> <div class="name w2hpx">PHP 0.00</div></div>

								</div>
								<div class="holder">

									<div class="color"></div>

									<div class="name"><span>Direct Pass Up Bonus</span> <div class="name w2hpx">PHP 0.00</div></div>

								</div>
							@endif
						</div>
					</div>
				</div>

				<div class="square-container">
					<div class="title">Enter Product Code</div>
					<div class="sub-container">
						<div class="chart-legend text-center">
							<button class="btn btn-kol-custom" onClick="action_load_link_to_modal('/members/slot-useproductcode', 'md')">Use Product Code</button>
						</div>
					</div>
				</div>

			</div>

			{{-- <div class="square-container col-md-6">
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
			</div> --}}

		</div>

		<div class="row clearfix">
			<div class="col-md-6">
				<div class="title">Newest Direct Referrals</div>
				<div class="sub-container border-holder mob-view">
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
											<button onclick="action_load_link_to_modal('/members/slot-info?slot_no={{ Crypt::encrypt($direct->slot_id) }}&key={{ md5($direct->slot_id . $direct->slot_no) }}')" class="btn btn-kol-custom"><i class="fa fa-star"></i> VIEW INFO</button>
										@else
											<button onclick="action_load_link_to_modal('/members/enter-placement?slot_no={{ Crypt::encrypt($direct->slot_id) }}&key={{ md5($direct->slot_id . $direct->slot_no) }}')" class="btn btn-danger-custom"><i class="fa fa-warning"></i> PLACE THIS SLOT</button>
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
								<div class="text-center" style="padding: 20px">You don't have any rewards yet.</div>
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
	                        <div class="text-caption">You are now officially enrolled to<br><b>Kolorete</b>.</div>
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

<script>
$(window).on('hashchange', function() 
{
    if (window.location.hash) {
        var page = window.location.hash.replace('#', '');
        if (page == Number.NaN || page <= 0) {
            return false;
        } else {
            getPosts(page);
        }
    }
});
$(document).ready(function() 
{
	getPosts(1);
    $(document).on('click', '.pagination a', function (e) 
    {
        getPosts($(this).attr('href').split('page=')[1]);
        e.preventDefault();
    });
});
function getPosts(page) 
{
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
<link rel="stylesheet" type="text/css" href="/themes/{{ $shop_theme }}/css/atomic_color.css">
<style type="text/css">

input:-webkit-autofill {
    -webkit-box-shadow: 0 0 0 30px white inset;
}

</style>
@endsection