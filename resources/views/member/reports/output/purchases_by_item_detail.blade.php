<div class="wrapper-top-scroll">
	<div class="div-top-scroll">
	</div>
</div>
<div class="wrapper-bottom-scroll">
	<div class="div-bottom-scroll">
		<div class="report-container">
			<div class="panel panel-default panel-block panel-title-block panel-report load-data">
				<div class="panel-heading load-content">
					@include('member.reports.report_header')
					<div class="table-reponsive">
						<table class="table table-condensed collaptable">
							<tr>
								<th class="text-center">Item Type</th>
								<th class="text-center">Item Name</th>
								<th class="text-center">Type</th>
								<th class="text-center">Date</th>
								<th class="text-center">Num</th>
								<th class="text-center">Memo</th>
								<th class="text-center">Vendor Name</th>
								<th class="text-center">Qty</th>
								<th class="text-center">U/M</th>
								<th class="text-center">Cost Price</th>
								<th class="text-center">Amount</th>
								<th class="text-center">Balance</th>
							</tr>
							<tbody>
								@foreach($_item_type as $key => $item_type)
								
								<tr data-id="customer-{{$key}}" data-parent="" style="" bgcolor="#c2bcbc">
									<td>{{$item_type->item_type_name}}</td>
									<td colspan="6"></td>
									<td class="text-right"><b>{{$item_type->inventory_qty_sum}}</b></td>
									<td colspan="1"></td>
									<td class="text-right"><b>{{currency('PHP', $item_type->inventory_amt_sum)}}</b></td>
									<td class="text-right"><b>{{currency('PHP', $item_type->inventory_amt_sum)}}</b></td>
									<td class="text-right"><b>{{currency('PHP', $item_type->inventory_amt_sum)}}</b></td>
								</tr>
								@foreach($item_type->inventory as $key_result => $value)
								<tr data-id="customer2-{{$key_result}}" data-parent="customer-{{$key}}">
									<td nowrap></td>
									<td nowrap>{{$value->item_name}}</td>
									@foreach($value->inventory_group as $key_per_item => $per_item)
									<tr data-id="customer3-{{$key_per_item}}" data-parent="customer-{{$key}}">
										<td colspan="2"></td>
										<td nowrap>Bill</td>
										<td nowrap>{{date('F d, Y', strtotime($per_item->bill_date))}}</td>
										<td nowrap>{{$per_item->bill_refnum == '' || $per_item->bill_refnum == null ? $per_item->bill_id : $per_item->bill_refnum }}</td>
										<td class="text-center" nowrap>{{$per_item->bill_memo}}</td>
										<td class="text-center" nowrap>{{$per_item->vendor_company == '' ? ucfirst($per_item->vendor_title_name." ".$per_item->vendor_first_name." ".$per_item->vendor_middle_name." ".$per_item->vendor_last_name." ".$per_item->vendor_suffix_name) : $per_item->vendor_company}}</td>
										<td nowrap class="text-right">{{$per_item->itemline_orig_qty}}</td>
										<td nowrap class="text-right">{{$per_item->multi_name}}</td>
										<td nowrap class="text-right">{{currency('PHP', $per_item->itemline_rate)}}</td>
										<td nowrap class="text-right">{{currency('PHP', $per_item->itemline_orig_qty * $per_item->itemline_rate)}}</td>
										<td nowrap class="text-right">{{currency('PHP', $per_item->itm_balance_cummulative_sum)}}</td>
									</tr>
									@foreach($per_item->inventory_group_checking as $key_per_item_sort => $sort_per_item)
									@if($per_item->bill_refnum == $sort_per_item['bill_refnum'])
									@if(abs($per_item->itemline_qty - $per_item->itemline_orig_qty) > 0)
									<tr data-id="customer3-{{$key_per_item}}" data-parent="customer-{{$key}}">
										<td colspan="2"></td>
										<td nowrap>Credit</td>
										<td nowrap>{{date('F d, Y', strtotime($sort_per_item->bill_date))}}</td>
										<td nowrap>{{$sort_per_item->dbt_memo_refnum == '' || $sort_per_item->dbt_memo_refnum == null ? $sort_per_item->db_id : $sort_per_item->dbt_memo_refnum }}</td>
										<td class="text-center" nowrap>{{$sort_per_item->bill_memo}}</td>
										<td class="text-center" nowrap>{{$sort_per_item->vendor_company == '' ? ucfirst($sort_per_item->vendor_title_name." ".$sort_per_item->vendor_first_name." ".$sort_per_item->vendor_middle_name." ".$sort_per_item->vendor_last_name." ".$sort_per_item->vendor_suffix_name) : $sort_per_item->vendor_company}}</td>
										<td nowrap class="text-right">{{$sort_per_item->dbline_qty * -1}}</td>
										<td nowrap class="text-right">{{$sort_per_item->multi_name}}</td>
										<td nowrap class="text-right">{{currency('PHP', $sort_per_item->itemline_rate)}}</td>
										<td nowrap class="text-right">{{currency('PHP', $sort_per_item->dbline_qty * $sort_per_item->itemline_rate * -1)}}</td>
										<td nowrap class="text-right">{{currency('PHP', $sort_per_item->itm_balance_cummulative_sum)}}</td>
									</tr>
									@else
									@endif
									@else
									@endif
									@endforeach
									@endforeach
									<tr data-id="customer2-{{$key}}" data-parent="customer-{{$key}}" bgcolor="#e2e0e0">
										<td colspan="1"></td>
										<td nowrap colspan="6"><b>Total {{$value->item_name}}</b></td>
										<td nowrap class="text-right"><b>{{$value->inventory_group_qty_sum}}</b></td>
										<td colspan="2"></td>
										<td nowrap class="text-right"><b>{{currency('PHP', $value->inventory_group_amt_sum)}}</b></td>
										<td nowrap class="text-right"><b>{{currency('PHP', $value->inventory_group_amt_sum)}}</b></td>
									</tr>
								</tr>
								@endforeach
								<tr data-id="customer2-{{$key}}" data-parent="customer-{{$key}}" bgcolor="#d1d1d1">
									<td colspan="" nowrap><b>Total {{$item_type->item_type_name}}</b></td>
									<td colspan="6"></td>
									<td nowrap class="text-right"><b>{{$item_type->inventory_qty_sum}}</b></td>
									<td colspan="1"></td>
									<td nowrap class="text-right"><b>{{currency('PHP', $item_type->inventory_amt_sum)}}</b></td>
									<td nowrap class="text-right"><b>{{currency('PHP', $item_type->inventory_amt_sum)}}</b></td>
									<td nowrap class="text-right"><b>{{currency('PHP', $item_type->inventory_amt_sum)}}</b></td>
								</tr>
								@endforeach
							</tbody>
						</table>
					</div>
					<h5 class="text-center">---- {{$now or ''}} ----</h5>
				</div>
			</div>
		</div>
	</div>
</div>
<style type="text/css">
.wrapper-top-scroll
{
width: 100%; border: none 0px RED;
overflow-x: scroll; /*overflow-y:hidden;*/
margin: 0 auto;
}
.wrapper-bottom-scroll
{
width: 100%; border: none 0px RED;
overflow-x: scroll; /*overflow-y:hidden;*/
margin: 0 auto;
/*height: 100%; */
}
.div-top-scroll
{
width:130%; height: 20px;
}
.div-bottom-scroll
{
width:130%; /*height: 100%;*//*overflow: auto;*/
} 
@page
{
    page-break-after: always;
}

</style>