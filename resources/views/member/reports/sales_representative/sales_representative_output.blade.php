<div class="report-container">
	<div class="panel panel-default panel-block panel-title-block panel-report load-data">
		<div class="panel-heading load-content">
			@include('member.reports.report_header');
			<div class="table-reponsive">
				<table class="table table-condensed collaptable">
					<tr>
						<th class="text-center" class="text-center">Employee Number</th>
						<th class="text-center" class="text-center">Sales Reprensentative</th>
						<th class="text-center">Transaction Number</th>
						<th class="text-center">Customer</th>
						<th class="text-center">Date</th>
						<th class="text-center">Sales Amount</th>
						<th class="text-center">Paid Amount</th>
					</tr>
					<tbody>

						@foreach($_sales_rep as $key => $salesrep)
						<tr data-id="customer-{{$key}}" data-parent="">
							<td><b>{{$salesrep->sales_rep_employee_number}}</b></td>
							<td><b>{{$salesrep->sales_rep_first_name." ".$salesrep->sales_rep_middle_name." ".$salesrep->sales_rep_last_name}}</b>
							</td>
							<td colspan="3"></td>
							<td class="text-right">
								<text class="total-report">{{currency('PHP', $salesrep->sales_amount)}}</text>
							</td>
							<td class="text-right">
								<text class="total-report">{{currency('PHP', $salesrep->paid_amount)}}</text>
							</td>
						</tr>
						@foreach($salesrep->sales as $key2=>$sale)
						<tr data-id="customer2-{{$key}}" data-parent="customer-{{$key}}">
							<td nowrap colspan="2"></td>
							<td nowrap>{{$sale->transaction_refnum}}</td>
							<td nowrap>
								{{$sale->company != '' ?  $sale->company : $sale->first_name .' '. $sale->last_name}}
							</td>
							<td nowrap>{{date('F d, Y',strtotime($sale->inv_date))}}</td>
							<td nowrap>{{currency('PHP',$sale->inv_overall_price)}}</td>
							<td nowrap>{{currency('PHP',$sale->inv_payment_applied)}}</td>
						</tr>
						@endforeach
						<tr data-id="customer2-{{$key}}" data-parent="customer-{{$key}}">
							<td colspan="5"><b>Total
									{{$salesrep->sales_rep_first_name." ".$salesrep->sales_rep_middle_name." ".$salesrep->sales_rep_last_name}}</b>
							</td>
							<td class="text-right">{{currency('PHP', $salesrep->sales_amount)}}</td>
							<td class="text-right">{{currency('PHP', $salesrep->paid_amount)}}</td>
						</tr>
						@endforeach
					</tbody>
				</table>
			</div>
			<h5 class="text-center">---- {{$now or ''}} ----</h5>
		</div>
	</div>
</div>