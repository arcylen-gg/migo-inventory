@extends("member.member_layout")

@section("member_content")
	<div class="report-container" style="overflow: hidden;">
		<div class="report-header clearfix">
			<div class="left">
				<div class="icon">
					<img src="/themes/{{ $shop_theme }}/img/report-icon.png">
				</div>
			<div class="text">
					<div class="name">Reports</div>
					<div class="sub">All reward logs are shown here. </div>
				</div>
			</div>
			<!-- <div class="animated fadeInRight right">
				<div class="search">
					<select class="form-control">
						<option>All Slots</option>
					</select>
				</div>
			</div> -->
		</div>
		
		@if(count($_codes) > 0)
		<h3 class="text-center">My Notifications</h3>
	    @endif
	    <div class="text-center" style="padding:25px;"></div>
		<div class="col-md-3" style="bottom: 20px;">
			<select class="form-control sort_by_log">
				<option value="0">All</option>
				<option {{Request::input("sort_by") == "direct" ? "selected" : ""}} value="direct">Wholesale Commission</option>
				<option {{Request::input("sort_by") == "rank_repurchase_cashback" ? "selected" : ""}} value="rank_repurchase_cashback">Personal Rebates</option>
				<option {{Request::input("sort_by") == "stairstep" ? "selected" : ""}} value="stairstep">Performance Commission</option>
			</select>
		</div>
		<div class="report-content">
	        <div class="holder">
	          	<div class="table-responsive">
	          		<table class="table">
	        	  		<thead>
	        	  			<tr>
	        	  				<th class="text-center" width="200px">DATE</th>
	        	  				<!-- <th class="text-center" width="100px">SLOT</th> -->
	        	  				<th class="text-left">DETAILS</th>
	        	  				<th class="text-right" width="200px">AMOUNT</th>
	        	  			</tr>
	        	  		</thead>
	        	  		<tbody>
	        	  			@if(count($_rewards) > 0)
	        		  			@foreach($_rewards as $reward)
	        		  			<tr>
	        		  				<td class="text-center">
	        		  					<div><b>{{ $reward->display_date }}</b></div>
	        		  					<div>{{ $reward->time_ago }}</div>
	        		  				</td>
	     <!--    		  				<td class="text-center">
	        		  					<div>{{ $reward->slot_no }}</div>
	        		  				</td> -->
	        		  				<td class="text-left">{!! $reward->log !!}</td>
	        		  				<td class="text-right"><b>{!! $reward->display_wallet_log_amount !!}</b></td>
	        		  			</tr>
	        		  			@endforeach
	        		  		@else
	        		  			<tr class="text-center" >
	        		  				<td colspan="4">NO REWARD YET</td>
	        		  			</tr>
	        	  			@endif
	        	  		</tbody>
	        	  	</table>
	          	</div>
	          	<div class="clearfix">
	        	  	<div class="pull-right">
	        	  		{!! session('notification_paginate') !!}
	        	  	</div>
	          	</div>
	        </div>
		</div>
		
		<hr>
		
		@if(count($_codes) > 0)
		<h3 class="text-center">Purchased Kits and Codes</h3>
		<div class="report-content">
			<div class="holder">
			  	<div class="table-responsive">
			  		<table class="table">
				  		<thead>
				  			<tr>
				  				<th class="text-left" width="200px">PIN</th>
				  				<th class="text-left" width="200px">ACTIVATION</th>
				  				<th class="text-center">STATUS</th>
				  				<th class="text-center">USED BY</th>
				  				<th></th>
				  			</tr>
				  		</thead>
				  		<tbody>
				  			@foreach($_codes as $code)
				  			<tr>
				  				<td class="text-left">{{ $code->mlm_pin }}</td>
				  				<td class="text-left">{{ $code->mlm_activation }}</td>
				  				<td class="text-center">{{ $code->item_in_use }}</td>
				  				<td class="text-center">{{ $code->used_by }}</td>
				  			</tr>
				  			@endforeach
				  		</tbody>
				  	</table>
			  	</div>
			</div>
		</div>
		@endif
	</div>

@endsection

@section("member_script")
<script type="text/javascript">
	var getUrlParameter = function getUrlParameter(sParam) 
	{
	    var sPageURL = decodeURIComponent(window.location.search.substring(1)),
	        sURLVariables = sPageURL.split('&'),
	        sParameterName,
	        i;

	    for (i = 0; i < sURLVariables.length; i++) {
	        sParameterName = sURLVariables[i].split('=');

	        if (sParameterName[0] === sParam) {
	            return sParameterName[1] === undefined ? true : sParameterName[1];
	        }
	    }
	};


	$(".sort_by_log").change(function()
	{
		var pagination = getUrlParameter('page');
		var sort_by    = $(this).val();
		// var sort_by    = getUrlParameter('sort_by');
		var string     = "";
		// if(pagination)
		// {
		// 	string  = "?page="+pagination;
		// 	string  = string + "&sort_by="+sort_by;
		// }
		// else
		// {
			string  = "?sort_by="+sort_by;
		// }

	    var url = 'http://' + window.location.hostname + window.location.pathname+string;
	    window.location.href = url;
	});
</script>
@endsection

@section("member_css")
<link rel="stylesheet" type="text/css" href="/themes/{{ $shop_theme }}/css/report.css">
@endsection