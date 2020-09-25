<div class="modal-header">
	<button type="button" class="close" data-dismiss="modal">×</button>
	<h4 class="modal-title">NETWORK LIST ON LEVEL {{ request('level') }} OF <b>{{ request('slot_no') }}</b></h4>
</div>
<div class="modal-body clearfix">
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
