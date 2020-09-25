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
			  			@foreach($_lead as $lead)

			  			@endforeach
			  		</tbody>
			  	</table>
		  	</div>
		  	<div class="clearfix">
			  	<div class="pull-right">
		
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