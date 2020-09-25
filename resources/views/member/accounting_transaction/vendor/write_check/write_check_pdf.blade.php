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
						<th>SKU</th>
						<th>DESCRIPTION</th>
						<th width="10%" style="text-align: center;">QTY</th>
						<th width="15%" style="text-align: center;">PRICE</th>
						<th width="15%" style="text-align: center;">DISCOUNT</th>
						<th width="20%" style="text-align: center;">REF #</th>
						<th width="15%" style="text-align: center;">AMOUNT</th>
					</tr>
						<input type="hidden" name="{{$total = 0}}" class="{{$taxable_item = 0}}" >
					<tbody>	
						@foreach($_wcline as $wcline)
							<tr >
								<td>{{$wcline->item_sku}}</td>
								<td>{{$wcline->item_purchasing_information}}</td>
								<td style="text-align: center;">{{$wcline->qty}}</td>
								<td style="text-align: right;">{{currency("PHP",$wcline->wcline_rate)}}</td>
								@if($wcline->wcline_discounttype == 'fixed')
								<td style="text-align: right;">{{currency("",$wcline->wcline_discount)}}</td>
								@else
								<td style="text-align: right;">{{$wcline->wcline_discount * 100}}%</td>
								@endif
								<td style="text-align: right;">{{$wcline->ref}}</td>
								<td style="text-align: right;">{{currency("PHP",$wcline->wcline_amount)}}</td>
							</tr>
						@endforeach
						<!-- <div class="$invoice->inv_is_paid == 1 ? 'watermark' : 'hidden'"> PAID </div> -->
					</tbody>
				</table>
				@endif

				@if(count($_wcline_acc) > 0)
				<table width="100%" style="padding: 0; margin-top: 20px ">
					<tr>
						<th>ACCOUNT#</th>
						<th>ACCOUNT NAME</th>
						<th>DESCRIPTION</th>
						<th width="15%" style="text-align: center;">AMOUNT</th>
					</tr>
						<input type="hidden" name="{{$total = 0}}" class="{{$taxable_item = 0}}" >
					<tbody>
						@foreach($_wcline_acc as $wcline_acc)
							<tr >
								<td>{{$wcline_acc->account_number}}</td>
								<td>{{$wcline_acc->account_name}}</td>
								<td>{{$wcline_acc->account_description}}</td>
								<td style="text-align: right;">{{currency("PHP",$wcline_acc->accline_amount)}}</td>
							</tr>
						@endforeach
					</tbody>
				</table>
				@endif
				<table width="100%" style="padding: 0; margin-top: 20px">
					<tbody>
						@if($total_account_amount > 0)
						<tr>
							<td width="50%"></td>
							<td width="30%" style="text-align: right;font-weight: bold">ITEM</td>
							<td width="20%" style="text-align: right; font-weight: bold">{{currency('PHP', $subtotal)}}</td>
						</tr>
						<tr>
							<td width="50%"></td>
							<td width="30%" style="text-align: right;font-weight: bold">EXPENSE</td>
							<td width="20%" style="text-align: right; font-weight: bold">{{currency('PHP', $total_account_amount)}}</td>
						</tr> 
						<tr>
							<td width="50%"></td>
							<td width="30%" style="text-align: right;font-weight: bold">SUBTOTAL</td>
							<td width="20%" style="text-align: right; font-weight: bold">{{currency('PHP', $subtotal + $total_account_amount)}}</td>
						</tr>
						@else
						<tr>
							<td width="50%"></td>
							<td width="30%" style="text-align: right;font-weight: bold">SUBTOTAL</td>
							<td width="20%" style="text-align: right; font-weight: bold">{{currency('PHP', $subtotal)}}</td>
						</tr>
						@endif
						@if($wc->wc_discount_value > 0)
						<tr>
							<td width="50%"></td>
							@if($wc->wc_discount_type == 'percent')
							<td width="30%" style="text-align: right;font-weight: bold">DISCOUNT ({{$wc->wc_discount_value}}%)</td>
							<td width="20%" style="text-align: right; font-weight: bold">{{currency('PHP', ($wc->wc_discount_value / 100) * $subtotal)}}</td>
							@else
							<td width="30%" style="text-align: right;font-weight: bold">DISCOUNT</td>
							<td width="20%" style="text-align: right; font-weight: bold">{{currency('PHP', $wc->wc_discount_value)}}</td>
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
	<div class="row pull-right" style="text-align:right;margin-right: 10px" >
		<h3><strong>TOTAL</strong> {{currency('PHP',($wc->wc_total_amount))}}</h3>
	</div>
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