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
			  				<th class="text-left" width="200px">DATE JOINED</th>
			  				<th class="text-right" width="100px">NAME</th>
			  			</tr>
			  		</thead>
			  		<tbody>
			  			@if(count($_direct) > 0)
				  			@foreach($_direct as $direct)
				  			<tr>
				  				<td class="text-left">
				  					<div><b>{{ $direct->display_date }}</b></div>
				  					<div>{{ $direct->time_ago }}</div>
				  				</td>
				  				<td class="text-right">
				  					<div>{{ $direct->first_name }} {{ $direct->last_name }}</div>
				  				</td>
				  			</tr>
				  			@endforeach
				  		@else
				  			<tr class="text-center" >
				  				<td colspan="4">NO DIRECT YET</td>
				  			</tr>
			  			@endif
			  		</tbody>
			  	</table>
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