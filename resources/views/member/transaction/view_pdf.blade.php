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
                                        <td class="img">
                                            <img src="{{ public_path() }}/assets/front/img/myphone-emblem.png">
                                        </td>
                                        <td class="text">
                                            <div class="title">MYSOLID TECHNOLOGIES & DEVICES CORPORATION</div>
                                            <div class="sub-title">Green Sun, 2285 Chino Roces Avenue Extension, Makati City</div>
                                            <div class="sub-title">Tel.No. (02) 548-9251</div>
                                            <div class="sub-title">VAT Reg. No. 007-283-114</div>
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
                <div class="detail-row"><strong>TIN :</strong> {{ $customer_info->tin_number }}</div>
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
                        @endforeach
                    </tbody>
                </table>
                <!-- TOTAL SUMMARY -->
                <div class="total-border clearfix">
                    <div class="total-summary" style="margin-bottom: 15px;">
                        <div class="rows"><span style="width: 115px; display: inline-block;">VATable</span><span style="display: inline-block;">: PHP {{ number_format($list->vatable, 2) }}</span></div>
                        <div class="rows"><span style="width: 115px; display: inline-block;">VAT 12%</span><span style="display: inline-block;">: PHP {{ number_format($list->vat, 2) }}</span></div>
                        <div class="rows"><span style="width: 115px; display: inline-block;">TOTAL AMOUNT</span><span style="display: inline-block;">: PHP {{ number_format($list->transaction_subtotal, 2) }}</span></div>
                    </div>
                </div>
                <!-- END TOTAL SUMMARY -->
            </div>
            <div class="sub-title">PAYMENT DETAILS:</div>
            <div class="holder">
                <div class="clearfix">
                    <div class="payment-detail pull-left">
                        <div class="rows"><strong>Payment Date :</strong> {{date('M d, Y',strtotime($list->transaction_date_created))}}</div>
                        <div class="rows"><strong>Payment Type :</strong> {{ ucfirst(isset($customer_payment->payment_method) ? $customer_payment->payment_method : '' ) }}</div>
                        <div class="rows"><strong>Payment Receipt Number :</strong> {{ isset($customer_payment->checkout_id) ?  $customer_payment->checkout_id : ''}}</div>
                    </div>
                    <div class="total-summary pull-right">
                        <div class="rows">TOTAL PAID AMOUNT : PHP {{ number_format($list->transaction_total, 2) }}</div>
                    </div>
                </div>
            </div>
        </div>
        <footer>BIR PERMIT NO. : 1214-052-000004-CAS
Date Issued : February 21, 2014
Approved Series FR : 10000001 TO: 19999999
This Sales Invoice shall be valid for five (5) years from the date of ATP</footer>
    </div>
</body>
</html>