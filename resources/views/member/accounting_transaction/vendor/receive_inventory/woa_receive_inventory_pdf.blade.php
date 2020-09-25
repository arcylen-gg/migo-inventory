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
	<table style="width: 100%">
		<tr>
			<td style="{{$content_width}}">
				<div class="form-group">
					@include("member.accounting_transaction.pdf_header")	
				</div>
				<div class="form-group" style="padding-bottom: 50px">
					<div class="col-md-6 text-left" style="float: left; width: 50%">
						<strong>To </strong><br>
						<strong>{{ucfirst($ri->vendor_company)}}</strong><br>
						<strong>{{ucfirst($ri->vendor_title_name)." ".ucfirst($ri->vendor_first_name)." ".ucfirst($ri->vendor_middle_name)." ".ucfirst($ri->vendor_last_name)." ".ucfirst($ri->vendor_suffix_name)}}</strong> <br>
						<span>{{$ri->ven_billing_street . " " .$ri->ven_billing_city}} </span> <br>
						PHILIPPINES <br>
						PHONE : {{$ri->ven_info_phone}} Fax : {{$ri->ven_info_fax}}
					</div>
					<div class="col-md-6 text-right" style="float: right; width: 50%">
						<div class="col-md-6 text-right" style="float: left; width: 50%">
							<strong>R.I NO.</strong><br>
							<strong>DATE.</strong><br>
							<strong>TERMS.</strong><br>
						</div>
						<div class="col-md-6 text-left" style="float: left; width: 50%">
							<span>{{isset($ri->transaction_refnum) ? $ri->transaction_refnum : sprintf("%'.04d\n", $ri->bill_id)}}</span><br>
							<span>{{date('m/d/Y',strtotime($ri->ri_date))}}</span><br>
							<span>{{$terms}}</span><br>
						</div>
					</div>
				</div>

				<table width="100%" style="padding: 0; margin-top: 20px ">
					<tr>
						<th>SKU/ITEM CODE</th>
						<th>PATTERN</th>
						<th>COLOR</th>
						<th>SIZE</th>
						<th width="20%" style="text-align: center;">QTY</th>
						<th width="15%" style="text-align: center;">PRICE</th>
						<th width="15%" style="text-align: center;">DISCOUNT</th>
						<th width="15%" style="text-align: center;">AMOUNT</th>
					</tr>
						<input type="hidden" name="{{$total = 0}}" class="{{$taxable_item = 0}}" >
					<tbody>
					@if($_riline)		
						@foreach($_riline as $riline)
							<tr >
								<td style="text-align: center;">{{$riline->item_sku}}</td>
								<td style="text-align: center;">{{$riline->pattern}}</td>
								<td style="text-align: center;">{{$riline->color}}</td>
								<td style="text-align: center;">{{$riline->size}}</td>
								<td style="text-align: center;">{{$riline->qty}}</td>
								<td style="text-align: right;">{{currency("PHP",$riline->riline_rate)}}</td>
								@if($riline->riline_discounttype == 'fixed')
								<td style="text-align: right;">{{currency("",$riline->riline_discount)}}</td>
								@else
								<td style="text-align: right;">{{$riline->riline_discount * 100}}%</td>
								@endif
								<td style="text-align: right;">{{currency("PHP",$riline->riline_amount)}}</td>
							</tr>
						@endforeach
						<!-- <div class="$invoice->inv_is_paid == 1 ? 'watermark' : 'hidden'"> PAID </div> -->
					@endif	
					</tbody>
				</table>
				<table width="100%" style="padding: 0; margin-top: 20px">
					<tbody>
						<tr>
							<td width="50%"></td>
							<td width="30%" style="text-align: right;font-weight: bold">SUBTOTAL</td>
							<td width="20%" style="text-align: right; font-weight: bold">{{currency('PHP', $subtotal)}}</td>
						</tr>
						<tr>
							<td width="50%"></td>
							<td width="30%" style="text-align: right;font-weight: bold">TOTAL QUANTITY</td>
							<td width="20%" style="text-align: right; font-weight: bold">{{$_riline[0]->total_quantity}} PC</td>
						</tr>
						@if($ri->ri_discount_value > 0)
						<tr>
							<td width="50%"></td>
							@if($ri->ri_discount_type == 'percent')
							<td width="30%" style="text-align: right;font-weight: bold">DISCOUNT ({{$ri->ri_discount_value}}%)</td>
							<td width="20%" style="text-align: right; font-weight: bold">{{currency('PHP', ($ri->ri_discount_value / 100) * $subtotal)}}</td>
							@else
							<td width="30%" style="text-align: right;font-weight: bold">DISCOUNT</td>
							<td width="20%" style="text-align: right; font-weight: bold">{{currency('PHP', $ri->ri_discount_value)}}</td>
							@endif
						</tr>
						@endif
						@if($count_tax > 0)
						<tr>
							<td width="50%"></td>
							<td width="30%" style="text-align: right;font-weight: bold">VAT (12%)</td>
							<td width="20%" style="text-align: right; font-weight: bold">{{currency('PHP', $total_tax)}}</td>
						</tr> 
						@endif
					</tbody>
				</table>
				<div class="row pull-right" style="padding-right: 20px">
					<h3><strong>TOTAL</strong> {{currency('PHP',($ri->ri_total_amount))}}</h3>
				</div>
				@if($ri->ri_remarks !='')	
				<table width="100%" style="padding: 0; margin-top: 20px">
					<tbody>
						<tr>
							<td>
								<label style="font-weight:bold"> Remarks </label><br>
				                {{ucfirst($ri->ri_remarks)}}
				            </td>
				        </tr>
					</tbody>
				</table>	
				@endif
				<br>
				@if($ri->ri_memo !='')
				<table width="100%" style="padding: 0; margin-top: 20px">
					<tbody>	
						<tr>
							<td>
								<label style="font-weight:bold"> Memo </label><br>
				                {{ucfirst($ri->ri_memo)}}
				            </td>
				        </tr>
					</tbody>
				</table>
				@endif
				<br>
				<br>
				<br>
				@include("member.accounting_transaction.pdf_signatures")
			</td>
			@if($printed_width == "50")
			<td style="{{$content_width}}"> &nbsp;</td>
			@endif
		</tr>
	</table>
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