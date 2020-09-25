@extends('member.layout')
@section('content')
<form class="global-submit" action="{{$action or ''}}" method="post">
    <div class="panel panel-default panel-block panel-title-block">
        <input type="hidden" class="button-action" name="button_action" value="">
        <input type="hidden" class="range-discount" name="" value="{{$range_sales_discount or ''}}">
        <input type="hidden" name="sales_order_id" value="{{Request::input('id')}}">
        <input type="hidden" name="already_validate" value="0">
        <input type="hidden" name="_token" id="_token" value="{{csrf_token()}}"/>
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
                    <div>
                        <a class="btn btn-custom-white" href="/member/transaction/sales_order">Cancel</a>
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
    <div class="panel panel-default panel-block panel-title-block panel-gray "  style="margin-bottom: -10px;">
        <div class="data-container" >
            <div class="tab-content">
                <div class="row">
                    <div class="col-md-12" style="padding: 30px;">
                        <!-- START CONTENT -->
                        <div style="padding-bottom: 10px; margin-bottom: 10px;">
                            <div class="row clearfix">
                                <div class="col-sm-4">
                                    <label>Reference Number</label>
                                    <input type="text" class="form-control" name="transaction_refnum" value="{{isset($sales_order) ? $sales_order->transaction_refnum : $transaction_refnum}}">
                                </div>
                            </div>
                        </div>
                        <div style="border-bottom: 1px solid #ddd; padding-bottom: 10px; margin-bottom: 10px;">
                            <div class="row clearfix">
                                <div class="col-sm-4">
                                    <select class="form-control droplist-customer input-sm pull-left" name="customer_id" data-placeholder="Select a Customer" required>
                                        @include('member.load_ajax_data.load_customer', ['customer_id' => isset($sales_order) ? $sales_order->est_customer_id : (Request::input('c_id') ? Request::input('c_id') : '') ]);
                                    </select>
                                </div>
                                <div class="col-sm-4">
                                    <input type="text" class="form-control input-sm customer-email" name="customer_email" placeholder="E-Mail (Separate E-Mails with comma)" value="{{$sales_order->est_customer_email or ''}}"/>
                                </div>
                                <div class="col-sm-4 text-right open-transaction hidden" style="display: none;">
                                    <h4><a class="popup popup-link-open-transaction" size="md" link="/member/transaction/sales_invoice/load_transaction?customer_id="><i class="fa fa-handshake-o"></i> <span class="count-open-transaction">0</span> Open Transaction</a></h4>
                                </div>
                            </div>
                        </div>
                        <div class="row clearfix">
                            <div class="col-sm-3">
                                <label>Billing Address</label>
                                <textarea class="form-control input-sm textarea-expand customer-billing-address" name="customer_address" placeholder="">{{$sales_order->est_customer_billing_address or ''}}</textarea>
                            </div>
                            <div class="col-sm-2">
                                <label>Sales Order Date</label>
                                <input type="text" class="datepicker form-control input-sm" name="transaction_date" value="{{$sales_order->est_date or date('m/d/y')}}"/>
                            </div>
                            @if($monthly_budget)
                            <div class="col-sm-2">
                                <label>Adjusted Monthly Budget</label>
                                <input type="text" class="form-control text-right input-sm adjusted-monthly-budget" readonly="true" name="" >
                            </div>
                            @endif
                        </div>
                        @include("member.accounting_transaction.customer.sales_order.sales_order_pm")
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
                                                <th style="">U/M</th>
                                                <th style="">Qty</th>
                                                @if(isset($sales_order))
                                                <th style="width: 70px;">Received</th>
                                                <th style="width: 70px;">Backorder</th>
                                                <th style="width: 10px;">C</th>
                                                @endif
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
                                        <tbody class="draggable tbody-item">
                                            @if(isset($sales_order))
                                            @foreach($sales_order_item as $so_item)
                                            <tr class="tr-draggable">
                                                <td class="invoice-number-td text-right">1</td>
                                                <td><input type="text" class="for-datepicker" name="item_servicedate[]"value="{{($so_item->estline_service_date != '1970-01-01' ?  $so_item->estline_service_date != '0000-00-00' ? dateFormat($so_item->estline_service_date) : '' :'' )}}"/></td>
                                                @if($check_barcode == '1')
                                                <td class="item-select-td">
                                                    <input class="form-control input-sm pull-left item-textbox hidden" value="{{$so_item->item_barcode}}" onkeypress="event_search($(this), event)" type="text"/>
                                                    <select class="1111 form-control select-item droplist-item input-sm pull-left item-select {{$so_item->estline_item_id}}" name="item_id[]" required >
                                                        @include("member.load_ajax_data.load_item_category", ['add_search' => "", 'item_id' => $so_item->estline_item_id])
                                                        <option class="hidden" value="" />
                                                    </select>
                                                </td>
                                                @else
                                                <td>
                                                    <select class="1111 form-control select-item droplist-item input-sm pull-left " name="item_id[]" >
                                                        @include("member.load_ajax_data.load_item_category", ['add_search' => "", 'item_id' => $so_item->estline_item_id])
                                                        <option class="hidden" value="" />
                                                    </select>
                                                </td>
                                                @endif
                                                <td><textarea class="textarea-expand txt-desc" name="item_description[]">{{$so_item->estline_description}}</textarea></td>
                                                <td>
                                                    <select class="droplist-um select-um {{isset($so_item->multi_id) ? 'has-value' : ''}}" name="item_um[]">
                                                        @if($so_item->invline_um)
                                                        @include("member.load_ajax_data.load_one_unit_measure", ['item_um_id' => $so_item->multi_um_id, 'selected_um_id' => $so_item->estline_um])
                                                        @else
                                                        <option class="hidden" value="" />
                                                            @endif
                                                        </select>
                                                </td>
                                                <td><input class="text-center number-input txt-qty change-qty compute" type="text" name="item_qty[]" value="{{$so_item->estline_orig_qty}}"/></td>
                                                <td><input class="text-center number-input txt-received compute" type="text" value="{{$so_item->estline_orig_qty - $so_item->estline_qty}}" readonly="readonly"/></td>
                                                <td><input class="text-center number-input txt-remaining compute" type="text" name="item_remaining[]" value="{{$so_item->estline_qty}}" readonly="readonly"/></td>
                                                <td class="text-center">
                                                    <input type="hidden" name="item_status[]" class="item-status" value="{{$so_item->estline_status}}">
                                                    <input type="hidden" name="" class="item-name" value="{{$so_item->item_name}}">
                                                    <input type="checkbox" class="item-status-check" value="{{$so_item->estline_item_id}}" {{$so_item->estline_status == 1 ? 'checked' : ''}}>
                                                </td>
                                                <td><input class="text-right number-input txt-rate compute" type="text" name="item_rate[]" value="{{$so_item->estline_rate}}"/></td>
                                                <td><input class="text-right txt-discount compute" type="text" name="item_discount[]" value="{{$so_item->estline_discount_type != 'fixed' ? ($so_item->estline_discount).'%': $so_item->estline_discount}}"/></td>
                                                <td><textarea class="textarea-expand" type="text" name="item_remarks[]" ></textarea> {{$so_item->estline_discount_remark}}</td>
                                                <td><input class="text-right number-input txt-amount" type="text" name="item_amount[]" value="{{$so_item->estline_amount}}"/></td>
                                                <td class="text-center">
                                                    <input type="hidden" name="item_taxable[]" class="taxable-input" value="{{$so_item->taxable}}">
                                                    <input type="checkbox" class="taxable-check compute"  {{$so_item->taxable == 1 ? 'checked' : ''}} value="1">
                                                </td>
                                                <td class="text-center remove-tr cursor-pointer">
                                                    <i class="fa fa-trash-o" aria-hidden="true"></i>
                                                    <input type="hidden" name="item_refname[]" value="{{$so_item->estline_refname}}">
                                                    <input type="hidden" name="item_refid[]" value="{{$so_item->estline_refid}}">
                                                </td>
                                            </tr>
                                            @endforeach
                                            @endif
                                            <tr class="tr-draggable">
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
                                                    <select class="form-control select-item droplist-item input-sm pull-left" name="item_id[]" >
                                                        @include("member.load_ajax_data.load_item_category", ['add_search' => ""])
                                                        <option class="hidden" value="" />
                                                    </select>
                                                </td>
                                                @endif
                                                <td><textarea class="textarea-expand txt-desc" name="item_description[]"></textarea></td>
                                                <td><select class="droplist-um select-um" name="item_um[]"><option class="hidden" value="" /></select></td>
                                                <td><input class="text-center number-input txt-qty change-qty compute" type="text" name="item_qty[]"/></td>
                                                @if(isset($sales_order))
                                                <td><input class="text-center number-input txt-received" type="text" value="" readonly="readonly"/></td>
                                                <td><input class="text-center number-input txt-remaining" type="text" value="" name="item_remaining[]" readonly="readonly"/></td>
                                                <td class="text-center">
                                                    <input type="hidden" name="item_status[]" class="item-status" value="0">
                                                </td>
                                                @endif
                                                <td><input class="text-right number-input txt-rate compute" type="text" name="item_rate[]"/></td>
                                                <td><input class="text-right txt-discount compute" type="text" name="item_discount[]"/></td>
                                                <td><textarea class="textarea-expand" type="text" name="item_remarks[]" ></textarea></td>
                                                <td><input class="text-right number-input txt-amount" type="text" name="item_amount[]"/></td>
                                                <td class="text-center">
                                                    <input type="hidden" name="item_taxable[]" class="taxable-input">
                                                    <input type="checkbox" class="taxable-check compute" value="1">
                                                </td>
                                                <td class="text-center remove-tr cursor-pointer">
                                                    <i class="fa fa-trash-o" aria-hidden="true"></i>
                                                    <input type="hidden" name="item_refname[]">
                                                    <input type="hidden" name="item_refid[]">
                                                </td>
                                            </tr>
                                                
                                            <tr class="tr-draggable">
                                                <td class="invoice-number-td text-right">2</td>
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
                                                <td><textarea class="textarea-expand txt-desc" name="item_description[]"></textarea></td>
                                                <td><select class="droplist-um select-um" name="item_um[]"><option class="hidden" value="" /></select></td>
                                                <td><input class="text-center number-input txt-qty change-qty compute" type="text" name="item_qty[]"/></td>
                                                @if(isset($sales_order))
                                                <td><input class="text-center number-input txt-received" type="text" value="" readonly="readonly"/></td>
                                                <td><input class="text-center number-input txt-remaining" type="text" value="" name="item_remaining[]" readonly="readonly"/></td>
                                                <td class="text-center">
                                                    <input type="hidden" name="item_status[]" class="item-status" value="0">
                                                </td>
                                                @endif
                                                <td><input class="text-right number-input txt-rate compute" type="text" name="item_rate[]"/></td>
                                                <td><input class="text-right txt-discount compute" type="text" name="item_discount[]"/></td>
                                                <td><textarea class="textarea-expand" type="text" name="item_remarks[]" ></textarea></td>
                                                <td><input class="text-right number-input txt-amount" type="text" name="item_amount[]"/></td>
                                                <td class="text-center">
                                                    <input type="hidden" name="item_taxable[]" class="taxable-input">
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
                                <label>Message Displayed on Sales Order</label>
                                <textarea class="form-control input-sm textarea-expand remarks-so" name="customer_message" placeholder="">{{$sales_order->est_message or ''}}</textarea>
                            </div>
                            <div class="col-sm-3">
                                <label>Statement Memo</label>
                                <textarea class="form-control input-sm textarea-expand" name="customer_memo" placeholder="">{{$sales_order->est_memo or ''}}</textarea>
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
                                                    <option value="0" {{isset($sales_order) ? $sales_order->ewt == 0 ? 'selected' : '' : ''}}></option>
                                                    <option value="0.01" {{isset($sales_order) ? $sales_order->ewt == 0.01 ? 'selected' : '' : ''}}>1%</option>
                                                    <option value="0.02" {{isset($sales_order) ? $sales_order->ewt == 0.02 ? 'selected' : '' : ''}}>2%</option>
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
                                                    <option value="percent" {{isset($sales_order) ? $sales_order->inv_discount_type == 'percent' ? 'selected' : '' : ''}}>Discount percentage</option>
                                                    <option value="value" {{isset($sales_order) ? $sales_order->inv_discount_type == 'value' ? 'selected' : '' : ''}}>Discount value</option>
                                                </select>
                                            </div>
                                            <div class="col-sm-2  padding-lr-1">
                                                <input class="form-control input-sm text-right number-input discount_txt compute" type="text" name="customer_discount" value="{{$sales_order->est_discount_value or ''}}">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-5 text-right digima-table-value">
                                        PHP&nbsp;<span class="discount-total">0.00</span>
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
                                @if(isset($sales_invoice))
                                <div class="row">
                                    <div class="col-md-7 text-right digima-table-label">
                                        Payment Appplied
                                    </div>
                                    <div class="col-md-5 text-right digima-table-value">
                                        <input type="hidden" name="payment-receive" class="payment-receive-input" />
                                        PHP&nbsp;<span class="payment-applied">{{currency('',$sales_invoice->inv_payment_applied)}}</span>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-7 text-right digima-table-label total">
                                        Balance Due
                                    </div>
                                    <div class="col-md-5 text-right digima-table-value total">
                                        <input type="hidden" name="balance-due" class="balance-due-input" />
                                        PHP&nbsp;<span class="balance-due">0.00</span>
                                    </div>
                                </div>
                                @endif
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
            <td class="invoice-number-td text-right">1</td>
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
            <td><textarea class="textarea-expand txt-desc" name="item_description[]"></textarea></td>
            <td><select class="select-um" name="item_um[]"><option class="hidden" value="" /></select></td>
            <td><input class="text-center number-input txt-qty change-qty compute" type="text" name="item_qty[]"/></td>
            @if(isset($sales_order))
            <td><input class="text-center number-input txt-received" type="text" value="" readonly="readonly"/></td>
            <td><input class="text-center number-input txt-remaining" type="text" value="" name="item_remaining[]" readonly="readonly"/></td>
            <td class="text-center">
                <input type="hidden" name="item_status[]" class="item-status" value="0">
            </td>
            @endif
            <td><input class="text-right number-input txt-rate compute" type="text" name="item_rate[]"/></td>
            <td><input class="text-right txt-discount compute" type="text" name="item_discount[]"/></td>
            <td><textarea class="textarea-expand" type="text" name="item_remarks[]" ></textarea></td>
            <td><input class="text-right number-input txt-amount" type="text" name="item_amount[]"/></td>
            <td class="text-center">
                <input type="hidden" name="item_taxable[]" class="taxable-input">
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
<script type="text/javascript" src="/assets/member/js/accounting_transaction/customer/sales_order.js"></script>
@endsection
