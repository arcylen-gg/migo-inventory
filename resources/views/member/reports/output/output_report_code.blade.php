<div class="report-container">
	<div class="panel panel-default panel-block panel-title-block panel-report load-data">
		<div class="panel-heading load-content">
			@include('member.reports.report_header')
			<div class="table-reponsive">
				@if(count($codes) > 0)
				<table class="table table-condensed collaptable">
					<thead style="text-transform: uppercase">
						<tr>
							<th>#</th>
							<th>Customer Name</th>
							<th>Slot Number</th>
							<th>Item Name</th>
							<th class="text-center">Code</th>
							<!-- <th class="text-right">Discrepancy</th> -->
							<th class="text-center">Activation</th>
							@if($warehouse_data->main_warehouse == 3)
							<th>Control Number</th>
							@endif
						</tr>
					</thead>
					<tbody>	
					@foreach($codes as $key => $code)
						<tr>
							<td>{{$key+1}}</td>
							<td>{{ucwords($code->first_name. ' ' .$code->middle_name. ' ' .$code->last_name)}}</td>
							<td>{{$code->slot_no}}</td>
							<td>{{$code->item_name}}</td>
							<td class="text-center">{{$code->mlm_pin}}</td>
							<td class="text-center">{{$code->mlm_activation}}</td>
							@if($warehouse_data->main_warehouse == 3)
							<td>{{str_pad($code->ctrl_number, 9, '0', STR_PAD_LEFT)}}</td>
							@endif
						</tr>
					@endforeach			
					</tbody>
				</table>
				@else	
				<h3 class="text-center">No Transaction</h3>
				@endif
			</div>
			<h5 class="text-center">---- {{$now or ''}} ----</h5>
		</div>
	</div>
</div>