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
	<table style="width: 100%">
		<tr>
			<td style="{{$content_width}}">
				<div class="form-group">
					@include("member.accounting_transaction.pdf_header")	
				</div>
				<div class="form-group">
					<div class="col-md-6 text-left" style="float: left; width: 50%">
						<strong>BILL TO</strong><br>
						<span>{{$sr->company != ''? $sr->company : $sr->title_name." ".$sr->first_name." ".$sr->middle_name." ".$sr->last_name." ".$sr->suffix_name}}</span>
					</div>
					<div class="col-md-6 text-right" style="float: right; width: 50%">
						<div class="col-md-6 text-right" style="float: left; width: 50%">
							<strong>SALES RECEIPT NO.</strong><br>
							<strong>Payment Method</strong><br><br>
							<strong>DATE.</strong><br>
							<strong>SALES REP.</strong><br>
						</div>
						<div class="col-md-6 text-left" style="float: left; width: 50%">
							<span>{{$sr->transaction_refnum != '' ? $sr->transaction_refnum : sprintf("%'.04d\n", $sr->new_inv_id)}}</span><br>
							@if($sr->payment_name)
							<span>{{$sr->payment_name }}</span><br>
							@else
							<span>
								@foreach($sr_pm as $key => $val)
								{{$val->payment_name." - ".number_format($val->invoice_amount,2).", "}}
								@endforeach
							</span><br>
							@endif
							<span>{{date('m/d/Y',strtotime($sr->inv_date))}}</span><br><br>
							<span>{{isset($sales_rep) ? $sales_rep->sales_rep_employee_number : '' }}</span><br>
						</div>
					</div>
					<div class="col-md-6 text-left">
						<strong>ADDRESS</strong><br>
						<span>{{$sr->inv_customer_billing_address != '' ? $sr->inv_customer_billing_address : $customer_address}}</span><br>
						<span> PHONE : {{$sr->customer_phone}} - {{$sr->customer_mobile}} {{$sr->customer_fax ? 'Fax :'.$sr->customer_fax : '' }}</span>
					</div>
				</div>
				<table width="100%" style="padding: 0; margin-top: 20px ">
					<tr>
						<th style="text-align: center;" width="5%">QTY</th>
						<th style="text-align: center;" width="30%">UNIT</th>
						<th style="text-align: center;" width="10%">DESCRIPTION</th>
						<th style="text-align: center;" width="25%">ITEM</th>
						<th style="text-align: center;" width="15%">UNIT PRICE</th>
						<th style="text-align: center;" width="15%">TOTAL</th>			
					</tr>
						<input type="hidden" name="{{$total = 0}}" class="{{$taxable_item = 0}}" >
					<tbody>
					@if($sr_item)		
						@foreach($sr_item as $item)
							<tr>
								<td style="text-align: center;padding-top:2px">{{$item->invline_qty}}</td>
								<td style="text-align: center;">{{$item->item_name}}</td>
								<td style="text-align: center;">{{$item->invline_description}}</td>
								<td style="text-align: center;">{{$item->item_sku}}</td>
								<td style="text-align: right;">{{currency("PHP",$item->invline_rate)}}</td>
								<td style="text-align: right;">{{currency("PHP",$item->invline_rate * $item->invline_qty)}}</td>
							</tr>
						@endforeach
						<div class="{{$sr->inv_is_paid == 1 ? 'watermark' : 'hidden'}}"> PAID </div>
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
							<td width="30%" style="text-align: right;font-weight: bold">VATABLE</td>
							<td width="20%" style="text-align: right; font-weight: bold">{{currency('PHP', $subtotal)}}</td>
						</tr>
						<tr>
							<td width="50%"></td>
							<td width="30%" style="text-align: right;font-weight: bold">VAT AMOUNT</td>
							<td width="20%" style="text-align: right; font-weight: bold">{{currency('PHP', $total_tax)}}</td>
						</tr>
						@if($sr->ewt > 0)
							<tr>
								<td width="50%"></td>
								<td width="30%" style="text-align: right;font-weight: bold">EWT ({{$sr->ewt * 100}}%)</td>
								<td width="20%" style="text-align: right; font-weight: bold">{{currency('PHP',$sr->ewt *  $subtotal)}}</td>
							</tr>
						@endif
						@if($sr->inv_discount_value > 0)
						<tr>
							<td width="50%"></td>
							@if($sr->inv_discount_type == 'percent')
							<td width="30%" style="text-align: right;font-weight: bold">DISCOUNT ({{$sr->inv_discount_value}}%)</td>
							<td width="20%" style="text-align: right; font-weight: bold">{{currency('PHP', ((($subtotal + $total_tax) - ($sr->ewt *  $subtotal)) * ($sr->inv_discount_value / 100)))}}</td>
							@else
							<td width="30%" style="text-align: right;font-weight: bold">DISCOUNT</td>
							<td width="20%" style="text-align: right; font-weight: bold">{{currency('PHP', $sr->inv_discount_value)}}</td>
							@endif
						</tr>
						@endif
					</tbody>
				</table>
				<div class="row pull-right" style="margin-right: 10px">
					<h3><strong>TOTAL</strong> {{currency('PHP',$sr->inv_overall_price)}}</h3>
				</div>
				@if($sr->inv_message !='')
				<table width="100%" style="padding: 0; margin-top: 20px">
					<tbody>
						<tr>
							<td>
								<label style="font-weight:bold"> Remarks </label><br>
				                {{ucfirst($sr->inv_message)}}
				            </td>
				        </tr>
					</tbody>
				</table>	
				@endif
				<br>
				@if($sr->inv_memo !='')
				<table width="100%" style="padding: 0; margin-top: 20px">
					<tbody>	
						<tr>
							<td>
								<label style="font-weight:bold"> Memo </label><br>
				                {{ucfirst($sr->inv_memo)}}
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
	.page 
	{
		page-break-after:always;
		position: relative;
	}
</style>
</html>