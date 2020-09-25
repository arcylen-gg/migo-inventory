@extends("member.member_layout")
@section("member_content")
<div class="report-container">
	<div class="report-header clearfix">
		<div class="left">
			<div class="icon">
				<img src="/themes/{{ $shop_theme }}/img/report-icon.png">
			</div>
			<div class="text">
				<div class="name">Reports</div>
				<div class="sub">All rewards logs are shown here. </div>
			</div>
		</div>
		<div class="right">
			<div class="search">
				<select class="form-control">
					<option>All Slots</option>
				</select>
			</div>
		</div>
	</div>
	<div class="report-content">
		<div class="holder">
		  	<div class="table-responsive">
		  		<table class="table">
			  		<thead>
			  			<tr>
			  				<th class="text-left" width="200px">SLOT</th>
			  				<th class="text-left">DETAILS</th>
			  				<th class="text-right">AMOUNT</th>
			  			</tr>
			  		</thead>
			  		<tbody>
			  			@foreach($_rewards as $reward)
			  			<tr>
			  				<td class="text-left">
			  					<div>{{ $reward->slot_no }}</div>
			  				</td>
			  				<td class="text-left">{!! $reward->log !!}</td>
			  				<td class="text-right"><a href="javascript:"><b>{!! $reward->display_wallet_log_amount !!}</b></a></td>
			  			</tr>
			  			@endforeach
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
</div>
@endsection
@section("member_script")
@endsection
@section("member_css")
<link rel="stylesheet" type="text/css" href="/themes/{{ $shop_theme }}/css/report.css">
@endsection