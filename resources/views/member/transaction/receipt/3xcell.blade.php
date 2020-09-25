<!DOCTYPE html>
<html>
<head>
	<title>3xcell Receipt</title>

	<link href="https://fonts.googleapis.com/css?family=Roboto:400,500,700" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css?family=Fjalla+One" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,400,600,700,800" rel="stylesheet">

	<!-- BOOTSTRAP -->
	<link rel="stylesheet" href="{{ public_path('/assets/initializr/css/bootstrap.min.css') }}">
	<link rel="stylesheet" href="{{ public_path('/assets/initializr/css/bootstrap-theme.min.css') }}">

	<style type="text/css">
	body{font-family:"Open Sans", sans-serif;font-size:15px;font-weight:400}.wrapper{margin:10px 0;padding:20px;border:2px solid #333}.wrapper .header{text-align:center;padding-top:25px}.wrapper .header .h-container .logo-container{max-width:250px;margin:auto}.wrapper .header .h-container .logo-container img{width:100%}.wrapper .header .h-container h1{margin:0;font-weight:800;font-size:24px}.wrapper .header .h-container .right{float:right}.wrapper .content{padding-top:20px}.wrapper .content .per-container{padding:10px;border:2px solid #333}.wrapper .content .per-container .per-label{font-weight:600}.wrapper .content .per-container:nth-child(2),.wrapper .content .per-container:nth-child(3){border-top:0}.wrapper .content .remarks{margin-top:20px;border:2px solid #333;padding:10px 10px 75px 10px;font-weight:700}.wrapper .content .sign{margin-top:20px;padding-top:85px;border-bottom:4px solid #333}.wrapper .content table,.wrapper .content th,.wrapper .content td{border:2px solid #333}.wrapper .content table{margin-top:20px;width:100%}.wrapper .content table th{width:100%;text-align:center;padding:3px 0}.wrapper .content table .head-green{background-color:#c0e4a5}.wrapper .content table .head-blue{background-color:#bcd0eb}.wrapper .content table td{padding:10px}.wrapper .footer{margin-top:20px}.wrapper .footer p{font-size:13px}.wrapper .footer p:nth-child(2){font-weight:600}
	</style>
</head>
<body>	
	<div class="content">
		<div class="container">
			<div class="wrapper">
				<!-- HEADER -->
				<div class="header">
					<div class="h-container">
						<div class="logo-container">
							<img src="{{ public_path('/themes/3xcell/img/logo-lg.png') }}">
						</div>
						<h1>3XCELL-E SALES & MARKETING INC.</h1>
						<p>
							Unit 202 Vicar's Bldg. #31 Visayas Avenue, Brgy. Vasra Quezon City<br>
							Telephone No.(02)518-8637
						</p>
					</div>
					<div class="h-container row clearfix">
						<div class="text-right" style="padding-right: 25px;">
							<p><span>AR NO.</span><span style="color: #00ac47;">{{ $list->transaction_number }}</span></p>
						</div>
					</div>
					<div class="h-container" style="font-weight: 700; font-size: 18px;">ACKNOWLEDGEMENT RECEIPT</div>
					<div class="h-container row clearfix">
						<div class="col-xs-8"></div>
						<div class="col-xs-4">
							<p><span>Date:&nbsp;&nbsp;</span><span>{{ date('M d, Y',strtotime($list->transaction_date_created)) }}</span></p>
						</div>
					</div>
				</div>
				<!-- RECIEPT CONTENT -->
				<div class="content">
					<div class="per-container">
						<span class="per-label">Name:</span>&nbsp;&nbsp;<span>{{ ucwords($customer_name) }}</span>
					</div>
					<div class="per-container">
						<span class="per-label">Address:</span>&nbsp;&nbsp;<span>{{ $customer_address->customer_street }} {{ $customer_address->customer_state }} {{ $customer_address->customer_city }} {{ $customer_address->customer_zipcode }}</span>
					</div>
					<div class="per-container">
						<span class="per-label">Receive the amount of:</span>&nbsp;&nbsp;<span>PHP {{ number_format($list->transaction_subtotal, 2) }}</span>
					</div>
					<div class="row clearfix">
						<div class="col-xs-8">
							<table>
								<tr>
									<th class="head-green">DETAILS</th>
									<th class="head-green" style="width: 50px; padding-left: 10px; padding-right: 10px;">QTY</th>
								</tr>
								@foreach($_item as $key => $item)
		                        <tr>
		                            <td>
		                                <b>{{$item->item_name}}</b>
		                            </td>
		                            <td class="text-center">{{ number_format($item->quantity) }}</td>
		                        </tr>
		                        @if(count($_codes) > 0)
		                        <tr>
		                            <td colspan="2">
		                                @if(isset($_codes[$item->item_id]) && count($_codes[$item->item_id]) > 0)
		                                    @foreach($_codes[$item->item_id] as $c)
		                                    <div>PIN <b>{{$c['item_pin']}}</b> - ACTIVATION CODE <b>{{$c['item_activation']}}</b></div>
		                                    @endforeach
		                                @endif
		                            </td>
		                        </tr>
		                        @endif
		                        @endforeach
							</table>
						</div>
						<div class="col-xs-4">
							<table>
								<tr>
									<th class="head-blue">TOTAL AMOUNT</th>
								</tr>
								@foreach($_item as $key => $item)
		                        <tr>
		                            <td>
		                                <b>{{currency('PHP',$item->subtotal)}}</b>
		                            </td>
		                        </tr>
		                        @if(count($_codes) > 0)
		                        <tr>
		                            <td colspan="2" style="opacity: 0;">
		                                &nbsp;
		                            </td>
		                        </tr>
		                        @endif
		                        @endforeach
							</table>
						</div>
					</div>
					<div class="row clearfix">
						<div class="col-xs-8">
							<div class="remarks">
								REMARKS:
							</div>
						</div>
						<div class="col-xs-4">
							<div class="sign"></div>
							<div><span>AUTHORIZED CASHIER</span></div>
						</div>
					</div>
				</div>
				<!-- FOOTER -->
				<div class="footer">
					<p>This Receipt is not valid unless countersigned by our authorized cashier. Defective payment will automatically render this receipt invalid</p>
					<p>"THIS DOCUMENT IS NOT VALID FOR CLAIMING INPUT TAXES"</p>
				</div>
			</div>
		</div>
	</div>
</body>
</html>