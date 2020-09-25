<!DOCTYPE html>
<html>
<head>	
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<style type="text/css">
	*
	{
		font-family: Arial;
	}
	table
	{
		font-family: Arial;
	}
	.head td
	{
		border: 1px solid #000;
		font-size: 8px;
		font-weight: bold;
	}
	.title
	{
		font-size: 27px;
		font-family: Inherit;
	}
	.sub
	{
		font-size: 16px;
		font-family: Arial;
	}
	</style>
</head>
<body>
	<table>
		<tr>
			<td class="title" colspan="18">Order Reference</td>
		</tr>
		<tr>
			<td class="sub" colspan="18">{{ ucfirst($method) }}</td>
		</tr>
		<tr class="head">
			<td>Order ID</td>
			<td>Month</td>
			<td>Ordered By</td>
			<td>Email</td>
			<td>Invoice #</td>
			<td>Invoice Date</td>
			<td>Contact No</td>
			<td>Birthday</td>
			<td>Street</td>
			<td>Zip</td>
			<td>City</td>
			<td>State</td>
		</tr>
		@foreach($_transaction as $transaction)
		<tr>
			<td>{{ $transaction->transaction_id }}</td>
			<td>{{ date("F", strtotime($transaction->transaction_date_created)) }}</td>
			<td>{{ $transaction->customer_name }}</td>
			<td>{{ $transaction->email }}</td>
			<td>{{ $transaction->transaction_number }}</td>
			<td>{{ date("m/d/y", strtotime($transaction->transaction_date_created)) }}</td>
			<td>"{{ $transaction->customer_mobile }}"</td>
			<td>{{ date("m/d/y", strtotime(($transaction->b_day ? $transaction->b_day : $transaction->birthday))) }}</td>
			<td>{{ $transaction->customer_street }}</td>
			<td>{{ $transaction->customer_zipcode }}</td>
			<td>{{ $transaction->customer_city }}</td>
			<td>{{ $transaction->customer_state }}</td>
		</tr>
		@endforeach
	</table>
</body>
</html>