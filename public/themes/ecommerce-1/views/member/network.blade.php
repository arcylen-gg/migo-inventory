@extends("member.member_layout")
@section("member_content")
<div class="report-container">
	<div class="report-header clearfix">
		<div class="left">
			<div class="icon">
				<img src="/themes/{{ $shop_theme }}/img/report-icon.png">
			</div>
			<div class="text">
				<div class="name">Network List</div>
				<div class="sub">List of network on your <b>SOLID TREE</b></div>
			</div>
		</div>
		<div class="right">
			<div class="search">
				<select class="form-control">
					<option>MYPHONE0001</option>
				</select>
			</div>
		</div>
	</div>
	<div class="network-content" style="margin-top: 40px;">
		<div class="holder">
		  	<table class="table">
		  		<thead>
		  			<tr>
		  				<th class="text-left" width="200px">LEVEL</th>
		  				<th class="text-left" width="200px">SLOT</th>
		  				<th class="text-left">NAME</th>
		  				<th class="text-right">DATE JOINED</th>
		  			</tr>
		  		</thead>
		  		<tbody>
		  			@foreach($_tree as $tree)
		  			<tr>
		  				<td class="text-left">{!! $tree->ordinal_level !!}</td>
		  				<td class="text-left">
		  					<div>{{ $tree->slot_no }}</div>
		  				</td>
		  				<td class="text-left">{!! $tree->first_name !!} {!! $tree->last_name !!}</td>
		  				<td class="text-right"><a href="javascript:"><b>{!! $tree->display_slot_date_created !!}</b></a></td>
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
<link rel="stylesheet" type="text/css" href="/themes/{{ $shop_theme }}/css/report.css">
@endsection