<div class="report-container">
	<div class="panel panel-default panel-block panel-title-block panel-report load-data">
		<div class="panel-heading load-content">
			@include('member.reports.report_header')
			<div class="table-reponsive">
				<table class="table table-condensed collaptable">
					<thead style="text-transform: uppercase">
						<tr>
							<th class="text-center">Item</th>
							<th class="text-center">Type</th>
							<th class="text-center">U/M</th>
							<th class="text-center">Date</th>
							<th class="text-center">Company</th>
							<th class="text-center">Transaction</th>
							<th class="text-center">Ref#</th>
							<th class="text-center">Qty in</th>
							<th class="text-center">Qty out</th>
							<th class="text-center {{$w_type != 'branches' ? '' : 'hidden'}}">Cost</th>
							<th class="text-right {{$w_type != 'branches' ? '' : 'hidden'}}">Total Cost</th>
							<th class="text-center">Sales Price</th>
							<th class="text-right">Total Sales</th>
							<th class="text-center">Stock on hand-Qty</th>
						</tr>
					</thead>
					<tbody>
	         			@foreach($_report as $key => $item) 	
		       			<tr {{ $qty_balance  = 0}} {{ $total_qty_in = 0}} {{ $total_qty_out = 0}} {{ $total_qty = 0}}}} >
			                <td>{{$item->item_name}}</td>
			                <td>{{$item->item_type_name}}</td>
			                <td></td>
			                <td></td>
			                <td></td>
			                <td></td>
			                <td></td>
			                <td></td>
			                <td></td>
			                <td class="{{$w_type != 'branches' ? '' : 'hidden'}}"></td>
			                <td class="{{$w_type != 'branches' ? '' : 'hidden'}}"></td>
			                <td></td>
			                <td></td>
			                <td class="text-center">{{ $item->inventory_count_balance }}</td>

			            </tr>
				            @if(count($item->transaction) > 0)
				            	@foreach($item->transaction as $key1 => $item1)
				            		@if($item1)
				            			@foreach($item1 as $key2 => $item2)
				            				@if($item2['transaction_line_qty'] != 0 && $item2['transaction_line_qty'] != '')
							            		<td></td>
								                <td></td>
								                <td></td>
							                	<td>{{date('m/d/Y', strtotime($item2['transaction_date']))}}</td>
							                	@if($item2['transaction_name'] == 'Receive Transfer')
							                		<td class="text-center">Receive from {{$item2['transaction_company']}}</td>
							                	@elseif($item2['transaction_name'] == 'Warehouse Transfer')
							                		<td class="text-center">Transfer to {{$item2['transaction_company']}}</td>
							                	@else
							                		<td class="text-center">{{$item2['transaction_company']}}</td>
							                	@endif
							            		<td>{{$item2['transaction_name']}}</td>
							            		<td>{{$item2['transaction_ref_num']}}</td>
							            		@if($item2['transaction_status'] == 'in')
								            		<td {{ $total_qty_in += $item2['transaction_line_qty']}} class="text-center">
								            		{{$item2['transaction_line_qty']}}</td>
								            		<td></td>
								            		<td class="text-right {{$w_type != 'branches' ? '' : 'hidden'}}">{{currency('',$item2['transaction_line_cost'])}}</td>
								            		<td class="text-right {{$w_type != 'branches' ? '' : 'hidden'}}">{{currency('',$item2['transaction_line_total_cost'])}}</td>
								            		<td></td>
								            		<td></td>
							            		@elseif($item2['transaction_status'] == 'out')
								            		<td></td>
								            		<td {{ $total_qty_out += $item2['transaction_line_qty']}} class="text-center">{{$item2['transaction_line_qty']}}</td>
								            		<td class="text-right {{$w_type != 'branches' ? '' : 'hidden'}}"></td>
								            		<td class="text-right {{$w_type != 'branches' ? '' : 'hidden'}}"></td>
								            		<td class="text-right">{{currency('',$item2['transaction_line_sales'])}}</td>
								            		<td class="text-right">{{currency('',$item2['transaction_line_total_sales'])}}</td>
							            		@endif
							            		<td class="text-center">{{$item->inventory_count_balance += $item2['transaction_line_qty']}}</td>
					              			</tr>
					              			@endif
			              				@endforeach
			              			@endif
			              		@endforeach
				            @endif
					        <tr style="font-weight: bold;" bgcolor="#A9A9A9">
				                <td class="text-center" nowrap colspan="7">TOTAL</td>
				                <td class="text-center" nowrap>{{ $total_qty_in }}</td>
				                <td class="text-center" nowrap>{{ $total_qty_out }}</td>
				                <td class="text-right {{$w_type != 'branches' ? '' : 'hidden'}}" nowrap ></td>
				                <td class="text-right {{$w_type != 'branches' ? '' : 'hidden'}}" nowrap ></td>
				                <td class="text-right" nowrap></td>
				                <td class="text-right" nowrap></td>
				                <td class="text-center">{{ $item->inventory_count_balance }}</td>
			              	</tr>
		              	@endforeach
	         		</tbody>
				</table>
			</div>
			<h5 class="text-center">---- {{$now or ''}} ----</h5>
		</div>
	</div>
</div>

<style type="text/css">
	tr { page-break-inside: avoid; }
</style>
				