@extends('member.layout')
@section('content')
<form class="global-submit" action="{{$action or ''}}" method="post">
    <div class="panel panel-default panel-block panel-title-block">
        <input type="hidden" class="button-action" name="button_action" value="">
        <input type="hidden" class="range-discount" name="" value="{{$range_sales_discount or ''}}">
        <input type="hidden" name="sales_receipt_id" class="sr-id" value="{{Request::input('id')}}">
        <input type="hidden" name="_token" id="_token" value="{{csrf_token()}}"/>
        <input type="hidden" name="c_id" class="c-id" value="{{$c_id or ''}}"/>
        <input type="hidden" class="transaction-status" disabled="false" value="">
        <div class="panel-heading">
            <div>
                <i class="fa fa-calendar"></i>
                <h1>
                <span class="page-title">{{$page or ''}}</span>
                <small>
                Insert Description Here
                </small>
                </h1>
                <div class="dropdown pull-right">    
                    <div class="hidden" style="width: 200px;padding: 10px;background-color: #DFF2BF;color:  #4F8A10; font-size: 20px; font-weight: bold; text-align: center;">POSTED</div>
                    <div>
                        @if(isset($sales_receipt))
                        <a class="btn btn-custom-white {{isset($accounting_module) ? ($accounting_module ? '' : 'hidden') : 'hidden'}}" href="/member/accounting/journal/entry/sales-receipt/{{$sales_receipt->inv_id}}">Transaction Journal</a>
                        @endif
                        <a class="btn btn-custom-white" href="/member/transaction/credit_memo">Cancel</a>
                        <button class="btn btn-primary dropdown-toggle" type="button" data-toggle="dropdown">Select Action
                        <span class="caret"></span></button>
                        <ul class="dropdown-menu  dropdown-menu-custom">
                          <li><a class="select-action" code="sclose">Save & Close</a></li>
                          <li><a class="select-action" code="sedit">Save & Edit</a></li>
                          <li><a class="select-action" code="sprint">Save & Print</a></li>
                          <li><a class="select-action" code="snew">Save & New</a></li>
                          <li><a class="select-action" code="swis">Save & Create WIS</a></li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="panel panel-default panel-block panel-title-block panel-gray "  style="margin-bottom: -10px;">
        <div class="data-container" >
            <div class="tab-content">
                <div class="row">      
                    <div class="col-md-12" style="padding: 30px;">
                        <!-- START CONTENT -->
                        <div style="padding-bottom: 10px; margin-bottom: 10px;">
                            <div class="row clearfix">                        
                                <div class="col-sm-4">    
                                    <label >Reference Number</label>
                                    <input type="text" class="form-control input-sm" name="transaction_refnumber" value="{{isset($sales_receipt) ? $sales_receipt->transaction_refnum : $transaction_refnum}}">
                                </div>
                            </div>
                        </div>
                        <div style="border-bottom: 1px solid #ddd; padding-bottom: 10px; margin-bottom: 10px;">
                            <div class="row clearfix">
                                <div class="col-sm-4">
                                    <select class="form-control droplist-customer input-sm pull-left" name="customer_id" data-placeholder="Select a Customer" required>
                                        @include('member.load_ajax_data.load_customer', ['customer_id' => isset($sales_receipt) ? $sales_receipt->inv_customer_id : (isset($c_id) ? $c_id : '') ])
                                    </select>
                                </div>
                                <div class="col-sm-4">
                                    <input type="text" class="form-control input-sm customer-email" name="customer_email" placeholder="E-Mail (Separate E-Mails with comma)" value="{{$sales_receipt->inv_customer_email or ''}}"/>
                                </div>
                                <div class="col-sm-4 text-right open-transaction" style="display: none;">
                                    <h4><a class="popup popup-link-open-transaction" size="md" link="/member/transaction/sales_invoice/load_transaction?customer_id="><i class="fa fa-handshake-o"></i> <span class="count-open-transaction">0</span> Open Transaction</a></h4>
                                </div>
                            </div>
                        </div>                          
                        <div class="row clearfix">
                            <div class="col-sm-3">
                                <label>Billing Address</label>
                                <textarea class="form-control input-sm textarea-expand customer-billing-address" name="customer_address" placeholder="">{{$sales_receipt->inv_customer_billing_address or ''}}</textarea>
                            </div>
                            <div class="col-sm-2">
                                <label>Date</label>
                                <input type="text" class="datepicker form-control input-sm" name="transaction_date" value="{{isset($sales_receipt) ? dateFormat($sales_receipt->inv_date) : date('m/d/y')}}"/>
                            </div>
                            @if($sales_rep_enabled)
                            <div class="col-sm-3">
                                <label>Sales Representative</label>
                                <select class="form-control input-sm new droplist-sales-rep" name="sales_rep_id">
                                    <option> No Sales Representative</option>
                                    @foreach($_sales_rep as $salesrep)
                                    <option value="{{$salesrep->sales_rep_id}}" {{isset($sales_receipt->inv_sales_rep_id) ? ($sales_receipt->inv_sales_rep_id == $salesrep->sales_rep_id ? 'selected' : '') : ''}}>{{$salesrep->sales_rep_first_name . ' ' .$salesrep->sales_rep_middle_name.' '.$salesrep->sales_rep_last_name}}</option>
                                    @endforeach
                                </select>
                            </div>
                            @endif
                        </div>
                        
                        @include("member.accounting_transaction.customer.sales_receipt.sales_receipt_pm")
                        <div class="row clearfix draggable-container">
                            <div class="table-responsive">
                                <div class="col-sm-12">
                                    <table class="digima-table">
                                        <thead>
                                            <tr>
                                                <th style="" class="text-right">#</th>
                                                <th style="width: 100px">Service Date</th>
                                                <th style="width: 300px">Product/Service</th>
                                                <th style="">Description</th>
                                                @if($check_settings == 1)
                                                    <th style="">BIN</th>
                                                @endif
                                                <th style="">U/M</th>
                                                <th style="">Qty</th>
                                                <th style="">Rate</th>
                                                <th style="">Discount</th>
                                                <th style="">Remark</th>
                                                <th style="">Amount</th>
                                                <th>
                                                    <label style="cursor: pointer" class="select-all-tax-check unselect-tax">
                                                        <i class="fa fa-icon fa-check tax-icon"></i> Tax</label>
                                                </th>
                                                <th width="10"></th>
                                            </tr>
                                        </thead>
                                        @include("member.accounting_transaction.loading_items")
                                        <tbody class="applied-transaction-list">
                                        </tbody>
                                        <tbody class="draggable tbody-item estimate-tbl">
                                            @if(isset($sales_receipt))
                                                @foreach($sales_receipt_item as $key => $sr_item)
                                                <tr class="tr-draggable">
                                                    <td class="invoice-number-td text-right">
                                                        1
                                                    </td>
                                                    <td><input type="text" class="for-datepicker" name="item_servicedate[]"/>{{$sr_item->invline_service_date != '1970-01-01' ? $sr_item->invline_service_date : ''}}</td>
                                                    @if($check_barcode == '1')
                                                    <td class="item-select-td">
                                                        <input class="form-control input-sm pull-left item-textbox hidden" value="{{$sr_item->item_barcode}}" onkeypress="event_search($(this), event)" type="text"/>
                                                        <select class="1111 form-control select-item droplist-item input-sm pull-left item-select {{$sr_item->invline_item_id}}" name="item_id[]" required >
                                                            @include("member.load_ajax_data.load_item_category", ['add_search' => "", 'item_id' => $sr_item->invline_item_id])
                                                            <option class="hidden" value="" />
                                                        </select>
                                                    </td>
                                                    @else
                                                    <td>
                                                        <select class="1111 form-control select-item droplist-item input-sm pull-left " name="item_id[]" >
                                                            @include("member.load_ajax_data.load_item_category", ['add_search' => "", 'item_id' => $sr_item->invline_item_id])
                                                            <option class="hidden" value="" />
                                                        </select>
                                                        
                                                    </td>
                                                    @endif
                                                    <td>
                                                        <textarea class="textarea-expand txt-desc" name="item_description[]">{{$sr_item->invline_description}}</textarea>
                                                    </td>
                                                    @if($check_settings == 1)
                                                    <td>
                                                        <select class="form-control droplist-sub-warehouse select-sub-warehouse input-sm" name="item_sub_warehouse[]" >
                                                            @include('member.warehousev2.load_sub_warehouse_v2_select', ['_bin_warehouse' => $_bin_item_warehouse[$key]])
                                                            <option class="hidden" value="" />
                                                        </select>
                                                    </td>
                                                    @endif
                                                    <td><select class="2222 droplist-um select-um {{isset($sr_item->multi_id) ? 'has-value' : ''}}" name="item_um[]">
                                                            @if($sr_item->invline_um)
                                                                @include("member.load_ajax_data.load_one_unit_measure", ['item_um_id' => $sr_item->multi_um_id, 'selected_um_id' => $sr_item->invline_um])
                                                            @else
                                                                <option class="hidden" value="" />
                                                            @endif
                                                            <option class="hidden" value="" />
                                                        </select>
                                                    </td>
                                                    <td><input class="text-center number-input txt-qty change-qty compute" value="{{$sr_item->invline_orig_qty}}" type="text" name="item_qty[]"/></td>
                                                    <td><input class="text-right number-input txt-rate compute" type="text" value="{{$sr_item->invline_rate}}" name="item_rate[]"/></td>
                                                    <td><input class="text-right txt-discount compute" type="text" name="item_discount[]"  value="{{$sr_item->invline_discount_type != 'fixed' ? ($sr_item->invline_discount * 100).'%': $sr_item->invline_discount}}"/></td>
                                                    <td><textarea class="textarea-expand" type="text" name="item_remarks[]">{{$sr_item->invline_discount_remark}}</textarea></td>
                                                    <td><input class="text-right number-input txt-amount" type="text" name="item_amount[]" value="{{$sr_item->invline_amount}}" /></td>
                                                    <td class="text-center">
                                                        <input type="hidden" name="item_taxable[]" class="taxable-input" value="{{$sr_item->taxable}}">
                                                        <input type="checkbox" class="taxable-check compute" value="1" {{$sr_item->taxable == 1 ? 'checked' : ''}}>
                                                    </td>
                                                    <td class="text-center remove-tr cursor-pointer">
                                                        <i class="fa fa-trash-o" aria-hidden="true"></i>
                                                        <input type="hidden" name="item_refname[]" value="{{$sr_item->invline_refname}}">
                                                        <input type="hidden" name="item_refid[]" value="{{$sr_item->invline_refid}}">
                                                    </td>
                                                </tr>
                                                @endforeach
                                            @endif
                                            <tr class="tr-draggable">
                                                <td class="invoice-number-td text-right">
                                                    1
                                                </td>
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
                                                    <select class="form-control select-item droplist-item input-sm pull-left" name="item_id[]" >
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
                                                <td><input class="text-center number-input txt-qty change-qty compute" type="text" name="item_qty[]"/></td>
                                                <td><input class="text-right number-input txt-rate compute" type="text" name="item_rate[]"/></td>
                                                <td><input class="text-right txt-discount compute" type="text" name="item_discount[]"/></td>
                                                <td><textarea class="textarea-expand" type="text" name="item_remarks[]" ></textarea></td>
                                                <td><input class="text-right number-input txt-amount" type="text" name="item_amount[]"/></td>
                                                <td class="text-center">
                                                    <input type="hidden" name="item_taxable[]" class="taxable-input" >
                                                    <input type="checkbox" class="taxable-check compute" value="1">
                                                </td>
                                                <td class="text-center remove-tr cursor-pointer">
                                                    <i class="fa fa-trash-o" aria-hidden="true"></i>
                                                    <input type="hidden" name="item_refname[]">
                                                    <input type="hidden" name="item_refid[]">
                                                </td>
                                            </tr>
                                              <tr class="tr-draggable">
                                                <td class="invoice-number-td text-right">
                                                    2
                                                </td>
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
                                                    <select class="form-control select-item droplist-item input-sm pull-left" name="item_id[]" >
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
                                                <td><input class="text-center number-input txt-qty change-qty compute" type="text" name="item_qty[]"/></td>
                                                <td><input class="text-right number-input txt-rate compute" type="text" name="item_rate[]"/></td>
                                                <td><input class="text-right txt-discount compute" type="text" name="item_discount[]"/></td>
                                                <td><textarea class="textarea-expand" type="text" name="item_remarks[]" ></textarea></td>
                                                <td><input class="text-right number-input txt-amount" type="text" name="item_amount[]"/></td>
                                                <td class="text-center">
                                                    <input type="hidden" name="item_taxable[]" class="taxable-input" >
                                                    <input type="checkbox" class="taxable-check compute" value="1">
                                                </td>
                                                <td class="text-center remove-tr cursor-pointer">
                                                    <i class="fa fa-trash-o" aria-hidden="true"></i>
                                                    <input type="hidden" name="item_refname[]">
                                                    <input type="hidden" name="item_refid[]">
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                        <div class="row clearfix">
                            <div class="col-sm-3">
                                <label>Message Displayed on Receipt</label>
                                <textarea class="form-control input-sm textarea-expand remarks-sr" name="customer_message" placeholder="">{{isset($sales_receipt) ? $sales_receipt->inv_message : ''}}</textarea>
                                @if($bank_interest) 
                                    <br>
                                    <label>Bank Interest <br> <small>Eg : 2% - P 5,000.00 - BDO - 12 months - "Remarks"</small></label>
                                    <textarea class="form-control input-sm textarea-expand" name="customer_bank_interest" placeholder="_% - P_ - BDO - _ months - '_'">{{isset($sales_invoice) ? $sales_invoice->bank_interest : ''}}</textarea>
                                @endif
                            </div>
                            <div class="col-sm-3">
                                <label>Statement Memo</label>
                                <textarea class="form-control input-sm textarea-expand" name="customer_memo" placeholder="">{{isset($sales_receipt) ? $sales_receipt->inv_memo : ''}}</textarea>
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
                                        Vatable
                                    </div>
                                    <div class="col-md-5 text-right digima-table-value">
                                        <input type="hidden" name="customer_vat_exclusive" class=vatable-sales-input" />
                                        PHP&nbsp;<span class="vatable-sales">0.00</span>
                                    </div>
                                </div> 
                                <div class="row">
                                    <div class="col-md-7 text-right digima-table-label">
                                        Vat Amount
                                    </div>
                                    <div class="col-md-5 text-right digima-table-value">
                                        <input type="hidden" name="customer_vat_amount" class=vat-amount-input" />
                                        PHP&nbsp;<span class="vat-amount">0.00</span>
                                    </div>
                                </div> 
                                <div class="row">
                                    <div class="col-md-7 text-right digima-table-label">
                                        <div class="row">
                                            <div class="col-sm-6 col-sm-offset-3  padding-lr-1">
                                                <label>EWT</label>
                                            </div>
                                            <div class="col-sm-3  padding-lr-1">
                                                <!-- <input class="form-control input-sm text-right ewt_value number-input" type="text" name="ewt"> -->
                                                <select class="form-control input-sm ewt-value compute" name="customer_ewt">  
                                                    <option value="0" {{isset($sales_receipt) ? $sales_receipt->ewt == 0 ? 'selected' : '' : ''}}></option>
                                                    <option value="0.01" {{isset($sales_receipt) ? $sales_receipt->ewt == 0.01 ? 'selected' : '' : ''}}>1%</option>
                                                    <option value="0.02" {{isset($sales_receipt) ? $sales_receipt->ewt == 0.02 ? 'selected' : '' : ''}}>2%</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-5 text-right digima-table-value">
                                        PHP&nbsp;<span class="ewt-total">0.00</span>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-7 text-right digima-table-label">
                                        <div class="row">
                                            <div class="col-sm-6 col-sm-offset-4  padding-lr-1">
                                                <select class="form-control input-sm compute discount_selection" name="customer_discounttype">  
                                                    <option value="percent" {{isset($invsales_receipt) ? $sales_receipt->inv_discount_type == 'percent' ? 'selected' : '' : ''}}>Discount percentage</option>
                                                    <option value="value" {{isset($sales_receipt) ? $sales_receipt->inv_discount_type == 'value' ? 'selected' : '' : ''}}>Discount value</option>
                                                </select>
                                            </div>
                                            <div class="col-sm-2  padding-lr-1">
                                                <input class="form-control input-sm text-right number-input discount_txt compute" type="text" name="customer_discount" value="{{$sales_receipt->inv_discount_value or ''}}">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-5 text-right digima-table-value">
                                        PHP&nbsp;<span class="discount-total">0.00</span>
                                    </div>
                                </div> 
                                <div class="row hidden" >
                                     <div class="col-md-7 text-right digima-table-label hidden">
                                        <div class="row">
                                            <div class="col-sm-4 col-sm-offset-8  padding-lr-1">
                                                <select class="form-control input-sm tax_selection compute" name="customer_tax">  
                                                    <option value="0" {{isset($sales_receipt) ? $sales_receipt->taxable == 0 ? 'selected' : '' : ''}}>No Tax</option>
                                                    <option value="1" {{isset($sales_receipt) ? $sales_receipt->taxable == 1 ? 'selected' : '' : ''}}>Vat (12%)</option>
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
                    </div>
                </div>
            </div>
        </div>
    </div>
</form>

<div class="div-script-pm">
     <table class="div-item-row-script-pm hide">
        <tr class="tr-pm-row">
            <td class="td-pm-id">1</td>
            <td>
                <select class="form-control select-pm" name="txn_payment_method[]" >
                    @include("member.load_ajax_data.load_payment_method")
                </select>
            </td>
            <td >
                <input type="text" class="form-control rcvpymnt-refno" name="txn_ref_no[]" id="rcvpymnt-refno" value="{{$sales_order->est_cheque_ref_no or ''}}" />     
            </td>
            <td>
                <input type="text" class="form-control  text-right number-input-pm" name="txn_payment_amount[]" id="rcvpymnt-refno" value="0.00" />                
            </td>
            <td class="text-center remove-tr-pm" width="10">
                <i class="fa fa-trash-o" aria-hidden="true"></i>        
            </td>
        </tr>
     </table>
</div>
<div class="div-script">
    <table class="div-item-row-script hide">
        <tr class="tr-draggable">
            <td class="invoice-number-td text-right">
                1
            </td>
            <td><input type="text" class="for-datepicker" name="item_servicedate[]"/></td>
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
                <select class="form-control select-item input-sm pull-left" name="item_id[]" >
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
            <td><select class="2222 select-um" name="item_um[]"><option class="hidden" value="" /></select></td>
            <td><input class="text-center number-input txt-qty change-qty compute" type="text" name="item_qty[]"/></td>
            <td><input class="text-right number-input txt-rate compute" type="text" name="item_rate[]"/></td>
            <td><input class="text-right txt-discount compute" type="text" name="item_discount[]"/></td>
            <td><textarea class="textarea-expand" type="text" name="item_remarks[]" ></textarea></td>
            <td><input class="text-right number-input txt-amount" type="text" name="item_amount[]"/></td>
            <td class="text-center">
                <input type="hidden" name="item_taxable[]" class="taxable-input" >
                <input type="checkbox" class="taxable-check compute" value="1">
            </td>
            <td class="text-center remove-tr cursor-pointer">
                <i class="fa fa-trash-o" aria-hidden="true"></i>
                <input type="hidden" name="item_refname[]">
                <input type="hidden" name="item_refid[]">
            </td>
        </tr>
    </table>
</div>
@endsection
@section('script')
<script type="text/javascript" src="/assets/member/js/accounting_transaction/customer/sales_receipt.js"></script>
<script type="text/javascript">
    $('.droplist-sub-warehouse').globalDropList({
        link : "/member/item/v2/warehouse/add",
        width : "100%",
        placeholder : 'Search Location...',
        onCreateNew : function()
        {

        },
        onChangeValue : function()
        {
            
        }
    });
    $(".draggable .tr-draggable:last td select.select-sub-warehouse").globalDropList(
    {
        link : "/member/item/v2/warehouse/add",
        width : "100%",
        placeholder : 'Search Location...',
        onCreateNew : function()
        {
            
        },
        onChangeValue : function()
        {
            
        }
    });
</script>
@endsection