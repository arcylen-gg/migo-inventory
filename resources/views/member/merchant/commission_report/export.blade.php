<!DOCTYPE html>
<html>
<head>	
	<style type="text/css">
	
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
		<tr class="head">
			<th>Record Id</th>
			<th>Pin</th>
            <th>Activation</th>
            <th>Date Used</th>
            <th>Amount</th>
            <th>Commission Percentage</th>
            <th>Receivable Amount</th>
		</tr>
		@foreach($table as $t)
		<tr>
			<td>{{$t->record_log_id}}</td>
			<td>{{$t->mlm_pin}}</td>
            <td>{{$t->mlm_activation}}</td>
            <td>{{$t->record_log_date_updated}}</td>
            <td>{{currency('Php',$t->item_price)}}</td>
            <td>{{$t->merchant_commission_percentage}}%</td>
            <td>{{currency('Php',$t->item_price*($t->merchant_commission_percentage/100))}}</td>
		</tr>
		@endforeach
		<br>
		<tr>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <th>Total Commission </th>
            <td>{{currency('Php',$totalcommission)}}</td>
        </tr>
	</table>
</body>
</html>