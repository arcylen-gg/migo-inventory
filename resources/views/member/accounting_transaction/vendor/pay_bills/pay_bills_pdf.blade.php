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
						<strong>PAYMENT TO</strong><br>
						<strong>{{ucfirst($pb->vendor_company)}}</strong><br>
						<strong>{{ucfirst($pb->vendor_title_name)." ".ucfirst($pb->vendor_first_name)." ".ucfirst($pb->vendor_middle_name)." ".ucfirst($pb->vendor_last_name)." ".ucfirst($pb->vendor_suffix_name)}}</strong><br>
						<span>{{$pb->ven_billing_street . " " .$pb->ven_billing_city}} </span> <br>
						PHILIPPINES <br>
						PHONE : {{$pb->ven_info_phone}} Fax : {{$pb->ven_info_fax}}
					</div>
					<div class="col-md-6 text-right" style="float: right; width: 50%">
						<div class="col-md-6 text-right" style="float: left; width: 50%">
							<strong>BILLPAYMENT NO.</strong><br>
							<strong>DATE.</strong><br>
							<strong>PAYMENT METHOD.</strong><br>
							<strong>PAYMENT ACCOUNT.</strong><br>
							<strong>PAYMENT REF NO.</strong><br>
						</div>
						<div class="col-md-6 text-left" style="float: left; width: 50%">
							<span>{{isset($pb->transaction_refnum)? $pb->transaction_refnum : sprintf("%'.04d\n", $pb->paybill_id)}}</span><br>
							<span>{{date('m/d/Y',strtotime($pb->paybill_date))}}</span><br>
							<span>{{$pb->payment_name}}</span><br>
							<span>{{$pb->account_number."-".$pb->account_name}}</span><br>
							<span>{{$pb->paybill_ref_num}}</span><br>
						</div>
					</div>
				</div>

				<table width="100%" style="padding: 0; margin-top: 20px ">
					<tr>
						<th>PAYMENT FOR:</th>
						<th>TOTAL AMOUNT</th>
						<th width="15%">PAYMENT</th>
						<th>BALANCE</th>
					</tr>
					<tbody>
					@if($_pbline)		
						@foreach($_pbline as $item)
							<tr >
								@if($item->bill_ri_id)
								<td>Receive Inventory # {{$item->ri_refnum}}</td>
								@else
								<td>Bill # {{$item->bill_refnum}}</td>
								@endif
								<td style="text-align: right;">{{currency("PHP",$item->bill_total_amount)}}</td>
								<td style="text-align: right;">{{currency("PHP",$item->pbline_amount)}}</td>
								@if($item->bill_applied_payment == $item->bill_total_amount)
								<td style="text-align: right;">FULLY PAID</td>
								@else
								<td style="text-align: right;">{{currency("PHP",$item->bill_total_amount - $item->bill_applied_payment)}}</td>
								@endif
							</tr>
						@endforeach
					@endif	
					</tbody>
				</table>
				<div class="row" style="text-align:right;margin-right: 10px">
					<h3><strong>TOTAL</strong> {{currency('PHP',($pb->paybill_total_amount))}}</h3>
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