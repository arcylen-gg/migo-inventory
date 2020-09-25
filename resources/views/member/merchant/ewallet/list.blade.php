@if(isset($headers))
<h3>{{$headers}}</h3>
@endif
@if(isset($back))
<a onClick="view_link('{{$back}}')">[BACK]</a>
@endif
<table class="table table-bordered">
	<tr>
		<th>#</th>
		<th>Date</th>
		<th>Item Price</th>
		<th>Customer <br>Paid Amount</th>
		<th>Discount</th>
		<th>Commission</th>
		<th>@if(isset($headers)) {{$headers}} @else Receivable @endif</th>
	</tr>
	@if(count($payable) >= 1)
		<?php $item_total = 0; 
			  $item_subtotal = 0;
			  $item_discount = 0;
			  $paid = 0;
			  $merchant_markup_value = 0;
		?>
		@foreach($payable as $key => $value)
			<tr>
				<td><a href="/member/mlm/product_code/receipt?invoice_id={{$value->item_code_invoice_id}}" target="_blank">{{$value->item_code_invoice_id}}</a></td>
				<td>{{$value->item_code_date_created}}</td>
				<td>{{currency('PHP', $value->item_subtotal)}}</td>
				<td>{{currency('PHP', $value->item_total)}}</td>
				<td>{{currency('PHP', $value->item_discount)}}</td>
				<td>{{currency('PHP', $value->merchant_markup_value)}}</td>
				<td>{{currency('PHP', $value->item_total - $value->merchant_markup_value)}}</td>
			</tr>
			<?php 
					$item_subtotal += $value->item_subtotal; 
					$item_discount += $value->item_discount; 
					$item_total += $value->item_total - $value->merchant_markup_value;
					$merchant_markup_value += $value->merchant_markup_value; 
					$paid += $value->item_total;
			?>
		@endforeach
		<tr>
			<td>Total</td>
			<td></td>
			<td>{{currency('PHP', $item_subtotal)}}</td>
			<td>{{currency('PHP', $paid)}}</td>
			<td>{{currency('PHP', $item_discount)}}</td>
			<td>{{currency('PHP', $merchant_markup_value)}}</td>
			<td>{{currency('PHP', $item_total)}}</td>
		</tr>
	@else
		<tr>
			<td colspan="20">
				<center>-No Data Available-</center>
			</td>
		</tr>
	@endif
</table>