@extends('member.layout')
@section('content')
<div class="panel panel-default panel-block panel-title-block">
    <input type="hidden" name="_token" id="_token" value="{{csrf_token()}}"/>
    <div class="panel-heading">
        <div>
            <i class="fa fa-calendar"></i>
            <h1>
            <span class="page-title">Redeemable Item</span>
            <small>
            <!-- Insert Description Here  -->
            </small>
            </h1>
            <div class="dropdown pull-right">
                <!-- <button onclick="location.href=''" class="btn btn-def-white btn-custom-white"><i class="fa fa-check"></i> Secondary Command</button> -->
                <!-- <button onclick="action_load_link_to_modal('/member/item/redeemable/add', 'lg')" class="btn btn-primary"><i class="fa fa-plus"></i> Add Redeemable</button> -->
            </div>
        </div>
    </div>
</div>
<div class="panel panel-default panel-block panel-title-block panel-gray "  style="margin-bottom: -10px;">
    <ul class="nav nav-tabs">
        <li class="active change-tab pending-tab cursor-pointer" mode="0"><a class="cursor-pointer"><i class="fa fa-check"></i> Pending</a></li>
        <li class="cursor-pointer change-tab complete-tab" mode="1"><a class="cursor-pointer"><i class="fa fa-trash"></i> Complete</a></li>
        <li class="cursor-pointer change-tab cancel-tab" mode="1"><a class="cursor-pointer"><i class="fa fa-trash"></i> Cancelled</a></li>
    </ul>
    <div class="search-filter-box">
        <!-- <div class="col-md-3" style="padding: 10px">
            <select class="form-control">
                <option value="0">Filter Sample 001</option>
            </select>
        </div>
        <div class="col-md-3" style="padding: 10px">
            <select class="form-control">
                <option value="0">Filter Sample 002</option>
            </select>
        </div> -->
        <div class="col-md-8" style="padding: 10px">
        </div>
<!--         <div class="col-md-4" style="padding: 10px">
            <div class="input-group">
                <span style="background-color: #fff; cursor: pointer;" class="input-group-addon" id="basic-addon1"><i class="fa fa-search"></i></span>
                <input type="text" class="form-control search" placeholder="Search by item name" aria-describedby="basic-addon1">
            </div>
        </div> -->
    </div>
    <div class="tab-content codes_container" style="min-height: 300px;">
        <div id="all" class="tab-pane fade in active">
            <div class="form-group order-tags"></div>
            <div class="clearfix">
                <div class="col-md-12">
                    <div class="table-responsive load-table-here">
                        
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
</div>
@endsection

@section('script')
<script type="text/javascript">
var table_data  = {};
var current_tab = 1;
action_load_table("PENDING");

function action_table_loader()
{
    $(".load-table-here").html('<div style="padding: 100px; text-align: center; font-size: 20px;"><i class="fa fa-spinner fa-pulse fa-fw"></i></div>');
}
function action_load_table($status)
{
    table_data.activetab = $status;

    action_table_loader();
    $.ajax(
    {
        url: '/member/mlm/item_redeemable_points_table',
        data: table_data,
        type: "get",
        success: function(data)
        {
            $('.load-table-here').html(data);
        }
    });
}


$(".pending-tab").click(function()
{
    if(current_tab != 1)
    {
        $('.change-tab').removeClass('active');
        $(this).addClass('active');
        current_tab = 1;
        action_load_table("PENDING");
    }
});

$(".complete-tab").click(function()
{
    if(current_tab != 2)
    {
        $('.change-tab').removeClass('active');
        $(this).addClass('active');
        current_tab = 2;
        action_load_table("COMPLETE");
    }
});

$(".cancel-tab").click(function()
{
    if(current_tab != 3)
    {
        $('.change-tab').removeClass('active');
        $(this).addClass('active');
        current_tab = 3;
        action_load_table("CANCELLED");
    }
});


</script>
@endsection