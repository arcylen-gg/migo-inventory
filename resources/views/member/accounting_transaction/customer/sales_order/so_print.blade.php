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
						PHONE : {{$so->customer_phone}} Fax : {{$so->customer_fax}}
					</div>
					<div class="col-md-6 text-right" style="float: right; width: 50%">
						<div class="col-md-6 text-right" style="float: left; width: 50%">
							<strong>{{ucwords($transaction_type)}} NO.</strong><br>
							<strong>DATE.</strong><br>
						</div>
						<div class="col-md-6 text-left" style="float: left; width: 50%">
							<span>{{$so->transaction_refnum != '' ? $so->transaction_refnum : sprintf("%'.04d\n", $so->est_id)}}</span><br>
							<span>{{date('m/d/Y',strtotime($so->est_date))}}</span><br>
						</div>
					</div>
				</div>

				<table width="100%" style="padding: 0; margin-top: 20px ">
					<tr>
						<th>SKU</th>
						<th>DESCRIPTION</th>
						<th width="12%" style="text-align: center;">QTY</th>
						<th width="12%" style="text-align: center;">RECEIVED</th>
						<th width="12%" style="text-align: center;">BACKORDER</th>
						<th width="15%" style="text-align: center;">PRICE</th>
						<th width="15%" style="text-align: center;">DISCOUNT</th>
						<th width="15%" style="text-align: center;">AMOUNT</th>
						<th width="8%" style="text-align: center;">TAXABLE</th>
					</tr>
						<input type="hidden" name="{{$total = 0}}" class="{{$taxable_item = 0}}" >
					<tbody>
					@if($so_item)		
						@foreach($so_item as $item)
							<tr >
								<td>{{$item->item_sku}}</td>
								<td>{{$item->item_purchasing_information}}</td>
								<td style="text-align: center;">{{$item->qty}}</td>
								<td style="text-align: center;">{{$item->received}}</td>
								<td style="text-align: center;">{{$item->backorder}}</td>
								<td style="text-align: right;">{{currency("PHP",$item->estline_rate)}}</td>
								@if($item->estline_discount_type == 'fixed')
								<td style="text-align: right;">{{currency("",$item->estline_discount)}}</td>
								@else
								<td style="text-align: right;">{{$item->estline_discount * 100}}%</td>
								@endif
								<td style="text-align: right;">{{currency("PHP",$item->estline_amount)}}</td>
								<td style="text-align: center;" {{$taxable_item += $item->taxable == 1 ? $item->estline_amount : 0}}>{{$item->taxable == 1 ? "&#10004;" : '' }}</td>
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