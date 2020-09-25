{!! $header !!}


<table class="table table-bordered">
	<thead>
		<tr>
			<th>
				Item
			</th>
			<th>
				Quantiy
			</th>
			<th>
				Total (Discount Deducted)
			</th>
		</tr>
	</thead>
	<tbody>
		<?php $total = 0; ?>
		@if(count($sales_item) >= 1)
			@foreach($sales_item as $key => $value)
			<tr>
				<td>{{$key}}</td>
				<td>{{$value['qty']}}</td>
				<td>{{currency('PHP', $value['total'])}}</td>
				<?php $total += $value['total']; ?>
			</tr>
			@endforeach
			<tr>
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