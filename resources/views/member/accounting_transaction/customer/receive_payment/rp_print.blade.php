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
						<strong>PAYMENT FROM</strong><br>
						<span>{{$receive_payment->company}}</span><br>
						<span>{{ucfirst($receive_payment->title_name).' '.ucfirst($receive_payment->first_name).' '.ucfirst($receive_payment->middle_name).' '.ucfirst($receive_payment->last_name).' '.ucfirst($receive_payment->last_name)}}</span>
					</div>
					<div class="col-md-6 text-right" style="float: right; width: 50%">
						<div class="col-md-6 text-right" style="float: left; width: 50%">
							<strong>PAYMENT NO.</strong><br>
							<strong>DATE.</strong><br>
							<strong>PAYMENT METHOD.</strong><br>
							<strong>PAYMENT ACCOUNT.</strong><br>
						</div>
						<div class="col-md-6 text-left" style="float: left; width: 50%">
							<span>{{$receive_payment->transaction_refnum != '' ? $receive_payment->transaction_refnum : sprintf("%'.04d\n", $receive_payment->rp_id)}}</span><br>
							<span>{{date('m/d/Y',strtotime($receive_payment->rp_date))}}</span><br>
							<span>{{$receive_payment->payment_name}}</span><br>
							<span>{{$receive_payment->account_number."-".$receive_payment->account_name}}</span><br>
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
					@if($_invoice)		
						@foreach($_invoice as $item)
						<tr >
							<td>{{ $item->transaction_refnum != '' ? $item->transaction_refnum : "INV #".$item->rpline_reference_id}}</td>
							<td style="text-align: right;">{{currency("PHP",$item->inv_overall_price)}}</td>
							<td style="text-align: right;">{{currency("PHP",$item->rpline_amount)}}</td>
							@if($item->inv_payment_applied == $item->inv_overall_price)
							<td style="text-align: right;">FULLY PAID</td>
							@else
							<td style="text-align: right;">{{currency("PHP",$item->inv_overall_price - $item->inv_payment_applied)}}</td>
							@endif
						</tr>
						@endforeach
					@endif	
					</tbody>
				</table>
				<div class="row" style="text-align:right;margin-right: 10px">
					<h3><strong>TOTAL</strong> {{currency('PHP',($receive_payment->rp_total_amount))}}</h3>
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