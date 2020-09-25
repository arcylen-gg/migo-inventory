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
		<h2>CREDIT MEMO</h2>		
	</div>
<div class="form-group" style="padding-bottom: 50px">
	<div class="col-md-6 text-left" style="float: left; width: 50%">
		<strong>Customer </strong><br>
		<span>{{$cm->company}}</span><br>
		<span>{{ucfirst($cm->title_name)." ".ucfirst($cm->first_name)." ".ucfirst($cm->middle_name)." ".ucfirst($cm->last_name)." ".ucfirst($cm->suffix_name)}}</span>
	</div>
	<div class="col-md-6 text-right" style="float: right; width: 50%">
		<div class="col-md-6 text-right" style="float: left; width: 50%">
			<strong>C.M NO.</strong><br>
			<strong>DATE.</strong><br>
		</div>
		<div class="col-md-6 text-left" style="float: left; width: 50%">
			<span>{{sprintf("%'.04d\n", $cm->cm_id)}}</span><br>
			<span>{{date('m/d/Y',strtotime($cm->cm_date))}}</span><br>
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
	@if($_cmline)		
		@foreach($_cmline as $cmline)
			<tr >
				<td>{{$cmline->item_name}}</td>
				<td style="text-align: center;">{{$cmline->cmline_qty}}</td>
				<td style="text-align: right;">{{currency("PHP",$cmline->cmline_rate)}}</td>
				<td style="text-align: right;">{{currency("PHP",$cmline->cmline_amount)}}</td>
			</tr>
		@endforeach
		<!-- <div class="$invoice->inv_is_paid == 1 ? 'watermark' : 'hidden'"> PAID </div> -->
	@endif	
		<!-- <tr>
			<td colspan="1"></td>
			<td colspan="2" style="text-align: left;font-weight: bold">SUBTOTAL</td>
			<td style="text-align: right; font-weight: bold">{{currency('PHP', $cm->po_subtotal_price)}}</td>
		</tr> -->
		

	</tbody>
</table>
	<div class="row pull-right" >
		<h3><strong>TOTAL</strong> {{currency('PHP',($cm->cm_amount))}}</h3>
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