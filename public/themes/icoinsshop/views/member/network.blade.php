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
				<div class="sub">List of network on your <b>SPONSOR TREE</b></div>
			</div>
		</div>
		<div class="right">
			<div class="search">
				<select class="form-control select-slot">
					@foreach($_slot as $slot)
					<option value="{{ $slot->slot_no }}" {{ $slot->slot_no == request('slot_no') ? 'selected' : '' }}>{{ $slot->slot_no }}</option>
					@endforeach
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
		  				<th class="text-right">SLOT COUNT</th>
		  			</tr>
		  		</thead>
		  		<tbody>
		  			@if(count($_tree_level) > 0)
			  			@foreach($_tree_level as $tree)
			  			<tr>
			  				<td class="text-left">{!! $tree->ordinal_level !!}</td>
			  				<td class="text-right"><a onclick="action_load_link_to_modal('/members/network-slot?slot_no={{ request("slot_no") }}&level={{ $tree->sponsor_tree_level }}','lg')" href="javascript:"><b>{!! $tree->display_slot_count !!}</b></a></td>
			  			</tr>
			  			@endforeach
			  		@else
			  			<tr>
			  				<td colspan="2" class="text-center"><b>{{ request("slot_no") }}</b> doesn't have any network yet.</td>
			  			</tr>
		  			@endif
		  		</tbody>
		  	</table>
		</div>
	</div>
</div>
@endsection
@section("member_script")

<script type="text/javascript">
	$(".select-slot").change(function(e)
	{
		window.location.href='/members/network?slot_no=' + $(e.currentTarget).val();
	})
</script>

@endsection
@section("member_css")
<link rel="stylesheet" type="text/css" href="/themes/{{ $shop_theme }}/css/report.css">
@endsection