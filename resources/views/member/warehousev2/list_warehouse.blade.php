@extends('member.layout')
@section('content')

<link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
<div class="panel panel-default panel-block panel-title-block" id="top">
    <input type="hidden" id="_token" value="{{csrf_token()}}" name="">
    <div class="panel-heading">
        <div>
            <i class="fa fa-building"></i>
            <h1>
                <span class="page-title">Warehouse</span>
                <small>
                    List of warehouse.
                </small>
            </h1>
            <div class="text-right">
                <a class="btn btn-primary panel-buttons popup" link="/member/item/v2/warehouse/add" size="lg" data-toggle="modal" data-target="#global_modal">Add Warehouse</a>
            </div>
        </div>
    </div>
</div>
<div class="panel panel-default panel-block panel-title-block panel-gray "  style="margin-bottom: -10px;">
    <ul class="nav nav-tabs">
        <li class="active change-tab cursor-pointer 0-tab" mode="0"><a class="cursor-pointer"><i class="fa fa-star"></i> Active Warehouse</a></li>
        <li class="cursor-pointer change-tab 1-tab" mode="1"><a class="cursor-pointer"><i class="fa fa-trash"></i> Archived Warehouse</a></li>
    </ul>
    <div class="search-filter-box">
        <div class="col-md-3" style="padding: 10px">
        </div>
        <div class="col-md-3" style="padding: 10px">
        </div>
        <div class="col-md-2" style="padding: 10px">
        </div>
        <div class="col-md-4" style="padding: 10px">
            <div class="input-group">
                <span style="background-color: #fff; cursor: pointer;" class="input-group-addon" id="basic-addon1"><i class="fa fa-search"></i></span>
                <input type="text" class="form-control search-keyword" placeholder="Search ..." aria-describedby="basic-addon1">
            </div>
        </div>
    </div>
    <div class="tab-content codes_container" style="min-height: 300px;">
        <div id="all" class="tab-pane fade in active">
            <div class="form-group order-tags"></div>
            <div class="clearfix">
                <div class="col-md-12">
                    <div class="table-responsive load-item-table">
                      <div class="text-center">LOADING WAREHOUSE...</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section("script")
<script type="text/javascript" src="/assets/member/js/warehouse/warehousev2.js"></script>

<script type="text/javascript">
    
$('.droplist-vendor').globalDropList(
{ 
    width : "100%",
    link : "/member/vendor/add",
    onChangeValue : function ()
    {
        var vendor_id = $(this).val();
        if(vendor_id != "other")
        {
            var warehouse_id = $("#warehouse_id").val();
            $(".warehouse-refill-container").load("/item/warehouse/refill/by_vendor/"+warehouse_id+"/"+vendor_id +" .warehouse-refill-container") 
        }
    }
});
@endsection