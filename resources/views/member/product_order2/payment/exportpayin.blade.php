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
			<td>Date</td>
			<td>Ordered By</td>
			<td>Email</td>
			<td>Invoice #</td>
			<td>Invoice Date</td>
			<td>Contact No</td>
			<td>Date</td>
			<td>Reference Number</td>
			<td>Sender Name</td>
			<td>Amount</td>
		</tr>
		@foreach($_transaction as $transaction)
		<tr>
			<td>{{ $transaction->transaction_id }}</td>
			<td>{{ $transaction->transaction_date_created }}</td>
			<td>{{ $transaction->customer_name }}</td>
			<td>{{ $transaction->email }}</td>
			<td>{{ $transaction->transaction_number }}</td>
			<td>{{ date("m/d/y", strtotime($transaction->transaction_date_created)) }}</td>
			<td>"{{ $transaction->customer_mobile }}"</td>
			@if(array_key_exists($transaction->transaction_id,$date))
			<td>{{ $date[$transaction->transaction_id] }}</td>
			<td>{{ $refnum[$transaction->transaction_id] }}</td>
			<td>{{ $sendername[$transaction->transaction_id] }}</td>
			<td>{{ $amount[$transaction->transaction_id] }}</td>
			@endif
		</tr>
		@endforeach
	</table>
</body>
</html>