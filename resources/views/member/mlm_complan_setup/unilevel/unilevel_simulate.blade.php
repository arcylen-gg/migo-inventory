<table class="table table-bordered">
	<thead>
		<th>Member</th>
		<th>Slot</th>
		<th>PV</th>
		<th>GPV</th>
		<th>Income</th>
	</thead>
	<tbody>
		@foreach($structured as $key => $value)
			<tr>
				<td>{{name_format_from_customer_info($value['info'])}}</td>
				<td>{{$value['info']->slot_no}}</td>
				<td>{{ $value['personal_points']}}</td>
				<td>{{ $value['group_points']}}</td>
				<td>{{currency('PHP', $value['income'])}}</td>
			</tr>
		@endforeach
	</tbody>
</table>
