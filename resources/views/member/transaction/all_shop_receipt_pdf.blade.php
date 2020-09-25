<!doctype html>
<!--[if lt IE 7]>      <html class="no-js lt-ie9 lt-ie8 lt-ie7" lang=""> <![endif]-->
<!--[if IE 7]>         <html class="no-js lt-ie9 lt-ie8" lang=""> <![endif]-->
<!--[if IE 8]>         <html class="no-js lt-ie9" lang=""> <![endif]-->
<!--[if gt IE 8]><!--> <html class="no-js" lang=""> <!--<![endif]-->
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <title>Digima House</title>
    <meta name="description" content="">
    <link rel="stylesheet" href="{{ public_path() }}/assets/initializr/css/bootstrap.min.css">
    <link rel="stylesheet" href="{{ public_path() }}/assets/initializr/css/bootstrap-theme.min.css">
    <link rel="stylesheet" href="{{ public_path() }}/assets/member/css/custom_invoice.css">
</head>
<body>
    <!--[if lt IE 8]>
    <p class="browserupgrade">You are using an <strong>outdated</strong> browser. Please <a href="http://browsehappy.com/">upgrade your browser</a> to improve your experience.</p>
    <![endif]-->
    
    <div class="container">
        <div class="header">
            <table>
                <tbody>
                    <tr>
                        <td class="left">
                            <table>
                                <tbody>
                                    <tr>
                                        <td colspan="2" class="text">
                                            <div class="title">{{$shop_key or ''}}</div>
                                            <div class="sub-title">{{$shop_address or ''}}</div>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </td>
                        <td class="right">
                            <table>
                                <tbody>
                                    <tr>
                                        <td class="do-label" style="width: 60px;">NO.</td>
                                        <td class="do-value">: {{ $list->transaction_number }}</td>
                                    </tr>
                                    <tr>
                                        <td class="do-label">DATE.</td>
                                        <td class="do-value">: {{date('M d, Y',strtotime($list->transaction_date_created))}}</td>
                                    </tr>
                                    <tr>
                                        <td class="do-label">DO No.</td>
                                        <td class="do-value">: {{ $list->transaction_list_id }}</td>
                                    </tr>
                                </tbody>
                            </table>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
        <div class="invoice">
            <div class="title">INVOICE</div>
            <div class="holder">
                <div class="detail-row"><strong>Bill To :</strong> {{ucwords($customer_name)}}</div>
                @if($customer_address)
                <div class="detail-row"><strong>Shipping Address :</strong> {{ $customer_address->customer_street }} {{ $customer_address->customer_state }} {{ $customer_address->customer_city }} {{ $customer_address->customer_zipcode }}</div>
                @endif
                @if($customer_info)
                <div class="detail-row"><strong>TIN :</strong> {{ $customer_info->tin_number }}</div>
                @endif
            </div>
            <div class="sub-title">INVOICE DETAILS:</div>
            <div class="holder invoice-details">
                <table class="table">
                    <thead>
                        <tr>
                            <th>CODE</th>
                            <th>DESCRIPTION</th>
                            <th>QTY</th>
                            <th>U. PRICE</th>
                            <th>TOTAL</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($_item as $key => $item)
                        <tr>
                            <td>{{$key+1}}</td>
                            <td>
                                <b>{{$item->item_name}}</b>
                            </td>
                            <td>{{number_format($item->quantity)}}</td>
                            <td>{{currency('PHP',$item->item_price)}}</td>
                            <td>{{currency('PHP',$item->subtotal)}}</td>
                        </tr>
                        @if(count($_codes) > 0)
                        <tr>
                            <td colspan="5">
                                @if(isset($_codes[$item->item_id]) && count($_codes[$item->item_id]) > 0)
                                    @foreach($_codes[$item->item_id] as $c)
                                    <div>PIN <b>{{$c['item_pin']}}</b> - ACTIVATION CODE <b>{{$c['item_activation']}}</b></div>
                                    @endforeach
                                @endif
                            </td>
                        </tr>
                        @endif
                        @endforeach
                    </tbody>
                </table>
                <!-- TOTAL SUMMARY -->
                <div class="total-border clearfix">
                    <div class="total-summary" style="margin-bottom: 15px;">
                        <!-- <div class="rows"><span style="width: 115px; display: inline-block;">VATable</span><span style="display: inline-block;">: PHP {{ number_format($list->vatable, 2) }}</span></div>
                        <div class="rows"><span style="width: 115px; display: inline-block;">VAT 12%</span><span style="display: inline-block;">: PHP {{ number_format($list->vat, 2) }}</span></div> -->
                        <div class="rows"><span style="width: 115px; display: inline-block;">TOTAL AMOUNT</span><span style="display: inline-block;">: PHP {{ number_format($list->transaction_subtotal, 2) }}</span></div>
                    </div>
                </div>
                <!-- END TOTAL SUMMARY -->
            </div>
            <div class="payment-detail-container">
                <div class="sub-title">PAYMENT DETAILS:</div>
                <div class="holder" {{$total_tendered = 0}}>
                    <div class="clearfix">
                        @if($list->payment_method != 'pos')
                        <div class="payment-detail pull-left"  {{$total_tendered = $list->transaction_total}}>
                            <div class="rows"><strong>Payment Date :</strong> {{date('M d, Y',strtotime($list->transaction_date_created))}}</div>
                            <div class="rows"><strong>Payment Type :</strong> </div>
                            <div class="rows">
                                <div style="width:750px;margin-left: 30px;">
                                    <span style="width: 375px">{{ ucfirst($list->payment_method) }}</span>
                                    <span  class="pull-right" style="width: 175px">{{currency('',$list->transaction_total)}}</span>
                                </div>
                            </div>
                        </div>
                        @else
                         <div class="payment-detail pull-left">
                            <div class="rows"><strong>Payment Date :</strong> {{date('M d, Y',strtotime($list->transaction_date_created))}}</div>
                            <div class="rows" ><strong>Payment Type :</strong> </div>
                            @if(count($_payment_list) > 0)
                                @foreach($_payment_list as $payment)
                                <div class="rows">
                                    <div class="{{$total_tendered += $payment->transaction_payment_amount}}" style="width:750px;margin-left: 30px;">
                                        <span style="width: 375px">{{strtoupper($payment->transaction_payment_type)}}</span>
                                        <span  class="pull-right" style="width: 175px">{{currency('',$payment->transaction_payment_amount)}}</span>
                                    </div>
                                </div>
                                @endforeach
                            @endif
                        </div>
                        @endif
                    </div>
                </div>
                <div class="holder">
                    <div class="total-summary text-right" {{isset($total_tendered) ? '' : $total_tendered = 0}}>
                        <div class="rows">TENDERED PAYMENT : PHP {{ number_format($total_tendered, 2) }}</div>
                        <div class="rows">TOTAL PAID AMOUNT : PHP {{ number_format($list->transaction_total, 2) }}</div>
                        <div class="rows">CHANGE : PHP {{ number_format($total_tendered - $list->transaction_total, 2) }}</div>
                    </div>
                </div>
            </div>
        </div>
        {{-- <footer>BIR PERMIT NO. : XXXX-XXX-XXXXXX-YYY
Date Issued : February 21, 2014
Approved Series FR : 10000001 TO: 19999999
This Sales Invoice shall be valid for five (5) years from the date of ATP
        </footer> --}}
    </div>
</body>
</html>
<style type="text/css">
    body
    {
        font-size: 12px;
    }
</style>

<style type="text/css">
    div.payment-detail-container { page-break-inside: avoid; }
</style>