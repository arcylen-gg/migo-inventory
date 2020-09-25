<!DOCTYPE html>
<html>
<head>
	<title></title>
	<style type="text/css">
		body
		{
			font-size: 11px;
			font-family: 'Titillium Web',sans-serif;
		}
	</style>
</head>
<body>
	<div class="form-group">
		<h2>Receive Inventory</h2>		
	</div>
<div class="form-group" style="padding-bottom: 50px">
	<div class="col-md-6 text-left" style="float: left; width: 50%">
		<strong>Vendor </strong><br>
		<span>{{$ri->vendor_company}}</span><br>
		<span>{{$ri->title_name." ".$ri->first_name." ".$ri->middle_name." ".$ri->last_name." ".$ri->suffix_name}}</span>
	</div>
	<div class="col-md-6 text-right" style="float: right; width: 50%">
		<div class="col-md-6 text-right" style="float: left; width: 50%">
			<strong>R.I NO.</strong><br>
			<strong>DATE.</strong><br>
		</div>
		<div class="col-md-6 text-left" style="float: left; width: 50%">
			<span>{{sprintf("%'.04d\n", $ri->bill_id)}}</span><br>
			<span>{{date('m/d/Y',strtotime($ri->bill_date))}}</span><br>
		</div>
	</div>
</div>

<table width="100%" style="padding: 0; margin-top: 20px ">
	<tr>
		<th>PRODUCT NAME</th>
		<th width="20%">QTY</th>
		<th width="15%">PRICE</th>
		<th width="15%">AMOUNT</th>
	</tr>
		<input type="hidden" name="{{$total = 0}}" class="{{$taxable_item = 0}}" >
	<tbody>
	@if($_riline)		
		@foreach($_riline as $riline)
			<tr >
				<td>{{$riline->item_name}}</td>
				<td style="text-align: center;">{{$riline->itemline_qty}}</td>
				<td style="text-align: right;">{{currency("PHP",$riline->itemline_rate)}}</td>
				<td style="text-align: right;">{{currency("PHP",$riline->itemline_amount)}}</td>
			</tr>
		@endforeach
		<!-- <div class="$invoice->inv_is_paid == 1 ? 'watermark' : 'hidden'"> PAID </div> -->
	@endif	
		<tr>
			<!-- <td colspan="1"></td>
			<td colspan="2" style="text-align: left;font-weight: bold">SUBTOTAL</td>
			<td style="text-align: right; font-weight: bold">{{currency('PHP', $ri->po_subtotal_price)}}</td> -->
		</tr>
		

	</tbody>
</table>
	<div class="row pull-right" >
		<h3><strong>TOTAL</strong> {{currency('PHP',($ri->bill_total_amount))}}</h3>
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