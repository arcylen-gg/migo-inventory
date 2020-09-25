
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
                <br>
                <table  style="width: 100%">
                    <tr>
                        <td colspan="2">
                            <strong>FROM :</strong> {{strtoupper($from_warehouse->warehouse_name)}} <br>
                            <p>{{ucwords($from_warehouse->warehouse_address)}}</p>
                        </td>
                    </tr>
                    <tr>
                        <td colspan="2">
                            <strong>TO :</strong> {{strtoupper($to_warehouse->warehouse_name)}} <br>
                            <p>{{ucwords($to_warehouse->warehouse_address)}}</p>
                        </td>
                    </tr>
                    <tr style="margin-bottom: 20px">
                        <td>{{date('F d, Y h:i:s A',strtotime($rr->created_at))}}</td>
                        <td class="text-right">{{strtoupper($rr->rr_number)}}</td>
                    </tr>
                    <tr>
                        <td colspan="5">
                            <b>REMARKS : </b>{!! $rr->rr_remarks !!}
                        </td>
                    </tr>
                </table>
                <br>
                <table style="width: 100%;">
                    <thead style="font-weight: bold;">
                        <tr>
                            <td width="15%">SKU/ITEM CODE</td>
                            <td width="15%">PATTERN</td>
                            <td width="15%">COLOR</td>
                            <td width="15%">SIZE</td>
                            <td class="text-center" width="10%">ISSUED QTY</td>
                            <td  width="20%" class="text-center">SRP</td>
                            <td  width="20%" class="text-center">AMOUNT</td>
                        </tr>
                    </thead>
                    <tbody>
                         @if(count($rr_item) > 0)
                            @foreach($rr_item as $item)
                            <tr class="tr-class">
                                <td>{{$item->item_sku}}</td>
                                <td>{{$item->pattern}}</td>
                                <td>{{$item->color}}</td>
                                <td>{{$item->size}}</td>
                                <td class="text-center">{{$item->qty}}</td>
                                <td class="text-right">{{currency('',$item->rr_rate)}}</td>
                                <td class="text-right">{{currency('',$item->rr_amount2)}}</td>
                            </tr>
                            @endforeach
                            <tr class="tr-class" style="font-weight: bold;">
                            <td colspan="3"></td>
                            <td style="text-align: right;">Total Quantity:</td>
                            <td class="text-center">{{$total_qty}} PCS</td>
                            <td style="text-align: right;">Total Amount:</td>
                            <td class="text-center" >{{$total_amount}}</td>
                            </tr>
                        @elseif(count($rr_item_v1) > 0)
                            @foreach($rr_item_v1 as $itemv1)
                            <tr class="tr-class">
                                <td>{{$itemv1->item_name}}</td>
                                <td>{{$itemv1->item_sku}}</td>
                                <td class="text-center">{{$itemv1->qty}} pc(s)</td>
                                <td class="text-right">{{currency('',$itemv1->item_price)}}</td>
                                <td class="text-right">{{currency('',($itemv1->qty * $itemv1->item_price))}}</td>
                            </tr>
                            @endforeach
                        @else
                        <tr>
                            <td colspan="3" class="text-center">NO ITEMS</td>
                        </tr>
                        @endif
                    </tbody>
                </table>
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
    }

    .tr-class td
    {
        padding: 5px
    }
    body 
    {
    	font-size: 12px;
    }
    thead
    {
        border: 1px solid #000;
    }

</style>