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
		<strong>BILL TO</strong><br>
		<span>{{$bill->vendor_company}}</span>
	</div>
	<div class="col-md-6 text-right" style="float: right; width: 50%">
		<div class="col-md-6 text-right" style="float: left; width: 50%">
			<strong>BILL NO.</strong><br>
			<strong>DATE.</strong><br>
			<strong>DUE DATE</strong><br>
		</div>
		<div class="col-md-6 text-left" style="float: left; width: 50%">
			<span>{{sprintf("%'.04d\n", $bill->bill_id)}}</span><br>
			<span>{{date('m/d/Y',strtotime($bill->bill_date))}}</span><br>
			<span>{{date('m/d/Y',strtotime($bill->bill_due_date))}}</span><br>
		</div>
	</div>
</div>

<table width="100%" style="padding: 0; margin-top: 20px ">
	<tr>
		<th>PRODUCT NAME</th>
		<th>DESCRIPTION</th>
		<th width="10%">QTY</th>
		<th width="10%">U/M</th>
		<th width="15%">PRICE</th>
		<th width="15%">AMOUNT</th>
		<th width="10%">REF</th>
	</tr>
	<tbody>
	@if($bill_item)		
		@foreach($bill_item as $item)
			<tr >
				<td>{{$item->item_name}}</td>
				<td>{{$item->itemline_description}}</td>
				<td style="text-align: center;">{{$item->itemline_qty}}</td>
				<td style="text-align: center;">{{$item->multi_abbrev}}</td>
				<td style="text-align: right;">{{number_format($item->itemline_rate,2)}}</td>
				<td style="text-align: right;">{{number_format($item->itemline_amount,2)}}</td>
				<td style="text-align: right;">{{($item->itemline_ref_name == 'purchase_order' ? 'PO#' : '' ) . $item->itemline_ref_id}}</td>
			</tr>
		@endforeach
		<div class="{{$bill->bill_is_paid == 1 ? 'watermark' : 'hidden'}}"> PAID </div>
	@endif	
	</tbody>
</table>
	<div class="row" style="text-align:right;margin-right: 10px">
		<h3><strong>TOTAL</strong> {{currency('PHP',($bill->bill_total_amount))}}</h3>
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
	html
	{
		font-size: 13px;
	}
</style>
</html>