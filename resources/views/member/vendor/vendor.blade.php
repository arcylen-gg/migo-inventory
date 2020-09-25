@extends('member.layout')
@section('content')

<div class="panel panel-default panel-block panel-title-block">
    <input type="hidden" name="_token" id="_token" value="{{csrf_token()}}"/>
    <div class="panel-heading">
        <div>
            <i class="fa fa-users"></i>
            <h1>
                <span class="page-title">vendors</span>
                <small>
                Manage your vendor
                </small>
            </h1>
            
            <a href="javascript:" class="panel-buttons btn btn-custom-primary pull-right popup" link="/member/vendor/add" size="lg" data-toggle="modal" data-target="#global_modal">Create Vendor</a>
        </div>
    </div>
</div>
<div class="panel panel-default panel-block panel-title-block">
    <div class="panel-body">
        <ul class="nav nav-tabs">
            <li class="active cursor-pointer vendor-tab active-vendor change-tab 0-tab" mode="0"><a class="cursor-pointer" data-toggle="tab"><i class="fa fa-star"></i> Active Vendor</a></li>
            <li class="cursor-pointer vendor-tab inactive-vendor change-tab 1-tab" mode="1"><a class="cursor-pointer" data-toggle="tab"><i class="fa fa-trash"></i> Inactive Vendor</a></li>
        </ul>

        <div class="search-filter-box">
            <div class="col-md-4 col-md-offset-8" style="padding: 10px">
                <div class="input-group">
                    <span style="background-color: #fff; cursor: pointer;" class="input-group-addon" id="basic-addon1"><i class="fa fa-search"></i></span>
                    <input type="text" class="form-control search-keyword" name='search' data-value="1" placeholder="Search by vendor Name" aria-describedby="basic-addon1">
                </div>
            </div>  
        </div>

        <div class="load-vendor">
            <div class="tab-content load-vendor-data">
                <div id="active" class="tab-pane fade in active">
                    <div class="panel-vendor load-data load-item-table">   
                        @include('member.vendor.load_vendor_tbl')
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>

@endsection

@section('script')<!-- 
<script type="text/javascript" src="/assets/member/js/paginate_ajax_multiple.js"></script> -->
<!-- <script type="text/javascript" src="/assets/member/js/vendor.js"></script> -->
<script type="text/javascript" src="/assets/member/js/vendorlist.js"></script>
<script type="text/javascript">
function submit_done(data)
{
    if(data.status == 'error')
    {
        toastr.error(data.error);
    }
    else
    {
        toastr.success("success");
        location.reload();
        // $(".load-vendor-data").load("/member/vendor/list .load-vendor");
        // load_vendor_data();
        $("#global_modal").modal("toggle");
    }
}
function success_vendor(data)
{
    if(data.status == 'success')
    {
        toastr.success("success");
        location.reload();
        // $(".load-vendor-data").load("/member/vendor/list .load-vendor");
        // load_vendor_data();
        $("#global_modal").modal("toggle");
    }

}
function load_vendor_data()
{
    $.ajax(
    {
        url: '/member/vendor/load-vendor-tbl',

        success: function(data)
        {
            $(".load-data").html(data);
        },
        error: function()
        {
            console.log("error");
        }

    })
}
</script>
@endsection