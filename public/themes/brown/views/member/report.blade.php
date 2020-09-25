@extends("member.member_layout")
@section("member_content")
<div class="report-container" style="overflow: hidden;">
	<div class="report-header clearfix">
		<div class="animated fadeInLeft left">
			<div class="icon">
				<img src="/themes/{{ $shop_theme }}/img/report-icon.png">
			</div>
			<div class="text">
				<div class="name">Reports</div>
				<div class="sub">All rewards logs are shown here. </div>
			</div>
		</div>
		<div class="animated fadeInRight right">
			<div class="search">
				<select class="form-control">
					<option>All Slots</option>
				</select>
			</div>
		</div>
	</div>
	
	<h3 class="animated slideInDown text-center">My Notifications</h3>
	
	<div class="report-content">
		<div class="animated fadeInUp holder">
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
	<hr>
	
	@if(count($_codes) > 0)
	<h3 class="text-center">Purchased Kits and Codes</h3>
	<div class="report-content">
		<div class="animated fadeInUp holder">
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
@endsection
@section("member_css")
<link rel="stylesheet" type="text/css" href="/themes/{{ $shop_theme }}/css/report.css">
@endsection