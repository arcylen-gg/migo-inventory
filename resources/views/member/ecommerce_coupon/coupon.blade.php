@extends('member.layout')
@section('content')
<link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
<div class="panel panel-default panel-block panel-title-block" id="top">
    <div class="panel-heading">
        <div>
            <i class="fa fa-tags"></i>
            <h1>
                <span class="page-title">Coupon Code &raquo; List </span>
                <small>
                    List of Coupon
                </small>
            </h1>
            <a class="panel-buttons btn btn-custom-primary pull-right popup" size="md" link="/member/ecommerce/coupon/generate-code" >Generate Coupon Code</a>
        </div>
    </div>
</div>

<div class="panel panel-default panel-block panel-title-block panel-gray ">
     <div class="panel-body">
        <ul class="nav nav-tabs">
            <li class="active cursor-pointer"><a class="cursor-pointer" data-toggle="tab" href="#unused"><i class="fa fa-star"></i> Unused Coupon</a></li>
            <li class="cursor-pointer"><a class="cursor-pointer" data-toggle="tab" href="#used"><i class="fa fa-trash"></i> Used Coupon</a></li>
        </ul>
        
        <div class="search-filter-box">
            <div class="col-md-4 col-md-offset-8" style="padding: 10px">
                <div class="input-group">
                    <span style="background-color: #fff; cursor: pointer;" class="input-group-addon" id="basic-addon1"><i class="fa fa-search"></i></span>
                    <input type="text" class="form-control global-search" url="" data-value="1" placeholder="Press Enter to Search" aria-describedby="basic-addon1">
                </div>
            </div>
        </div>
        <div class="tab-content">
            <div id="unused" class="tab-pane fade in active">
                <div class="load-data" target="unused_coupon" filter="unused">
                    <div id="unused_coupon">
                         @include('member.ecommerce_coupon.load_coupon_tbl',['_coupon' => $unused_coupon, 'filter' => 'unused'])
                    </div>
                </div>
            </div>
            <div id="used" class="tab-pane fade in">
                <div class="load-data" target="used_coupon" filter="used">
                    <div id="used_coupon">
                        @include('member.ecommerce_coupon.load_coupon_tbl',['_coupon' => $used_coupon, 'filter' => 'used'])
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>
@endsection
@section('script')
<script>
var coupon_code = new coupon_code();

function coupon_code()
{
    init();
    function init()
    {
        document_ready();
    }

    function document_ready()
    {

    }
}

function submit_done(data)
{
    if(data.status == "success")
    {
        toastr.success(data.status_message);
        data.element.modal("toggle");
        $("#unused .load-data").load("/member/ecommerce/coupon/list #unused_coupon");

    }
    else
    {
        toastr.error(data.status_message);
    }
}
</script>
<script type="text/javascript" src="/assets/member/js/paginate_ajax_multiple.js"></script>
@endsection