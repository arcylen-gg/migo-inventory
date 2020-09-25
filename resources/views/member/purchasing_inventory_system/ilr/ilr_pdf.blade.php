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
		<td width="40%" ><span style="font-size: 25px;font-weight: bold">SIR NO : {{sprintf("%'.05d\n", $sir->sir_id)}}</span></td>
	</tr>
	<tr>
		<td width="60%"><span style="font-size: 15px;">PLATE NUMBER : {{$sir->plate_number}} </span></td>
		<td width="40%" ><span style="font-size: 15px;">DATE : {{date('m/d/Y',strtotime($sir->sir_created))}}</span></td>
	</tr>
	<tr>
		<td><span style="font-size: 15px;">SALESMAN : {{strtoupper($sir->first_name)}} {{strtoupper($sir->middle_name)}} {{strtoupper($sir->last_name)}}</span></td>
	</tr>
</table>
 <div class="form-group">
    <div class="col-md-12">
        <div class="row clearfix draggable-container ilr-container">
            <div class="col-sm-12">
                <table class="digima-table">
                    <thead >
                        <tr>
                            <th style="width: 30px;" class="text-center">#</th>
                            <th style="width: 200px;">Product Name</th>
                            <th style="width: 200px;">Issued QTY</th>
                            <th style="width: 200px;">Sold QTY</th>
                            <th style="width: 200px;">Remaining QTY</th>
                            <th style="width: 200px;">Physical Count</th>
                            <th style="width: 200px;">Status</th>
                            <th style="width: 200px;">Info</th>
                        </tr>
                    </thead>
                    <tbody class="{{$loss = 0}} {{$over = 0}} {{$total_sold = 0}} {{$total_issued = 0}}">
                    @if($_sir_item)
                        @foreach($_sir_item as $key => $sir_item)                                
                        <tr class="tr-draggable tr-draggable-html">
                            <td class="invoice-number-td text-center">{{$key+1}}</td>
                            <td>{{$sir_item->item_name}}</td>                                            
                            <td>{{$sir_item->item_qty}} {{$sir_item->um_abbrev}}</td>
                            <td>{{$sir_item->sold_qty}}</td>
                            <td class="total_issued {{$total_issued += (($sir_item->item_qty * $sir_item->um_qty) * $sir_item->sir_item_price)}}">
                            	{{$sir_item->remaining_qty}}
                            </td>
                            <td class="total_sold {{$total_sold += ($sir_item->quantity_sold * $sir_item->sir_item_price)}}">
                                {{$sir->ilr_status == 1 ? '' : $sir_item->physical_count}}
                            </td>
                            <td class="loss {{$loss += $sir_item->infos < 0 ? $sir_item->infos : 0}}">
                                {{$sir_item->status}}
                            </td>
                            <td class="over {{$over += $sir_item->infos > 0 ? $sir_item->infos : 0}} text-right">
                                {{$sir_item->is_updated == 1 ? number_format($sir_item->infos,2) : ''}}
                            </td>
                        </tr>
                        @endforeach
                        <tr>                        	
                        	<td colspan="6"></td>
                        	<td><strong>TOTAL ISSUED</strong></td>
                        	<td>{{currency('PHP',$total_issued)}}</td>
                        </tr>
                        <tr>                        	
                        	<td colspan="6"></td>
                        	<td><strong>TOTAL SOLD</strong></td>
                        	<td>{{currency('PHP',$total_sold)}}</td>
                        </tr>
                        <tr>
                        	<td colspan="6"></td>
                        	<td><strong>LOSS AMOUNT</strong></td>
                        	<td>{{currency('PHP',$loss)}}</td>
                        </tr>
                        <tr>
                        	<td colspan="6"></td>
                        	<td><strong>OVER AMOUNT</strong></td>
                        	<td>{{currency('PHP',$over)}}</td>
                        </tr>
                    @endif
                    </tbody>                                   
                </table>
            </div>
        </div>
    </div>                
</div>

@if($ctr_returns != 0)
<div class="form-group">
    <div class="col-md-12">
        <label>EMPTIES</label>
    </div>
</div>
 <div class="form-group">
    <div class="col-md-12">
        <div class="row clearfix draggable-container empties-container">
            <div class="col-sm-12">
                <table class="digima-table">
                    <thead >
                        <tr>
                            <th style="width: 30px;" class="text-center">#</th>
                            <th style="width: 200px;">Product Name</th>
                            <th style="width: 200px;">RETURNS QTY</th>
                            <th style="width: 200px;">Physical Count</th>
                            <th style="width: 200px;">Status</th>
                            <th style="width: 200px;">Info</th>
                        </tr>
                    </thead>
                    <tbody class="{{$empties_loss = 0}} {{$empties_over = 0}} {{$total_return = 0}}">
                    @if($_returns)
                        @foreach($_returns as $keys => $return)                                
                        <tr class="tr-draggable tr-draggable-html">
                            <td class="invoice-number-td text-center">{{$keys+1}}</td>
                            <td>{{$return->item_name}}</td>                                            
                            <td>{{$return->item_count}}</td>
                            <td class="total_sold {{$total_return += ($return->sc_item_qty * $return->sc_item_price)}}">
                                {{$sir->ilr_status == 1 ? '' : $return->sc_physical_count}}
                            </td>
                            <td class="loss {{$empties_loss += $return->sc_infos < 0 ? $return->sc_infos : 0}}">
                                {{$return->status}}
                            </td>
                            <td class="over {{$empties_over += $return->sc_infos > 0 ? $return->sc_infos : 0}} text-right">
                                {{$return->sc_is_updated == 1 ? number_format($return->sc_infos,2) : ''}}
                            </td>
                        </tr>
                        @endforeach
                        <tr>                            
                            <td colspan="4"></td>
                            <td><strong>TOTAL RETURN</strong></td>
                            <td>{{currency('PHP',$total_return)}}</td>
                        </tr>
                        <tr>
                            <td colspan="4"></td>
                            <td><strong>LOSS AMOUNT</strong></td>
                            <td>{{currency('PHP',$empties_loss)}}</td>
                        </tr>
                        <tr>
                            <td colspan="4"></td>
                            <td><strong>OVER AMOUNT</strong></td>
                            <td>{{currency('PHP',$empties_over)}}</td>
                        </tr>
                    @endif
                    </tbody>                                   
                </table>
            </div>
        </div>
    </div>                
</div>
@endif
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