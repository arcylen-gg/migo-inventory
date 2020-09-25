

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
							<th class="text-center">Stock-on-hand</th>
							<th class="text-center {{$w_type != 'branches' ? '' : 'hidden'}}">Cost</th>
							<th class="text-center {{$w_type != 'branches' ? '' : 'hidden'}}">Total Cost</th>
							<th class="text-center">Total Asset(%)</th>
							<th class="text-center">Sales Price</th>
							<th class="text-center">Total Sales</th>
							<th class="text-center">Total Retail(%)</th>
						</tr>
					</thead>
					<tbody class="{{ $total_asset_value = 0 }}" class=" {{ $total_retail_value = 0 }}">
						@if(count($_item) > 0)
							@foreach($_item as $key => $report)
								<tr>
									<td class="text-center">
										<a href="/member/report/inventory/detailed/{{$report['item_id']}}" >{{$report['item_name']}}</a><br>
										<small>{{$report['item_sku']}}</small><br>
										
										<small>

											@foreach ( $_item_category as $data )
												@if ($data['type_id']==$report['item_category_id'])
													{{$value=$data['type_name']}}
												@endif
											@endforeach
										</small><br>
									</td>
									<td class="text-center">{{$report['item_type_name']}}</td>

									<td class="text-center">{{$report['invty_count']}}</td>
									<td class="text-center {{$w_type != 'branches' ? '' : 'hidden'}}">{{number_format($report['invty_cost'], 2)}}</td>
									<td class="text-center {{$w_type != 'branches' ? '' : 'hidden'}}">{{number_format($report['total_cost'], 2)}}</td>
									<td class="text-center" {{$total_ct = $total_cost_total == 0 ? 1 : $total_cost_total}}>{{number_format(($report['total_cost'] / $total_ct ) * 100, 2).'%'}}</td>
									<td class="text-center">{{number_format($report['item_price'], 2)}}</td>
									<td class="text-center">{{number_format($report['total_price'], 2)}}</td>
									<td class="text-center">{{number_format(($report['total_price'] / $total_price_total) * 100, 2).'%'}}</td>
								</tr class="{{$total_asset_value += ($report['total_cost'] / $total_ct) * 100}}"
								class="{{$total_retail_value += ($report['total_price'] / $total_price_total) * 100}}">  
							@endforeach
							<tr style="font-weight: bold;">
								<td class="text-center" colspan="2">TOTAL</td>
								<td class="text-center">{{$inventory_count_total }}</td>
								<td class="text-center {{$w_type != 'branches' ? '' : 'hidden'}}">{{number_format($cost_total, 2) }}</td>
								<td class="text-center {{$w_type != 'branches' ? '' : 'hidden'}}">{{number_format($total_cost_total, 2) }}</td>
								<td class="text-center">{{number_format($total_asset_value, 2)}}%</td>
								<td class="text-center">{{number_format($price_total, 2) }}</td>
								<td class="text-center">{{number_format($total_price_total, 2) }}</td>
								<td class="text-center">{{number_format($total_retail_value, 2)}}%</td>
							</tr>
						@else
						<tr><td colspan="22"><h3 class="text-center">No Transaction</h3></td></tr>
						@endif					
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