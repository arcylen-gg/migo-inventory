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
						<strong>Vendor </strong><br>
						<span style="font-size: 20px">{{ucfirst($po->vendor_company)}}</span><br>
						<span style="font-size: 20px">{{ucfirst($po->title_name)." ".ucfirst($po->first_name)." ".ucfirst($po->middle_name)." ".ucfirst($po->last_name)." ".ucfirst($po->suffix_name)}}</span> <br>
						<span> Contact No.: {{ $po->ven_info_phone }}, {{ $po->ven_info_mobile }}</span><br>
						<span>TIN: {{$po->ven_info_tin_no ?? ''}}</span> <br>

						<span>Bank Details</span> <br>
						@if(!empty($_bank))
							@foreach ($_bank as $bank)
								<span>{{$bank->vendor_account_name}} : {{$bank->vendor_account_number}}</span> <br>
							@endforeach
						@endif
					</div>
					<div class="col-md-6 text-right" style="float: right; width: 50%">
						<div class="col-md-6 text-right" style="float: left; width: 50%">
							<strong>P.O NO.</strong><br>
							<strong>DATE.</strong><br>
							<strong>TERMS.</strong><br>
						</div>
						<div class="col-md-6 text-left" style="float: left; width: 50%">
							<span>{{isset($po->transaction_refnum)? $po->transaction_refnum : sprintf("%'.04d\n", $po->po_id)}}</span><br>
							<span>{{date('m/d/Y',strtotime($po->po_date))}}</span><br>
							<span>{{$terms}}</span><br>
						</div>
					</div>
				</div>

				<table width="100%" style="padding: 0; margin-top: 20px ">
					<tr>
						<th>PRODUCT</th>
						<th>DESCRIPTION</th>
						<th width="20%" style="text-align: center;">QTY</th>
						<th width="12%" style="text-align: center;">RECEIVED</th>
						<th width="12%" style="text-align: center;">BACKORDER</th>
						<th width="15%" style="text-align: center;">PRICE</th>
						<th width="15%" style="text-align: center;">DISCOUNT</th>
						<th width="15%" style="text-align: center;">AMOUNT</th>
					</tr>
						<input type="hidden" name="{{$total = 0}}" class="{{$taxable_item = 0}}" >
					<tbody>
					@if($_poline)		
						@foreach($_poline as $poline)
							<tr >
								<td>{{$poline->item_sku}}</td>
								<td>{{$poline->item_purchasing_information}}</td>
								<td style="text-align: center;">{{$poline->qty}}</td>
								<td style="text-align: center;">{{$poline->received}}</td>
								<td style="text-align: center;">{{$poline->backorder}}</td>
								<td style="text-align: right;">{{currency("PHP",$poline->poline_rate)}}</td>
								@if($poline->poline_discounttype == 'fixed')
								<td style="text-align: right;">{{currency("",$poline->poline_discount)}}</td>
								@else
								<td style="text-align: right;">{{$poline->poline_discount * 100}}%</td>
								@endif
								<td style="text-align: right;">{{currency("PHP",$poline->poline_amount)}}</td>
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
						@if($po->po_discount_value > 0)
						<tr>
							<td width="50%"></td>
							@if($po->po_discount_type == 'percent')
							<td width="30%" style="text-align: right;font-weight: bold">DISCOUNT ({{$po->po_discount_value}}%)</td>
							<td width="20%" style="text-align: right; font-weight: bold">{{currency('PHP', ($po->po_discount_value / 100) * $po->po_subtotal_price)}}</td>
							@else
							<td width="30%" style="text-align: right;font-weight: bold">DISCOUNT</td>
							<td width="20%" style="text-align: right; font-weight: bold">{{currency('PHP', $po->po_discount_value)}}</td>
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
					<h3><strong>TOTAL</strong> {{currency('PHP',($po->po_overall_price))}}</h3>
				</div>
				@if($po->po_message !='')	
				<table width="100%" style="padding: 0; margin-top: 20px">
					<tbody>
						<tr>
							<td>
								<label style="font-weight:bold"> Remarks </label><br>
				                {{ucfirst($po->po_message)}}
				            </td>
				        </tr>
					</tbody>
				</table>	
				@endif
				@if($po->po_memo !='')
				<table width="100%" style="padding: 0; margin-top: 20px">
					<tbody>	
						<tr>
							<td>
								<label style="font-weight:bold"> Memo </label><br>
				                {{ucfirst($po->po_memo)}}
				            </td>
				        </tr>
						
					</tbody>
				</table>
				@endif
				
				<!-- <br>
				<br>
				@if($po->po_message !='') 
				<div>
				    <p>
				       <b>REMARKS : </b><br>
				        {{ucfirst($po->po_message)}}
				    </p>
				</div>
				<br>
				<br>
				@endif

				@if($po->po_memo !='') 
				<div>
				    <p>
				       <b>MEMO : </b><br>
				        {{ucfirst($po->po_memo)}}
				    </p>
				</div>
				@endif
				<br>
				<br> -->
				
				<table width="100%" style="padding: 0; margin-top: 50px">
					<tbody>	
						<tr>
							<td>
								<label style="font-weight:bold"> Requested By: </label><br>
				            </td>
							<td>
								<label style="font-weight:bold"> Approved By: </label><br>
				            </td>
				        </tr>						
					</tbody>
				</table>
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
<script type="text/javascript">
	window.print();
</script>
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