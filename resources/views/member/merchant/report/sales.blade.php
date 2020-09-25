{!! $header !!}


<table class="table table-bordered">
	<thead>
		<tr>
			<th>
				Id
			</th>
			<th>
				Date
			</th>
			<th>
				Payment Method
			</th>
			<th>
				Total
			</th>
		</tr>
	</thead>
	<tbody>
		<?php $total = 0; ?>
		@if(count($sales) >= 1)
			@foreach($sales as $key => $value)
				<tr>
					<td>{{$value->item_code_invoice_id}}</td>
					<td>{{$value->item_code_date_created}}</td>
					<td>{{get_payment_method_mlm($value->item_code_payment_type)}}</td>
					<td>{{currency('PHP', $value->item_total)}}</td>
					<?php $total += $value->item_total; ?>
				</tr>
			@endforeach
			<tr>
				<td></td>
				<td></td>
				<td></td>
				<td>{{currency('PHP', $total)}}</td>
			</tr>
		@else
		<tr>
			<td colspan="20">
				<center>-No Data Found-</center>
			</td>
		</tr>
		@endif
	</tbody>
</table>