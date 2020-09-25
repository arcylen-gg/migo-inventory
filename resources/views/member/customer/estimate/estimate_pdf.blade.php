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
		<strong>ADDRESS</strong><br>
		<span>{{$estimate->title_name." ".$estimate->first_name." ".$estimate->middle_name." ".$estimate->last_name." ".$estimate->suffix_name}}</span>
	</div>
	<div class="col-md-6 text-right" style="float: right; width: 50%">
		<div class="col-md-6 text-right" style="float: left; width: 50%">
			<strong>{{ucwords($transaction_type)}} NO.</strong><br>
			<strong>DATE.</strong><br>
			<strong>EXPIRATION DATE</strong><br>
		</div>
		<div class="col-md-6 text-left" style="float: left; width: 50%">
			<span>{{sprintf("%'.04d\n", $estimate->est_id)}}</span><br>
			<span>{{date('m/d/Y',strtotime($estimate->est_date))}}</span><br>
			<span>{{date('m/d/Y',strtotime($estimate->est_exp_date))}}</span><br>
		</div>
	</div>
</div>

<table width="100%" style="padding: 0; margin-top: 20px ">
	<tr>
		<th>PRODUCT NAME</th>
		<th width="20%">QTY</th>
		<th width="15%">PRICE</th>
		<th width="15%">AMOUNT</th>
		<th width="15%">Taxable</th>
	</tr>
		<input type="hidden" name="{{$total = 0}}" class="{{$taxable_item = 0}}" >
	<tbody>
	@if($estimate_item)		
		@foreach($estimate_item as $item)
			<tr >
				<td>{{$item->item_name}}</td>
				<td style="text-align: center;">{{$item->qty}}</td>
				<td style="text-align: right;">{{currency("PHP",$item->estline_rate)}}</td>
				<td style="text-align: right;">{{currency("PHP",$item->estline_amount)}}</td>
				<td style="text-align: center;" {{$taxable_item += $item->taxable == 1 ? $item->estline_amount : 0}}>{{$item->taxable == 1 ? "&#10004;" : '' }}</td>
			</tr>
		@endforeach
	@endif	
		<tr>
			<td colspan="2"></td>
			<td colspan="2" style="text-align: left;font-weight: bold">SUBTOTAL</td>
			<td style="text-align: right; font-weight: bold">{{currency('PHP', $estimate->est_subtotal_price)}}</td>
		</tr>
		@if($estimate->ewt != 0)
		<tr>
			<td colspan="2"></td>
			<td colspan="2" style="text-align: left;font-weight: bold">EWT ({{$estimate->ewt * 100}} %)</td>
			<td style="text-align: right; font-weight: bold">{{currency('PHP',$estimate->ewt *  $estimate->est_subtotal_price)}}</td>
		</tr>
		@endif
		@if($estimate->est_discount_value != 0)
		<tr>
			<td colspan="2"></td>
			<td colspan="2" style="text-align: left;font-weight: bold">Discount {{$estimate->est_discount_type == 'percent' ? $estimate->est_discount_type."%" : '' }}</td>
			<td style="text-align: right; font-weight: bold">{{$estimate->est_discount_type == 'value' ? currency("PHP",$estimate->est_discount_value) : currency("PHP",($estimate->est_discount_value/100) * $estimate->est_subtotal_price) }}</td>
		</tr>
		@endif
		@if($estimate->taxable != 0)
		<tr>
			<td colspan="2" ></td>
			<td colspan="2" style="text-align: left;font-weight: bold">Vat (12%)</td>
			<td style="text-align: right; font-weight: bold">{{currency("PHP",$taxable_item * (12/100))  }}</td>
		</tr>
		@endif
		<tr class="{{$cm_total = 0}}">
			<td colspan="2"></td>
			<td colspan="2" style="text-align: left;font-weight: bold">TOTAL</td>
			<td style="text-align: right; font-weight: bold">{{currency("PHP",$estimate->est_overall_price)}}</td>
		</tr>

	</tbody>
</table>
	<div class="row pull-right">
		<h3><strong>TOTAL</strong> {{currency('PHP',$estimate->est_overall_price)}}</h3>
	</div>
	@if($estimate->is_sales_order == 0)
	<table width="100%">
		<tr>
			<td>
				Accepted By: <label>{{$estimate->est_accepted_by}}</label>
			</td>
			<td>
				Accepted Date: <label>{{strtotime($estimate->est_accepted_date) == '' ? '' : dateFormat($estimate->est_accepted_date)}}</label>
			</td>
		</tr>
	</table>
	@endif
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