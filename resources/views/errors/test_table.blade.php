<style type="text/css">
	table
	{
		text-align: center;
	}
	th
	{
		cursor: pointer;
	}
</style>

<table class="table table-condensed" width="100%" border="1">
	<thead>
		<tr>
			<th class="sorts" sortby="first_name">First Name</th>
			<th class="sorts" sortby="last_name">Last Name</th>
			<th class="sorts" sortby="middle_name">Middle Name</th>
		</tr>
	</thead>
	<tbody>
		@foreach($_customer as $customer)
		<tr>
			<td>{{ $customer->first_name }}</td>
			<td>{{ $customer->middle_name }}</td>
			<td>{{ $customer->last_name }}</td>
		</tr>
		@endforeach
	</tbody>
</table>

{{ $_customer->render() }}