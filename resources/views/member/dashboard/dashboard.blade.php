@extends('member.layout')
@section('content')

@if(isset($pis) && $pis != 0)
<div class="row">
   <div class="form-group">
        <div class="col-md-12 text-center">
            <h2>{{$shop_name or ''}}</h2>
        </div>
    </div>
</div>
<div class="reports-class">
    <div class="row clearfix" style="margin-bottom: 15px;">
        <form method="get" class="range-date">
            <div class="col-md-12">
            <small>Date Range </small>                
            </div>
            <div class="col-sm-5">
                <input class="form-control datepicker" type="text" name="start_date" placeholder="Start Date" value="{{ Request::input('start_date') ? Request::input('start_date') : date('m/d/Y',strtotime($mants)) }}">
            </div>
            <div class="col-sm-5">
                <input class="form-control datepicker" type="text" name="end_date" placeholder="End Date" value="{{ Request::input('end_date') ? Request::input('end_date') : date('m/d/Y') }}">
            </div>
            <div class="col-md-2 text-center"><button type="button" class="btn btn-primary" onClick="location.href='/member'">Clear Date</button></div>
        </form>
    </div>
    <div class="row cleafix">
        <div class="col-md-6">
            <div class="btn-class sales-class">
                <span><strong>{{$sales_amount or 0}}</strong></span><br>
                <span>Total Sales</span>
            </div>
        </div>
        <div class="col-md-6">
            <a href="/member/vendor/paybill/list" class="btn-class pb-class">
                <span><strong>{{$pb_amount or 0}}</strong></span><br>
                <span>Total Paid Bills</span>
            </a>
        </div>
    </div>
    <div class="row cleafix">
        <div class="col-md-4">
            <a href="/member/vendor/purchase_order/list" class="btn-class po-class"> 
                <span><strong>{{$po_amount or 0}}</strong></span><br>
                <span>{{$count_po or 0}} Purchase Orders</span>
            </a>
        </div>
        <div class="col-md-4">
            <a href="/member/customer/invoice_list" class="btn-class ar-class">
                <span><strong>{{$ar_amount or 0}}</strong></span><br>
                <span>{{$count_ar or 0}} Account Receivable</span>
            </a>
        </div>
        <div class="col-md-4">
            <a href="/member/vendor/bill_list" class="btn-class ap-class">
                <span><strong>{{$ap_amount or 0}}</strong></span><br>
                <span>{{$count_ap or 0}} Account Payables</span>
            </a>
        </div>
    </div>
</div>

@else
<div class="row">
    <div class="col-md-12 text-center">
        <h3>Welcome to Digima House</h3>
    </div>
</div>

<!-- TRIAL COUNT DOWN -->
<div class="row">
    <div class="col-md-8 col-md-offset-2">
        <div class="trial-warning clearfix">
            <!-- <div class="word-warning pull-left">You have 23 days left of your trial</div> -->
            <a class="pull-right btn btn-success" href="">Select Plan</a>
        </div>
    </div>
</div>

<!-- ADVICE FOR STARTUPS -->
<div class="row text-center">
    <div class="col-md-12">
        <div class="title-advice"><i class="fa fa-comment"></i> Here's some tips to setup your shop</div>
    </div>
    <div class="col-md-8 col-md-offset-2">
        <div class="simple-advice clearfix">
            <form type="post" action="member/utilities/make-developer">
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
        </div>
    </div>
    <div class="col-md-4 col-md-offset-2">
        <div class="simple-advice clearfix">
            <div class="advice-word">Add an item to buy or sell</div>
            <a class="btn btn-primary" href="/member/item">Add Item</a>
        </div>
    </div>
    <div class="col-md-4">
        <div class="simple-advice clearfix">
            <div class="advice-word">Add a customer to do a transaction</div>
            <a class="btn btn-primary" href="/member/customer/list">Add Customer</a>
        </div>
    </div>
    <div class="col-md-4 col-md-offset-2">
        <div class="simple-advice clearfix">
            <div class="advice-word">Add a vendor to do a transaction</div>
            <a class="btn btn-primary" href="/member/vendor/list">Add Vendor</a>
        </div>
    </div>
    <div class="col-md-4">
        <div class="simple-advice clearfix">
            <div class="advice-word">Add a product to see it in your store</div>
            <a class="btn btn-primary" href="/member/ecommerce/product/add">Add Ecommerce Product</a>
        </div>
    </div>
   <!--  <div class="col-md-8 col-md-offset-2">
        <div class="simple-advice clearfix">
            <div class="advice-word">Add a product to see it in your store</div>
            <a class="btn btn-primary" href="/member/ecommerce/product/add">Add a product</a>
        </div>
    </div> -->
    <div class="col-md-4 col-md-offset-2">
        <div class="simple-advice clearfix">
            <div class="advice-word">Customize the look of your website</div>
            <a class="btn btn-primary" href="/member/page/themes">Select Theme</a>
        </div>
    </div>
    <!-- <div class="col-md-8 col-md-offset-2">
        <div class="simple-advice clearfix">
            <div class="advice-word">Setup a custom domain name</div>
            <a class="btn btn-primary" href="">Add a domain</a>
        </div>
    </div> -->
    <div class="col-md-12">
    <div class="chart-container" style="position: relative; height:40vh; width:80vw">
        <canvas id="myChart"></canvas>
    </div>
    </div>
</div>

@endif

@endsection
@section('css')
<link rel="stylesheet" type="text/css" href="/assets/member/css/btn_dashboard.css">
<link rel="stylesheet" type="text/css" href="/assets/member/css/dashboard.css">
@endsection
@section('script')

<script type="text/javascript">
    $(".drop-down-any").globalDropList();
    @if(Session::has('success'))
        toastr.success('{{Session::get('success')}}');
    @elseif(Session::has('error'))
        toastr.error('{{Session::get('error')}}');
    @endif

    $('.btn-class').matchHeight();
    $('body').on("change", ".datepicker", function()
    {
        if($('.range-date input[name="start_date"]').val().length != 0)
        {
            if($('.range-date input[name="end_date"]').val().length != 0)
            {
                $('.range-date').submit();
            }
        }
    });
</script>
<script>
var data = {
  labels: ["Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul"],
  datasets: [{
    label: "Dataset #1",
    backgroundColor: "rgba(255,99,132,0.2)",
    borderColor: "rgba(255,99,132,1)",
    borderWidth: 2,
    hoverBackgroundColor: "rgba(255,99,132,0.4)",
    hoverBorderColor: "rgba(255,99,132,1)",
    data: [65, 59, 20, 81, 56, 55, 40],
  }]
};

var option = {
  responsive: true,
  maintainAspectRatio : true,
  scales: {
    yAxes: [{
      stacked: true,
      gridLines: {
        display: true,
        color: "rgba(255,99,132,0.2)"
      }
    }],
    xAxes: [{
      gridLines: {
        display: false
      }
    }]
  }
};

Chart.Bar('myChart', {
  options: option,
  data: data
});
</script>
@endsection