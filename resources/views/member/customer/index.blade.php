@extends('member.layout')
@section('css')
<style type="text/css">
    .popover{
        left: 1122px !important;
    }
</style>
@endsection

@section('content')

<div class="panel panel-default panel-block panel-title-block">
    <input type="hidden" name="_token" id="_token" value="{{csrf_token()}}"/>
    <div class="panel-heading">
        <div>
            <i class="fa fa-users"></i>
            <h1>
                <span class="page-title">Customers</span>
                <small>
                Manage your customer
                </small>
            </h1>
            <a href="javascript:" class="panel-buttons btn btn-custom-primary pull-right popup" link="/member/customer/modalcreatecustomer" size="lg" data-toggle="modal" data-target="#global_modal">Create Customer</a>
            <a href="javascript:" class="panel-buttons btn btn-default pull-right popup" link="/member/customer/bulk_archive" size="lg">Customer Bulk Archive</a>
        </div>
    </div>
</div>
<div class="panel panel-default panel-block panel-title-block">
    <div class="panel-body">
        <ul class="nav nav-tabs">
            <li class="active cursor-pointer customer-tab" data-value="0"><a class="cursor-pointer" data-toggle="tab"><i class="fa fa-star"></i> Active Customers</a></li>
            <li class="cursor-pointer customer-tab" data-value="1"><a class="cursor-pointer" data-toggle="tab"><i class="fa fa-trash"></i> Inactive Customers</a></li>
        </ul>
        
        <div class="search-filter-box">
            @if(isset($migo_customization))
               @if($migo_customization)
                <div class="col-md-3 " style="padding: 10px">
                    <select class="form-control select-category">
                      <option value="all">All Category</option>
                      <option value="new-client">New Client</option>
                      <option value="regular">Regular</option>
                      <option value="former">Former</option>
                      <option value="employee">Employee</option>
                      <option value="do-not-call">Do not Call</option>
                    </select>
                </div>
                <div class="col-md-3 " style="padding: 10px">
                    <select class="form-control select-category-type">
                      <option value="all">All Type</option>
                      <option value="non-vip">Non VIP</option>
                      <option value="vip">VIP</option>
                    </select>
                </div>
                @endif
            @endif
            <div class="col-md-4 col-md-offset-2" style="padding: 10px">
                <div class="input-group">
                    <span style="background-color: #fff; cursor: pointer;" class="input-group-addon" id="basic-addon1"><i class="fa fa-search"></i></span>
                    <input type="text" class="form-control customer-search" placeholder="Search by Customer Name press Enter" aria-describedby="basic-addon1">
                </div>
            </div>  
        </div>
        
        <div class=" panel-customer load-data">
            @include("member.customer.customer_tbl")
        </div>
    </div>
</div>
@endsection

@section('script')
<script type="text/javascript">
    function filter_customer_slot(sel)
    {
        var filter = $(sel).val();
        var link = '/member/customer/list?filter_slot=' + filter;
        // location.redirect(link);
        window.location = link;
        // $('.load-data').html('<div style="margin: 100px auto;" class="loader-16-gray"></div>');
        // $('.load-data').load(link);
    }
    function submit_done(data)
    {
        if(data.message == "success")
        {
            console.log(121212);
        }
    }
    function success_update_customer(data)
    {
        if(data.message == 'success')
        {
            toastr.success('Success');
            location.href = '/member/customer/list';
        }
    }
</script>
<script type="text/javascript" src="/assets/member/js/customer.js"></script>
<script type="text/javascript" src="/assets/member/js/customerlist.js"></script>
<script type="text/javascript" src="/assets/member/js/paginate_ajax.js"></script>
@endsection