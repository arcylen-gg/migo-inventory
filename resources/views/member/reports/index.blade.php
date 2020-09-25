@extends('member.layout')

@section('css')
<link rel="stylesheet" href="/assets/member/css/customBTN.css" type="text/css" />
@endsection

@section('content')

<div class="panel panel-default panel-block panel-title-block">
    <div class="panel-heading">
        <div>
            <i class="fa fa-area-chart"></i>
            <h1>
                <span class="page-title">Reports</span>
                <small>
                Generate your reports
                </small>
            </h1>
            
            <!--<a href="#" class="panel-buttons btn btn-custom-blue pull-right btn-create-modal" data-toggle="modal" data-target="#ShippingModal">Create Customer</a>-->
        </div>
    </div>
</div>
<div class="form-horizontal">
    <div class="form-group">
        <div class="col-md-6">
            <div class="panel panel-default panel-block panel-title-block">
                <div class="panel-heading"><strong>Sales</strong></div>
                <div class="panel-body">
                    <span>Make business decisions by comparing sales across products, staff, channels, and more.</span>
                    <br><Br>
                    <span><strong>ORDERS LAST 30 DAYS</strong></span>
                    <h3>{{$order_count['num']}}</h3>
                    <table class="table table-condensed">
                        <tr>
                            <td>Reports</td>
                            <td></td>
                        </tr>
                        <tr>
                            <td><a href="/member/report/sale/month">Sales by month</a></td>
                            <td class="text-right">
                                <span class="color-dark-gray">{{$order_count['str']}}</span>
                            </td>
                        </tr>
                        <tr>
                            <td><a href="/member/report/sale/product">Sales by product</a></td>
                            <td class="text-right">
                                <span class="color-dark-gray">{{$product_count['str']}}</span>
                            </td>
                        </tr>
                        <tr>
                            <td><a href="/member/report/sale/product_variant">Sales by product variant SKU</a></td>
                            <td class="text-right">
                                <span class="color-dark-gray">{{$variant_count['str']}}</span>
                            </td>
                        </tr>
                        <!--<tr>-->
                        <!--    <td><a href="#">Sales by billing country</a></td>-->
                        <!--    <td class="text-right">-->
                        <!--        <span class="color-dark-gray"></span>-->
                        <!--    </td>-->
                        <!--</tr>-->
                        <!--<tr>-->
                        <!--    <td><a href="/member/report/sale/customer">Sales by customer name</a></td>-->
                        <!--    <td class="text-right">-->
                        <!--        <span class="color-dark-gray"></span>-->
                        <!--    </td>-->
                        <!--</tr>-->
                    </table>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="panel panel-default panel-block panel-title-block">
                <div class="panel-heading"><strong>Customer</strong></div>
                <div class="panel-body">
                    <span>Gain insights into who your customers are and how they interact with your business.</span>
                    <br><br>
                    <span>CUSTOMERS LAST 30 DAYS</span>
                    <h3>{{$customer_count['num']}}</h3>
                    <table class="table table-condensed">
                        <tr>
                            <td>Reports</td>
                            <td></td>
                        </tr>
                        <tr>
                            <td><a href="/member/report/sale/customerOverTime">Customers over time</a></td>
                            <td class="text-right">
                                <span class="color-dark-gray">{{$customer_count['str']}}</span>
                            </td>
                        </tr>
                        <!--<tr>-->
                        <!--    <td><a href="#">First-time vs returning customer sales</a></td>-->
                        <!--    <td class="text-right">-->
                        <!--        <span class="color-dark-gray"></span>-->
                        <!--    </td>-->
                        <!--</tr>-->
                        <!--<tr>-->
                        <!--    <td><a href="#">Customer by country</a></td>-->
                        <!--    <td class="text-right">-->
                        <!--        <span class="color-dark-gray"></span>-->
                        <!--    </td>-->
                        <!--</tr>-->
                        <!--<tr>-->
                        <!--    <td><a href="#">Returning customers</a></td>-->
                        <!--    <td class="text-right">-->
                        <!--        <span class="color-dark-gray"></span>-->
                        <!--    </td>-->
                        <!--</tr>-->
                        <!--<tr>-->
                        <!--    <td><a href="#">One-time customers</td>-->
                        <!--    <td class="text-right">-->
                        <!--        <span class="color-dark-gray"></span>-->
                        <!--    </td>-->
                        <!--</tr>-->
                        <!--<tr>-->
                        <!--    <td><a href="#">At-risk customers</a></td>-->
                        <!--    <td class="text-right">-->
                        <!--        <span class="color-dark-gray"></span>-->
                        <!--    </td>-->
                        <!--</tr>-->
                        <!--<tr>-->
                        <!--    <td><a href="#">Loyal Customer</a></td>-->
                        <!--    <td class="text-right">-->
                        <!--        <span class="color-dark-gray"></span>-->
                        <!--    </td>-->
                        <!--</tr>-->
                    </table>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="panel panel-default panel-block panel-title-block">
                <div class="panel-heading"><strong>Finances</strong></div>
                <div class="panel-body">
                    <span>View your store's finances including sales, refunds, taxes, payments, and more.</span>
                    <br><br>
                    <span>TOTAL SALES LAST 30 DAYS</span>
                    <h3></h3>
                    <table class="table table-condensed">
                        <tr>
                            <td>Reports</td>
                            <td></td>
                        </tr>
                        <tr>
                            <td><a href="#">Finances History</a></td>
                            <td class="text-right">
                                <span class="color-dark-gray"></span>
                            </td>
                        </tr>
                        <tr>
                            <td><a href="#">Total Sales</a></td>
                            <td class="text-right">
                                <span class="color-dark-gray"></span>
                            </td>
                        </tr>
                        <tr>
                            <td><a href="#">Taxes</a></td>
                            <td class="text-right">
                                <span class="color-dark-gray"></span>
                            </td>
                        </tr>
                        <tr>
                            <td><a href="#">Payments</a></td>
                            <td class="text-right">
                                <span class="color-dark-gray"></span>
                            </td>
                        </tr>
                        
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('script')
@endsection