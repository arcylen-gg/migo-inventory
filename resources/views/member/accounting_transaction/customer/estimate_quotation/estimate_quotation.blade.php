@extends('member.layout')
@section('content')
<form class="global-submit" action="{{$action or ''}}" method="post">
    <div class="panel panel-default panel-block panel-title-block">
        <input type="hidden" class="button-action" name="button_action" value="">
        <input type="hidden" class="range-discount" name="" value="{{$range_sales_discount or ''}}">
        <input type="hidden" name="estimate_quotation_id" value="{{Request::input('id')}}">
        <input type="hidden" name="_token" id="_token" value="{{csrf_token()}}"/>
        <input type="hidden" name="proposal_number" class="proposal-number" id="proposal_number" value="{{$proposal_number or ''}}"/>
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
                        <a class="btn btn-custom-white" href="/member/transaction/estimate_quotation">Cancel</a>
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
                                    <input type="text" class="form-control" name="transaction_refnumber" value="{{isset($estimate_quotation) ? $estimate_quotation->transaction_refnum : $transaction_refnum}}">
                                </div>
                            </div>
                        </div>
                        <div style="border-bottom: 1px solid #ddd; padding-bottom: 10px; margin-bottom: 10px;">
                            <div class="row clearfix">
                                <div class="col-sm-4">
                                    <select class="form-control droplist-customer input-sm pull-left" name="customer_id" data-placeholder="Select a Customer" required>
                                        @include('member.load_ajax_data.load_customer', ['customer_id' => isset($estimate_quotation) ? $estimate_quotation->est_customer_id : (Request::input('c_id') ? Request::input('c_id') : '') ]);
                                    </select>
                                </div>
                                <div class="col-sm-4">
                                    <input type="text" class="form-control input-sm customer-email" name="customer_email" placeholder="E-Mail (Separate E-Mails with comma)" value="{{$estimate_quotation->est_customer_email or ''}}"/>
                                </div>
                            </div>
                        </div>
                        <div class="row clearfix">
                            <div class="col-sm-3">
                                <label>Billing Address</label>
                                <textarea class="form-control input-sm textarea-expand customer-billing-address" name="customer_address" placeholder="">{{$estimate_quotation->est_customer_billing_address or ''}}</textarea>
                            </div>
                            <div class="col-sm-2">
                                <label>Estimate Date</label>
                                <input type="text" class="datepicker form-control input-sm" name="transaction_date" value="{{$estimate_quotation->est_date or date('m/d/y')}}"/>
                            </div>
                            <div class="col-sm-2">
                                <label>Expiration Date</label>
                                <input type="text" class="datepicker form-control input-sm" name="transaction_duedate" value="{{$estimate_quotation->est_exp_date or date('m/d/y')}}" />
                            </div>
                        </div>
                        
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
                                                @if(isset($estimate_quotation))
                                                <th style="width: 70px;">Received</th>
                                                <th style="width: 70px;">Backorder</th>
                                                <th style="width: 10px;">C</th>
                                                @endif
                                                @if($proposal_number)
                                                    @if($fieldmen != 1)
                                                    <th style="">Proposal Number</th>
                                                    @endif
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
                                        <tbody class="draggable tbody-item">   
                                            @if(isset($estimate_quotation))
                                                @foreach($estimate_quotation_item as $eq_item)
                                                <tr class="tr-draggable">
                                                    <td class="invoice-number-td text-right">1</td>
                                                    <td><input type="text" class="for-datepicker" name="item_servicedate[]"value="{{($eq_item->estline_service_date != '1970-01-01' ?  $eq_item->estline_service_date != '0000-00-00' ? dateFormat($eq_item->estline_service_date) : '' :'' )}}"/></td>

                                                    @if($check_barcode == '1')
                                                    <td class="item-select-td">
                                                        <input class="form-control input-sm pull-left item-textbox hidden" value="{{$eq_item->item_barcode}}" onkeypress="event_search($(this), event)" type="text"/>
                                                        <select class="1111 form-control select-item droplist-item input-sm pull-left item-select {{$eq_item->estline_item_id}}" name="item_id[]" required >
                                                            @include("member.load_ajax_data.load_item_category", ['add_search' => "", 'item_id' => $eq_item->estline_item_id])
                                                            <option class="hidden" value="" />
                                                        </select>
                                                    </td>
                                                    @else
                                                    <td>
                                                        <select class="1111 form-control select-item droplist-item input-sm pull-left " name="item_id[]" >
                                                            @include("member.load_ajax_data.load_item_category", ['add_search' => "", 'item_id' => $eq_item->estline_item_id])
                                                            <option class="hidden" value="" />
                                                        </select>
                                                        
                                                    </td>
                                                    @endif
                                                    <td><textarea class="textarea-expand txt-desc" name="item_description[]">{{$eq_item->estline_description}}</textarea></td>
                                                    <td>
                                                        <select class="droplist-um select-um {{isset($eq_item->multi_id) ? 'has-value' : ''}}" name="item_um[]">
                                                          @if($eq_item->invline_um)
                                                                @include("member.load_ajax_data.load_one_unit_measure", ['item_um_id' => $eq_item->multi_um_id, 'selected_um_id' => $eq_item->estline_um])
                                                            @else
                                                                <option class="hidden" value="" />
                                                            @endif
                                                        </select>
                                                    </td>
                                                    <td><input class="text-center number-input txt-qty change-qty compute" type="text" name="item_qty[]" value="{{$eq_item->estline_orig_qty}}" /></td>
                                                    <td><input class="text-center number-input txt-backorder compute" type="text" value="{{$eq_item->estline_orig_qty - $eq_item->estline_qty}}" readonly="readonly"/></td>
                                                    <td><input class="text-center number-input txt-remaining compute" type="text" name="item_remaining[]" value="{{$eq_item->estline_qty}}" readonly="readonly"/></td>
                                                    <td class="text-center">
                                                        <input type="hidden" name="item_status[]" class="item-status" value="{{$eq_item->estline_status}}">
                                                        <input type="hidden" name="" class="item-name" value="{{$eq_item->item_name}}">
                                                        <input type="checkbox" class="item-status-check" value="{{$eq_item->estline_item_id}}" {{$eq_item->estline_status == 1 ? 'checked' : ''}}>
                                                    </td>
                                                    @if($proposal_number)
                                                        @if($fieldmen != 1)
                                                        <td class="proposal-td">
                                                            <select class="form-control droplist-proposal select-proposal" name="estline_proposal_number[]">
                                                              @include("member.accounting_transaction.customer.estimate_quotation.load_customer_proposal", ["item_proposal" => $eq_item->estline_proposal_number])  
                                                            </select>
                                                        </td>
                                                        @endif
                                                    @endif
                                                    <td><input class="text-right number-input txt-rate compute" type="text" name="item_rate[]" value="{{$eq_item->estline_rate}}"/></td>
                                                    <td><input class="text-right txt-discount compute" type="text" name="item_discount[]" value="{{$eq_item->estline_discount_type != 'fixed' ? ($eq_item->estline_discount).'%': $eq_item->estline_discount}}"/></td>
                                                    <td><textarea class="textarea-expand" type="text" name="item_remarks[]" ></textarea> {{$eq_item->estline_discount_remark}}</td>
                                                    <td><input class="text-right number-input txt-amount" type="text" name="item_amount[]" value="{{$eq_item->estline_amount}}"/></td>
                                                    <td class="text-center">
                                                        <input type="hidden"  name="item_taxable[]" class="taxable-input" value="{{$eq_item->taxable}}">
                                                        <input type="checkbox" class="taxable-check compute" value="1"{{$eq_item->taxable == 1 ? 'checked' : ''}}>
                                                    </td>
                                                    <td class="text-center remove-tr cursor-pointer"><i class="fa fa-trash-o" aria-hidden="true"></i></td>
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
                                                @if(isset($estimate_quotation))
                                                <td><input class="text-center number-input txt-backorder" type="text" value="" readonly="readonly"/></td>
                                                <td><input class="text-center number-input txt-remaining" type="text" value="" name="item_remaining[]" readonly="readonly"/></td>
                                                <td class="text-center">
                                                    <input type="hidden" name="item_status[]" class="item-status" value="0">
                                                </td>
                                                @endif
                                                @if($proposal_number)
                                                    @if($fieldmen != 1)
                                                    <td class="proposal-td">
                                                        <select class="form-control droplist-proposal select-proposal" name="estline_proposal_number[]">
                                                          @include("member.accounting_transaction.customer.estimate_quotation.load_customer_proposal")  
                                                        </select>
                                                    </td>
                                                    @endif
                                                @endif
                                                <td><input class="text-right number-input txt-rate compute" type="text" name="item_rate[]"/></td>
                                                <td><input class="text-right txt-discount compute" type="text" name="item_discount[]"/></td>
                                                <td><textarea class="textarea-expand" type="text" name="item_remarks[]" ></textarea></td>
                                                <td><input class="text-right number-input txt-amount" type="text" name="item_amount[]"/></td>
                                                <td class="text-center">
                                                    <input type="hidden"  name="item_taxable[]" class="taxable-input" value="">
                                                    <input type="checkbox" class="taxable-check compute" value="1">
                                                </td>
                                                <td class="text-center remove-tr cursor-pointer"><i class="fa fa-trash-o" aria-hidden="true"></i></td>
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
                                                @if(isset($estimate_quotation))
                                                <td><input class="text-center number-input txt-backorder" type="text" value="" readonly="readonly"/></td>
                                                <td><input class="text-center number-input txt-remaining" type="text" value="" name="item_remaining[]" readonly="readonly"/></td>
                                                <td class="text-center">
                                                    <input type="hidden" name="item_status[]" class="item-status" value="0">
                                                </td>
                                                @endif
                                                @if($proposal_number)
                                                    @if($fieldmen != 1)
                                                    <td class="proposal-td">
                                                        <select class="form-control droplist-proposal select-proposal" name="estline_proposal_number[]">
                                                          @include("member.accounting_transaction.customer.estimate_quotation.load_customer_proposal")  
                                                        </select>
                                                    </td>
                                                    @endif
                                                @endif
                                                <td><input class="text-right number-input txt-rate compute" type="text" name="item_rate[]"/></td>
                                                <td><input class="text-right txt-discount compute" type="text" name="item_discount[]"/></td>
                                                <td><textarea class="textarea-expand" type="text" name="item_remarks[]" ></textarea></td>
                                                <td><input class="text-right number-input txt-amount" type="text" name="item_amount[]"/></td>
                                                <td class="text-center">
                                                   <input type="hidden"  name="item_taxable[]" class="taxable-input" value="">
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
                                <label>Message Displayed on Estimate</label>
                                <textarea class="form-control input-sm textarea-expand" name="customer_message" placeholder="">{{$estimate_quotation->est_message or ''}}</textarea>
                            </div>
                            <div class="col-sm-3">
                                <label>Statement Memo</label>
                                <textarea class="form-control input-sm textarea-expand" name="customer_memo" placeholder="">{{$estimate_quotation->est_memo or ''}}</textarea>
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
                                                    <option value="0" {{isset($estimate_quotation) ? $estimate_quotation->ewt == 0 ? 'selected' : '' : ''}}></option>
                                                    <option value="0.01" {{isset($estimate_quotation) ? $estimate_quotation->ewt == 0.01 ? 'selected' : '' : ''}}>1%</option>
                                                    <option value="0.02" {{isset($estimate_quotation) ? $estimate_quotation->ewt == 0.02 ? 'selected' : '' : ''}}>2%</option>
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
                                                    <option value="percent" {{isset($estimate_quotation) ? $estimate_quotation->inv_discount_type == 'percent' ? 'selected' : '' : ''}}>Discount percentage</option>
                                                    <option value="value" {{isset($estimate_quotation) ? $estimate_quotation->inv_discount_type == 'value' ? 'selected' : '' : ''}}>Discount value</option>
                                                </select>
                                            </div>
                                            <div class="col-sm-2  padding-lr-1">
                                                <input class="form-control input-sm text-right number-input discount_txt compute" type="text" name="customer_discount" value="{{$estimate_quotation->est_discount_value or ''}}">
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
            <td><select class=" select-um" name="item_um[]"><option class="hidden" value="" /></select></td>
            <td><input class="text-center number-input txt-qty change-qty compute" type="text" name="item_qty[]"/></td>
            @if(isset($estimate_quotation))
                <td><input class="text-center number-input txt-backorder" type="text" value="" readonly="readonly"/></td>
                <td><input class="text-center number-input txt-remaining" type="text" value="" name="item_remaining[]" readonly="readonly"/></td>
                <td class="text-center">
                    <input type="hidden" name="item_status[]" class="item-status" value="0">
                </td>
                @endif
            @if($proposal_number)
                @if($fieldmen != 1)
                <td class="proposal-td">
                    <select class="form-control select-proposal" name="estline_proposal_number[]">
                      @include("member.accounting_transaction.customer.estimate_quotation.load_customer_proposal")  
                    </select>
                </td>
                @endif
            @endif
            <td><input class="text-right number-input txt-rate compute" type="text" name="item_rate[]"/></td>
            <td><input class="text-right txt-discount compute" type="text" name="item_discount[]"/></td>
            <td><textarea class="textarea-expand" type="text" name="item_remarks[]" ></textarea></td>
            <td><input class="text-right number-input txt-amount" type="text" name="item_amount[]"/></td>
            <td class="text-center">
                <input type="hidden"  name="item_taxable[]" class="taxable-input" value="">
                <input type="checkbox" class="taxable-check compute" value="1">
            </td>
            <td class="text-center remove-tr cursor-pointer"><i class="fa fa-trash-o" aria-hidden="true"></i></td>
        </tr>                                            
    </table>
</div>
@endsection
@section('script')
<script type="text/javascript" src="/assets/member/js/accounting_transaction/customer/estimate_quotation.js"></script>
@endsection