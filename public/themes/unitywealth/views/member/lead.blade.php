@extends("member.member_layout")
@section("member_content")
<div class="report-container" style="overflow: hidden;">
	<div class="report-header clearfix">
		<div class="left">
			<div class="icon">
				<img src="/themes/{{ $shop_theme }}/img/report-icon.png">
			</div>
			<div class="text">
				<div class="name">Lead List</div>
				<div class="sub">List of Leads under your account.</div>
			</div>
		</div>
	</div>
	
	<div class="report-content">
		<div class="holder">
		  	<div class="table-responsive">
		  		<table class="table">
			  		<thead>
			  			<tr>
			  				<th class="text-left" width="200px">CUSTOMER NAME</th>
			  				<th class="text-left" width="200px">EMAIL</th>
			  				<th class="text-left" width="200px">CONTACT</th>
			  				<th class="text-left">DATE JOINED</th>
			  				<th class="text-right">SLOT OWNED</th>
			  			</tr>
			  		</thead>
			  		<tbody>
			  			@if(count($_lead) > 0)
				  			@foreach($_lead as $lead)
				  			<tr>
				  				<td>{{ $lead->first_name }} {{ $lead->last_name }}</td>
				  				<td>{{ $lead->email }}</td>
				  				<td>{{ $lead->contact }}</td>
				  				<td>{!! $lead->date_created !!}</td>
				  				<td class="text-right">{{ $lead->slot_owned }}</td>
				  			</tr>
				  			@endforeach
			  			@endif
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