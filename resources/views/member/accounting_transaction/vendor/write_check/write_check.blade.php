@extends('member.layout')
@section('content')

<form class="global-submit" role="form" action="{{ $action or ''}}" method="post" >
    <input type="hidden" class="token" name="_token" value="{{csrf_token()}}" >
    <input type="hidden" class="button-action" name="button_action" value="">
    <input type="hidden" name="wc_id" value="{{ $wc->wc_id or ''}}">
    <input type="hidden" name="item_new_cost" class="item-new-cost" value="{{ $item_new_cost }}">

    <input type="hidden" name="po_id" class="po-id" value="{{$po_id or ''}}">
    <input type="hidden" name="po_vendor_id" class="po-vendor-id" value="{{isset($po->po_vendor_id) ? $po->po_vendor_id :''}}">
    <input type="hidden" name="type_id" class="" value="{{$cmdata->cm_id or ''}}">
    <input type="hidden" class="transaction-status" disabled="false" value="">
    <input type="hidden" class="current-warehouse" disabled="false" value="{{$warehouse_id}}">
    <div class="panel panel-default panel-block panel-title-block" id="top">
        <div class="panel-heading">
            <div>
                <i class="fa fa-tags"></i>
                <h1>
                    <span class="page-title">{{ $page or ''}}</span>
                    <small>
                    <!--Add a product on your website-->
                    </small>
                </h1>
                <div class="dropdown pull-right">
                    <div class="hidden" style="width: 200px;padding: 10px;background-color: #DFF2BF;color:  #4F8A10; font-size: 20px; font-weight: bold; text-align: center;">POSTED</div>
                    <div>
                        <a class="btn btn-custom-white" href="/member/transaction/write_check">Cancel</a>
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
                @if(isset($wc))
                <div class="pull-right {{isset($accounting_module) ? ($accounting_module ? '' : 'hidden') : 'hidden'}}">
                    <div class="dropdown">
                        <button class="btn btn-custom-white dropdown-toggle" type="button" data-toggle="dropdown">More
                        <span class="caret"></span></button>
                        <ul class="dropdown-menu">
                            <li><a href="/member/accounting/journal/entry/write-check/{{$wc->wc_id}}">Transaction Journal</a></li>
                            <li><a href="#">Void</a></li>
                        </ul>
                    </div>
                </div>
                @endif 
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
                                <div style="padding-bottom: 10px; margin-bottom: 10px;">
                                    <div class="row clearfix">
                                        <div class="col-sm-4">
                                            <label>Reference Number</label>
                                            <input type="text" class="form-control" name="transaction_refnumber" value="{{ isset($wc->transaction_refnum) ? $wc->transaction_refnum : $transaction_refnum }}">
                                        </div>
                                    </div>
                                </div>
                                <div style="border-bottom: 1px solid #ddd; padding-bottom: 10px; margin-bottom: 10px;">
                                    <div class="row clearfix">
                                        <div class="col-sm-4">
                                            <select class="form-control drop-down-name input-sm pull-left" name="wc_ref_id">
                                                @include("member.load_ajax_data.load_name", ['name_id'=> isset($wc->wc_reference_id) ? $wc->wc_reference_id : (isset($po->po_vendor_id) ? $po->po_vendor_id :''), 'ref_name'=>isset($wc->wc_reference_name) ? $wc->wc_reference_name : (isset($po->po_vendor_id) ? 'vendor' : '' )])
                                                <option class="hidden" value="" />
                                            </select>
                                            <input type="hidden" name="wc_reference_name" class="wc-ref-name" value="{{isset($wc->wc_reference_name) ? $wc->wc_reference_name : (isset($cmdata->cm_refname) ? $cmdata->cm_refname : '')}}">
                                        </div>
                                        <div class="col-sm-4">
                                            <input type="text" class="form-control input-sm customer-email" name="wc_customer_vendor_email" placeholder="E-Mail (Separate E-Mails with comma)" value="{{$wc->wc_customer_vendor_email or ''}}"/>
                                        </div>
                                        <div class="col-sm-4 text-right open-transaction" style="display: none;">
                                            <h4><a class="popup popup-link-open-transaction" size="md" link="/member/transaction/write_check/load-transaction?vendor_id="><i class="fa fa-handshake-o"></i> <span class="count-open-transaction">0</span> Open Transaction</a></h4>
                                        </div>
                                    </div>
                                </div>
                                <div style="border-bottom: 1px solid #ddd; padding-bottom: 10px; margin-bottom: 10px;">
                                    <div class="row clearfix">
                                        <div class="col-sm-3">
                                            <label>Mailing Address</label>
                                            <textarea class="form-control input-sm textarea-expand customer-mailing-address" name="wc_mailing_address" placeholder="">{{isset($wc) ? $wc->wc_mailing_address : ''}}</textarea>
                                        </div>
                                        <div class="col-sm-2">
                                            <label>Payment Date</label>
                                            <input type="text" class="form-control input-sm datepicker" value="{{isset($wc) ? date('m/d/Y', strtotime($wc->wc_payment_date)) : date('m/d/Y')}}" name="wc_payment_date">
                                        </div>
                                        <div class="col-sm-3">
                                            <label>Bank Account</label>
                                            <select class="drop-down-coa" name="wc_cash_account_id" required>
                                                @include("member.load_ajax_data.load_chart_account", ['add_search' => "","_account" => $_account_id,"account_id" => isset($wc) ? $wc->wc_cash_account_id : ''])
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="title">    
                                    <h3><a id="acct-a"> <i class="fa fa-caret-down"></i>  Account Details </a></h3>
                                </div>
                                <div class="row clearfix">
                                    <div class="table-responsive" id="account-tbl">
                                        <div class="col-sm-12">
                                            <table class="digima-table">
                                                <thead >
                                                    <tr>
                                                        <th style="width: 15px;">#</th>
                                                        <th style="width: 200px;">Account</th>
                                                        <th>Description</th>
                                                        <th style="width: 150px;">Amount</th>
                                                        <th style="width: 15px;"></th>
                                                    </tr>
                                                </thead>
                                                <tbody class="draggable tbody-acct">
                                                    @if(isset($_wc_acct_line))
                                                        @foreach($_wc_acct_line as $accline)
                                                        <tr class="tr-draggable">
                                                            <td class="acct-number-td text-right">1</td>
                                                            <td >                                           
                                                                <select name="expense_account[]" class="form-control drop-down-coa select-coa input-sm" >
                                                                    @include("member.load_ajax_data.load_chart_account", ['add_search' => "", 'account_id' => $accline->accline_coa_id])
                                                                </select>
                                                            </td>
                                                            <td><textarea class="textarea-expand acct-desc" name="account_desc[]">{{$accline->accline_description}}</textarea></td>
                                                            <td><input type="text"  name="account_amount[]" class="form-control text-right number-input input-sm acct-amount compute" value="{{currency('',$accline->accline_amount)}}"></td>
                                                            <td class="text-center acct-remove-tr cursor-pointer"><i class="fa fa-trash-o" aria-hidden="true"></i></td>
                                                        </tr>
                                                        @endforeach
                                                    @endif
                                                    <tr class="tr-draggable">
                                                        <td class="acct-number-td text-right">1</td>
                                                        <td >                                           
                                                            <select name="expense_account[]" class="form-control drop-down-coa select-coa input-sm" >
                                                                @include("member.load_ajax_data.load_chart_account", ['add_search' => "", 'account_id' => isset($cmdata->cm_account_id) ? $cmdata->cm_account_id : ''])
                                                            </select>
                                                        </td>
                                                        <td><textarea class="textarea-expand acct-desc" name="account_desc[]">{{isset($cmdata->cm_description) ? $cmdata->cm_description : ''}}</textarea></td>
                                                        <td><input type="text" class="form-control text-right input-sm number-input acct-amount compute" value="{{isset($cmdata->cm_amount) ? number_format($cmdata->cm_amount,2) : '0.00'}}" name="account_amount[]"></td>
                                                        <td class="text-center acct-remove-tr  cursor-pointer"><i class="fa fa-trash-o" aria-hidden="true"></i></td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                                <div class="title">
                                    <h3><a id="item-a" > <i class="fa fa-caret-down"></i>  Item Details </a></h3>
                                </div> 
                                 <div class="row clearfix">
                                    <div class="table-responsive " id="item-tbl">
                                        <div class="col-sm-12">
                                            <table class="digima-table">
                                                <thead >
                                                    <tr>
                                                        <th style="width: 15px;">#</th>
                                                        <th style="width: 300px;">Product/Service</th>
                                                        <th>Description</th>
                                                        @if($check_settings == 1)
                                                            <th style="">BIN</th>
                                                        @endif
                                                        <th style="width: 70px;">U/M</th>
                                                        <th style="width: 70px;">Qty</th>
                                                        <th style="width: 120px;">Rate</th>
                                                        <th style="width: 100px;">Discount</th>
                                                        <th style="width: 120px;">Amount</th>
                                                        <th style="width: 30px;">
                                                            <label style="cursor: pointer" class="select-all-tax-check unselect-tax">
                                                            <i class="fa fa-icon fa-check tax-icon"></i> Tax
                                                            </label>
                                                        </th>
                                                        <th width="10"></th>
                                                    </tr>
                                                </thead>
                                                @include("member.accounting_transaction.loading_items")
                                                <tbody class="applied-transaction-list"></tbody>
                                                <tbody class="draggable tbody-item">
                                                    @if(isset($wc))
                                                        @foreach($_wcline as $key => $item)
                                                        <tr class="tr-draggable">
                                                            <td class="invoice-number-td text-right">1</td>
                                                            <input type="hidden" class="poline_id" name="item_ref_name[]" value="{{$item->wcline_ref_name}}">
                                                            <input type="hidden" class="itemline_po_id" name="item_ref_id[]" value="{{$item->wcline_ref_id}}">
                                                            @if($check_barcode == '1')
                                                            <td class="item-select-td">
                                                                <input class="form-control input-sm pull-left item-textbox hidden" value="{{$item->item_barcode}}" onkeypress="event_search($(this), event)" type="text"/>
                                                                <select class="1111 form-control select-item droplist-item input-sm pull-left item-select {{$item->wcline_item_id}}" name="item_id[]" required >
                                                                    @include("member.load_ajax_data.load_item_category", ['add_search' => "", 'item_id' => $item->wcline_item_id])
                                                                    <option class="hidden" value="" />
                                                                </select>
                                                            </td>
                                                            @else
                                                            <td>
                                                                <select class="1111 form-control select-item droplist-item input-sm pull-left " name="item_id[]" >
                                                                    @include("member.load_ajax_data.load_item_category", ['add_search' => "", 'item_id' => $item->wcline_item_id])
                                                                    <option class="hidden" value="" />
                                                                </select>
                                                                
                                                            </td>
                                                            @endif
                                                            <td><textarea class="textarea-expand txt-desc" name="item_description[]">{{$item->wcline_description}}</textarea></td>
                                                            
                                                            @if($check_settings == 1)
                                                            <td>
                                                                <select class="form-control droplist-sub-warehouse select-sub-warehouse input-sm" name="item_sub_warehouse[]" >
                                                                    @include('member.warehousev2.load_sub_warehouse_v2_select', ['_bin_warehouse' => $_bin_item_warehouse[$key]])
                                                                    <option class="hidden" value="" />
                                                                </select>
                                                            </td>
                                                            @endif
                                                            <td>
                                                                <select class="1111 droplist-um select-um {{isset($item->wcline_um) ? 'has-value' : ''}}" name="item_um[]">
                                                                    @if($item->wcline_um)
                                                                        @include("member.load_ajax_data.load_one_unit_measure", ['item_um_id' => $item->multi_um_id, 'selected_um_id' => $item->wcline_um])
                                                                    @else
                                                                        <option class="hidden" value="" />
                                                                    @endif
                                                            </select>
                                                            </td>
                                                            <td><input class="text-center number-input txt-qty compute" type="text" value="{{$item->wcline_qty}}" name="item_qty[]"/></td>
                                                            <td>
                                                                <input class="text-right number-input txt-rate new-cost compute" type="text" name="item_rate[]" value="{{ $item->wcline_rate }}" />
                                                            </td>
                                                            @if($item->wcline_discounttype == 'fixed')
                                                                <td><input class="text-right txt-discount compute" type="text" name="item_discount[]" value="{{$item->wcline_discount}}" /></td>
                                                            @else
                                                                <td><input class="text-right txt-discount compute" type="text" name="item_discount[]" value="{{$item->wcline_discount * 100}}%" /></td>
                                                            @endif
                                                            <td><input class="text-right number-input txt-amount" type="text" value="{{$item->wcline_amount}}" name="item_amount[]"/></td>
                                                            <td class="text-center">
                                                                <input type="hidden" name="item_taxable[]" class="taxable-input" value="{{$item->wcline_taxable}}" >
                                                                <input type="checkbox" class="taxable-check" {{$item->wcline_taxable == 1 ? 'checked' : ''}}>
                                                            </td>
                                                            <td class="text-center remove-tr cursor-pointer"><i class="fa fa-trash-o" aria-hidden="true"></i></td>
                                                        </tr>
                                                        @endforeach
                                                    @endif
                                                    <tr class="tr-draggable">
                                                        <td class="invoice-number-td text-right">1</td>
                                                        <input type="hidden" class="poline_id" name="item_ref_name[]">
                                                        <input type="hidden" class="itemline_po_id" name="item_ref_id[]">
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
                                                        <td><textarea class="textarea-expand txt-desc" name="item_description[]"></textarea></td>
                                                        @if($check_settings == 1)
                                                        <td>
                                                            <select class="form-control droplist-sub-warehouse select-sub-warehouse input-sm" name="item_sub_warehouse[]" >
                                                                @include('member.warehousev2.load_sub_warehouse_v2_select')
                                                                <option class="hidden" value="" />
                                                            </select>
                                                        </td>
                                                        @endif
                                                        <td><select class="2222 droplist-um select-um" name="item_um[]"><option class="hidden" value="" /></select></td>
                                                        <td><input class="text-center number-input txt-qty compute" type="text" name="item_qty[]"/></td>
                                                        <td>
                                                            <input class="text-right number-input txt-rate new-cost compute" type="text" name="item_rate[]"/>
                                                        </td>
                                                        <td><input class="text-right txt-discount compute" type="text" name="item_discount[]" /></td>
                                                        <td><input class="text-right number-input txt-amount" type="text" name="item_amount[]"/></td>
                                                        <td class="text-center">
                                                            <input type="hidden" name="item_taxable[]" class="taxable-input" value="" >
                                                            <input type="checkbox" class="taxable-check compute" value="1">
                                                        </td>
                                                        <td class="text-center remove-tr cursor-pointer"><i class="fa fa-trash-o" aria-hidden="true"></i></td>
                                                    </tr>
                                                    <tr class="tr-draggable">
                                                        <td class="invoice-number-td text-right">2</td>
                                                        <input type="hidden" class="poline_id" name="item_ref_name[]">
                                                        <input type="hidden" class="itemline_po_id" name="item_ref_id[]">
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
                                                        <td><textarea class="textarea-expand txt-desc" name="item_description[]"></textarea></td>
                                                        @if($check_settings == 1)
                                                        <td>
                                                            <select class="form-control droplist-sub-warehouse select-sub-warehouse input-sm" name="item_sub_warehouse[]" >
                                                                @include('member.warehousev2.load_sub_warehouse_v2_select')
                                                                <option class="hidden" value="" />
                                                            </select>
                                                        </td>
                                                        @endif
                                                        <td><select class="2222 droplist-um select-um" name="item_um[]"><option class="hidden" value="" /></select></td>
                                                        <td><input class="text-center number-input txt-qty compute" type="text" name="item_qty[]"/></td>
                                                        <td>
                                                            <input class="text-right number-input txt-rate new-cost compute" type="text" name="item_rate[]"/>
                                                        </td>
                                                        <td><input class="text-right txt-discount compute" type="text" name="item_discount[]" /></td>
                                                        <td><input class="text-right number-input txt-amount" type="text" name="item_amount[]"/></td>
                                                        <td class="text-center">
                                                            <input type="hidden" name="item_taxable[]" class="taxable-input" value="" >
                                                            <input type="checkbox" class="taxable-check compute" value="1">
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
                                        <label>Remarks</label>
                                        <textarea class="form-control input-sm textarea-expand remarks-wc" name="wc_remarks">{{ isset($wc->wc_remarks) ? $wc->wc_remarks : '' }}</textarea>
                                    </div>
                                    <div class="col-sm-3">
                                        <label>Memo</label>
                                        <textarea class="form-control input-sm textarea-expand" name="wc_memo" >{{ isset($wc->wc_memo) ? $wc->wc_memo : '' }}</textarea>
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
                                                        <select class="form-control input-sm compute discount_selection disc-type-wc" name="vendor_discounttype">  

                                                            <option value="percent" {{isset($wc) ? $wc->wc_discount_type == 'percent' ? 'selected' : '' : ''}}>Discount percentage</option>
                                                            <option value="value" {{isset($wc) ? $wc->wc_discount_type == 'value' ? 'selected' : '' : ''}}>Discount value</option>
                                                        </select>
                                                    </div>
                                                    <div class="col-sm-2  padding-lr-1">
                                                        <input class="form-control input-sm text-right number-input discount_txt compute disc-percentage-wc" type="text" name="vendor_discount" value="{{$wc->wc_discount_value or ''}}">
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
                                                        <select class="form-control input-sm tax_selection compute tax-wc" name="vendor_tax">  
                                                            <option value="0" {{isset($wc) ? $wc->taxable == 0 ? 'selected' : '' : ''}}>No Tax</option>
                                                            <option value="1" {{isset($wc) ? $wc->taxable == 1 ? 'selected' : '' : ''}}>Input Vat (12%)</option>
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
                                                <input type="hidden" name="wc_total_amount" class="total-amount-input" />
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
<div class="acct-div-script">
    <table class="div-acct-row-script hide">
        <tr class="tr-draggable">
            <td class="acct-number-td text-right">1</td>
            <td >                                           
                <select name="expense_account[]" class="form-control select-coa input-sm" >
                    @include("member.load_ajax_data.load_chart_account", ['add_search' => ""])
                </select>
            </td>
            <td><textarea class="textarea-expand acct-desc" name="account_desc[]"></textarea></td>
            <td><input type="text" class="form-control text-right number-input input-sm acct-amount compute" value="0.00" name="account_amount[]"></td>
            <td class="text-center acct-remove-tr cursor-pointer"><i class="fa fa-trash-o" aria-hidden="true"></i></td>
        </tr>
    </table>
</div>
<div class="div-script">
    <table class="div-item-row-script hide">
        <tr class="tr-draggable">
            <td class="invoice-number-td text-right">1</td>
            <input type="hidden" class="poline_id" name="item_ref_name[]">
            <input type="hidden" class="itemline_po_id" name="item_ref_id[]">
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
            <td><textarea class="textarea-expand txt-desc" name="item_description[]"></textarea></td>
            @if($check_settings == 1)
            <td>
                <select class="form-control select-sub-warehouse input-sm" name="item_sub_warehouse[]" >
                    @include('member.warehousev2.load_sub_warehouse_v2_select')
                    <option class="hidden" value="" />
                </select>
            </td>
            @endif
            <td><select class="2222 select-um" name="item_um[]"><option class="hidden" value="" /></select></td>
            <td><input class="text-center number-input txt-qty compute" type="text" name="item_qty[]"/></td>
            <td>
                <input class="text-right number-input txt-rate new-cost compute" type="text" name="item_rate[]"/>
            </td>
            <td><input class="text-right txt-discount compute" type="text" name="item_discount[]" /></td>
            <td><input class="text-right number-input txt-amount" type="text" name="item_amount[]"/></td>
            <td class="text-center">
                <input type="hidden" name="item_taxable[]" class="taxable-input" value="" >
                <input type="checkbox" class="taxable-check compute" value="1">
            </td>
            <td class="text-center remove-tr cursor-pointer"><i class="fa fa-trash-o" aria-hidden="true"></i></td>
        </tr>
    </table>
</div>
@endsection

@section('script')
<script type="text/javascript" src="/assets/member/js/accounting_transaction/vendor/write_check.js"></script>
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
