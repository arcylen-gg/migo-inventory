<!DOCTYPE html>
<html>
<head>
    <title></title>
    <style type="text/css">
        body
        {
            font-size: 11px;
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
                        <strong>SHIP TO</strong><br>
                        <span>{{$wis->company != '' ? $wis->company : ($wis->title_name." ".$wis->first_name." ".$wis->middle_name." ".$wis->last_name." ".$wis->suffix_name)}}</span><br>
                        <strong>ADDRESS</strong>
                        <p>{!! $wis->destination_customer_address !!}</p>
                    </div>
                    <div class="col-md-6 text-right" style="float: right; width: 50%">
                        <div class="col-md-6 text-right" style="float: left; width: 50%">
                            <strong>NO :</strong><br>
                            <strong>DATE :</strong><br>
                            <strong>FROM :</strong><br>
                            <strong>Delivered By :</strong><br>
                        </div>
                        <div class="col-md-6 text-left" style="float: left; width: 50%">
                            <span>{{$wis->transaction_refnum != '' ? $wis->transaction_refnum : sprintf("%'.04d\n", $wis->new_inv_id)}}</span><br>
                            <span>{{date('m/d/Y',strtotime($wis->cust_delivery_date))}}</span><br>
                            <span>{{$wis->warehouse_name}}</span><br>
                            <span>{{$wis->plate_number}}</span><br>
                        </div>
                    </div>
                </div>
                <br>
                <table style="width: 100%;">
                    <thead style="font-weight: bold;">
                        <tr> 
                            @if($check_settings == 1)
                                <td>BIN LOCATION</td>
                            @endif
                            <td>ITEM NAME</td>
                            <td>ITEM SKU</td>
                            <td>ISSUED QTY</td>
                        </tr>
                    </thead>
                    <tbody>
                        @if(count($wis_item) > 0)
                            @foreach($wis_item as $item)
                            <tr>
                                @if($check_settings == 1)
                                    <td>{{$item->warehouse_name}}</td>
                                @endif
                                <td>{{$item->item_name}}</td>
                                <td>{{$item->item_sku}}</td>
                                <td>{{$item->qty}}</td>
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
    	font-size: 12px;
    }
    thead
    {
        border: 1px solid #000;
    }

</style>