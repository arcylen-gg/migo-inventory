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
			<td class="title" colspan="18">Paymaya Checkout Cross Reference</td>
		</tr>
		<tr>
			<td class="sub" colspan="18">This page allows to get corresponding checkout ID and status of payment.</td>
		</tr>
		<tr class="head">
			<td>Order ID</td>
			<td>Paymaya Log Date</td>
			<td>Month</td>
			<td>Ordered By</td>
			<td>Email</td>
			<td>Upline Code / Referror</td>
			<td>Invoice #</td>
			<td>Invoice Date</td>
			<td>Contact No</td>
			<td>TIN</td>
			<td>Birthday</td>
			<td>Street</td>
			<td>Zip</td>
			<td>City</td>
			<td>State</td>
			<td>Checkout ID</td>
			<td>Paymaya Response</td>
			<td>Slot No.</td>
			<td>Slot ID.</td>
			<td>Investigation</td>
		</tr>
		@foreach($_transaction as $transaction)
		<tr>
			<td>{{ $transaction->transaction_id }}</td>
			<td>{{ date("m/d/y", strtotime($transaction->transaction_date_created)) }}</td>
			<td>{{ date("F", strtotime($transaction->transaction_date_created)) }}</td>
			<td>{{ $transaction->customer_name }}</td>
			<td>{{ $transaction->email }}</td>
			<td>{{ $transaction->slot_upline_no }}</td>
			<td>{{ $transaction->transaction_number }}</td>
			<td>{{ date("m/d/y", strtotime($transaction->transaction_date_created)) }}</td>
			<td>"{{ $transaction->customer_mobile or $transaction->contact}}"</td>
			<td>{{$transaction->tin_number or ''}}</td>
			<td>{{ date("m/d/y", strtotime(($transaction->b_day ? $transaction->b_day : $transaction->birthday))) }}</td>
			<td>{{ $transaction->customer_street }}</td>
			<td>{{ $transaction->customer_zipcode }}</td>
			<td>{{ $transaction->customer_city }}</td>
			<td>{{ $transaction->customer_state }}</td>
			<td>{{ $transaction->checkout_id }}</td>
			<td>{{ $transaction->paymaya_response }}</td>
			<td>{{ $transaction->slot_no }}</td>
			<td>{{ $transaction->slot_id }}</td>
			<td>{{ $transaction->paymaya_status }}</td>
		</tr>
		@endforeach
	</table>
</body>
</html>