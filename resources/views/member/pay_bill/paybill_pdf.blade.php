<!DOCTYPE html>
<html>
<head>
	<title></title>
	<style type="text/css">
		body
		{
			font-size: 13px;
			font-family: 'Titillium Web',sans-serif;
		}
	</style>
</head>
<body>
	<div class="form-group">
		<h2>{{$transaction_type}}</h2>		
	</div>
<div class="form-group">
	<div class="col-md-6 text-left" style="float: left; width: 50%">
		<strong>PAYMENT TO</strong><br>
		<span>{{$paybill->vendor_company}}</span>
	</div>
	<div class="col-md-6 text-right" style="float: right; width: 50%">
		<div class="col-md-6 text-right" style="float: left; width: 50%">
			<strong>BILLPAYMENT NO.</strong><br>
			<strong>DATE.</strong><br>
		</div>
		<div class="col-md-6 text-left" style="float: left; width: 50%">
			<span>{{sprintf("%'.04d\n", $paybill->paybill_id)}}</span><br>
			<span>{{date('m/d/Y',strtotime($paybill->paybill_date))}}</span><br>
		</div>
	</div>
</div>

<table width="100%" style="padding: 0; margin-top: 20px ">
	<tr>
		<th>Payment For:</th>
		<th width="15%">AMOUNT</th>
	</tr>
	<tbody>
	@if($paybill_line)		
		@foreach($paybill_line as $item)
			<tr >
				<td>BILL #{{$item->pbline_reference_id}}</td>
				<td style="text-align: right;">{{currency("PHP",$item->pbline_amount)}}</td>
			</tr>
		@endforeach
	@endif	
	</tbody>
</table>
	<div class="row" style="text-align:right;margin-right: 10px">
		<h3><strong>TOTAL</strong> {{currency('PHP',($paybill->paybill_total_amount))}}</h3>
	</div>
</body>
<style type="text/css">
	table
	{
		border-collapse: collapse;
		padding: 5px;
	}
	tr th
	{
		padding: 5px;
		border: 1px solid #000;
	}
	.watermark
	{
		font-size: 100px;
		text-align: center;
		 position:fixed;
		 left: 300px;
		 top: 250px;
		 opacity:0.5;
		 z-index:99;
		 color:#000;

		 -ms-transform: rotate(-40deg); /* IE 9 */
	    -webkit-transform: rotate(-40deg); /* Chrome, Safari, Opera */
	    transform: rotate(-40deg);
	}
</style>
</html>