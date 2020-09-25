@if($_active != '0')
	@foreach($_active as $active)
		@foreach($active as $a)
			<tr>
				<td>{{ $a['name'] }}</td>
				<td>{{ $a['department'] }}</td>
				<td>{{ $a['job_title'] }}</td>
				<td>{{ $a['period'] }}</td>
				<td class="text-right">{{ $a['basic_salary'] }}</td>
				<td class="text-right">{{ $a['amount_of_13'] }}</td>
				<td class="text-right">{{ $a['sub_total'] }}</td>
			</tr>
		@endforeach										
	@endforeach
@else
	No Records Found!
@endif