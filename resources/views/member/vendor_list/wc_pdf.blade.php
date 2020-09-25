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
		<h2>Write Check</h2>		
	</div>
<div class="form-group" style="padding-bottom: 50px">
	<div class="col-md-6 text-left" style="float: left; width: 50%">
		@if($wc->wc_reference_name == 'vendor')
			<strong>Vendor </strong><br>
			<span>{{$wc->vendor_company}}</span><br>
			<span>{{ucfirst($wc->vendor_title_name)." ".ucfirst($wc->vendor_first_name)." ".ucfirst($wc->vendor_middle_name)." ".ucfirst($wc->vendor_last_name)." ".ucfirst($wc->vendor_suffix_name)}}</span>
		@else
			<strong>Customer </strong><br>
			<span>{{$wc->company}}</span><br>
			<span>{{ucfirst($wc->title_name)." ".ucfirst($wc->first_name)." ".ucfirst($wc->middle_name)." ".ucfirst($wc->last_name)." ".ucfirst($wc->suffix_name)}}</span>
		@endif
	</div>
	<div class="col-md-6 text-right" style="float: right; width: 50%">
		<div class="col-md-6 text-right" style="float: left; width: 50%">
			<strong>W.C NO.</strong><br>
			<strong>DATE.</strong><br>
		</div>
		<div class="col-md-6 text-left" style="float: left; width: 50%">
			<span>{{$wc->transaction_refnum != '' ? $wc->transaction_refnum : sprintf("%'.04d", $wc->wc_id)}}</span><br>
			<span>{{date('m/d/Y',strtotime($wc->date_created))}}</span><br>
		</div>
	</div>
</div>

@if(count($_wcline) > 0)
<table width="100%" style="padding: 0; margin-top: 20px ">
	<tr>
		<th>PRODUCT NAME</th>
		<th width="20%">QTY</th>
		<th width="15%">PRICE</th>
		<th width="15%">AMOUNT</th>
	</tr>
		<input type="hidden" name="{{$total = 0}}" class="{{$taxable_item = 0}}" >
	<tbody>	
		@foreach($_wcline as $wcline)
			<tr >
				<td>{{$wcline->item_name}}</td>
				<td style="text-align: center;">{{$wcline->wcline_qty}}</td>
				<td style="text-align: right;">{{currency("PHP",$wcline->wcline_rate)}}</td>
				<td style="text-align: right;">{{currency("PHP",$wcline->wcline_amount)}}</td>
			</tr>
		@endforeach
		<!-- <div class="$invoice->inv_is_paid == 1 ? 'watermark' : 'hidden'"> PAID </div> -->
		<tr>
			<!-- <td colspan="1"></td>
			<td colspan="2" style="text-align: left;font-weight: bold">SUBTOTAL</td>
			<td style="text-align: right; font-weight: bold">{{currency('PHP', $wc->po_subtotal_price)}}</td> -->
		</tr>
		

	</tbody>
</table>
@endif

@if(count($_wcline_acc) > 0)
<table width="100%" style="padding: 0; margin-top: 20px ">
	<tr>
		<th>ACCOUNT NAME</th>
		<th width="50%">DESCRIPTION</th>
		<th width="15%">AMOUNT</th>
	</tr>
		<input type="hidden" name="{{$total = 0}}" class="{{$taxable_item = 0}}" >
	<tbody>
		@foreach($_wcline_acc as $wcline_acc)
			<tr >
				<td>{{$wcline_acc->account_number}} - {{$wcline_acc->account_name}}</td>
				<td>{{$wcline_acc->accline_description}}</td>
				<td style="text-align: right;">{{currency("PHP",$wcline_acc->accline_amount)}}</td>
			</tr>
		@endforeach
		<!-- <div class="$invoice->inv_is_paid == 1 ? 'watermark' : 'hidden'"> PAID </div> -->

		<tr>
			<!-- <td colspan="1"></td>
			<td colspan="2" style="text-align: left;font-weight: bold">SUBTOTAL</td>
			<td style="text-align: right; font-weight: bold">{{currency('PHP', $wc->po_subtotal_price)}}</td> -->
		</tr>
		

	</tbody>
</table>
@endif
	<div class="row pull-right" >
		<h3><strong>TOTAL</strong> {{currency('PHP',($wc->wc_total_amount))}}</h3>
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