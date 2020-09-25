<div class="report-container">
	<div class="panel panel-default panel-block panel-title-block panel-report load-data">
		<div class="panel-heading load-content">
			@include('member.reports.report_header')
			<div class="table-reponsive">
				@if(count($_item_product_code) > 0)
				<table class="table table-condensed collaptable">
					<thead style="text-transform: uppercase">
						<tr>
							<th>#</th>
							@if($warehouse_data->main_warehouse == 3)
							<th>Control Number</th>
							@else
							<th>Item Name</th>							
							@endif
							<th class="text-center">Code</th>
							<!-- <th class="text-right">Discrepancy</th> -->
							<th class="text-center">Activation</th>
						</tr>
					</thead>
					<tbody>	
					@foreach($_item_product_code as $key => $code)
						<tr>
							<td>{{$key+1}}</td>
							@if($warehouse_data->main_warehouse == 3)
							<td>{{str_pad($code->ctrl_number, 9, '0', STR_PAD_LEFT)}}</td>
							@else
							<td class="text-center">{{$code->item_name}}</td>
							@endif
							<td class="text-center">{{$code->mlm_pin}}</td>
							<td class="text-center">{{$code->mlm_activation}}</td>
						</tr>
					@endforeach			
					</tbody>
				</table>
				@else	
				<h3 class="text-center">No Codes</h3>
				@endif
			</div>
			<h5 class="text-center">---- {{$now or ''}} ----</h5>
		</div>
	</div>
</div>