
<!DOCTYPE html>
<html>
<head>
	<title></title>
	<style type="text/css">
		body
		{
			font-size: 14px;
			font-family: 'Arial',sans-serif;
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
				<table style="width: 100%">
					<tr>
						<td style="width: 70% " colspan="2">
							The following number must appear on all correspondence, shipping papers, and invoices:
						</td>
					</tr>
					<tr>
						<td style="width: 70%" colspan="2">
							<h5><u><strong>P.O. NUMBER : {{isset($po->transaction_refnum)? $po->transaction_refnum : sprintf("%'.04d\n", $po->po_id)}}</strong></u></h5>
						</td>
					</tr>
					<tr>
						<td style="width: 70%" colspan="2">
							Date: {{date('m/d/Y', strtotime($po->po_date))}}
						</td>
					</tr>
				</table>
				<br>
				<table style="width: 100%">
					<tr >
						<td style="width: 50%">
							<strong>TO: </strong><br>
							<strong>{{ucfirst($po->vendor_title_name)." ".ucfirst($po->vendor_first_name)." ".ucfirst($po->vendor_middle_name)." ".ucfirst($po->vendor_last_name)." ".ucfirst($po->vendor_suffix_name)}}</strong><br>
							<strong>{{ucfirst($po->vendor_company)}}</strong><br>
							<span>{{$po->ven_billing_street . " " .$po->ven_billing_city}} </span> <br>
							PHILIPPINES <br>
							PHONE : {{$po->ven_info_phone}} Fax : {{$po->ven_info_fax}}
						</td>
						<td style="width: 50%">
							<strong>SHIP TO:</strong><br>
							<strong>{{$shop_info->user_first_name." ".$shop_info->user_last_name}} </strong><br>
							<strong>Fieldmen Janitorial Services Corporation</strong><br>
							<span>
								1040-A Teresa Street, Brgy. Valenzuela, Rizal Village, Makati City
								PHILIPPINES 1224
								Truck Line Number (632) 519-2233  Fax No. : (632) 818-1100
							</span>
						</td>
					</tr>
				</table>
				<br>
				<br>
				<table style="width: 100%; border: solid 1px #000; padding: 2px;border-top: solid 2px #000; ">
					<tr>
						<td style="width: 20%;border: solid 1px #000; padding: 5px;text-align: center"><strong>P.O DATE</strong></td>
						<td style="width: 20%;border: solid 1px #000; padding: 5px;text-align: center"><strong>REQUISITIONER</strong></td>
						<td style="width: 20%;border: solid 1px #000; padding: 5px;text-align: center"><strong> SHIPPED VIA</strong></td>
						<td style="width: 20%;border: solid 1px #000; padding: 5px;text-align: center"><strong> F.O.B Point</strong></td>
						<td style="width: 20%;border: solid 1px #000; padding: 5px;text-align: center"><strong> TERMS</strong></td>
					</tr>
					<tr>
						<td style="width: 20%;border: solid 1px #000; padding: 10px;text-align: center">{{date('m/d/Y',strtotime($po->po_date))}}</td>
						<td style="width: 20%;border: solid 1px #000; padding: 10px;text-align: center">Purchasing Dept.</td>
						<td style="width: 20%;border: solid 1px #000; padding: 10px;text-align: center">Supplier</td>
						<td style="width: 20%;border: solid 1px #000; padding: 10px;text-align: center">Destination, Freight Prepaid and Allowed</td>
						<td style="width: 20%;border: solid 1px #000; padding: 10px;text-align: center">{{$terms}}</td>
					</tr>
				</table>
				<br>
				<br>
				<table style="width: 100%; border:solid 1px #000;border-top: solid 2px #000;border-bottom: solid 1px #000;">
					<tr>
						<td style="width: 15%;border: solid 1px #000; padding: 5px;text-align: center"><strong>QTY</strong></td>
						<td style="width: 55%;border: solid 1px #000; padding: 5px;text-align: center"><strong>DESCRIPTION</strong></td>
						<td style="width: 15%;border: solid 1px #000; padding: 5px;text-align: center"><strong>UNIT PRICE</strong></td>
						<td style="width: 15%;border: solid 1px #000; padding: 5px;text-align: center"><strong>TOTAL</strong></td>
					</tr>

					@if($_poline)		
						@foreach($_poline as $poline)
						<tr>
							<td style="width: 15%;border-right: solid 1px #000; padding: 10px;text-align: center">{{$poline->qty}}</td>
							<td style="width: 55%;border-right: solid 1px #000; padding: 10px;">{{$poline->item_name}} {{$poline->poline_description != '' ? '-'.$poline->poline_description : ''}}</td>
							<td style="width: 15%;border-right: solid 1px #000; padding: 10px;text-align: right">{{currency('',$poline->poline_rate)}}</td>
							<td style="width: 15%;border-right: solid 1px #000; padding: 10px;text-align: right"><u>{{currency('',$poline->poline_amount)}}</u></td>
						</tr>
						@endforeach
						<tr>
							<td style="width: 15%;border-right: solid 1px #000; padding: 10px;text-align: center"></td>
							<td style="width: 55%;border-right: solid 1px #000; padding: 10px;"><strong>----- Nothing Follows -----</strong></td>
							<td style="width: 15%;border-right: solid 1px #000; padding: 10px;text-align: right"></td>
							<td style="width: 15%;border-right: solid 1px #000; padding: 10px;text-align: right"></td>
						</tr>
					@endif
				</table>
				<table style="width: 100%">
					<tr>
						<td style="width: 70%"> * 12% VAT Inclusive</td>
						<td style="width: 15%;text-align: center"><strong>TOTAL</strong></td>
						<td style="width: 15%;text-align: right;border: solid 1px #000;padding: 10px;border-top: solid 1px #fff"><strong>{{currency('P ',($po->po_overall_price))}}</strong></td>
					</tr>
				</table>
				@if($po->po_message)	
				<table width="100%" style="padding: 0; margin-top: 20px">
					<tbody>
						<tr>
							<td>
								<label style="font-weight:bold"> Remarks </label><br>
				                <strong>{{ucfirst($po->po_message)}}</strong>
				            </td>
				        </tr>
					</tbody>
				</table>	
				@endif
				@if($po->po_memo)
				<table class="hidden" width="100%" style="padding: 0; margin-top: 20px">
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
				<br>
				<table style="width: 100%">
					<tr>
						<td style="width: 50%">
							<ol>
								<li> Please send two copies of your invoice. </li>
								<li> Enter this order in accordance with the prices, items, delivery method, and specifications listed above. </li>
								<li> Please notify us immediately if you are unable to ship as specified. <br>
								<li> Send all correspondes to : </li>
								   Jomalyn Y. Palconit <br>
								   Fieldmen Janitorial Services Corporation <br>
								   1040-A Teresa Street, Brgy. Valenzuela, Rizal Village, Makati City <br>
								   PHILIPPINES 1224 <br>
								   Truck Line Number (632) 519-2233  Fax No. : (632) 818-1100 <br>
							</ol>
						</td>
						<td style="width: 50%">
							@include("member.accounting_transaction.pdf_signatures_column")		
						</td>
					</tr>
				</table>
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