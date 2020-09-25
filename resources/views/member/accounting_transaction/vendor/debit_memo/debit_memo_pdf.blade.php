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
						<span>{{ucfirst($db->vendor_company)}}</span><br>
					<span>{{ucfirst($db->title_name)." ".ucfirst($db->first_name)." ".ucfirst($db->middle_name)." ".ucfirst($db->last_name)." ".ucfirst($db->suffix_name)}}</span>
				</div>
					<div class="col-md-6 text-right" style="float: right; width: 50%">
						<div class="col-md-6 text-right" style="float: left; width: 50%">
							<strong>DM No</strong><br>
							<strong>DATE.</strong><br>
						</div>
						<div class="col-md-6 text-left" style="float: left; width: 50%">
							<span>{{isset($db->transaction_refnum)? $db->transaction_refnum : sprintf("%'.04d\n", $db->db_id)}}</span><br>
							<span>{{date('m/d/Y',strtotime($db->db_date))}}</span><br>
						</div>
					</div>
				</div>	
				<table width="100%" style="padding: 0; margin-top: 20px ">
					<tr>
						@if($check_settings == 1)
				            <th width="20%">BIN LOCATION</th>
				        @endif
						<th>SKU</th>
						<th>DESCRIPTION</th>
						<th width="8%" style="text-align: center;">QTY</th>
						<th width="10%" style="text-align: center;">PRICE</th>
						<th width="8%" style="text-align: center;">DISCOUNT</th>
						<th width="15%" style="text-align: center;">REF #</th>
						<th width="12%" style="text-align: center;">AMOUNT</th>
					</tr>
					<tbody class="draggable tbody-item {{$total = 0}}">
						@foreach($_dbline as $dbline)
						<tr class="tr-draggable">
							@if($check_settings == 1)
				                <td>{{$dbline->warehouse_name}}</td>
				            @endif
				            <td>{{$dbline->item_sku}}</td>
							<td>{{$dbline->item_purchasing_information}}</td>
							<td style="text-align: center;">{{$dbline->qty}}</td>
							<td style="text-align: right;">{{currency("PHP",$dbline->dbline_rate)}}</td>
							@if($dbline->dbline_discounttype == 'fixed')
							<td style="text-align: right;">{{currency("",$dbline->dbline_discount)}}</td>
							@else
							<td style="text-align: right;">{{$dbline->dbline_discount * 100}}%</td>
							@endif
							<td style="text-align: right;">{{$dbline->ref}}</td>
							<td style="text-align: right;">{{currency("PHP",$dbline->dbline_amount)}}</td>
						</tr>
						@endforeach
					</tbody>
				</table>
				<table width="100%" style="padding: 0; margin-top: 20px">
					<tbody>
						<tr>
							<td width="50%"></td>
							<td width="30%" style="text-align: right;font-weight: bold">SUBTOTAL</td>
							<td width="20%" style="text-align: right; font-weight: bold">{{currency('PHP', $subtotal)}}</td>
						</tr>
						@if($db->db_discount_value > 0)
						<tr>
							<td width="50%"></td>
							@if($db->db_discount_type == 'percent')
							<td width="30%" style="text-align: right;font-weight: bold">DISCOUNT ({{$db->db_discount_value}}%)</td>
							<td width="20%" style="text-align: right; font-weight: bold">{{currency('PHP', ($db->db_discount_value / 100) * $subtotal)}}</td>
							@else
							<td width="30%" style="text-align: right;font-weight: bold">DISCOUNT</td>
							<td width="20%" style="text-align: right; font-weight: bold">{{currency('PHP', $db->db_discount_value)}}</td>
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
					<h3><strong>TOTAL</strong> {{currency('PHP',($db->db_amount))}}</h3>
				</div>
				@if($db->db_message !='')	
				<table width="100%" style="padding: 0; margin-top: 20px">
					<tbody>
						<tr>
							<td>
								<label style="font-weight:bold"> Remarks </label><br>
				                {{ucfirst($db->db_message)}}
				            </td>
				        </tr>
					</tbody>
				</table>	
				@endif
				<br>
				@if($db->db_memo !='')
				<table width="100%" style="padding: 0; margin-top: 20px">
					<tbody>	
						<tr>
							<td>
								<label style="font-weight:bold"> Memo </label><br>
				                {{ucfirst($db->db_memo)}}
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
	</style>
</html>