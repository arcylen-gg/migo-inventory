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
                <div class="form-group">
                    <table  style="width: 100%">
                        <tr>
                            <td>{{date('F d, Y h:i:s A',strtotime($wis->created_at))}}</td>
                            <td class="text-right">{{strtoupper($wis->wis_number)}}</td>
                        </tr>
                        <tr>
                            <td colspan="2">
                                <strong> RECEIVER'S CODE : {{ucwords($wis->receiver_code)}}</strong>
                            </td>
                        </tr>
                        <tr>
                            <td colspan="2">
                                <b>DELIVER FROM : {{$owner->warehouse_name}} - ({{$plate_number}})</b>
                            </td>
                        </tr>
                        <tr>
                            <td colspan="2">
                                <b>DELIVER TO : {{$deliver_to->warehouse_name or ''}}</b>
                            </td>
                        </tr>
                        <tr>
                            <td colspan="2">
                                <b>ADDRESS :</b> 
                                {{ $wis->destination_warehouse_address }}
                            </td>
                        </tr>
                        <tr>
                            <td colspan="2">
                                <b>REMARKS : </b>{!! $wis->wis_remarks !!}
                            </td>
                        </tr>
                    </table>
                </div>
                <br>
                <table style="width: 100%;">
                    <thead style="font-weight: bold;">
                        <tr>
                            @if($check_settings == 1)
                                <th>BIN LOCATION</th>
                            @endif
                            <th width="20%">ITEM NAME</th>
                            <th width="20%">DESCRIPTION</th>
                            <th width="10%">ISSUED QTY</th>
                            <th width="25%" class="text-center">SRP</th>
                            <th width="25%" class="text-center">AMOUNT</th>
                        </tr>
                    </thead>
                    <tbody>
                        @if(count($wis_item) > 0)
                            @foreach($wis_item as $item)
                            <tr class="tr-class">
                                @if($check_settings == 1)
                                    <td>{{$item->warehouse_name}}</td>
                                @endif
                                <td style="text-align: center">{{$item->item_name}}</td>
                                <td style="text-align: center">{{$item->wt_description}}</td>
                                <td style="text-align: center">{{$item->qty}}</td>
                                <td style="text-align: right">{{currency('',$item->item_price)}}</td>
                                <td style="text-align: right" class="{{$itemtotal = $item->int_qty * $item->item_price}} text-right">{{currency('',$itemtotal)}}</td>
                            </tr>
                            @endforeach
                        @elseif(count($wis_item_v1) > 0)
                            @foreach($wis_item_v1 as $itemv1)
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
                <div class="row pull-right" style="margin-right: 10px">
                    <h3><strong>TOTAL: </strong> {{currency('PHP',$total)}}</h3>
                </div>
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
<script type="text/javascript">
    window.print();
</script>
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
    .tr-class td
    {
        padding: 5px
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
    .page 
    {
        page-break-after:always;
        position: relative;
    }
</style>
</html>