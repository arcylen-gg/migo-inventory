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
						<span>{{ucfirst($eb->vendor_company)}}</span><br>
						<span>{{ucfirst($eb->vendor_title_name." ".$eb->vendor_first_name." ".$eb->vendor_middle_name." ".$eb->vendor_last_name." ".$eb->vendor_suffix_name)}}</span>
					</div>
					<div class="col-md-6 text-right" style="float: right; width: 50%">
						<div class="col-md-6 text-right" style="float: left; width: 50%">
							<strong>BILL NO.</strong><br>
							<strong>DATE.</strong><br>
							<strong>DUE DATE.</strong><br>
							<strong>TERMS.</strong><br>
						</div>
						<div class="col-md-6 text-left" style="float: left; width: 50%">
							<span>{{isset($eb->transaction_refnum)? $eb->transaction_refnum : sprintf("%'.04d\n", $eb->bill_id)}}</span><br>
							<span>{{date('m/d/Y',strtotime($eb->bill_date))}}</span><br>
							<span>{{date('m/d/Y',strtotime($eb->bill_due_date))}}</span><br>
							<span>{{$terms}}</span><br>
						</div>
					</div>
				</div>

				<table width="100%" style="padding: 0; margin-top: 20px ">
					<tr>
						<th>SKU</th>
						<th>DESCRIPTION</th>
						<th width="10%" style="text-align: center;">QTY</th>
						<th width="15%" style="text-align: center;">PRICE</th>
						<th width="15%" style="text-align: center;">DISCOUNT</th>
						<th width="15%" style="text-align: center;">REF #</th>
						<th width="15%" style="text-align: center;">AMOUNT</th>
					</tr>
					<tbody>
					@if($_ebline)		
						@foreach($_ebline as $ebline)
							<tr >
								<td>{{$ebline->item_sku}}</td>
								<td>{{$ebline->item_purchasing_information}}</td>
								<td style="text-align: center;">{{$ebline->qty}}</td>
								<td style="text-align: right;">{{currency("PHP",$ebline->itemline_rate)}}</td>
								@if($ebline->itemline_discounttype == 'fixed')
								<td style="text-align: right;">{{currency("",$ebline->itemline_discount)}}</td>
								@else
								<td style="text-align: right;">{{$ebline->itemline_discount * 100}}%</td>
								@endif
								<td style="text-align: right;">{{$ebline->ref}}</td>
								<td style="text-align: right;">{{currency("PHP",$ebline->itemline_amount)}}</td>
							</tr>
						@endforeach
						<div class="{{$eb->bill_is_paid == 1 ? 'watermark' : 'hidden'}}"> PAID </div>
					@endif	
					</tbody>
				</table>
				@if(count($_ebaccount) > 0)
				<table width="100%" style="padding: 0; margin-top: 20px ">
					<tr>
						<th>ACCOUNT#</th>
						<th>ACCOUNT NAME</th>
						<th>DESCRIPTION</th>
						<th width="15%" style="text-align: center;">AMOUNT</th>
					</tr>
					<tbody>		
						@foreach($_ebaccount as $ebaccount)
						<tr >
							<td>{{$ebaccount->account_number}}</td>
							<td>{{$ebaccount->account_name}}</td>
							<td>{{$ebaccount->account_description}}</td>
							<td style="text-align: right;">{{currency("PHP",$ebaccount->accline_amount)}}</td>
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
						@if($eb->bill_discount_value > 0)
						<tr>
							<td width="50%"></td>
							@if($eb->bill_discount_type == 'percent')
							<td width="30%" style="text-align: right;font-weight: bold">DISCOUNT ({{$eb->bill_discount_value}}%)</td>
							<td width="20%" style="text-align: right; font-weight: bold">{{currency('PHP', ($eb->bill_discount_value / 100) * $subtotal)}}</td>
							@else
							<td width="30%" style="text-align: right;font-weight: bold">DISCOUNT</td>
							<td width="20%" style="text-align: right; font-weight: bold">{{currency('PHP', $eb->bill_discount_value)}}</td>
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
				<div class="row" style="text-align:right;margin-right: 10px">
					<h3><strong>TOTAL</strong> {{currency('PHP',($eb->bill_total_amount))}}</h3>
				</div>
				@if($eb->bill_remarks !='')	
				<table width="100%" style="padding: 0; margin-top: 20px">
					<tbody>
						<tr>
							<td>
								<label style="font-weight:bold"> Remarks </label><br>
				                {{ucfirst($eb->bill_remarks)}}
				            </td>
				        </tr>
					</tbody>
				</table>	
				@endif
				<br>
				@if($eb->bill_memo !='')
				<table width="100%" style="padding: 0; margin-top: 20px">
					<tbody>	
						<tr>
							<td>
								<label style="font-weight:bold"> Memo </label><br>
				                {{ucfirst($eb->bill_memo)}}
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
	html
	{
		font-size: 13px;
	}
</style>
</html>