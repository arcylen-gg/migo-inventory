
@extends('member.layout')
@section('content')
<div class="panel panel-default panel-block panel-title-block" id="top">
    <input type="hidden" name="_token" value="{{csrf_token()}}">
    <input type="hidden" name="w_id" id='warehouse_id' value="{{$warehouse_id}}">
    <div class="panel-heading">
        <div>
            <i class="fa fa-area-chart"></i>
            <h1>
                <span class="page-title">View Warehouse &raquo; {{ucwords($warehouse_name)}}</span>
                <small>
                    Warehouse Inventory
                </small>
            </h1>
            <div class="text-right">
                <a href="/member/item/warehouse/view_v2/print/{{$warehouse_id}}/bundle" class="btn btn-primary print-inventory">Print Inventory</a>
            </div>
        </div>
    </div>
</div>

<div class="panel panel-default panel-block panel-title-block">
    <div class="panel-body form-horizontal row clearfix">
        <div class="form-group">
            <div class="col-md-6">
                <ul class="nav nav-tabs">
                  <li id="all-list" class="active"><a data-toggle="tab" href="#bundle" onClick="select_inventory('bundle');"><i class="fa fa-yelp" aria-hidden="true"></i>&nbsp;Bundle/Group</a></li>
                  <li id="archived-list"><a data-toggle="tab" href="#odd-inventory" onClick="select_inventory('inventory');"><i class="fa fa-opencart" aria-hidden="true"></i>&nbsp;Single Inventory</a></li>
                  <li id="archived-list"><a data-toggle="tab" href="#bundle-empties-empties" onClick="select_inventory('bundle_empties');"><i class="fa fa-qrcode" aria-hidden="true"></i>&nbsp;Bundled Empties</a></li>
                  <li id="archived-list"><a data-toggle="tab" href="#unused-empties" onClick="select_inventory('empties');"><i class="fa fa-slack" aria-hidden="true"></i>&nbsp;Empties</a></li>
                </ul>
            </div>
            <div class="col-md-3">
              <select class="form-control select-manufacturer" placeholder="Enter Manufacturer" >
                  <option value="">All</option>
                  @include("member.load_ajax_data.load_manufacturer")
              </select>
            </div>
            <div class="col-md-3">
                <div class="input-group pos-search">
                  <span style="background-color: #eee" class="input-group-addon button-scan" id="basic-addon1">
                    <i class="fa fa-search scan-icon"></i>
                    <i style="display: none;" class="fa fa-spinner fa-pulse fa-fw scan-load"></i>
                  </span>
                  <input type="text" class="form-control event_search_item" placeholder="Enter item SKU" aria-describedby="basic-addon1">
                </div>
            </div>
        </div>
        <div class="inventory-table">
           @include("member.warehouse.warehouse_view_v2_inventory_table")
        </div>
    </div>
</div>

@endsection

@section('script')
<script type="text/javascript">
    var global_type = 'bundle';
    var global_m_id = null;
    function select_inventory(type = '')
    {
        $('.tab-pane').removeClass('active');
        $('#'+type).addClass('active in');
        var id = $('#warehouse_id').val();
        global_type = type;
        $('.print-inventory').attr('href','/member/item/warehouse/view_v2/print/'+id+'/'+type+'?m_id='+global_m_id);
    }
    $('.select-manufacturer').globalDropList(
    {
        hasPopup : 'false',
        width : '100%',
        onChangeValue : function ()
        {
            $(".inventory-table").css("opacity", 0.3);
            var id = $('#warehouse_id').val();
            var m_id = $(this).val();
            if($(this).val())
            {
                $('.inventory-table').load('/member/item/warehouse/view_v2/table/'+id+'?type='+global_type+'&m_id='+m_id+' .inventory-container', function()
                {
                    $('.tab-pane').removeClass('active');
                    $('#'+global_type).addClass('active in');
                    global_m_id = m_id;
                    $('.print-inventory').attr('href','/member/item/warehouse/view_v2/print/'+id+'/bundle?m_id='+global_m_id);
                    $(".inventory-table").css("opacity", 1);
                })
            }
            else
            {
                $('.inventory-table').load('/member/item/warehouse/view_v2/table/'+id+'?type='+global_type + ' .inventory-container', function()
                {
                    $('.tab-pane').removeClass('active');
                    $('#'+global_type).addClass('active in');
                    $('.print-inventory').attr('href','/member/item/warehouse/view_v2/print/'+id+'/'+global_type);
                    $(".inventory-table").css("opacity", 1);
                });
            }
        }
    });
</script>
@endsection