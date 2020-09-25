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
	<div class="form-group">
		<h2>{{$type}}</h2>
	</div>
	<div class="form-group" style="padding-bottom: 50px">
		<div class="col-md-6 text-left" style="float: left; width: 50%">
			<strong>Vendor </strong><br>
			<span>{{$db->vendor_company}}</span><br>
			<span>{{$db->title_name." ".$db->first_name." ".$db->middle_name." ".$db->last_name." ".$db->suffix_name}}</span>
		</div>
		<div class="col-md-6 text-right" style="float: right; width: 50%">
			<div class="col-md-6 text-right" style="float: left; width: 50%">
				<strong>{{$type}} No</strong><br>
				<strong>DATE.</strong><br>
			</div>
			<div class="col-md-6 text-left" style="float: left; width: 50%">
				<span>{{sprintf("%'.04d\n", $db->db_id)}}</span><br>
				<span>{{date('m/d/Y',strtotime($db->db_date))}}</span><br>
			</div>
		</div>
	</div>	
	<div class="row clearfix draggable-container db-replace-container">
		<div >
			<div class="col-sm-12">
				<table class="digima-table" style="width:100%">
					<thead >
						<tr>
							<th style="width: 180px;">Product/Service</th>
							<th style="width: 120px;">QTY</th>
							<th style="width: 100px;">Rate</th>
							<th style="width: 100px;">Amount</th>
							@if($db->is_bad_order == 1)
							<th style="width: 120px;">Replace QTY</th>
							<th style="width: 100px;">Rate</th>
							<th style="width: 100px;">Amount</th>
							<th style="width: 100px;">Total Amount</th>
							<th style="width: 100px;">Condemned</th>
							@endif
						</tr>
					</thead>
					<tbody class="draggable tbody-item {{$total = 0}}">
						@foreach($_db_item as $dbline)
						<tr class="tr-draggable">
							<td><span>{{ $dbline->item_name}}</span></td>
							<td><span>{{$dbline->dbline_qty_um}}</span></td>
							<td class="text-right"><span>{{number_format($dbline->dbline_rate,2)}}</span></td>
							<td class="text-right"><span {{$total += $dbline->dbline_amount - $dbline->dbline_replace_amount}}>{{number_format($dbline->dbline_amount,2)}}</span></td>
							@if($db->is_bad_order == 1)
							<td><span>{{$dbline->dbline_replace_qty_um}}</span></td>
							<td><span>{{number_format($dbline->dbline_replace_rate,2)}}</span></td>
							<td><span>{{number_format($dbline->dbline_replace_amount,2)}}</span></td>
							<td>
								<span >{{number_format(($dbline->dbline_amount - $dbline->dbline_replace_amount),2)}}</span>
							</td>
							<td class="text-center"><input disabled type="checkbox" {{$dbline->dbline_replace_status == 1 ? 'checked' : '' }} name=""></td>
							@endif
						</tr>
						<tr>
							<td ></td>
							<td colspan="8"> SERIAL NUMBER CONSUMED <br> {{$dbline->serial_number_consume}} </td>
						</tr>
							@if($db->is_bad_order == 1)
							<tr>
								<td ></td>
								<td colspan="8"> SERIAL NUMBER REPLACED <br> {{$dbline->serial_number_replace}}</td>
							</tr>
							@endif
						@endforeach
					</tbody>
				</table>
			</div>
		</div>
	</div>

                    
	<div class="row pull-right" style="margin-right: 5px" >
		<h3><strong>TOTAL</strong> {{currency('PHP',($total))}}</h3>
	</div>
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