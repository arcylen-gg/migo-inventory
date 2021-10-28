@extends('member.layout')
@section('content')
<form class="hide" type="post" action="member/utilities/make-developer">
    <input type="hidden" name="_token" value="{{csrf_token()}}">
    <div class="col-md-8">
        <div class="input-group">
            <input type="text" class="form-control" name="pwd" placeholder="Type developer password here (hint: as usual)">
            <span class="input-group-btn">
                <button class="btn btn-secondary" type="submit">Let Me Access All Page</button>
            </span>
        </div>
    </div>
</form>
<input type="hidden" class="show-reorder-class" name="show_reorder" value="{{$show_reorder}}">
<div class="button-wrapper clearfix" style="margin-top: 15px; min-height: 55px">
    <div class="dashboard-home cursor-pointer dashboard-arrow">
        <span class="fa fa-angle-left fa-4x"></span>
        <text class="">Home</text>
    </div>
    <div class="pull-right dashboard-insights cursor-pointer dashboard-arrow" style="display: none">
        <text class="">Insights</text>
        <span class="fa fa-angle-right fa-4x"></span>
    </div>
</div>
<div class="dashboard home-content" style="display: none">
    <!-- add extra container element for Masonry- -->
    <div class="grid row-no-padding clearfix">
        <div class="grid-item col-md-8">
            <!-- add inner element for column content -->
            <div class="grid-item-content" style="position: relative;">
                <div class="text-center">
                    <div class="grid-title active"><span>V</br>E</br>N</br>D</br>O</br>R</br>S</span></div>
                </div>
                <div class="main-holder">
                    <div class="per-row">
                        <a href="/member/transaction/purchase_order/create">
                            <div class="holder">
                                <div class="icon"><img src="/assets/member/img/sample-icon.png"></div>
                                <div class="name">Purchase Order</div>
                            </div>
                        </a>
                        <div class="horizontal-line right" style="width: 15%;"></div>
                        <a href="/member/transaction/receive_inventory/create">
                            <div class="holder">
                                <div class="icon"><img src="/assets/member/img/sample-icon.png"></div>
                                <div class="name">Receive Inventory</div>
                            </div>
                        </a>
                        <div class="horizontal-line right" style="width: 15%;"></div>
                        <a href="/member/transaction/enter_bills/create">
                            <div class="holder">
                                <div class="icon"><img src="/assets/member/img/sample-icon.png"></div>
                                <div class="name">Enter Bills Against Inventory</div>
                            </div>
                        </a>
                        <div class="space-line" style="width: 15%;"></div>
                        <a href="/member/transaction/debit_memo/create">
                            <div class="holder">
                                <div class="icon"><img src="/assets/member/img/sample-icon.png"></div>
                                <div class="name">Debit Memo</div>
                            </div>
                        </a>
                    </div>
                    <div class="per-row">
                        <a href="/member/transaction/enter_bills/create">
                            <div class="holder">
                                <div class="icon"><img src="/assets/member/img/sample-icon.png"></div>
                                <div class="name">Enter Bills</div>
                            </div>
                        </a>
                        <div class="horizontal-line" style="width: 47.5%;"></div>
                        <div class="vertical-line intersecting"></div>
                        <div class="horizontal-line right" style="width: 20%;"></div>
                        <a href="/member/transaction/pay_bills/create">
                            <div class="holder">
                                <div class="icon"><img src="/assets/member/img/sample-icon.png"></div>
                                <div class="name">Pay Bills</div>
                            </div>
                        </a>
                    </div>
                </div>
            </div>
            <div class="grid-item-content">
                <div class="text-center">
                    <div class="grid-title active"><span>C</br>U</br>S</br>T</br>O</br>M</br>E</br>R</br>S</span></div>
                </div>
                <div class="main-holder">
                    <div class="per-row">
                        <a href="/member/transaction/sales_order/create">
                            <div class="holder">
                                <div class="icon"><img src="/assets/member/img/sample-icon.png"></div>
                                <div class="name">Sales Orders</div>
                                <div class="vertical-line up" style="height: 20px; vertical-align: middle; margin-top: 15px;"></div>
                            </div>
                        </a>
                        <div class="horizontal-line" style="width: 12.5%;"></div>
                        <div class="vertical-line down" style="height: 100px; vertical-align: middle; margin-left: -7.5px; margin-right: -7.5px; margin-top: 25px;"></div>
                        <div class="space-line" style="width: 35%;"></div>
                        <a href="/member/transaction/sales_receipt/create">
                            <div class="holder">
                                <div class="icon"><img src="/assets/member/img/sample-icon.png"></div>
                                <div class="name">Create Sales Receipts</div>
                            </div>
                        </a>
                        <div class="space-line" style="width: 5%;"></div>
                        <a href="/member/transaction/credit_memo/create">
                            <div class="holder">
                                <div class="icon"><img src="/assets/member/img/sample-icon.png"></div>
                                <div class="name">Refund & Credits</div>
                            </div>
                        </a>
                    </div>
                    <div class="per-row">
                        <a href="/member/transaction/estimate_quotation/create">
                            <div class="holder">
                                <div class="icon"><img src="/assets/member/img/sample-icon.png"></div>
                                <div class="name">Estimates</div>
                            </div>
                        </a>
                        <div class="horizontal-line" style="width: 7.5%;"></div>
                        <a href="/member/transaction/sales_invoice/create">
                            <div class="holder">
                                <div class="icon"><img src="/assets/member/img/sample-icon.png"></div>
                                <div class="name">Invoices</div>
                            </div>
                        </a>
                        <div class="horizontal-line right" style="width: 30%;"></div>
                        <a href="/member/transaction/receive_payment/create">
                            <div class="holder">
                                <div class="icon"><img src="/assets/member/img/sample-icon.png"></div>
                                <div class="name">Receive Payments</div>
                            </div>
                        </a>
                    </div>
                </div>
            </div>
            <div class="grid-item-content">
                <div class="text-center">
                    <div class="grid-title active"><span>E</br>M</br>P</br>L</br>O</br>Y</br>E</br>E</br>S</span></div>
                </div>
                <div class="main-holder">
                    <div class="per-row">
                        <a href="javascript:">
                            <div class="holder">
                                <div class="icon"><img src="/assets/member/img/sample-icon.png"></div>
                                <div class="name">Payroll Center</div>
                            </div>
                        </a>
                        <div class="space-line" style="width: 10%;"></div>
                        <a href="javascript:">
                            <div class="holder">
                                <div class="icon"><img src="/assets/member/img/sample-icon.png"></div>
                                <div class="name">Pay Employees</div>
                            </div>
                        </a>
                        <div class="horizontal-line" style="width: 7.5%;"></div>
                        <a href="javascript:">
                            <div class="holder">
                                <div class="icon"><img src="/assets/member/img/sample-icon.png"></div>
                                <div class="name">Pay Liabilities</div>
                            </div>
                        </a>
                        <div class="horizontal-line right" style="width: 7.5%;"></div>
                        <a href="javascript:">
                            <div class="holder">
                                <div class="icon"><img src="/assets/member/img/sample-icon.png"></div>
                                <div class="name">Process Payroll Forms</div>
                            </div>
                        </a>
                        <div class="space-line" style="width: 7.5%;"></div>
                        <a href="javascript:">
                            <div class="holder">
                                <div class="icon"><img src="/assets/member/img/sample-icon.png"></div>
                                <div class="name">HR Essentials and Insurance</div>
                            </div>
                        </a>
                    </div>
                </div>
            </div>
        </div>
        <div class="grid-item col-md-4">
            <!-- add inner element for column content -->
            <div class="grid-item-content mini-side">
                <div class="text-center">
                    <div class="grid-title"><span>M</br>E</br>M</br>B</br>E</br>R</br>S</br>H</br>I</br>P</span></div>
                </div>
                <div class="main-holder">
                    <div class="centered">
                        <div class="per-row">
                            <a href="/member/mlm/code2">
                                <div class="holder">
                                    <div class="icon"><img src="/assets/member/img/sample-icon.png"></div>
                                    <div class="name">Membership Codes</div>
                                </div>
                            </a>
                            <div class="space-line" style="width: 30px;"></div>
                            <a href="/member/mlm/developer">
                                <div class="holder">
                                    <div class="icon"><img src="/assets/member/img/sample-icon.png"></div>
                                    <div class="name">Customer Slots</div>
                                </div>
                            </a>
                        </div>
                        <div class="per-row">
                            <a href="/member/mlm/product">
                                <div class="holder">
                                    <div class="icon"><img src="/assets/member/img/sample-icon.png"></div>
                                    <div class="name">Product Points</div>
                                </div>
                            </a>
                            <div class="space-line" style="width: 30px;"></div>
                            <a href="/member/mlm/product/discount">
                                <div class="holder">
                                    <div class="icon"><img src="/assets/member/img/sample-icon.png"></div>
                                    <div class="name">Product Discount</div>
                                </div>
                            </a>
                        </div>
                        <div class="per-row">
                            <a href="/member/mlm/product_code2">
                                <div class="holder">
                                    <div class="icon"><img src="/assets/member/img/sample-icon.png"></div>
                                    <div class="name">Product Code</div>
                                </div>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            <div class="grid-item-content mini-side">
                <div class="text-center">
                    <div class="grid-title"><span>B</br>A</br>N</br>K</br>I</br>N</br>G</span></div>
                </div>
                <div class="main-holder">
                    <div class="centered">
                        <div class="per-row">
                            <a href="javascript:">
                                <div class="holder">
                                    <div class="icon"><img src="/assets/member/img/sample-icon.png"></div>
                                    <div class="name">Record Deposits</div>
                                </div>
                            </a>
                            <div class="space-line" style="width: 30px;"></div>
                            <a href="javascript:">
                                <div class="holder">
                                    <div class="icon"><img src="/assets/member/img/sample-icon.png"></div>
                                    <div class="name">Reconcile</div>
                                </div>
                            </a>
                        </div>
                        <div class="per-row">
                            <a href="/member/transaction/write_check/create">
                                <div class="holder">
                                    <div class="icon"><img src="/assets/member/img/sample-icon.png"></div>
                                    <div class="name">Write Checks</div>
                                </div>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div id="page-wrapper" class="insights-content" style="overflow: hidden; margin-top: 5px;">
    <div class="row clearfix">
        <div class="col-md-4">
            <div class="panel panel-default" style="margin-bottom: 15px !important">
                <div class="panel-heading">
                   <div class="row cleafix">
                        <div class="col-md-6">
                            <input type="hidden" name="range_mo" class="range-mo" value="{{$selected_mo or ''}}">
                            <input type="hidden" name="range_yr" class="range-yr" value="{{$selected_yr or ''}}">
                            <label>Month</label>
                            <select type="text" class="form-control change-mo" name="month">
                                @foreach($_month as $keymo => $mo)
                                <option value="{{$keymo+1}}" {{$month_now == $keymo+1 ? 'selected' : '' }} >{{$mo}}</option>
                                @endforeach
                                <option value="this_year" {{$month_now == 'this_year' ? 'selected' : '' }}>Year</option>
                            </select> 
                        </div>
                        <div class="col-md-6">
                            <label>Year</label>
                            <select class="form-control change-yr" name="year">
                                @foreach($_year as $yr)
                                <option {{$year_now == $yr ? 'selected' : '' }} value="{{$yr}}">{{$yr}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>
            </div>
            @if ($user_data && $user_data->user_level == 91 || $user_data->user_level == 1)
            <div class="panel panel-default" style="margin-bottom: 10px !important">
                <div class="panel-heading">
                    <i class="fa fa-list fa-2x" style="margin-right: 5px;"></i>
                    <span style="font-size:20px;">Raw Materials</span>
                </div>
                <div class="panel-body" style="padding-top: 5px;padding-bottom: 5px">
                    <div class="row clearfix">
                        <div class="transaction-class" style="padding-top: 5px;margin-top: 5px">
                            <div class="row cleafix">
                                <div class="col-md-6 po-transaction-class box-padding">
                                    <a href="#">
                                        <span class="span-amount">{{currency("PHP ",$_migo['r_po'])}}</span><br>
                                        <span>Total Purchase Order</span>
                                    </a>
                                </div>
                                <div class="col-md-6 pr-transaction-class box-padding">
                                    <a href="/member/report/accounts_payable">
                                        <span class="span-amount">{{currency("PHP ",$_migo['r_ap'])}}</span><br>
                                        <span>Total Accounts Payable</span>
                                    </a>
                                </div>
                            </div>
                            <div class="row cleafix" style="margin-top: 5px;">
                                <div class="col-md-6 so-transaction-class box-padding">
                                    <a href="#">
                                        <span class="span-amount">{{currency("PHP ",$_migo['r_ri'])}}</span><br>
                                        <span>Total Received Item</span>
                                    </a>
                                </div>
                                <div class="col-md-6 ap-transaction-class box-padding">
                                    <a href="#">
                                        <span class="span-amount">{{currency("PHP ",$_migo['r_pb'])}}</span><br>
                                        <span>Total Paid Bills</span>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            @endif
            @if ($user_data && $user_data->user_level == 89 || $user_data->user_level == 1)
            <div class="panel panel-default" style="margin-bottom: 10px !important">
                <div class="panel-heading">
                    <i class="fa fa-tree fa-2x" style="margin-right: 5px;"></i>
                    <span style="font-size:20px;">Finished Goods</span>
                </div>
                <div class="panel-body" style="padding-top: 5px;padding-bottom: 5px">
                    <div class="row clearfix">
                        <div class="transaction-class" style="padding-top: 5px;margin-top: 5px">
                            <div class="row cleafix">
                                <div class="col-md-6 po-transaction-class box-padding">
                                    <a href="#">
                                        <span class="span-amount">{{currency("PHP ",$_migo['f_rts'])}}</span><br>
                                        <span>Total Sales</span>
                                    </a>
                                </div>
                                <div class="col-md-6 pr-transaction-class box-padding">
                                    <a href="#">
                                        <span class="span-amount">{{currency("PHP ",$_migo['f_pt'])}}</span><br>
                                        <span>Paid Transaction</span>
                                    </a>
                                </div>
                            </div>
                            <div class="row cleafix" style="margin-top: 5px;">
                                <div class="col-md-6 so-transaction-class box-padding">
                                    <a href="/member/report/accounts_receivable">
                                        <span class="span-amount">{{currency("PHP ",$_migo['f_ar'])}}</span><br>
                                        <span>Accounts Receivable</span>
                                    </a>
                                </div>
                                <div class="col-md-6 ap-transaction-class box-padding">
                                    <a href="/member/report/retain_credit">
                                        <span class="span-amount">{{currency("PHP ",$_migo['f_cm'])}}</span><br>
                                        <span>Total Credit Memo</span>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            @endif
        </div>
        @if ($user_data && $user_data->user_level == 89 || $user_data->user_level == 1)
        <div class="col-md-8">            
            <div class="panel panel-default" style="padding: 20px">
                <h3><strong>Select Year</strong>
                <select name="selected_year" class="select-year">
                    @foreach($_year as $yr)
                    <option {{$selected_year == $yr ? 'selected' : ''}} value="{{$yr}}">{{$yr}}</option>
                    @endforeach
                </select></h3>
                <div class="panel-heading">
                    <h3><strong>Graph</strong></h3>
                </div>
                <div class="panel-body">
                    <div class="row clearfix">
                       <canvas id="myChart" class="form-control"></canvas>
                    </div>
                </div>
            </div>
        </div>
        @endif
    </div>
    <div class="row clearfix hidden">
        <div class="col-md-12">
            <div class="panel panel-default" style="padding: 20px">
                <div class="panel-heading">
                    <h3><strong>Description</strong></h3>
                </div>
                <div class="panel-body">
                    <div class="row clearfix">
                        <p style="margin-left:50px;font-size:20px; ">
                            <strong>Total Purchase Order</strong> - Total amount of open purchase order for raw materials.<br>
                            <strong>Total Received Item</strong> - Total amount of item receive from supplier/factory for raw materials.<br>
                            <strong>Accounts Payable</strong> - Total amount of payable from supplier for raw materials.<br>
                            <strong>Total Paid Bills</strong> - Total amount of paid from supplier for raw materials.<br>
                            <strong>Running Total Sales </strong> - Total paid & receivables amount from client.<br>
                            <strong>Total Delivered Item</strong> - Total delivered item to client.<br>
                            <strong>Accounts Payable</strong> - Total amount of payable from supplier for finished goods.<br>
                            <strong>Total Undelivered Item</strong> - Total undelivered item finished goods.
                        </p>
                    </div>
                </div>
            </div>
        </div> 
        <div class="col-md-12">
        </div>
    </div>
    <div class="row clearfix">
    </div>
</div>
@endsection
@section('css')
<link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/jointjs/1.1.0/joint.min.css">
<link rel="stylesheet" type="text/css" href="/assets/member/css/new_dashboard.css">
<link rel="stylesheet" type="text/css" href="/assets/member/css/dashboard.css">
<style type="text/css">
.dashboard-arrow{
display: inline;
}
.dashboard-arrow > *{
display: inline-block;
vertical-align: middle;
}
.chart-legend ul {
list-style: none;
margin: 0;
padding: 0;
}
.chart-legend span {
display: inline-block;
width: 14px;
height: 14px;
border-radius: 100%;
margin-right: 16px;
margin-bottom: -2px;
}
.chart-legend li {
margin-bottom: 10px;
display: inline-block;
margin-left: 20px;
}
canvas {
width: 100% !important;
height: auto !important;
}
.table {
display: table;
width: 100%;
table-layout: fixed;
}
.cell {
display: table-cell;
vertical-align: middle;
}
.panel-default
{
    /*border-color: initial;*/
    border-width: medium;
}
.box-padding
{
    padding: 10px;
}
</style>
@endsection
@section('script')
<script type="text/javascript">
var income_date     = {!! $income_date_migo !!}
var income_value    = {!! $income_value_migo !!}
</script>
<script type="text/javascript">
    $("body").on("change",".select-year", function()
    {
        location.href = "/member?selected_year="+$(this).val();
    });
    $("body").on("change",".change-mo", function()
    {
        $yr = $(".change-yr").val();
        if($(".range-yr").val())
        {
            $yr = $(".range-yr").val();
        }
        location.href = "/member?selected_mo="+$(this).val()+"&selected_yr="+$yr;
    });
    $("body").on("change",".change-yr", function()
    {
        $mo = $(".change-mo").val();
        if($(".range-mo").val())
        {
            $mo = $(".range-mo").val();
        }
        location.href = "/member?selected_yr="+$(this).val()+"&selected_mo="+$mo;
    });
</script>
<script type="text/javascript">
var ctx = document.getElementById("myChart");
var myChart = new Chart(ctx, {
    type: 'line',
    data: {
        labels: ["January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December"],
        datasets: [{
            label: 'Sales',
            data: income_value,
            backgroundColor: [
                'rgba(255, 99, 132, 0.2)',
                'rgba(54, 162, 235, 0.2)',
                'rgba(255, 206, 86, 0.2)',
                'rgba(75, 192, 192, 0.2)',
                'rgba(153, 102, 255, 0.2)',
                'rgba(255, 159, 64, 0.2)'
            ],
            borderColor: [
                'rgba(255,99,132,1)',
                'rgba(54, 162, 235, 1)',
                'rgba(255, 206, 86, 1)',
                'rgba(75, 192, 192, 1)',
                'rgba(153, 102, 255, 1)',
                'rgba(255, 159, 64, 1)'
            ],
            borderWidth: 1
        }]
    },
    options: {
         legend: {
            responsive: true,
            display: true,
          },
        scales: {
            yAxes: [{
                ticks: {
                    beginAtZero:true
                }
            }]
        }
    }
});
</script>
<script type="text/javascript" src="/assets/member/js/new_dashboard.js"></script>
@endsection