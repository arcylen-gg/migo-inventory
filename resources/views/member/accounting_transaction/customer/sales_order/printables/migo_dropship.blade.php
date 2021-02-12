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
                    <h4>DELIVERY RECEIPT</h4>
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
						<strong>ADDRESS</strong><br>
						<span>{{$so->est_customer_billing_address}}</span>
					</div>
				</div>

				<table width="100%" style="padding: 0; margin-top: 20px ">
					<tr>
						<th style="text-align: center;" width="5%">QTY</th>
						<th style="text-align: center;" width="30%">UNIT</th>
						<th style="text-align: center;" width="10%">DESCRIPTION</th>
					</tr>
						<input type="hidden" name="{{$total = 0}}" class="{{$taxable_item = 0}}" >
					<tbody>
					@if($so_item)		
						@foreach($so_item as $item)
							<tr >
								<td style="text-align: center;">{{$item->estline_qty}}</td>
								<td style="text-align: center;">{{$item->item_name}}</td>
								<td style="text-align: center;">{{$item->estline_description}}</td>
							</tr>
						@endforeach
					@endif	
					</tbody>
				</table>	
				<table width="100%" style="padding: 0; margin-top: 20px">
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
				<br>
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