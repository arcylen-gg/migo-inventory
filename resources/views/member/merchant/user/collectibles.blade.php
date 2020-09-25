<h3>{{$header}}</h3>
@if($back != null)
<a class="view_link" onClick="view_link('/member/merchant/commission/user/{{$user_id}}?commission={{$back_request}}')">[BACK]</a>
@endif
<table class="table table-bordered">
	<tr>
		<th>#</th>
		<th>Date</th>
		<th>Amount</th>
		<th>Discount</th>
		<th>Commission</th>
	</tr>
	@if(count($commission) >= 1)
		<?php $merchant_markup_value = 0; 
			  $item_subtotal = 0;
			  $item_discount = 0;
		?>
		@foreach($commission as $key => $value)
			<tr>
				<td><a href="/member/mlm/product_code/receipt?invoice_id={{$value->item_code_invoice_id}}" target="_blank">{{$value->item_code_invoice_id}}</a></td>
				<td>{{$value->item_code_date_created}}</td>
				<td>{{currency('PHP', $value->item_subtotal)}}</td>
				<td>{{currency('PHP', $value->item_discount)}}</td>
				<td>{{currency('PHP', $value->merchant_markup_value)}}</td>
			</tr>
			<?php 
					$item_subtotal += $value->item_subtotal; 
					$item_discount += $value->item_discount; 
					$merchant_markup_value += $value->merchant_markup_value; 
			?>
		@endforeach
		<tr>
			<td>Total</td>
			<td></td>
			<td>{{currency('PHP', $item_subtotal)}}</td>
			<td>{{currency('PHP', $item_discount)}}</td>
			<td>{{currency('PHP', $merchant_markup_value)}}</td>
		</tr>
	@else
		<tr>
			<td colspan="20">
				<center>-No Data Available-</center>
			</td>
		</tr>
	@endif
</table>