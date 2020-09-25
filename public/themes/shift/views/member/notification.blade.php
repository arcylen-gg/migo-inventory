@extends("member.member_layout")
@section("member_content")
<div class="notification-container">
	<div class="notification-header clearfix">
		<div class="left">
			<div class="icon">
				<div class="brown-icon-bell-o"></div>
			</div>
			<div class="text">
				<div class="name">Rewards</div>
				<div class="sub">All rewards notification are shown here. </div>
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
	<div class="notification-content">
		<div class="table table-responsive table-condensed">
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
	</div>
</div>
@endsection
@section("member_script")
@endsection
@section("member_css")
<link rel="stylesheet" type="text/css" href="/themes/{{ $shop_theme }}/css/notification.css">
@endsection