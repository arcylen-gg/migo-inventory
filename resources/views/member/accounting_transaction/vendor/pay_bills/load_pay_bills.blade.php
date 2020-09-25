@if(isset($_bill) && $_bill != null)
	@foreach($_bill as $bill)
		<tr>
			<input type="hidden" value="bill" name="pbline_txn_type[]">
			<input type="hidden" value="{{$bill['bill_id']}}" name="pbline_bill_id[]">
		    <td class="text-center">
		    	@if(isset($bill['pbline_pb_id']) && $bill['pbline_pb_id'] != null || isset($bill['pbline_pb_id']) && $bill['pbline_pb_id'] != '')
		    	<input type="hidden" class="line-is-checked inputebid-{{$bill['bill_id']}}" name="line_is_checked[]" value="1" >
		    	<input type="checkbox" class="line-checked checkboxebid-{{$bill['bill_id']}}" checked="true">
		    	@else
		    	<input type="hidden" class="line-is-checked inputebid-{{$bill['bill_id']}}" name="line_is_checked[]" value="" >
		    	<input type="checkbox" class="line-checked checkboxebid-{{$bill['bill_id']}}">
		    	@endif
		    </td>
		    <td>
		    	@if($bill["ri_refnum"] != '')
		    		Receive Inventory <a target='_blank' href="/member/transaction/enter_bills/print?id={{$bill['bill_id']}}">{{$bill["ri_refnum"]}} </a> ( {{dateFormat($bill["bill_date"])}} )
		    	@elseif($bill["bill_refnum"] != '')
		    		Bill <a target='_blank' href="/member/transaction/enter_bills/print?id={{$bill['bill_id']}}">{{$bill["bill_refnum"]}} </a> ( {{dateFormat($bill["bill_date"])}} )
		    	@else
		    		Bill <a target='_blank' href="/member/transaction/enter_bills/print?id={{$bill['bill_id']}}">{{$bill["bill_id"]}}</a>  ( {{dateFormat($bill["bill_date"])}} )
		    	@endif
		    </td>
		    <td class="text-right">{{dateFormat($bill["bill_due_date"])}}</td>
	    	<td><input type="text" class="text-right original-amount" value="{{currency('',$bill['bill_total_amount'])}}" disabled /></td>
		    <td><input type="text" class="text-right balance-due" value="{{currency('', $bill['bill_total_amount'] - $bill['amount_applied'] + (isset($bill['pbline_amount']) ? $bill['pbline_amount'] : 0 ))}}" disabled /></td>
		    <td><input type="text" class="text-right amount-payment" name="pbline_amount[]" value="{{$bill['pbline_amount'] or (isset($bill_id) == $bill['bill_id'] ? $bill['bill_total_amount'] : 0)}}" data="{{$bill['pbline_amount'] or 0}}"/></td>
		</tr>
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