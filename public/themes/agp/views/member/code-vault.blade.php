@extends("member.member_layout")
@section("member_content")
<div class="report-container">
	<div class="report-header clearfix">
		<div class="left">
			<div class="icon">
				<img src="/themes/{{ $shop_theme }}/img/report-icon.png">
			</div>
			<div class="text">
				<div class="name">{{$page}}</div>
				<div class="sub">You can view your available packages here</div>
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
			  				<th class="text-center" width="300px"><b>TRANSACTION ID</b></th>
			  				<th class="text-center" width="300px"><b>ITEM NAME</b></th>
			  				<th class="text-center" width="300px"><b>PIN</b></th>
			  				<th class="text-center" width="300px"><b>ACTIVATION KEY</b></th>
			  			</tr>
			  		</thead>
			  		<tbody>
			  			@if(count($_codes) > 0)
				  			@foreach($_codes as $code)
				  			<tr pin="{{ $code->mlm_pin }}" activation="{{ $code->mlm_activation }}">
				  				<td class="text-center">
				  					<div><b>{{ $code->transaction_id }}</b></div>
				  				</td>
				  				<td class="text-center">
				  					<div><b>{{ $code->item_name }}</b></div>
				  				</td>
				  				<td class="text-center">
				  					<div>{{ $code->mlm_pin }}</div>
				  				</td>
				  				<td class="text-center">
				  					<div>{{ $code->mlm_activation }}</div>
				  				</td>
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
			  		{{ $_codes->render() }}
			  	</div>
		  	</div>
		</div>
	</div>
</div>
@endsection
@section("member_script")
<script type="text/javascript" src="/themes/{{ $shop_theme }}/js/usecode.js"></script>
@endsection
@section("member_css")
<link rel="stylesheet" type="text/css" href="/themes/{{ $shop_theme }}/css/report.css">
@endsection