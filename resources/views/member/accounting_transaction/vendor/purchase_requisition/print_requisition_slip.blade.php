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
                <table  style="width: 100%">
                    <tr>
                        <td>{{date('F d, Y h:i:s A',strtotime($rs->requisition_slip_date_created))}}</td>
                        <td class="text-right">{{strtoupper($rs->requisition_slip_number)}}</td>
                    </tr>
                    <tr>
                        <td colspan="5">
                            <b>REMARKS : </b>{!! $rs->requisition_slip_remarks !!}
                        </td>
                    </tr>
                </table>
                <br>
                <table style="width: 100%;">
                    <thead style="font-weight: bold;">
                        <tr>
                            <td class="text-center">#</td>
                            <td class="text-center">ITEM NAME</td>
                            <td class="text-center">DESCRIPTION</td>
                            <td class="text-center">REM QTY</td>
                            <td class="text-center">QTY</td>
                            <td class="text-center">RATE</td>
                            <td class="text-center">AMOUNT</td>
                            <td class="text-center">VENDOR NAME</td>
                            <td class="text-center">REMARKS</td>
                        </tr>
                    </thead>
                    <tbody>
                        @if(count($_rs_item) > 0)
                            @foreach($_rs_item as $key => $item)
                            <tr class="td-row-item">
                                <td>{{$key+1}}</td>
                                <td>{{$item->item_name}}</td>
                                <td>{{$item->item_description}}</td>
                                <td class="text-right">{{$item->rem_qty}}</td>
                                <td class="text-right">{{$item->qty}}</td>
                                <td class="text-right">{{number_format($item->rs_item_rate,2)}}</td>
                                <td class="text-right">{{number_format($item->rs_item_amount,2)}}</td>
                                <td class="text-center">{{$item->vendor_company != "" ? $item->vendor_company : $item->vendor_first_name.' '.$item->vendor_last_name}}</td>
                                <td></td>
                            </tr>
                            @endforeach
                        @else
                        <tr>
                            <td colspan="3" class="text-center">NO ITEMS</td>
                        </tr>
                        @endif
                    </tbody>
                </table>
                @if($rs->requisition_slip_memo !='')
                <table width="100%" style="padding: 0; margin-top: 20px">
                    <tbody> 
                        <tr>
                            <td>
                                <label style="font-weight:bold"> Memo : </label><br>
                                {{ucfirst($rs->requisition_slip_memo)}}
                            </td>
                        </tr>
                        
                    </tbody>
                </table>
                @endif
                <br>
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
	tr 
    {
        page-break-inside: avoid;
        padding: 2px;
    }
    body 
    {
    	font-size: 12px;
    }
    thead
    {
        border: 1px solid #000;
    }
    .td-row-item td
    {
        padding: 2px;   
    }

</style>