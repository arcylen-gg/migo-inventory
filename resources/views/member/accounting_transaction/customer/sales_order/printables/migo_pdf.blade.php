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
						<strong>CUSTOMER</strong><br>
						<strong>{{$so->company != '' ? $so->company : $so->title_name." ".$so->first_name." ".$so->middle_name." ".$so->last_name." ".$so->suffix_name}}</strong> <br>
						<span>{{$so->customer_street . " " .$so->customer_city}} </span> <br>
						PHILIPPINES <br>
						PHONE : {{$so->customer_phone}} - {{$so->customer_mobile}} {{$so->customer_fax ? 'Fax :'.$so->customer_fax : '' }}
					</div>
					<div class="col-md-6 text-right" style="float: right; width: 50%">
						<div class="col-md-6 text-right" style="float: left; width: 50%">
							<strong>{{ucwords($transaction_type)}} NO.</strong><br>
							<strong>Payment Method</strong><br><br>
							<strong>DATE</strong><br>
						</div>
						<div class="col-md-6 text-left" style="float: left; width: 50%">
							<span>{{$so->transaction_refnum != '' ? $so->transaction_refnum : sprintf("%'.04d\n", $so->est_id)}}</span><br>
							@if($so->payment_name)
							<span>{{$so->payment_name }}</span><br>
							@else
							<span>
								@foreach($so_pm as $key => $val)
								{{$val->payment_name." - ".number_format($val->estimate_amount,2).", "}}
								@endforeach
							</span><br>
							@endif
							<span>{{date('m/d/Y',strtotime($so->est_date))}}</span><br>
						</div>
					</div>
					<div class="col-md-6 text-left">
						<strong style="display: none">ADDRESS</strong><br>
						<span style="display: none">{{$so->est_customer_billing_address}}</span>
					</div>
				</div>

				<table width="100%" style="padding: 0; margin-top: 20px ">
					<tr>
						<th style="text-align: center;" width="5%">QTY</th>
						<th style="text-align: center;" width="30%">UNIT</th>
						<th style="text-align: center;" width="10%">DESCRIPTION</th>
						<th style="text-align: center;" width="15%">UNIT PRICE</th>
						<th style="text-align: center;" width="15%">TOTAL</th>
					</tr>
						<input type="hidden" name="{{$total = 0}}" class="{{$taxable_item = 0}}" >
					<tbody>
					@if($so_item)		
						@foreach($so_item as $item)
							<tr >
								<td style="text-align: center;">{{$item->estline_qty}}</td>
								<td style="text-align: center;">{{$item->item_name}}</td>
								<td style="text-align: center;">{{$item->estline_description}}</td>
								<td style="text-align: right;">{{currency("PHP",$item->estline_rate)}}</td>
								<td style="text-align: right;">{{currency("PHP",$item->estline_rate * $item->estline_qty)}}</td>
							</tr>
						@endforeach
					@endif	
					</tbody>
				</table>
				<div class="row pull-right" style="margin-right: 10px">
					<h3><strong>TOTAL</strong> {{currency('PHP',$so->est_overall_price)}}</h3>
				</div>
				@if($so->est_message !='')	
				<table width="100%" style="padding: 0; margin-top: 20px">
					<tbody>
						<tr>
							<td>
								<label style="font-weight:bold"> Remarks </label><br>
				                {{ucfirst($so->est_message)}}
				            </td>
				        </tr>
					</tbody>
				</table>	
				@endif
				@if($so->est_memo !='')
				<table width="100%" style="padding: 0; margin-top: 20px">
					<tbody>	
						<tr>
							<td>
								<label style="font-weight:bold"> Memo </label><br>
				                {{ucfirst($so->est_memo)}}
				            </td>
				        </tr>
						
					</tbody>
				</table>
				@endif
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