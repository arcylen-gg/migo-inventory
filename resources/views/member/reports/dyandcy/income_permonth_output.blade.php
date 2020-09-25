<div class="report-container">
	<div class="panel panel-default panel-block panel-title-block panel-report load-data">
		<div class="panel-heading load-content">
			@include('member.reports.report_header')
			<div class="table-reponsive">
				<table class="table table-condensed collaptable">
					<thead style="text-transform: uppercase">
						<tr>
							<th class="text-center">RECEIPT NO.</th>
							<th class="text-center">ITEM NAME</th>
							<th class="text-center">CUSTOMER</th>
							<th class="text-center">ORIGINAL PRICE</th>
							<th class="text-center">MARK-UP PRICE</th>
							<th class="text-center">SELLING PRICE</th>
							<th class="text-center">SOLD PRICE PER UNIT</th>
							<th class="text-center">INCOME PER UNIT</th>
							<th class="text-center">QUANTITY</th>
							<th class="text-center">DISCOUNT</th>
							<th class="text-center">GROSS INCOME</th>
							<th class="text-center">NET INCOME</th>
							<th class="text-center">STATUS</th>
							<th class="text-center">EXPECTED DATE OF FULLPAYMENT</th>
						</tr>
					</thead>
					<tbody class="{{$total = 0}}">
						@if(count($_item) > 0)
							@foreach($_item as $item)
							<tr>
								<td class="text-center">{{$item->transaction_refnum}}</td>
								<td class="text-center">{{$item->item_name}}</td>
								<td class="text-center">
									{{$item->company}} <br>
									{{$item->first_name.' '.$item->last_name}}
								</td>
								<td class="text-right">{{currency('',$item->item_cost)}}</td>
								<td class="text-right">{{currency('',$item->item_price)}}</td>
								<td class="text-right">{{currency('',$item->item_price)}}</td>
								<td class="text-right">{{currency('',$item->sold_price)}}</td>
								<td class="text-right">{{currency('',$item->sold_price - $item->item_cost)}}</td>
								<td class="text-center">{{$item->invline_orig_qty}}</td>
								<td class="text-center">{{$item->discount}}</td>
								<td class="text-center">{{currency('', ($item->sold_price * $item->invline_orig_qty) - $item->discount)}}</td>
								<td class="text-right {{$total += (($item->sold_price - $item->item_cost) * $item->invline_orig_qty) - $item->discount}}">{{currency('', ($item->sold_price - $item->item_cost) * $item->invline_orig_qty) - $item->discount}}</td>
								<td class="text-center">{{$item->inv_is_paid == '1' ? 'PAID' : 'UNPAID'}}</td>
								<td class="text-center">{{date('F d, Y',strtotime($item->inv_due_date))}}</td>
							</tr>
							@endforeach
							<tr>
								<td colspan="11" class="text-right">TOTAL INCOME</td>
								<td class="text-right">{{currency('', $total)}}</td>
								<td colspan="2"></td>
							</tr>
						@else
						<tr><td colspan="15" class="text-center"> NO TRANSACTION YET</td></tr>
						@endif
					</tbody>
				</table>
			</div>
		</div>
	</div>
</div>