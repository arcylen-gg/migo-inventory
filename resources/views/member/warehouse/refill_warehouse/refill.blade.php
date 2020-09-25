@extends('member.layout')
@section('content')

<form class="global-submit form-to-submit-transfer" id="billing_form" role="form" action="{{$action}}" method="POST" >
    <input type="hidden" class="token" name="_token" value="{{csrf_token()}}" >
    <input type="hidden" class="button-action" name="button_action" value="">

    <input type="hidden" class="button-action" name="warehouse_id" value="{{Request::input('w_id')}}">

<div class="drawer-overlay">
    <div class="panel panel-default panel-block panel-title-block" id="top">
        <div class="panel-heading">
            <div>
                <i class="fa fa-tags"></i>
                <h1>
                    <span class="page-title">Warehouse &raquo; Refill Inventory</span>
                    <small>
                    <!--Add a product on your website-->
                    </small>
                </h1>
                <button type="submit" class="panel-buttons btn btn-custom-primary pull-right" data-action="save-and-edit">Refill</button>
                <a href='/member/item/warehouse' class="panel-buttons btn btn-custom-white pull-right" data-action="save-and-new">Cancel</a>
            </div>
        </div>
    </div>
    <div class="panel panel-default panel-block panel-title-block panel-gray">  
        <div class="tab-content">
            <div class="row">
                 <div class="form-group">
                     <div class="col-md-12">
                        <div class="form-group">
                            <div class="col-md-12" style="padding: 30px;">
                                <!-- START CONTENT -->
                                <div style="border-bottom: 1px solid #ddd; padding-bottom: 10px; margin-bottom: 10px;">
                                    <div class="row clearfix">
                                        <div class="col-sm-3">
                                            <select class="form-control droplist-vendor input-sm pull-left" name="bill_vendor_id">
                                                 @include('member.load_ajax_data.load_vendor', ['vendor_id' => isset($bill->bill_vendor_id) ? $bill->bill_vendor_id : (isset($vendor_id) ? $vendor_id : '')]);
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                
                                 <div class="row clearfix draggable-container">
                                    <div class="table-responsive " id="item-tbl">
                                        <div class="col-sm-12">
                                            <table class="digima-table">
                                                <thead >
                                                    <tr>
                                                        <th style="width: 15px;"></th>
                                                        <th style="width: 15px;">#</th>
                                                        <th style="width: 200px;">Product</th>
                                                        <th>Description</th>
                                                        <th style="width: 70px;">U/M</th>
                                                        <th style="width: 120px;">Rate</th>
                                                        <th style="width: 70px;">Qty</th>
                                                        <th style="width: 15px;"></th>
                                                    </tr>
                                                </thead>
                                                <tbody class="tbody-item">
                                                

                                                <tbody class="draggable tbody-item po-tbl">
                                                    <tr class="tr-draggable">
                                                         <td class="text-center cursor-move move">
                                                            <i class="fa fa-th-large colo-mid-dark-gray"></i>         
                                                         </td>
                                                        <td class="invoice-number-td text-right">1</td>
                                                        <td>
                                                        <input type="hidden" class="poline_id" name="itemline_ref_name[]">
                                                        <input type="hidden" class="itemline_po_id" name="itemline_ref_id[]">
                                                            <select class="1111 form-control select-item droplist-item input-sm pull-left" name="item_id[]" >
                                                                @include("member.load_ajax_data.load_item_category", ['add_search' => ""])
                                                            </select>
                                                        </td>
                                                        <td><textarea class="textarea-expand txt-desc" name="itemline_description[]"></textarea></td>
                                                        <td><select class="2222 droplist-um select-um" name="item_um[]"><option class="hidden" value="" /></select></td>
                                                        <td><input class="text-right number-input txt-rate compute" type="text" name="itemline_rate[]"/></td>
                                                        <td><input class="text-center number-input txt-qty compute" type="text" name="quantity[]"/></td>
                                                        <td class="text-center remove-tr cursor-pointer"><i class="fa fa-trash-o" aria-hidden="true"></i></td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                                <div class="row clearfix">
                                    <div class="col-sm-6">
                                        <label>Remarks</label>
                                        <textarea class="form-control input-sm textarea-expand" name="remarks" ></textarea>
                                    </div>
                                    
                                </div>
                                
                                <!-- END CONTENT -->
                            </div>
                        </div>
                     </div>
                </div>
            </div>
        </div>
    </div>
</div>
</form>
<div class="div-script">
    <table class="div-item-row-script hide">
       <tr class="tr-draggable">
            <td class="text-center cursor-move move">
                <i class="fa fa-th-large colo-mid-dark-gray"></i>
            </td>
            <td class="invoice-number-td text-right">1</td>
            <td>
                <input type="hidden" class="poline_id" name="itemline_ref_name[]">
                <input type="hidden" class="itemline_po_id" name="itemline_ref_id[]">
                <select class="1111 form-control select-item input-sm pull-left" name="item_id[]" >
                    @include("member.load_ajax_data.load_item_category", ['add_search' => ""])
                </select>
            </td>
            <td><textarea class="textarea-expand txt-desc" name="itemline_description[]"></textarea></td>
            <td><select class="2222 select-um" name="item_um[]"><option class="hidden" value="" /></select></td>
            <td><input class="text-right number-input txt-rate compute" type="text" name="itemline_rate[]"/></td>
            <td><input class="text-center number-input txt-qty compute" type="text" name="quantity[]"/></td>
            <td class="text-center remove-tr cursor-pointer"><i class="fa fa-trash-o" aria-hidden="true"></i></td>
        </tr>
    </table>
</div>
@endsection

@section('script')
<script type="text/javascript" src="/assets/member/js/textExpand.js"></script>
<script type="text/javascript" src="/assets/member/js/draggable_row.js"></script>
<script type="text/javascript" src="/assets/member/js/refill_inventory.js"></script>
<script type="text/javascript">
$("#acct-a").click(function()
{
	$('#account-tbl').toggle();
	$('i',this).toggleClass("fa-caret-right fa-caret-down")
});
$("#item-a").click(function()
{
	$('#item-tbl').toggle();
	$('i',this).toggleClass("fa-caret-right fa-caret-down")
});
</script>
@endsection
