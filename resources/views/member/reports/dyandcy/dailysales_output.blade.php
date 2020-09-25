<div class="report-container">
	<div class="panel panel-default panel-block panel-title-block panel-report load-data">
		<div class="panel-heading load-content">
			@include('member.reports.report_header')
			<div class="table-reponsive">
				<table class="table table-condensed collaptable">
					<thead style="text-transform: uppercase">
						<tr>
							<th class="text-center">ITEM NAME</th>
							<th class="text-center">SELLING PRICE</th>
							<th class="text-center">SOLD PRICE</th>
							<th class="text-center">QUANTITY</th>
							<th class="text-center">DISCOUNT</th>
							<th class="text-center">GROSS INCOME</th>
						</tr>
					</thead>
					<tbody class="{{$total = 0}}">
						@if(count($_item) > 0)
							@foreach($_item as $item)
							<tr>
								<td class="text-center">{{$item->item_name}}</td>
								<td class="text-right">{{currency('',$item->item_price)}}</td>
								<td class="text-right">{{currency('',$item->sold_price)}}</td>
								<td class="text-center">{{$item->invline_orig_qty}}</td>
								<td class="text-center">{{currency('', $item->discount)}}</td>
								<td class="text-right {{$total += ($item->sold_price * $item->invline_orig_qty) - $item->discount}}">{{currency('', ($item->sold_price * $item->invline_orig_qty) - $item->discount)}}</td>
							</tr>
							@endforeach
							<tr>
								<td colspan="5" class="text-center">TOTAL</td>
								<td class="text-right">{{currency('', $total)}}</td>
							</tr>
						@else
						<tr><td colspan="6" class="text-center"> NO TRANSACTION YET</td></tr>
						@endif
					</tbody>
				</table>
			</div>
		</div>
	</div>
</div>