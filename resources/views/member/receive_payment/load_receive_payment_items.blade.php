
@if(isset($_invoice) && $_invoice != null)
	@foreach($_invoice as $invoice)
		<tr>
			<input type="hidden" value="invoice" name="rpline_txn_type[]">
			<input type="hidden" value="{{$invoice['inv_id']}}" name="rpline_txn_id[]">
		    <td class="text-center">
		    	@if(isset($invoice['rpline_rp_id']) && $invoice['rpline_rp_id'] != null || isset($invoice['rpline_rp_id']) && $invoice['rpline_rp_id'] != '')
		    	<input type="hidden" class="line-is-checked inputinv-{{$invoice['inv_id']}}" name="line_is_checked[]" value="1" >
		    	<input type="checkbox" class="line-checked checkboxinv-{{$invoice['inv_id']}}" checked="true">
		    	@else
		    	<input type="hidden" class="line-is-checked inputinv-{{$invoice['inv_id']}}" name="line_is_checked[]" value="" >
		    	<input type="checkbox" class="line-checked checkboxinv-{{$invoice['inv_id']}}">
		    	@endif
		    </td>
		    <td>Invoice <a target='_blank' href="/member/transaction/sales_invoice/print?id={{$invoice['inv_id']}}"> {{$invoice['transaction_refnum'] != '' ? $invoice['transaction_refnum'] : $invoice["new_inv_id"]}}</a> ( {{dateFormat($invoice["inv_date"])}} )</a></td>
		    <td class="text-right">{{dateFormat($invoice["inv_due_date"])}}</td>
		    <td><input type="text" class="text-right original-amount" value="{{number_format($invoice['inv_overall_price'],2) }}" disabled /></td>
		    <td><input type="text" class="text-right balance-due" name="rpline_balance[]" value="{{number_format(($invoice['inv_overall_price']) - $invoice['amount_applied'] + (isset($invoice['rpline_amount']) ? $invoice['rpline_amount'] : 0 ),2)}}" readonly="true" /></td>
		    <td><input type="text" class="text-right amount-payment" name="rpline_amount[]" value="{{(isset($invoice['rpline_amount']) ? $invoice['rpline_amount'] - (isset($invoice['cm_amount']) ? $invoice['cm_amount'] : 0) : 0 )}}" data="{{(isset($invoice['rpline_amount']) ? $invoice['rpline_amount'] - (isset($invoice['cm_amount']) ? $invoice['cm_amount'] : 0) : 0 ) }}"/></td>
		</tr>

		<script type="text/javascript">
			console.log("{{(isset($invoice['rpline_amount']) ? $invoice['rpline_amount'] : 0 )}}");
		</script>
	@endforeach
@else
	<tr>
	    <td class="text-center"></td>
	    <td></td>
	    <td class="text-right"></td>
	    <td><input type="text" class="text-right" disabled /></td>
	    <td><input type="text" class="text-right" disabled /></td>
	    <td><input class="text-right" type="text" disabled /></td>
	</tr> 
@endif