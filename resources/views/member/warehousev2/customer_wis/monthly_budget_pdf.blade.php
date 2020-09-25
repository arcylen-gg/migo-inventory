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
                            <strong>Delivery for the month of:</strong><br>
                            <strong>Delivered By :</strong><br>
                        </div>
                        <div class="col-md-5 text-left" style="float: left; width: 50%">
                            <span>{{$wis->transaction_refnum != '' ? $wis->transaction_refnum : sprintf("%'.04d\n", $wis->new_inv_id)}}</span><br>
                            <span>{{date('m/d/Y',strtotime($wis->cust_delivery_date))}}</span><br>
                            <span>{{$wis->warehouse_name}}</span><br>
                            <span>{{date('F Y')}}</span><br>
                            <span>{{$wis->plate_number}}</span><br>
                        </div>
                    </div>
                </div>

                <br>
                <table style="width: 100%;">
                    <thead style="font-weight: bold;">
                        <tr> 
                            <td class="text-center" style="width: 5%">Item No</td>
                            @if($check_settings == 1)
                                <td>BIN LOCATION</td>
                            @endif
                            <td class="text-center" style="width: 25%">ITEM NAME</td>
                            <td class="text-center" style="width: 15%">ITEM SKU</td>
                            <td class="text-center" style="width: 10%">UNIT PRICE</td>
                            <td class="text-center" style="width: 10%">ISSUED QTY</td>
                            <td class="text-center" style="width: 20%">TOTAL AMOUNT</td>
                            <td class="text-center" style="width: 15%">REMARKS</td>
                        </tr>
                    </thead>
                    <tbody {{$total = 0 }}>
                        @if(count($wis_item) > 0)
                            @foreach($wis_item as $key => $item)
                            <tr {{$total += $item->itemline_amount}}>
                                <td class="text-center">{{$key+=1}}</td>
                                @if($check_settings == 1)
                                    <td>{{$item->warehouse_name}}</td>
                                @endif
                                <td>{{$item->item_name}}</td>
                                <td>{{$item->item_sku}}</td>
                                <td class="text-center">{{currency('', $item->item_price)}}</td>
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
                        @if($monthly_budget)
                        <tr>
                            <td></td>
                            @if($check_settings == 1)
                            <td></td>
                            @endif
                            <td colspan="4" class="text-right"><strong>{{$monthly_budget->budget_type}}</strong></td>
                            <td class="text-right" style="border-bottom: solid 1px #000"><strong>{{currency('', $monthly_budget->budget_adjusted)}}</strong></td>
                        </tr>
                        @if(!$type)
                        <tr>
                            <td></td>
                            @if($check_settings == 1)
                            <td></td>
                            @endif
                            <td colspan="4" class="text-right"><strong>{{$monthly_budget->current_budget_month}}</strong></td>
                            <td class="text-right"><strong>{{currency('',$monthly_budget->current_budget_month_amount)}}</strong></td>
                        </tr>
                        <tr>
                            <td></td>
                            @if($check_settings == 1)
                            <td></td>
                            @endif
                            <td colspan="4" class="text-right"><strong>{{$monthly_budget->prev_budget_month}}</strong></td>
                            <td class="text-right" style="border-bottom: solid 1px #000"><strong>{{currency('',$monthly_budget->prev_budget_month_amount)}}</strong></td>
                        </tr>
                        <tr>
                            <td></td>
                            @if($check_settings == 1)
                            <td></td>
                            @endif
                            <td colspan="4" class="text-right"><strong>{{$monthly_budget->adj_budget_month}}</strong></td>
                            <td class="text-right"><strong>{{currency('',$monthly_budget->adj_budget_month_amount)}}</strong></td>
                        </tr>
                       @if(count($monthly_budget_line) > 0)
                        <tr>
                            <td></td>
                            @if($check_settings == 1)
                            <td></td>
                            @endif
                            <td colspan="4" class="text-right"><strong>Less</strong>
                                <br>
                                @if(count($monthly_budget_line) > 0)
                                    @foreach($monthly_budget_line as $line)
                                    <small>{{$line->item_name}}</small><br>
                                    @endforeach
                                @endif
                            </td>
                            <td class="text-right"><strong>{{currency('',$monthly_budget->total_item_less_amount)}}</strong></td>
                        </tr>
                        @endif
                        <tr class="hidden">
                            <td></td>
                            @if($check_settings == 1)
                            <td></td>
                            @endif
                            <td colspan="4" class="text-right"><strong>Total Remaining Budget for the month of {{$monthly_budget->total_budget_month}}</strong></td>
                            <td class="text-right"><strong>{{currency('',$monthly_budget->total_budget_month_amount)}}</strong></td>
                        </tr>
                        @else
                        <tr {{$forbilling = $total > $monthly_budget->budget_adjusted ? abs($total - $monthly_budget->budget_adjusted) : 0 }}>
                            <td></td>
                            @if($check_settings == 1)
                            <td></td>
                            @endif
                            <td colspan="4" class="text-right"><strong>For Billing</strong></td>
                            <td class="text-right" style="border-bottom: solid 1px #000"><strong>{{currency('', $forbilling)}}</strong></td>
                        </tr>
                        @endif
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