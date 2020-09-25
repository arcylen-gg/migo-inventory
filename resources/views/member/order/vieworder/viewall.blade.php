
<table class="table table-hover">
	<tr>
		<thead>
			<th>
				<input type="checkbox" name="" >
			</th>
			<th>Order</th>
			<th>
				Date
			</th>
			<th>
				Customer
			</th>
			<th>
				Payment Status
			</th>
			<th>
				Order Status
			</th>
			<th class="text-right">
				Total
			</th>
		</thead>
	</tr>
	<tbody>
	@if($_orders)
	
	@foreach($_orders as $key => $order)
		<tr>
			<td>
				<input type="checkbox" name="" >
			</td>
			@if($order['status'] == 'draft')
			<td><a href="/member/{{$page}}/orders/draft/{{$order['tbl_order_id']}}">#{{$order['tbl_order_id']}}</a></td>
			@else
			<td><a href="/member/{{$page}}/order/{{$order['tbl_order_id']}}">#{{$order['tbl_order_id']}}</a></td>
			@endif
			<td>{{$order['date']}}</td>
			<td><a href="#">{{$order['customer_name']}}</a></td>
			<td>{{$order['payment_stat']}}</td>
			<td>{{$order['fulfillment_status']}}</td>
			<td class="text-right">{{$order['amount']}}</td>
		</tr>
	@endforeach
	@else
	<tr>
	        <td colspan="7">
	        <center>No Orders Found.</center>
	       </td>
	</tr>
	@endif
	</tbody>
</table>