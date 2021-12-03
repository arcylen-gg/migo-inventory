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
					<table style="width: 100%">
						<tr>
							<td style="width: 35%">
								<h3>Migo Skin Incorporated</h3>
							</td>
							<td style="width: 30%">
								Main Office: 15 Marcos Alvarez Ave. Talon 5, Las Pinas City
								0917-539-1213 (Globe), 0998-272-4378 (Smart),
								Facebook: www.facebook.com/migoskininc
								Email: migoskin@yahoo.com.ph
								Website: www.migoskin.com
							</td>
							<td style="width: 35%" class="text-center">
								<h4>{{$transaction_type or ''}}</h4>	
							</td>
						</tr>
					</table>
				</div>
				<div class="form-group">
					<div class="col-md-6 text-left" style="float: left; width: 50%">
						<strong>BILL TO</strong><br>
						<span>{{$sr->company != '' ? $sr->company : $sr->title_name." ".$sr->first_name." ".$sr->middle_name." ".$sr->last_name." ".$sr->suffix_name}}</span>
					</div>
					<div class="col-md-6 text-right" style="float: right; width: 50%">
						<div class="col-md-6 text-right" style="float: left; width: 50%">
							<strong>SALES RECEIPT NO.</strong><br>
							<strong>Payment Method</strong><br>
							<strong>DATE.</strong><br>
							<strong>DUE DATE.</strong><br>
							<strong>TERMS.</strong><br>
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
							<span>{{date('m/d/Y',strtotime($sr->inv_date))}}</span><br>
							<span>{{date('m/d/Y',strtotime($sr->inv_due_date))}}</span><br>
							<span>{{$terms}}</span><br>
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
								<td style="text-align: center;">{{$item->invline_qty}}</td>
								<td style="text-align: center;">{{$item->item_name}}</td>
								<td style="text-align: center;">{{$item->invline_description}}</td>
								<td style="text-align: center;">{{$item->item_sku}}</td>
								<td style="text-align: right;">{{currency("PHP",$item->invline_rate)}}</td>
								<td style="text-align: right;">{{currency("PHP",$item->invline_rate * $item->invline_qty)}}</td>
							</tr>
						@endforeach
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
					<span style="font-size: 15px; margin-right: 10px"><strong>TOTAL</strong> {{currency('PHP',$sr->inv_overall_price)}}</span>
					<br>
				
				</div>
				<br>
				@if($sr->inv_message !='')	
				<table width="100%" style="padding: 0; margin-top: 5px">
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
					
				<table width="100%" style="padding: 0; margin-top: 5px">
					<tbody>
						<tr>
							<td>
								<p> Received above merchandise in good order and condition: </p><br>
				            </td>
				        </tr>
						<tr>
							<td>
								&nbsp;
                                <br>
				            </td>
				        </tr>
						<tr>
							<td >
								<p style="width:30%;border-top: 1px solid #000; padding-top:10px">Signature over Printed Name / Date</p>
				            </td>
				        </tr>
					</tbody>
				</table>
				<br>
				<table width="100%" style="padding: 0; margin-top: 0px">
					<tbody>
						<tr>
							<td>
								Returns and any other claims will be entertained within 7 days from the receipts of goods/merchandise.
				            </td>
				        </tr>
					</tbody>
				</table>
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
	.page 
	{
		page-break-after:always;
		position: relative;
	}
</style>
</html>