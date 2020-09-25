<!DOCTYPE html>
<html>
<head>
    <title></title>
    <style type="text/css">
        body
        {
            font-size: 14px;
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
                        <strong>{{$type != '' ? 'For Billing' : ''}}</strong><br>
                        <strong>SHIP TO</strong><br>
                        <span>{{$wis->company != '' ? $wis->company : ($wis->title_name." ".$wis->first_name." ".$wis->middle_name." ".$wis->last_name." ".$wis->suffix_name)}}</span><br>
                        <strong>ADDRESS</strong>
                        <p>{!! $wis->destination_customer_address !!}</p>
                    </div>
                    <div class="col-md-6 text-right" style="float: right; width: 50%">
                        <div class="col-md-7 text-right" style="float: left; width: 50%">
                            <strong>NO :</strong><br>
                            <strong>DATE :</strong><br>
                            <strong>FROM :</strong><br>
                            @if($delivery_msg != '')
                            <strong>Delivery for the month of:</strong><br>
                            @endif
                            <strong>Delivered By :</strong><br>
                        </div>
                        <div class="col-md-5 text-left" style="float: left; width: 50%">
                            <span>{{$wis->transaction_refnum != '' ? $wis->transaction_refnum : sprintf("%'.04d\n", $wis->new_inv_id)}}</span><br>
                            <span>{{date('m/d/Y',strtotime($wis->cust_delivery_date))}}</span><br>
                            <span>{{$wis->warehouse_name}}</span><br>
                            @if($delivery_msg != '')
                            <span>{{$delivery_msg or ''}}</span><br>
                            @endif
                            <span>{{$wis->plate_number}}</span><br>
                        </div>
                    </div>
                </div>
                <br>
                <table style="width: 100%;">
                    <thead style="font-weight: bold;">
                        <tr> 
                            <td>Item No</td>
                            @if($check_settings == 1)
                                <td>BIN LOCATION</td>
                            @endif
                            <td>ITEM NAME</td>
                            <td>ITEM SKU</td>
                            <td>UNIT PRICE</td>
                            <td>ISSUED QTY</td>
                            <td>TOTAL AMOUNT</td>
                            <td>REMARKS</td>
                        </tr>
                    </thead>
                    <tbody {{$total = 0}}>
                        @if(count($wis_item) > 0)
                            @foreach($wis_item as $key => $item)
                            <tr {{$total += $item->itemline_amount}}>
                                <td class="text-center">{{$key+=1}}</td>
                                @if($check_settings == 1)
                                    <td>{{$item->warehouse_name}}</td>
                                @endif
                                <td>{{$item->item_name}}</td>
                                <td>{{$item->item_sku}}</td>
                                <td class="text-center">{{currency('', $item->itemline_rate)}}</td>
                                <td class="text-center">{{$item->qty}}</td>
                                <td class="text-center">{{currency('', $item->itemline_amount)}}</td>
                                <td></td>
                            </tr>
                            @endforeach
                        @else
                        <tr>
                            <td colspan="20" class="text-center">NO ITEMS</td>
                        </tr>
                        @endif 
                        <tr><td colspan="20"></td></tr>
                        <tr>
                            <td></td>
                            @if($check_settings == 1)
                            <td></td>
                            @endif
                            <td colspan="4" class="text-center"><strong>TOTAL</strong></td>
                            <td class="text-center"><strong>{{currency('', $total)}}</strong></td>
                        </tr>

                    </tbody>
                </table>
                <br>
                <br>
                <br>
                <br>
                @if($wis->cust_wis_remarks !='') 
                <div>
                    <p>
                       <b>REMARKS : </b><br>
                        {{ucfirst($wis->cust_wis_remarks)}}
                    </p>
                </div>
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
	tr 
    {
        page-break-inside: avoid;
    }
    body 
    {
    	font-size: 14px;
    }
    thead
    {
        border: 1px solid #000;
    }

</style>