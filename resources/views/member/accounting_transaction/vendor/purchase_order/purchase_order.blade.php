@extends('member.layout')
@section('content')
<form class="global-submit" role="form" action="{{$action or ''}}" method="POST" >
    <input type="hidden" name="_token" value="{{csrf_token()}}" >
    <input type="hidden" class="button-action" name="button_action" value="">
    <input type="hidden" name="po_id" value="{{$po->po_id or ''}}" >
    <div class="panel panel-default panel-block panel-title-block" id="top">
        <div class="panel-heading">
            <div>
                <i class="fa fa-tags"></i>
                <h1>
                    <span class="page-title">{{$page or ''}}</span>
                    <small>
                    Insert Description Here
                    </small>
                </h1>
	            <div class="dropdown pull-right">
                    <div>
                        <a class="btn btn-custom-white" href="/member/transaction/purchase_order">Cancel</a>
                        <button class="btn btn-primary dropdown-toggle" type="button" data-toggle="dropdown">Select Action
                        <span class="caret"></span></button>
                        <ul class="dropdown-menu  dropdown-menu-custom">
                            <li><a class="select-action" code="sclose">Save & Close</a></li>
                            <li><a class="select-action" code="sedit">Save & Edit</a></li>
                            <li><a class="select-action" code="sprint">Save & Print</a></li>
                            <li><a class="select-action" code="snew">Save & New</a></li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="panel panel-default panel-block panel-title-block panel-gray">
       <!--  <ul class="nav nav-tabs">
            <li class="active cursor-pointer"><a class="cursor-pointer" data-toggle="tab" href="#pending-codes"><i class="fa fa-star"></i> Invoice Information</a></li>
            <li class="cursor-pointer"><a class="cursor-pointer" data-toggle="tab" href="#used-codes"><i class="fa fa-list"></i> Activities</a></li>
        </ul> -->
        <div class="tab-content">
            <div class="row">
                <div class="col-md-12" style="padding: 30px;">
                    <!-- START CONTENT -->
                    <div style="padding-bottom: 10px; margin-bottom: 10px;">
                        <div class="row clearfix">
                            <div class="col-sm-4">
                                <label>Reference Number</label>
                                <input type="text" class="form-control" name="transaction_refnumber" value="{{ isset($po->transaction_refnum) ? $po->transaction_refnum : $transaction_refnum}}">
                            </div>
                        </div>
                    </div>
                    <div style="border-bottom: 1px solid #ddd; padding-bottom: 10px; margin-bottom: 10px;">
                        <div class="row clearfix">
                            <div class="col-sm-4">
                                <select class="form-control droplist-vendor input-sm pull-left" name="vendor_id" required>
                                    @include('member.load_ajax_data.load_vendor', ['vendor_id' => isset($po->po_vendor_id) ? $po->po_vendor_id : (isset($vendor_id) ? $vendor_id : '')])
                                </select>
                            </div>
                            <div class="col-sm-4">
                                <input type="text" class="form-control input-sm vendor-email" name="vendor_email" placeholder="E-Mail (Separate E-Mails with comma)" value="{{$po->po_vendor_email or ''}}"/>
                            </div>
                            <div class="col-sm-4 text-right">
                                <h4><a class="popup popup-link-open-transaction" size="md" link="/member/transaction/purchase_order/load-transaction"><i class="fa fa-handshake-o"></i> <span class="count-open-transaction">{{$count_transaction}}</span> Open Transaction</a></h4>
                            </div>
                        </div>
                    </div>
                    <div class="row clearfix">
                        <div class="col-sm-3">
                            <label>Billing Address</label>
                            <textarea class="form-control input-sm textarea-expand" name="vendor_address" placeholder="">{{$po->po_billing_address or ''}}</textarea>
                        </div>
                        <div class="col-sm-2">  
                            <label>Terms</label>
                                <select class="form-control input-sm droplist-terms" name="vendor_terms">
                                    @include("member.load_ajax_data.load_terms", ['terms_id' => isset($po->po_terms_id) ? $po->po_terms_id : (isset($terms_id) ? $terms_id : '')])
                                </select>
                        </div>
                        <div class="col-sm-2">
                            <label>P.O Date</label>
                            <input type="text" class="datepicker form-control input-sm" name="transaction_date" value="{{ isset($po)? date('m/d/Y',strtotime($po->po_date)) : date('m/d/Y') }}"/>
                        </div>
                        <div class="col-sm-2">
                            <label>Due Date</label>
                            <input type="text" class="datepicker form-control input-sm" name="transaction_duedate" value="{{ isset($po)? date('m/d/Y',strtotime($po->po_due_date)) : date('m/d/Y') }}" />
                        </div>
                        <div class="col-sm-2">
                            <label>Delivery Date</label>
                            <input type="text" class="datepicker form-control input-sm" name="delivery_date" value="{{ isset($po)? date('m/d/Y',strtotime($po->po_delivery_date)) : date('m/d/Y') }}"/>
                        </div>
                    </div>
                    
                    <div class="row clearfix draggable-container">
                        <div class="table-responsive">
                            <div class="col-sm-12">
                                <table class="digima-table">
                                    <thead >
                                        <tr>
                                            <th style="width: 15px;" class="text-right">#</th>
                                            <th style="width: 100px;">Service Date</th>
                                            <th style="width: 300px;">Product/Service</th>
                                            <th>Description</th>  
                                            @if($check_settings == 1)
                                                <th style="">BIN</th>
                                            @endif
                                            <th style="width: 120px;">U/M</th>
                                            <th style="width: 70px;">Qty</th>
                                            @if(isset($po))
                                            <th style="width: 70px;">Received</th>
                                            <th style="width: 70px;">Backorder</th>
                                            <th style="width: 10px;">C</th>
                                            @endif
                                            <th style="width: 100px;">Rate</th>
                                            <th style="width: 100px;">Discount</th>
                                            <th style="width: 100px;">Remark</th>
                                            <th style="width: 100px;">Amount</th>
                                            <th style="width: 30px;">
                                                <label style="cursor: pointer" class="select-all-tax-check unselect-tax">
                                                    <i class="fa fa-icon fa-check tax-icon"></i> Tax</label>
                                            </th>
                                            <th width="10"></th>
                                        </tr>
                                    </thead>
                                    @include("member.accounting_transaction.loading_items")
                                    <tbody class="applied-transaction-list"></tbody>
                                    <tbody class="draggable tbody-item">   
                                    @include("member.accounting_transaction.vendor.purchase_order.po_reorder_point")                             
                                        @if(isset($po))
                                            @foreach($_poline as $key => $poline)
                                                <tr class="tr-draggable">
                                                    <input type="hidden" name="item_ref_name[]" value="{{$poline->poline_refname}}"></td>
                                                    <input type="hidden" name="item_ref_id[]" value="{{$poline->poline_refsname}}"></td>
                                                    <td class="invoice-number-td text-right">1</td>
                                                    <td><input type="text" class="for-datepicker" name="item_servicedate[]" value="{{ $poline->poline_service_date != '0000-00-00 00:00:00'? date('m/d/Y',strtotime($poline->poline_service_date)) : ''}}" /></td>
                                                    @if($check_barcode == '1')
                                                    <td class="item-select-td">
                                                        <input class="form-control input-sm pull-left item-textbox hidden" value="{{$poline->item_barcode}}" onkeypress="event_search($(this), event)" type="text"/>
                                                        <select class="1111 form-control select-item droplist-item input-sm pull-left item-select {{$poline->poline_item_id}}" name="item_id[]" required >
                                                            @include("member.load_ajax_data.load_item_category", ['add_search' => "", 'item_id' => $poline->poline_item_id])
                                                            <option class="hidden" value="" />
                                                        </select>
                                                    </td>
                                                    @else
                                                    <td>
                                                        <select class="1111 form-control select-item droplist-item input-sm pull-left " name="item_id[]" >
                                                            @include("member.load_ajax_data.load_item_category", ['add_search' => "", 'item_id' => $poline->poline_item_id])
                                                            <option class="hidden" value="" />
                                                        </select>
                                                        
                                                    </td>
                                                    @endif
                                                    <td>
                                                        <textarea class="textarea-expand txt-desc" name="item_description[]">{{$poline->poline_description}}</textarea>
                                                    </td>
                                                    @if($check_settings == 1)
                                                    <td>
                                                        <select class="form-control droplist-sub-warehouse select-sub-warehouse input-sm" name="item_sub_warehouse[]" >
                                                            @include('member.warehousev2.load_sub_warehouse_v2_select', ['_bin_warehouse' => $_bin_item_warehouse[$key]])
                                                            <option class="hidden" value="" />
                                                        </select>
                                                    </td>
                                                    @endif
                                                    <td>
                                                        <select class="1111 droplist-um select-um {{isset($poline->poline_um) ? 'has-value' : ''}}" name="item_um[]">
                                                            @if($poline->poline_um)
                                                                @include("member.load_ajax_data.load_one_unit_measure", ['item_um_id' => $poline->multi_um_id, 'selected_um_id' => $poline->poline_um])
                                                            @else
                                                                <option class="hidden" value="" />
                                                            @endif
                                                        </select>
                                                    </td>
                                                    <td><input class="text-center number-input txt-qty compute" type="text" name="item_qty[]" value="{{$poline->poline_orig_qty}}"/></td>
                                                    <td><input class="text-center number-input txt-received compute" type="text" value="{{$poline->poline_received_qty}}" readonly="readonly"/></td>
                                                    <td><input class="text-center number-input txt-remaining compute" type="text" name="item_remaining[]" value="{{$poline->poline_qty}}" readonly="readonly"/></td>
                                                    <td class="text-center">
                                                        <input type="hidden" name="item_status[]" class="item-status" value="{{$poline->poline_item_status}}">
                                                        <input type="hidden" name="" class="item-name" value="{{$poline->item_name}}">
                                                        <input type="checkbox" class="item-status-check" value="{{$poline->poline_item_id}}" {{$poline->poline_item_status == 1 ? 'checked' : ''}}>
                                                    </td>
                                                    <td><input class="text-right number-input txt-rate compute" type="text" name="item_rate[]" value="{{$poline->poline_rate}}" /></td>
                                                    @if($poline->poline_discounttype == 'fixed')
                                                        <td><input class="text-right txt-discount compute" type="text" name="item_discount[]" value="{{$poline->poline_discount}}" /></td>
                                                    @else
                                                        <td><input class="text-right txt-discount compute" type="text" name="item_discount[]" value="{{$poline->poline_discount * 100}}%" /></td>
                                                    @endif
                                                    <td><textarea class="textarea-expand" type="text" name="item_remark[]">{{$poline->poline_discount_remark}}</textarea></td>
                                                    <td><input class="text-right number-input txt-amount" type="text" name="item_amount[]" value="{{$poline->poline_amount}}" /></td>
                                                    <td class="text-center">
                                                        <input type="hidden" name="item_taxable[]" class="taxable-input" value="{{$poline->taxable}}">
                                                        <input type="checkbox" class="taxable-check" {{$poline->taxable == 1 ? 'checked' : ''}}>
                                                    </td>
                                                    <td class="text-center remove-tr cursor-pointer"><i class="fa fa-trash-o" aria-hidden="true"></i></td>
                                                </tr>
                                            @endforeach
                                        @endif
                                        <tr class="tr-draggable">
                                            <input type="hidden" name="item_ref_name[]"></td>
                                            <input type="hidden" name="item_ref_id[]"></td>
                                            <td class="invoice-number-td text-right">1</td>
                                            <td><input type="text" class="for-datepicker" name="item_servicedate[]"/></td>

                                            @if($check_barcode == '1')
                                            <td class="item-select-td">
                                                <input class="form-control input-sm pull-left item-textbox hidden" onkeypress="event_search($(this), event)" type="text"/>
                                                <select class="1111 form-control select-item droplist-item input-sm pull-left item-select" name="item_id[]" >
                                                    @include("member.load_ajax_data.load_item_category", ['add_search' => ""])
                                                    <option class="hidden" value="" />
                                                </select>
                                            </td>
                                            @else
                                            <td>
                                                <select class="1111 form-control select-item droplist-item input-sm pull-left " name="item_id[]" >
                                                    @include("member.load_ajax_data.load_item_category", ['add_search' => ""])
                                                    <option class="hidden" value="" />
                                                </select>
                                                
                                            </td>
                                            @endif

                                            <td>
                                                <textarea class="textarea-expand txt-desc" name="item_description[]"></textarea>
                                            </td>
                                            @if($check_settings == 1)
                                            <td>
                                                <select class="form-control droplist-sub-warehouse select-sub-warehouse input-sm" name="item_sub_warehouse[]" >
                                                    @include('member.warehousev2.load_sub_warehouse_v2_select')
                                                    <option class="hidden" value="" />
                                                </select>
                                            </td>
                                            @endif

                                            <td><select class="2222 droplist-um select-um" name="item_um[]"><option class="hidden" value="" /></select></td>
                                            <td><input class="text-center number-input txt-qty compute" type="text" name="item_qty[]" /></td>
                                            @if(isset($po))
                                            <td><input class="text-center number-input txt-received" type="text" value="" readonly="readonly"/></td>
                                            <td><input class="text-center number-input txt-remaining" type="text" value="" name="item_remaining[]" readonly="readonly"/></td>
                                            <td class="text-center">
                                                <input type="hidden" name="item_status[]" class="item-status" value="0">
                                            </td>
                                            @endif
                                            <td><input class="text-right number-input txt-rate compute" type="text" name="item_rate[]" value=""/></td>
                                            <td><input class="text-right txt-discount compute" type="text" name="item_discount[]"/></td>
                                            <td><textarea class="textarea-expand" type="text" name="item_remark[]" ></textarea></td>
                                            <td><input class="text-right number-input txt-amount" type="text" name="item_amount[]"/></td>
                                            <td class="text-center">
                                                <input type="hidden" name="item_taxable[]" class="taxable-input" value="">
                                                <input type="checkbox" class="taxable-check compute" data-value="" value="1">
                                            </td>
                                            <td class="text-center remove-tr cursor-pointer"><i class="fa fa-trash-o" aria-hidden="true"></i></td>
                                        </tr>
                                        <tr class="tr-draggable">
                                            <input type="hidden" name="item_ref_name[]"></td>
                                            <input type="hidden" name="item_ref_id[]"></td>
                                            <td class="invoice-number-td text-right">2</td>
                                            <td><input type="text" class="for-datepicker" name="item_servicedate[]" /></td>
                                            @if($check_barcode == '1')
                                            <td class="item-select-td">
                                                <input class="form-control input-sm pull-left item-textbox hidden" onkeypress="event_search($(this), event)" type="text"/>
                                                <select class="1111 form-control select-item droplist-item input-sm pull-left item-select" name="item_id[]" >
                                                    @include("member.load_ajax_data.load_item_category", ['add_search' => ""])
                                                    <option class="hidden" value="" />
                                                </select>
                                            </td>
                                            @else
                                            <td>
                                                <select class="1111 form-control select-item droplist-item input-sm pull-left " name="item_id[]" >
                                                    @include("member.load_ajax_data.load_item_category", ['add_search' => ""])
                                                    <option class="hidden" value="" />
                                                </select>
                                                
                                            </td>
                                            @endif
                                            <td>
                                                <textarea class="textarea-expand txt-desc" name="item_description[]"></textarea>
                                            </td>
                                            @if($check_settings == 1)
                                            <td>
                                                <select class="form-control droplist-sub-warehouse select-sub-warehouse input-sm" name="item_sub_warehouse[]" >
                                                    @include('member.warehousev2.load_sub_warehouse_v2_select')
                                                    <option class="hidden" value="" />
                                                </select>
                                            </td>
                                            @endif
                                            <td><select class="3333 droplist-um select-um" name="item_um[]"><option class="hidden" value="" /></select></td>
                                            <td><input class="text-center number-input txt-qty compute" type="text" name="item_qty[]"/></td>
                                            @if(isset($po))
                                            <input class="auto-backorder-remaining" type="hidden" value="1"/>
                                            <td><input class="text-center number-input txt-received" type="text" value="" readonly="readonly"/></td>
                                            <td><input class="text-center number-input txt-remaining" type="text" value="" name="item_remaining[]" readonly="readonly"/></td>
                                            <td class="text-center">
                                                <input type="hidden" name="item_status[]" class="item-status" value="0">
                                            </td>
                                            @endif
                                            <td><input class="text-right number-input txt-rate compute" type="text" name="item_rate[]" value="" /></td>
                                            <td><input class="text-right txt-discount compute" type="text" name="item_discount[]"/></td>
                                            <td><textarea class="text-right number-input" type="text" name="item_remark[]"></textarea></td>
                                            <td><input class="text-right number-input txt-amount" type="text" name="item_amount[]"/></td>
                                            <td class="text-center">
                                                <input type="hidden" name="item_taxable[]" class="taxable-input" value="">
                                                <input type="checkbox" class="taxable-check compute" data-value="" value="1">
                                            </td>
                                            <td class="text-center remove-tr cursor-pointer"><i class="fa fa-trash-o" aria-hidden="true"></i></td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    
                    <div class="row clearfix">
                        <div class="col-sm-3">
                            <label>Message Displayed on P.O</label>
                            <textarea class="form-control input-sm textarea-expand remarks-po" name="vendor_message" placeholder="">{{ isset($po->po_message) ? $po->po_message : ''}}</textarea>
                        </div>
                        <div class="col-sm-3">
                            <label>Statement Memo</label>
                            <textarea class="form-control input-sm textarea-expand" name="vendor_memo" placeholder="">{{ isset($po->po_memo) ? $po->po_memo : ''}}</textarea>
                        </div>
                        <div class="col-sm-6">
                            <div class="row">
                                <div class="col-md-7 text-right digima-table-label">
                                    Sub Total
                                </div>
                                <div class="col-md-5 text-right digima-table-value">
                                    <input type="hidden" name="subtotal_price" class="subtotal-amount-input" />
                                    PHP&nbsp;<span class="sub-total">0.00</span>
                                </div>
                            </div> 
                            <div class="row">
                                <div class="col-md-7 text-right digima-table-label">
                                    <div class="row">
                                        <div class="col-sm-6 col-sm-offset-4  padding-lr-1">
                                            <select class="form-control input-sm compute discount_selection" name="vendor_discounttype">  
                                                <option value="percent" {{isset($po) ? $po->po_discount_type == 'percent' ? 'selected' : '' : ''}}>Discount percentage</option>
                                                <option value="value" {{isset($po) ? $po->po_discount_type == 'value' ? 'selected' : '' : ''}}>Discount value</option>
                                            </select>
                                        </div>
                                        <div class="col-sm-2  padding-lr-1">
                                            <input class="form-control input-sm text-right discount_txt compute discount-po" type="text" name="vendor_discount" value="{{$po->po_discount_value or ''}}">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-5 text-right digima-table-value">
                                    PHP&nbsp;<span class="discount-total">0.00</span>
                                </div>
                            </div> 
                            <div class="row">
                                <div class="col-md-7 text-right digima-table-label">
                                    <div class="row">
                                        <div class="col-sm-4 col-sm-offset-8  padding-lr-1">
                                            <select class="form-control input-sm tax_selection compute tax-po" name="vendor_tax">  
                                                <option value="0" {{isset($po) ? $po->taxable == 0 ? 'selected' : '' : ''}}>No Tax</option>
                                                <option value="1" {{isset($po) ? $po->taxable == 1 ? 'selected' : '' : ''}}>Input Vat (12%)</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-5 text-right digima-table-value">
                                    PHP&nbsp;<span class="tax-total">0.00</span>
                                </div>
                            </div> 
                            <div class="row">
                                <div class="col-md-7 text-right digima-table-label">
                                    Total
                                </div>
                                <div class="col-md-5 text-right digima-table-value total">
                                    <input type="hidden" name="overall_price" class="total-amount-input" />
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
</form>
<div class="div-script">
    <table class="div-item-row-script hide">
        <tr class="tr-draggable">
            <input type="hidden" name="item_ref_name[]"></td>
            <input type="hidden" name="item_ref_id[]"></td>
            <td class="invoice-number-td text-right">2</td>
            <td><input type="text" class="for-datepicker"  name="item_servicedate[]"/></td>
            @if($check_barcode == '1')
            <td class="item-select-td">
                <input class="form-control input-sm pull-left item-textbox hidden" onkeypress="event_search($(this), event)" type="text"/>
                <select class="1111 form-control select-item input-sm pull-left item-select" name="item_id[]" >
                    @include("member.load_ajax_data.load_item_category", ['add_search' => ""])
                    <option class="hidden" value="" />
                </select>
            </td>
            @else
            <td>
                <select class="1111 form-control select-item input-sm pull-left " name="item_id[]" >
                    @include("member.load_ajax_data.load_item_category", ['add_search' => ""])
                    <option class="hidden" value="" />
                </select>
                
            </td>
            @endif
            <td>
                <textarea class="textarea-expand txt-desc" name="item_description[]"></textarea>
            </td>
            @if($check_settings == 1)
            <td>
                <select class="form-control select-sub-warehouse input-sm" name="item_sub_warehouse[]" >
                    @include('member.warehousev2.load_sub_warehouse_v2_select')
                    <option class="hidden" value="" />
                </select>
            </td>
            @endif
            <td><select class="select-um" name="item_um[]"><option class="hidden" value="" /></select></td>
            <td><input class="text-center number-input txt-qty compute" type="text" name="item_qty[]"/></td>
            @if(isset($po))
            <td><input class="text-center number-input txt-received " type="text" value="" readonly="readonly"/></td>
            <td><input class="text-center number-input txt-remaining " type="text" value="" name="item_remaining[]" readonly="readonly"/></td>
            <td class="text-center">
                <input type="hidden" name="item_status[]" class="item-status" value="0">
            </td>
            @endif
            <td><input class="text-right number-input txt-rate compute" type="text" name="item_rate[]"/></td>
            <td><input class="text-right txt-discount compute" type="text" name="item_discount[]"/></td>
            <td><textarea class="textarea-expand" type="text" name="item_remark[]" ></textarea></td>
            <td><input class="text-right number-input txt-amount" type="text" name="item_amount[]"/></td>
            <td class="text-center">
                <input type="hidden" name="item_taxable[]" class="taxable-input" value="">
                <input type="checkbox" class="taxable-check compute" data-value="" value="1">
            </td>
            <td class="text-center remove-tr cursor-pointer"><i class="fa fa-trash-o" aria-hidden="true"></i></td>
        </tr>
    </table>
</div>

@endsection

@section('script')
<script type="text/javascript" src="/assets/member/js/accounting_transaction/vendor/purchase_order.js"></script>
<script type="text/javascript">
/*$('.txt-qty').keypress(function(event){
    var keycode = (event.keyCode ? event.keyCode : event.which);
    if(keycode == '13'){
        alert('You pressed a "enter" key in textbox');  
    }
});*/
</script>
@endsection