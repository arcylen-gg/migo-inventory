@extends('member.layout')
@section('content')

<form class="global-submit form-to-submit-transfer" id="billing_form" role="form" action="{{$action}}" method="POST" >
    <input type="hidden" class="token" name="_token" value="{{csrf_token()}}" >
    <input type="hidden" class="button-action" name="button_action" value="">
    <input type="hidden" name="bill_id" value="{{Request::input('id')}}" >

    <button class="drawer-toggle" type="button"> <i class="fa fa-angle-double-left"></i></button>

    <div class="drawer drawer-default">
        <div class="drawer-brand">Purchase Order</div>
        <nav class="drawer-nav">
            <div class="clearfix purchase-order-container">
                @include('member.load_ajax_data.load_purchase_order')
            </div>   
        </nav>
    </div>

<!--<div class="panel panel-default panel-block panel-title-block purchase-order hidden">
        <div class="panel-heading">
             <div class="form-group">
                 <div class="col-md-12">
                                       
                 </div>
             </div>  
        </div>
    </div>
 -->
<div class="drawer-overlay">
    <div class="panel panel-default panel-block panel-title-block" id="top">
        <div class="panel-heading">
            <div>
                <i class="fa fa-tags"></i>
                <h1>
                    <span class="page-title">Vendor &raquo; Receive Inventory</span>
                    <small>
                    <!--Add a product on your website-->
                    </small>
                </h1>
                <!-- <button type="submit" class="panel-buttons btn btn-custom-primary pull-right" data-action="save-and-edit">Save and Close</button>
                <button type="submit" class="panel-buttons btn btn-custom-white pull-right" data-action="save-and-new">Save and New</button> -->
            </div>
            <div class="dropdown pull-right">
                <div>
                    <a class="btn btn-custom-white" href="/member/vendor/receive_inventory/list">Cancel</a>
                    <button class="btn btn-custom-primary dropdown-toggle" type="button" data-toggle="dropdown">Select Action
                    <span class="caret"></span></button>
                    <ul class="dropdown-menu  dropdown-menu-custom">
                        <li><a class="select-action" code="save-and-close">Save & Close</a></li>
                        <li><a class="select-action" code="save-and-edit">Save & Edit</a></li>
                        <li><a class="select-action" code="save-and-print">Save & Print</a></li>
                        <li><a class="select-action" code="save-and-new">Save & New</a></li>
                    </ul>
                </div>
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
                                                 @include('member.load_ajax_data.load_vendor', ['vendor_id' => isset($bill->bill_vendor_id) ? $bill->bill_vendor_id : (isset($vendor_id) ? $vendor_id : '')])
                                            </select>
                                        </div>
                                        <div class="col-sm-4">
                                            <input type="text" class="form-control input-sm customer-email" name="bill_vendor_email" placeholder="E-Mail (Separate E-Mails with comma)" value="{{$bill->bill_vendor_email or ''}}"/>
                                        </div>
                                    </div>
                                </div>
                                
                                <div style="border-bottom: 1px solid #ddd; padding-bottom: 10px; margin-bottom: 10px;">
                                    <div class="row clearfix">
                                        <div class="col-sm-3">
                                            <label>Mailing Address</label>
                                            <textarea class="form-control input-sm textarea-expand" name="bill_mailing_address" placeholder="">{{isset($bill) ? $bill->bill_mailing_address : ''}}</textarea>
                                        </div>              
                                        <div class="col-sm-2">
                                        <label>Terms</label>
                                            <select class="form-control input-sm droplist-terms" name="bill_terms_id">
                                                @include("member.load_ajax_data.load_terms", ['terms_id' => isset($bill) ? $bill->bill_terms_id : ''])
                                            </select>
                                        </div>
                                        <div class="col-sm-2">
                                            <label>Billing Date</label>
                                            <input type="text" class="form-control input-sm datepicker" value="{{isset($bill) ? $bill->bill_date : date('m/d/y')}}" name="bill_date">
                                        </div>
                                        <div class="col-sm-2">
                                            <label>Due Date</label>
                                            <input type="text" class="form-control input-sm datepicker" value="{{isset($bill) ? $bill->bill_due_date : date('m/d/y')}}" name="bill_due_date">
                                        </div>
                                    </div>
                                </div>
                                
                                <!-- <div class="row clearfix">
                                    <div class="title">
                                        <h3><a id="acct-a"> <i class="fa fa-caret-down"></i>  Account Details </a></h3>
                                    </div>
                                    <div class="table-responsive draggable-container" id="account-tbl">
                                        <div class="col-sm-12">
                                            <table class="digima-table">
                                                <thead >
                                                    <tr>
                                                        <th style="width: 15px;"></th>
                                                        <th style="width: 15px;">#</th>
                                                        <th style="width: 200px;">Account</th>
                                                        <th>Description</th>
                                                        <th style="width: 40px;">Amount(PHP)</th>
                                                        <th style="width: 15px;"></th>
                                                    </tr>
                                                </thead>
                                                <tbody class="draggable">
                                                    <tr class="tr-draggable">
                                                        <td class="text-center cursor-move move"><i class="fa fa-th-large colo-mid-dark-gray"></i></td>
                                                        <td class="invoice-number-td text-right">1</td>
                                                        <td >                                           
                                                            <select class="form-control drop-down-coa input-sm" >
                                                                @include("member.load_ajax_data.load_chart_account", ['add_search' => ""])
                                                            </select>
                                                        </td>
                                                        <td><textarea class="textarea-expand"></textarea></td>
                                                        <td><input type="text" class="form-control input-sm" name=""></td>
                                                        <td class="text-center remove-tr cursor-pointer"><i class="fa fa-trash-o" aria-hidden="true"></i></td>
                                                    </tr>
                                                    <tr class="tr-draggable">
                                                        <td class="text-center cursor-move move"><i class="fa fa-th-large colo-mid-dark-gray"></i></td>
                                                        <td class="invoice-number-td text-right">2</td>
                                                        <td >                                           
                                                            <select class="form-control drop-down-coa input-sm" >
                                                                @include("member.load_ajax_data.load_chart_account", ['add_search' => ""])
                                                            </select>
                                                        </td>
                                                        <td><textarea class="textarea-expand"></textarea></td>
                                                        <td><input type="text" class="form-control input-sm" name=""></td>
                                                        <td class="text-center remove-tr cursor-pointer"><i class="fa fa-trash-o" aria-hidden="true"></i></td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>

                                 <div class="title">
                                        <h3><a > <i class="fa fa-caret-down"></i>  Item Details </a></h3>
                                    </div> 
                                </div> -->
                                 <div class="row clearfix draggable-container">
                                    <div class="table-responsive " id="item-tbl">
                                        <div class="col-sm-12">
                                            <table class="digima-table">
                                                <thead >
                                                    <tr>
                                                        <th style="width: 15px;"></th>
                                                        <th style="width: 15px;">#</th>
                                                        <th style="width: 200px;">Product/Service</th>
                                                        <th>Description</th>
                                                        <th style="width: 70px;">U/M</th>
                                                        <th style="width: 70px;">Qty</th>
                                                        <th style="width: 120px;">Rate</th>
                                                        <th style="width: 120px;">Amount</th>
                                                        @include("member.load_ajax_data.load_th_serial_number")
                                                        <th style="width: 15px;"></th>
                                                    </tr>
                                                </thead>
                                                <tbody class="tbody-item">
                                                    @if(isset($bill))
                                                        @foreach($_bill_item_line as $item)
                                                        <tr class="tr-draggable tr-id-{{$item->itemline_ref_id}}">
                                                            <td class="text-center cursor-move move"><i class="fa fa-th-large colo-mid-dark-gray"></i>                
                                                                <input type="text" class="hidden poline_id" name="poline_id[]" value="{{$item->itemline_poline_id}}">
                                                                <input type="text" class="hidden itemline_po_id" name="itemline_po_id[]" value="{{$item->itemline_po_id}}">
                                                            </td>
                                                            <td class="invoice-number-td text-right">1</td>
                                                            <td>

                                                                <input type="hidden" class="poline_id" name="itemline_ref_name[]" value="{{$item->itemline_ref_name}}">
                                                                <input type="hidden" class="itemline_po_id" name="itemline_ref_id[]" value="{{$item->itemline_ref_id}}">

                                                                <select class="1111 form-control select-item droplist-item input-sm pull-left" name="itemline_item_id[]" >
                                                                    @include("member.load_ajax_data.load_item_category", ['add_search' => "", 'item_id' => $item->itemline_item_id])
                                                                </select>
                                                            </td>
                                                            <td>
                                                            @if($pis)
                                                                <textarea class="textarea-expand txt-desc" readonly="true" name="itemline_description[]">{{$item->itemline_description}}</textarea>
                                                            @else
                                                                <textarea class="textarea-expand txt-desc" name="itemline_description[]">{{$item->itemline_description}}</textarea>
                                                            @endif
                                                            </td>
                                                            <td>
                                                                <select class="2222 droplist-um select-um" name="itemline_um[]"><option class="hidden" value="" />
                                                                    @if($item->itemline_um)
                                                                        @include("member.load_ajax_data.load_one_unit_measure", ['item_um_id' => $item->multi_um_id, 'selected_um_id' => $item->itemline_um])
                                                                    @else
                                                                        <option class="hidden" value="" />
                                                                    @endif
                                                                </select>
                                                            </td>
                                                            <td><input class="text-center number-input txt-qty compute" type="text" value="{{$item->itemline_qty}}" name="itemline_qty[]"/></td>
                                                            <td><input class="text-right number-input txt-rate compute" type="text" value="{{$item->itemline_rate}}" name="itemline_rate[]"/></td>
                                                            <td><input class="text-right number-input txt-amount" type="text" value="{{$item->itemline_amount}}" name="itemline_amount[]"/></td>
                                                            @if(isset($serial)) 
                                                             <td>
                                                                <textarea class="txt-serial-number" name="serial_number[]">{{$item->serial_number}}</textarea>
                                                            </td>
                                                            @endif
                                                            <td  tr_id="{{$item->itemline_ref_id}}" linked_in="{{$item->itemline_ref_name}}" class="text-center remove-tr cursor-pointer"><i class="fa fa-trash-o" aria-hidden="true"></i></td>
                                                        </tr>
                                                        @endforeach
                                                    @endif
                                                <tbody class="draggable tbody-item po-tbl">
                                                    @include("member.load_ajax_data.load_po_session_item")  
                                                    <tr class="tr-draggable">
                                                         <td class="text-center cursor-move move">
                                                            <i class="fa fa-th-large colo-mid-dark-gray"></i>         
                                                            <input type="text" class="hidden poline_id" name="poline_id[]">
                                                            <input type="text" class="hidden itemline_po_id" name="itemline_po_id[]">
                                                         </td>
                                                        <td class="invoice-number-td text-right">1</td>
                                                        <td>
                                                        <input type="hidden" class="poline_id" name="itemline_ref_name[]">
                                                        <input type="hidden" class="itemline_po_id" name="itemline_ref_id[]">
                                                            <select class="1111 form-control select-item droplist-item input-sm pull-left" name="itemline_item_id[]" >
                                                                @include("member.load_ajax_data.load_item_category", ['add_search' => ""])
                                                            </select>
                                                        </td>
                                                        <td> 
                                                        @if($pis)
                                                        <textarea class="textarea-expand txt-desc" name="itemline_description[]" readonly="true"></textarea>
                                                        @else
                                                        <textarea class="textarea-expand txt-desc" name="itemline_description[]"></textarea>
                                                        @endif
                                                        </td>
                                                        <td><select class="2222 droplist-um select-um" name="itemline_um[]"><option class="hidden" value="" /></select></td>
                                                        <td><input class="text-center number-input txt-qty compute" type="text" name="itemline_qty[]"/></td>
                                                        <td><input class="text-right number-input txt-rate compute" type="text" name="itemline_rate[]"/></td>
                                                        <td><input class="text-right number-input txt-amount" type="text" name="itemline_amount[]"/></td>
                                                        @include("member.load_ajax_data.load_td_serial_number")
                                                        <td class="text-center remove-tr cursor-pointer"><i class="fa fa-trash-o" aria-hidden="true"></i></td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                                <div class="row clearfix">
                                    <div class="col-sm-6">
                                        <label>Memo</label>
                                        <textarea class="form-control input-sm textarea-expand" name="bill_memo" ></textarea>
                                    </div>
                                    <div class="col-sm-6">                      
                                        <div class="row">
                                            <div class="col-md-7 text-right digima-table-label">
                                                Total
                                            </div>
                                            <div class="col-md-5 text-right digima-table-value total">
                                               <input type="hidden" name="bill_total_amount" class="total-amount-input" />
                                                    PHP&nbsp;<span class="total-amount">0.00</span>
                                            </div>
                                        </div> 
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
                <input type="text" class="hidden poline_id" name="poline_id[]">
                <input type="text" class="hidden itemline_po_id" name="itemline_po_id[]">
            </td>
            <td class="invoice-number-td text-right">1</td>
            <td>
                <input type="hidden" class="poline_id" name="itemline_ref_name[]">
                <input type="hidden" class="itemline_po_id" name="itemline_ref_id[]">
                <select class="1111 form-control select-item input-sm pull-left" name="itemline_item_id[]" >
                    @include("member.load_ajax_data.load_item_category", ['add_search' => ""])
                </select>
            </td>
            <td>
                @if($pis)
                <textarea class="textarea-expand txt-desc" name="itemline_description[]" readonly="true"></textarea>
                @else
                <textarea class="textarea-expand txt-desc" name="itemline_description[]"></textarea>
                @endif
            </td>
            <td><select class="2222 select-um" name="itemline_um[]"><option class="hidden" value="" /></select></td>
            <td><input class="text-center number-input txt-qty compute" type="text" name="itemline_qty[]"/></td>
            <td><input class="text-right number-input txt-rate compute" type="text" name="itemline_rate[]"/></td>
            <td><input class="text-right number-input txt-amount" type="text" name="itemline_amount[]"/></td>
            @include("member.load_ajax_data.load_td_serial_number")
            <td class="text-center remove-tr cursor-pointer"><i class="fa fa-trash-o" aria-hidden="true"></i></td>
        </tr>
    </table>
</div>
@endsection

@section('script')
<script type="text/javascript" src="/assets/member/js/textExpand.js"></script>
<script type="text/javascript" src="/assets/member/js/draggable_row.js"></script>
<script type="text/javascript" src="/assets/member/bootstrap_drawer/cooker.drawer.js"></script>
<script type="text/javascript" src="/assets/member/js/bill.js"></script>
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
$(document).ready(function() 
{
  $('.drawer').drawer({
    desktopEvent:'click'
  });
});
</script>
@endsection

@section("css")
<link rel="stylesheet" type="text/css" href="/assets/member/bootstrap_drawer/cooker.drawer.css">
<style type="text/css">
.po-style
{
    padding: 10px;
    background-color: #fff;
}
.drawer-toggle
{
    background-color: #76B6EC;
    color: #fff;
    border-top-left-radius: 2px;
    border-bottom-left-radius: 2px;
    border-bottom-right-radius: 0;
}
.drawer-toggle:hover
{
    background-color: #76B6EC;
    color: #fff;
}

.drawer-default
{
    -webkit-box-shadow: -1px 0px 10px 0px rgba(184,184,184,1);
    -moz-box-shadow: -1px 0px 10px 0px rgba(184,184,184,1);
    box-shadow: -1px 0px 10px 0px rgba(184,184,184,1);
    -webkit-transition: all 0.4s ease;
       -o-transition: all 0.4s ease;
          transition: all 0.4s ease;
    z-index: 2;
}
.drawer-toggle
{
    -webkit-transition: all 0.4s ease;
       -o-transition: all 0.4s ease;
          transition: all 0.4s ease;
}
.drawer-default + .drawer-overlay
{
    background-color: transparent !important;
    -webkit-transition: all 0.4s ease;
       -o-transition: all 0.4s ease;
          transition: all 0.4s ease;
}
.drawer-open .drawer-overlay
{
    padding-right: 30px;
}
.drawer-close .drawer.drawer-default
{
    right: -280px;
}
.drawer-open .drawer.drawer-default
{
    right: 0;
}
nav.user-menu
{
    background-color: #F5F5F5;
}
</style>
@endsection