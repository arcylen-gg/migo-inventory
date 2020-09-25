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
		<h2>Purchase Order</h2>		
	</div>
<div class="form-group" style="padding-bottom: 50px">
	<div class="col-md-6 text-left" style="float: left; width: 50%">
		<strong>Vendor </strong><br>
		<span>{{$po->vendor_company}}</span><br>
		<span>{{$po->vendor_title_name." ".$po->vendor_first_name." ".$po->vendor_middle_name." ".$po->vendor_last_name." ".$po->vendor_suffix_name}}</span> <br>
		<span>{{$po->ven_billing_street . " " .$po->ven_billing_city}} </span> <br>
		PHILIPPINES <br>
		PHONE : {{$po->ven_info_phone}} Fax : {{$po->ven_info_fax}}
	</div>
	<div class="col-md-6 text-right" style="float: right; width: 50%">
		<div class="col-md-6 text-right" style="float: left; width: 50%">
			<strong>P.O NO.</strong><br>
			<strong>DATE.</strong><br>
		</div>
		<div class="col-md-6 text-left" style="float: left; width: 50%">
			<span>{{sprintf("%'.04d\n", $po->po_id)}}</span><br>
			<span>{{date('m/d/Y',strtotime($po->po_date))}}</span><br>
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
	@if($_poline)		
		@foreach($_poline as $poline)
			<tr >
				<td>{{$poline->item_name}}</td>
				<td style="text-align: center;">{{$poline->qty}}</td>
				<td style="text-align: right;">{{currency("PHP",$poline->poline_rate)}}</td>
				<td style="text-align: right;">{{currency("PHP",$poline->poline_amount)}}</td>
			</tr>
		@endforeach
		<!-- <div class="$invoice->inv_is_paid == 1 ? 'watermark' : 'hidden'"> PAID </div> -->
	@endif	
		<tr>
			<td colspan="1"></td>
			<td colspan="2" style="text-align: left;font-weight: bold">SUBTOTAL</td>
			<td style="text-align: right; font-weight: bold">{{currency('PHP', $po->po_subtotal_price)}}</td>
		</tr>
		

	</tbody>
</table>
	<div class="row pull-right" >
		<h3><strong>TOTAL</strong> {{currency('PHP',($po->po_overall_price))}}</h3>
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