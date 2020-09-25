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
<table width="100%">
	<tr>
		<td width="60%" style="text-align: left"><span style="font-size: 50px;font-weight: bold">{{$type}}</span></td>
		<td width="40%" ><span style="font-size: 25px;font-weight: bold">NO : {{sprintf("%'.05d\n", $sir->sir_id)}}</span></td>
	</tr>
	<tr>
		<td width="60%"><span style="font-size: 15px;">PLATE NUMBER : {{$sir->plate_number}} </span></td>
		<td width="40%" ><span style="font-size: 15px;">DATE : {{date('m/d/Y',strtotime($sir->sir_created))}}</span></td>
	</tr>
	<tr>
		<td><span style="font-size: 15px;">SALESMAN : {{strtoupper($sir->first_name)}} {{strtoupper($sir->middle_name)}} {{strtoupper($sir->last_name)}}</span></td>
	</tr>
</table>
<table width="100%" style="padding: 0; margin-top: 20px ">
	<tr>
		<th width="10%">CODE</th>
		<th>NAME</th>
		<th>DESCRIPTION</th>
		<th width="10%">PACKING SIZE</th>
		<th width="5%">QTY</th>
		<th width="15%">PRICE</th>
		<th width="15%">AMOUNT</th>
	</tr>
		<input type="hidden" name="{{$total = 0}}">
	<tbody>
		@if($_sir_item)
			@foreach($_sir_item as $item)
				<tr class="{{$total += $item->sir_item_price * ($item->item_qty * $item->um_qty)}}">
					<td>{{strtoupper($item->item_barcode)}}</td>
					<td>{{$item->item_name}}</td>
					<td>{{$item->item_sales_information}}</td>
					<td>{{$item->packing_size}} /{{$item->um_name}}</td>
					<td class="text-right">{{$item->item_qty}}</td>
					<td class="text-right">{{number_format($item->sir_item_price * $item->um_qty,2)}}</td>
					<td class="text-right">{{number_format($item->sir_item_price * ($item->item_qty * $item->um_qty),2)}}</td>
				</tr>
			@endforeach
		@endif
		<tr>
			<td colspan="5"></td>
			<td class="text-left"><strong>TOTAL</strong></td>
			<td class="text-right"><strong>{{number_format($total,2)}}</strong></td>
		</tr>
	</tbody>
</table>
</body>
<style type="text/css">
	table
	{
		border-collapse: collapse;
	}
	tr th
	{
		padding: 5px;
		border: 1px solid #000;
	}
	tr td
	{
		padding: 5px;
	}
</style>
</html>