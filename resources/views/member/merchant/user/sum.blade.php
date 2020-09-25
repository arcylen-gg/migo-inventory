<br>
@if($user)
	<input type="hidden" name="user_id" value="{{$user->user_id}}">
	@if(count($collectable) >= 1)
		<table class="table table-bordered">
			<tr>
				<th>Invoice Count</th>
				<th>Invoice Amount</th>
				<th>Invoice Discount Amount</th>
				<th>Commission Amount</th>
			</tr>
			<tbody>
				<tr>
					<td>
						{{count($collectable)}}
					</td>
					<td>
						{{currency('PHP', $item_subtotal)}}
					</td>
					<td>
						{{currency('PHP', $item_discount)}}
					</td>
					<td>
						{{currency('PHP', $merchant_markup_value)}}
					</td>
				</tr>
				<tr>
					<td></td>
					<td></td>
					<td></td>
					<td><button class="btn btn-primary">Request collection</button></td>
				</tr>
			</tbody>
		</table>
	@else
	<center>-No data found-</center>
	@endif
@else
<center>-Inavalid User</center>
@endif