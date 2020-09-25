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
				<div class="form-group">
					<div class="col-md-6 text-left" style="float: left; width: 50%">
						<strong>BILL TO</strong><br>
						<span>{{$invoice->company != '' ? $invoice->company : $invoice->title_name." ".$invoice->first_name." ".$invoice->middle_name." ".$invoice->last_name." ".$invoice->suffix_name}}</span>
					</div>
					<div class="col-md-6 text-right" style="float: right; width: 50%">
						<div class="col-md-6 text-right" style="float: left; width: 50%">
							<strong>INVOICE NO.</strong><br>
							<strong>Payment Method</strong><br>
							<strong>DATE.</strong><br>
							<strong>DUE DATE.</strong><br>
							<strong>TERMS.</strong><br>
						</div>
						<div class="col-md-6 text-left" style="float: left; width: 50%">
							<span>{{$invoice->transaction_refnum != '' ? $invoice->transaction_refnum : sprintf("%'.04d\n", $invoice->new_inv_id)}}</span><br>
							<span>{{$invoice->payment_name}}</span><br>
							<span>{{date('m/d/Y',strtotime($invoice->inv_date))}}</span><br>
							<span>{{date('m/d/Y',strtotime($invoice->inv_due_date))}}</span><br>
							<span>{{$terms}}</span><br>
						</div>
					</div>
					<div class="col-md-6 text-left">
						<strong>ADDRESS</strong><br>
						<span>{{$invoice->inv_customer_billing_address}}</span>
					</div>
				</div>
				<table width="100%" style="padding: 0; margin-top: 20px ">
					<tr>
                        <th style="text-align: center;">SKU/ITEM CODE</th>
						<th style="text-align: center;">PATTERN</th>
						<th style="text-align: center;">COLOR</th>
						<th style="text-align: center;">SIZE</th>
                        <th style="text-align: center;">QTY</th>
						<th style="text-align: center;">PRICE</th>
                        <th style="text-align: center;">DISCOUNT</th>
						<th style="text-align: center;">AMOUNT</th>
                        <th style="text-align: center;">TAXABLE</th>

						<!-- // <th style="text-align: center;">QTY</th>
						// <th style="text-align: center;">UNIT</th>
						// <th style="text-align: center;">DESCRIPTION</th>
						// <th style="text-align: center;">ITEM</th>
						// <th style="text-align: center;">UNIT PRICE</th>
						// <th style="text-align: center;">TOTAL</th> -->
					</tr>
						<input type="hidden" name="{{$total = 0}}" class="{{$taxable_item = 0}}" >
					<tbody>
					@if($invoice_item)		
						@foreach($invoice_item as $item)
							<tr>
                                <td style="text-align: center;">{{$item->item_sku}}</td>
                                <td style="text-align: center;">{{$item->pattern}}</td>
                                <td style="text-align: center;">{{$item->color}}</td>
                                <td style="text-align: center;">{{$item->size}}</td>
								<td style="text-align: center;">{{$item->invline_qty}}</td>
                                <td style="text-align: right;">{{currency("PHP",$item->invline_rate)}}</td>
                                @if($item->invline_discount_type == 'fixed')
								<td style="text-align: right;">{{$item->invline_discount == '' ? 0 : currency("",$item->invline_discount)}}</td>
								@else
								<td style="text-align: right;">{{$item->invline_discount * 100}}%</td>
								@endif
								<td style="text-align: right;">{{currency("PHP",$item->invline_amount)}}</td>
								<td style="text-align: center;" {{$taxable_item += $item->taxable == 1 ? $item->invline_amount : 0}}>{{$item->taxable == 1 ? "&#10004;" : '' }}</td>
							</tr>
						@endforeach
						<div class="{{$invoice->inv_is_paid == 1 ? 'watermark' : 'hidden'}}"> PAID </div>
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
                        <tr>
							<td width="50%"></td>
							<td width="30%" style="text-align: right;font-weight: bold">TOTAL QUANTITY</td>
							<td width="20%" style="text-align: right; font-weight: bold">{{$invoice_item[0]['total_quantity']}} PCS </td>
						</tr>
						@if($invoice->ewt > 0)
							<tr>
								<td width="50%"></td>
								<td width="30%" style="text-align: right;font-weight: bold">EWT ({{$invoice->ewt * 100}}%)</td>
								<td width="20%" style="text-align: right; font-weight: bold">{{currency('PHP',$invoice->ewt *  $subtotal)}}</td>
							</tr>
						@endif
						@if($invoice->inv_discount_value > 0)
						<tr>
							<td width="50%"></td>
							@if($invoice->inv_discount_type == 'percent')
							<td width="30%" style="text-align: right;font-weight: bold">DISCOUNT ({{$invoice->inv_discount_value}}%)</td>
							<td width="20%" style="text-align: right; font-weight: bold">{{currency('PHP', ((($subtotal + $total_tax) - ($invoice->ewt *  $subtotal)) * ($invoice->inv_discount_value / 100)))}}</td>
							@else
							<td width="30%" style="text-align: right;font-weight: bold">DISCOUNT</td>
							<td width="20%" style="text-align: right; font-weight: bold">{{currency('PHP', $invoice->inv_discount_value)}}</td>
							@endif
						</tr>
						@endif
					</tbody>
				</table>
				<div class="row pull-right" style="margin-right: 10px">
					<h3 style="margin-right: 10px"><strong>TOTAL</strong> {{currency('PHP',$invoice->inv_overall_price)}}</h3>
					<br>
					<h3 style="margin-right: 10px"><strong>AMOUNT PAID</strong> {{currency('PHP',$invoice->inv_payment_applied)}}</h3>
					<br>
					<h3 style="margin-right: 10px; color:#009933"><strong>BALANCE</strong> {{currency('PHP',$invoice->inv_overall_price - $invoice->inv_payment_applied)}}</h3>
				</div>
				<br>
				@if($invoice->inv_message !='')	
				<table width="100%" style="padding: 0; margin-top: 20px">
					<tbody>
						<tr>
							<td>
								<label style="font-weight:bold"> Remarks </label><br>
				                {{ucfirst($invoice->inv_message)}}
				            </td>
				        </tr>
					</tbody>
				</table>	
				@endif
				<br>
				@if($invoice->inv_memo !='')
				<table width="100%" style="padding: 0; margin-top: 20px">
					<tbody>	
						<tr>
							<td>
								<label style="font-weight:bold"> Memo </label><br>
				                {{ucfirst($invoice->inv_memo)}}
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