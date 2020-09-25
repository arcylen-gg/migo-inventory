@extends('member.layout')
@section('content')
<form class="global-submit form-to-submit-transfer" id="invoice_form" role="form" action="{{$action}}" method="POST" >
    <input type="hidden" class="token" name="_token" value="{{csrf_token()}}" >
    <input type="hidden" class="button-action" name="button_action" value="">
    <input type="hidden" name="sir_id" value="{{$sir_id or ''}}" >
    <input type="hidden" name="invoice_id" value="{{Request::input('id')}}" >
    <input type="hidden" id="keep_val" name="keep_val" value="" >

    <button class="drawer-toggle" type="button"> <i class="fa fa-angle-double-left"></i></button>

    <div class="drawer drawer-default">
        <div class="drawer-brand">Add to Sales Receipt</div>
        <nav class="drawer-nav">
            <div class="clearfix estimate-container">
                @include('member.load_ajax_data.load_estimate_so')
            </div>   
        </nav>
    </div>

<div class="drawer-overlay">
    <div class="panel panel-default panel-block panel-title-block" id="top">
        <div class="panel-heading">
            <div>
                <i class="fa fa-tags"></i>
                <h1>
                    <span class="page-title">Create Sales Receipt</span>
                    <small>
                    
                    </small>
                </h1>
                <!-- <button class="panel-buttons btn btn-custom-white pull-right" onclick="window.location='{{ URL::previous() }}'">Cancel</button>
                <button type="submit" class="panel-buttons btn btn-custom-primary pull-right" data-action="save-and-edit">Save</button>
                <button type="submit" class="panel-buttons btn btn-custom-white pull-right" data-action="save-and-new">Save and New</button> -->
                <div class="dropdown pull-right">
                    <div>
                        <a class="btn btn-custom-white" href="/member/customer/sales_receipt/list">Cancel</a>
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
                @if(isset($inv))
                <div class="pull-right">
                    <div class="dropdown">
                        <button class="btn btn-custom-white dropdown-toggle" type="button" data-toggle="dropdown">More
                        <span class="caret"></span></button>
                        <ul class="dropdown-menu">
                            <!-- <li class="dropdown-header">Dropdown header 1</li> -->
                            <li><a href="/member/accounting/journal/entry/sales-receipt/{{$inv->inv_id}}">Transaction Journal</a></li>
                            <!-- <li class="divider"></li> -->
                            <!-- <li class="dropdown-header">Dropdown header 2</li> -->
                            <li><a href="#">Void</a></li>
                        </ul>
                    </div>
                </div>
                @endif  
               <!--  <a href="javascript:" class="panel-buttons btn btn-custom-white pull-right popup" link="/member/item/add" size="lg">Save</a> -->
            </div>
        </div>
    </div>

    <div class="panel panel-default panel-block panel-title-block panel-gray load-data">
        <div class="data-container" >
            <div class="tab-content">
                <div class="row">
                    <div class="col-md-12" style="padding: 30px;">
                        <!-- START CONTENT -->
                        <div style="padding-bottom: 10px; margin-bottom: 10px;">
                            <div class="row clearfix">
                            </div>
                        </div>
                        <div style="border-bottom: 1px solid #ddd; padding-bottom: 10px; margin-bottom: 10px;">
                            <div class="row clearfix">
                                <div class="col-sm-4">
                                    <select class="form-control droplist-customer input-sm pull-left" name="inv_customer_id" data-placeholder="Select a Customer" required>
                                        @include('member.load_ajax_data.load_customer', ['customer_id' => isset($inv) ? $inv->inv_customer_id : (isset($c_id) ? $c_id : '') ]);
                                    </select>
                                </div>
                                <div class="col-sm-4">
                                    <input type="text" class="form-control input-sm customer-email" name="inv_customer_email" placeholder="E-Mail (Separate E-Mails with comma)" value="{{$inv->inv_customer_email or ''}}"/>
                                </div>
                                <div class="col-sm-4">    
                                    <label for="new_invoice_id" class="col-md-4">Invoice No:</label>
                                    <div class="col-md-8">
                                        <input type="text" class="form-control input-sm" name="new_invoice_id" value="{{$inv->new_inv_id or $new_inv_id}}">
                                    </div>
                                </div>
                            </div>
                        </div>                          
                        <div class="row clearfix">
                            <div class="col-sm-3">
                                <label>Billing Address</label>
                                <textarea class="form-control input-sm textarea-expand" name="inv_customer_billing_address" placeholder="">{{$inv->inv_customer_billing_address or ''}}</textarea>
                            </div>
                            <div class="col-sm-2 hidden">  
                                <label>Terms</label>
                                <select class="form-control input-sm droplist-terms" name="inv_terms_id">
                                    @include("member.load_ajax_data.load_terms")
                                </select>
                            </div>
                            <div class="col-sm-2">
                                <label>Date</label>
                                <input type="text" class="datepicker form-control input-sm" name="inv_date" value="{{$inv->inv_date or date('m/d/y')}}"/>
                            </div>
                            <div class="col-sm-2 hidden">
                                <label>Due Date</label>
                                <input type="text" class="datepicker form-control input-sm" name="inv_due_date" value="{{$inv->inv_due_date or date('m/d/y')}}" />
                            </div>
                        </div>
                        
                        <div class="row clearfix draggable-container">
                            <div class="table-responsive">
                                <div class="col-sm-12">
                                    <table class="digima-table">
                                        <thead>
                                            <tr>
                                                <th style="" ></th>
                                                <th style="">Service Date</th>
                                                @include("member.load_ajax_data.load_th_serial_number")
                                                <th style="" class="text-right">#</th>
                                                <th style="width: 200px">Product/Service</th>
                                                <th style="">Description</th>
                                                <th style="">U/M</th>
                                                <th style="">Qty</th>
                                                <th style="">Rate</th>
                                                <th style="">Discount</th>
                                                <th style="">Remark</th>
                                                <th style="">Amount</th>
                                                <th style="">Tax</th>
                                                <th width="10"></th>
                                            </tr>
                                        </thead>
                                        <tbody class="tbody-item">     
                                            @if(isset($inv))
                                                @foreach($_invline as $invline)
                                                    <tr class="tr-draggable">
                                                        <td class="text-center cursor-move move"><i class="fa fa-th-large colo-mid-dark-gray"></i></td>

                                                        <td><input type="text" class="for-datepicker" name="invline_service_date[]" value="{{$invline->invline_service_date}}" /></td>
                                                        @if(isset($serial)) 
                                                        <td>
                                                            <textarea class="txt-serial-number" name="serial_number[]">{{$invline->serial_number}}</textarea>
                                                        </td>
                                                        @endif

                                                        <td class="invoice-number-td text-right">1</td>
                                                        <td>
                                                            <input type="hidden" name="invline_ref_name[]" value="{{$invline->invline_ref_name}}">
                                                            <input type="hidden" name="invline_ref_id[]" value="{{$invline->invline_ref_id}}">
                                                            <select class="form-control select-item droplist-item input-sm pull-left {{$invline->invline_item_id}}" name="invline_item_id[]" required>
                                                                @include("member.load_ajax_data.load_item_category", ['add_search' => "", 'item_id' => $invline->invline_item_id])
                                                            </select>
                                                        </td>
                                                        <td>
                                                            @if($pis)
                                                                <textarea class="textarea-expand txt-desc" name="invline_description[]" readonly="true">{{$invline->invline_service_date}}</textarea>
                                                            @else
                                                                <textarea class="textarea-expand txt-desc" name="invline_description[]" value="{{$invline->invline_service_date}}"></textarea>
                                                            @endif
                                                        </td>
                                                        <td>
                                                            <select class="1111 droplist-um select-um {{isset($invline->multi_id) ? 'has-value' : ''}}" name="invline_um[]">
                                                                @if($invline->invline_um)
                                                                    @include("member.load_ajax_data.load_one_unit_measure", ['item_um_id' => $invline->multi_um_id, 'selected_um_id' => $invline->invline_um])
                                                                @else
                                                                    <option class="hidden" value="" />
                                                                @endif
                                                            </select>
                                                        </td>
                                                        <td><input class="text-center number-input txt-qty compute" type="text" name="invline_qty[]" value="{{$invline->invline_qty}}" /></td>
                                                        <td><input class="text-right number-input txt-rate compute" type="text" name="invline_rate[]" value="{{$invline->invline_rate}}" /></td>
                                                        <td><input class="text-right txt-discount compute" type="text" name="invline_discount[]" value="{{$invline->invline_discount}}" /></td>
                                                        <td><textarea class="textarea-expand" type="text" name="invline_discount_remark[]" value="{{$invline->invline_discount_remark}}"></textarea></td>
                                                        <td><input class="text-right number-input txt-amount" type="text" name="invline_amount[]" value="{{$invline->invline_amount}}" /></td>
                                                        <td class="text-center">
                                                            <input type="hidden" class="invline_taxable" name="invline_taxable[]" value="{{$invline->taxable}}" >
                                                            <input type="checkbox" name="" class="taxable-check compute" {{$invline->taxable == 1 ? 'checked' : ''}}>
                                                        </td>
                                                        <td class="text-center remove-tr cursor-pointer"><i class="fa fa-trash-o" aria-hidden="true"></i></td>
                                                    </tr>
                                                @endforeach
                                            @else  
                                        </tbody>
                                        <tbody class="draggable tbody-item estimate-tbl">
                                                @include("member.load_ajax_data.load_est_session_item")
                                                <tr class="tr-draggable">
                                                    <td class="text-center cursor-move move" ><i class="fa fa-th-large colo-mid-dark-gray"></i></td>
                                                    <td><input type="text" class="datepicker" name="invline_service_date[]"/></td>
                                                    @include("member.load_ajax_data.load_td_serial_number")
                                                    <td class="invoice-number-td text-right">2</td>
                                                    <td>
                                                        <input type="hidden" name="invline_ref_name[]" value="">
                                                        <input type="hidden" name="invline_ref_id[]" value="">
                                                        <select class="22222 form-control select-item droplist-item input-sm pull-left" name="invline_item_id[]" >
                                                            @include("member.load_ajax_data.load_item_category", ['add_search' => ""])
                                                            <option class="hidden" value="" />
                                                        </select>
                                                    </td>
                                                    <td>
                                                        @if($pis)
                                                            <textarea class="textarea-expand txt-desc" name="invline_description[]" readonly="true"></textarea>
                                                        @else
                                                            <textarea class="textarea-expand txt-desc" name="invline_description[]"></textarea>
                                                        @endif
                                                    </td>
                                                    <td><select class="3333 droplist-um select-um" name="invline_um[]"><option class="hidden" value="" /></select></td>
                                                    <td><input class="text-center number-input txt-qty compute" type="text" name="invline_qty[]"/></td>
                                                    <td><input class="text-right number-input txt-rate compute" type="text" name="invline_rate[]"/></td>
                                                    <td><input class="text-right txt-discount compute" type="text" name="invline_discount[]"/></td>
                                                    <td><input class="text-right number-input" type="text" name="invline_discount_remark[]"/></td>
                                                    <td><input class="text-right number-input txt-amount" type="text" name="invline_amount[]"/></td>
                                                    <td class="text-center">
                                                        <input type="hidden" class="invline_taxable" name="invline_taxable[]" value="" >
                                                        <input type="checkbox" name="" class="taxable-check compute" value="checked">
                                                    </td>
                                                    <td class="text-center remove-tr cursor-pointer"><i class="fa fa-trash-o" aria-hidden="true"></i></td>
                                                </tr>
                                            @endif

                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                        <div class="row clearfix">
                            <div class="col-sm-3">
                                <label>Message Displayed on Invoice</label>
                                <textarea class="form-control input-sm textarea-expand" name="inv_message" placeholder="">{{$inv->inv_message or ''}}</textarea>
                            </div>
                            <div class="col-sm-3">
                                <label>Statement Memo</label>
                                <textarea class="form-control input-sm textarea-expand" name="inv_memo" placeholder="">{{$inv->inv_memo or ''}}</textarea>
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
                                            <div class="col-sm-6 col-sm-offset-3  padding-lr-1">
                                                <label>EWT</label>
                                            </div>
                                            <div class="col-sm-3  padding-lr-1">
                                                <!-- <input class="form-control input-sm text-right ewt_value number-input" type="text" name="ewt"> -->
                                                <select class="form-control input-sm ewt-value compute" name="ewt">  
                                                    <option value="0" {{isset($inv) ? $inv->ewt == 0 ? 'selected' : '' : ''}}></option>
                                                    <option value="0.01" {{isset($inv) ? $inv->ewt == 0.01 ? 'selected' : '' : ''}}>1%</option>
                                                    <option value="0.02" {{isset($inv) ? $inv->ewt == 0.02 ? 'selected' : '' : ''}}>2%</option>
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
                                                <select class="form-control input-sm compute discount_selection" name="inv_discount_type">  
                                                    <option value="percent" {{isset($inv) ? $inv->inv_discount_type == 'percent' ? 'selected' : '' : ''}}>Discount percentage</option>
                                                    <option value="value" {{isset($inv) ? $inv->inv_discount_type == 'value' ? 'selected' : '' : ''}}>Discount value</option>
                                                </select>
                                            </div>
                                            <div class="col-sm-2  padding-lr-1">
                                                <input class="form-control input-sm text-right number-input discount_txt compute" type="text" name="inv_discount_value" value="{{$inv->inv_discount_value or ''}}">
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
                                                <select class="form-control input-sm tax_selection compute" name="taxable">  
                                                    <option value="0" {{isset($inv) ? $inv->taxable == 0 ? 'selected' : '' : ''}}>No Tax</option>
                                                    <option value="1" {{isset($inv) ? $inv->taxable == 1 ? 'selected' : '' : ''}}>Vat (12%)</option>
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
                                     @if(isset($pis) && $pis != 0)
                                     Invoice
                                     @endif
                                      Total
                                    </div>
                                    <div class="col-md-5 text-right digima-table-value total">
                                        <input type="hidden" name="overall_price" class="total-amount-input" />
                                        PHP&nbsp;<span class="total-amount">0.00</span>
                                    </div>
                                </div>
                                @if(isset($inv))
                                    <div class="row">
                                        <div class="col-md-7 text-right digima-table-label">
                                            Payment Appplied
                                        </div>
                                        <div class="col-md-5 text-right digima-table-value">
                                            <input type="hidden" name="payment-receive" class="payment-receive-input" />
                                            PHP&nbsp;<span class="payment-applied">{{$inv->inv_payment_applied}}</span>
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
                        
                        <!--PIS CM here -->

                        @if(isset($pis) && $pis != 0)
                        <div class="row clearfix">
                            <div class="form-group">
                                <div class="col-md-12">
                                    <label>
                                        <h3>
                                            <input {{isset($inv->credit_memo) != 0 ? 'checked' : ''}} type="checkbox" onclick="toggle_returns('.returns-class', this)" value="returns" class="returns-check"  value="returns" name="returns"> Returns 
                                        </h3>
                                    </label>
                                </div>
                            </div>
                            <div class="returns-class" style="{{isset($inv->credit_memo) == null ? 'display:none' : ''}}"  >
                                <div class="row clearfix draggable-container">
                                     <div class="table-responsive">
                                        <div class="col-sm-12">
                                            <table class="digima-table">
                                                <thead>
                                                    <tr>
                                                        <th style="width: 10px;" ></th>
                                                        <th style="width: 15px;" class="text-right">#</th>
                                                        <th style="width: 180px;">Product/Service</th>
                                                        <th>Description</th>
                                                        <th style="width: 120px;">U/M</th>
                                                        <th style="width: 70px;">Qty</th>
                                                        <th style="width: 100px;">Rate</th>
                                                        <!-- <th style="width: 100px;">Discount</th> -->
                                                        <!-- <th style="width: 100px;">Remark</th> -->
                                                        <th style="width: 100px;">Amount</th>
                                                        <!-- <th style="width: 10px;">Tax</th> -->
                                                        <th width="10"></th>
                                                    </tr>
                                                </thead>
                                                <tbody class="cm-draggable tbody-item-cm">     
                                                    @if(isset($_cmline))
                                                        @foreach($_cmline as $cmline)
                                                            <tr class="tr-cm-draggable">
                                                                <td class="text-center cursor-move move"><i class="fa fa-th-large colo-mid-dark-gray"></i></td>

                                                                <td class="cm-number-td text-right">1</td>
                                                                <td>
                                                                    <select class="form-control select-item droplist-item-cm input-sm pull-left {{$cmline->cmline_item_id}}" name="cmline_item_id[]" required>
                                                                    </select>
                                                                </td>
                                                                <td>
                                                                    @if($pis)
                                                                        <textarea class="textarea-expand txt-desc" name="cmline_description[]" readonly="true" >{{$cmline->cmline_service_date}}</textarea>
                                                                    @else
                                                                        <textarea class="textarea-expand txt-desc" name="cmline_description[]">{{$cmline->cmline_service_date}}</textarea>
                                                                    @endif
                                                                </td>
                                                                <td>
                                                                    <select class="1111 droplist-um-cm select-um {{isset($cmline->multi_id) ? 'has-value' : ''}}" name="cmline_um[]">
                                                                        @if($cmline->cmline_um)
                                                                            @include("member.load_ajax_data.load_one_unit_measure", ['item_um_id' => $cmline->multi_um_id, 'selected_um_id' => $cmline->cmline_um])
                                                                        @else
                                                                            <option class="hidden" value="" />
                                                                        @endif
                                                                    </select>
                                                                </td>
                                                                <td><input class="text-center number-input txt-qty compute" type="text" name="cmline_qty[]" value="{{$cmline->cmline_qty}}" /></td>
                                                                <td><input class="text-right number-input txt-rate compute" type="text" name="cmline_rate[]" value="{{$cmline->cmline_rate}}" /></td>
                                                               <!-- <td><input class="text-right txt-discount compute" type="text" name="cmline_discount[]" value="" /> </td>
                                                                <td><textarea class="textarea-expand" type="text" name="cmline_discount_remark[]" value=""></textarea></td> -->
                                                                <td><input class="text-right number-input txt-amount" type="text" name="cmline_amount[]" value="{{$cmline->cmline_amount}}" /></td>
                                                               <!--  <td class="text-center">
                                                                    <input type="hidden" class="cmline_taxable" name="cmline_taxable[]" value="" >
                                                                    <input type="checkbox" name="" class="taxable-check" >
                                                                </td> -->
                                                                <td class="text-center remove-tr cursor-pointer"><i class="fa fa-trash-o" aria-hidden="true"></i></td>
                                                            </tr>
                                                        @endforeach
                                                    @endif                               
                                                        <tr class="tr-cm-draggable">
                                                            <td class="text-center cursor-move move"><i class="fa fa-th-large colo-mid-dark-gray"></i></td>

                                                            <td class="cm-number-td text-right">1</td>
                                                            <td>
                                                                <select class="1111 form-control select-item droplist-item-cm input-sm pull-left" name="cmline_item_id[]" >
                                                                    @include("member.load_ajax_data.load_item", ['add_search' => "", "_item" => $_cm_item])
                                                                    <option class="hidden" value="" />
                                                                </select>
                                                            </td>
                                                            <td>
                                                                @if($pis)
                                                                    <textarea class="textarea-expand txt-desc" readonly="true" name="cmline_description[]"></textarea>
                                                                @else   
                                                                    <textarea class="textarea-expand txt-desc" name="cmline_description[]"></textarea>
                                                                @endif
                                                            </td>
                                                            <td><select class="2222 droplist-um-cm select-um" name="cmline_um[]"><option class="hidden" value="" /></select></td>
                                                            <td><input class="text-center number-input txt-qty compute" type="text" name="cmline_qty[]"/></td>
                                                            <td><input class="text-right number-input txt-rate compute" type="text" name="cmline_rate[]"/></td>
                                                           <!--  <td><input class="text-right txt-discount compute" type="text" name="cmline_discount[]"/></td>
                                                            <td><textarea class="textarea-expand" type="text" name="cmline_discount_remark[]" ></textarea></td> -->
                                                            <td><input class="text-right number-input txt-amount" type="text" name="cmline_amount[]"/></td>
                                                           <!--  <td class="text-center">
                                                                <input type="hidden" class="cmline_taxable" name="cmline_taxable[]" value="" >
                                                                <input type="checkbox" name="" class="taxable-check" value="checked">
                                                            </td> -->
                                                            <td class="text-center remove-tr cursor-pointer"><i class="fa fa-trash-o" aria-hidden="true"></i></td>
                                                        </tr>
                                                            
                                                        <tr class="tr-cm-draggable">
                                                            <td class="text-center cursor-move move" ><i class="fa fa-th-large colo-mid-dark-gray"></i></td>
                                                            <td class="cm-number-td text-right">2</td>
                                                            <td>
                                                                <select class="22222 form-control select-item droplist-item-cm input-sm pull-left" name="cmline_item_id[]" >
                                                                    @include("member.load_ajax_data.load_item", ['add_search' => "","_item" => $_cm_item])
                                                                    <option class="hidden" value="" />
                                                                </select>
                                                            </td>
                                                            <td>
                                                                @if($pis)
                                                                    <label class="textarea-expand txt-desc" name="cmline_description[]"></label>
                                                                @else
                                                                    <textarea class="textarea-expand txt-desc" name="cmline_description[]"></textarea>
                                                                @endif
                                                            </td>
                                                            <td><select class="3333 droplist-um-cm select-um" name="cmline_um[]"><option class="hidden" value="" /></select></td>
                                                            <td><input class="text-center number-input txt-qty compute" type="text" name="cmline_qty[]"/></td>
                                                            <td><input class="text-right number-input txt-rate compute" type="text" name="cmline_rate[]"/></td>
                                                           <!--  <td><input class="text-right txt-discount compute" type="text" name="cmline_discount[]"/></td>
                                                            <td><input class="text-right number-input" type="text" name="cmline_discount_remark[]"/></td> -->
                                                            <td><input class="text-right number-input txt-amount" type="text" name="cmline_amount[]"/></td>
                                                           <!--  <td class="text-center">
                                                                <input type="hidden" class="cmline_taxable" name="cmline_taxable[]" value="" >
                                                                <input type="checkbox" name="" class="taxable-check" value="checked">
                                                            </td> -->
                                                            <td class="text-center remove-tr cursor-pointer"><i class="fa fa-trash-o" aria-hidden="true"></i></td>
                                                        </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row clearfix">
                            <div class="col-sm-6 col-sm-offset-6 text-right">
                                <div class="row">
                                    <div class="col-md-7 text-right digima-table-label">
                                        Returns Sub Total
                                    </div>
                                    <div class="col-md-5 text-right digima-table-value">
                                        <input type="hidden" name="subtotal_price_returns" class="subtotal-amount-input-returns" />
                                        PHP&nbsp;<span class="sub-total-returns">0.00</span>
                                    </div>
                                </div> 
                                <div class="row">
                                    <div class="col-md-7 text-right digima-table-label">
                                        <h3>Total<h3>
                                    </div>
                                    <div class="col-md-5 text-right digima-table-value total">
                                        <h3>
                                            <input type="hidden" name="overall_price_with_return" class="total-amount-input-with-returns" />
                                            PHP&nbsp;<span class="total-amount-with-returns">0.00</span>
                                        </h3>
                                    </div>
                                </div>
                            </div>
                        </div>

                        @endif
                        <!-- END CONTENT -->
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
            <td class="text-center cursor-move move" ><i class="fa fa-th-large colo-mid-dark-gray"></i></td>
            <td><input type="text" class="for-datepicker"  name="invline_service_date[]"/></td>
            @include("member.load_ajax_data.load_td_serial_number")
            <td class="invoice-number-td text-right">2</td>
            <td>
                <input type="hidden" name="invline_ref_name[]" value="">
                <input type="hidden" name="invline_ref_id[]" value="">
                <select class="form-control select-item input-sm pull-left" name="invline_item_id[]">
                    @include("member.load_ajax_data.load_item_category", ['add_search' => ""])
                    <option class="hidden" value="" />
                </select>
            </td>
            <td>
                @if($pis)
                    <textarea class="textarea-expand txt-desc" readonly="true" name="invline_description[]"></textarea>
                @else
                    <textarea class="textarea-expand txt-desc" name="invline_description[]"></textarea>
                @endif
            </td>
            <td><select class="select-um" name="invline_um[]"><option class="hidden" value="" /></select></td>
            <td><input class="text-center number-input txt-qty compute" type="text" name="invline_qty[]"/></td>
            <td><input class="text-right number-input txt-rate compute" type="text" name="invline_rate[]"/></td>
            <td><input class="text-right txt-discount compute" type="text" name="invline_discount[]"/></td>
            <td><textarea class="textarea-expand" type="text" name="invline_discount_remark[]" ></textarea></td>
            <td><input class="text-right number-input txt-amount" type="text" name="invline_amount[]"/></td>
            <td class="text-center">
                <input type="hidden" class="invline_taxable" name="invline_taxable[]" value="" >
                <input type="checkbox" name="" class="taxable-check compute" value="checked">
            </td>
            <td class="text-center remove-tr cursor-pointer"><i class="fa fa-trash-o" aria-hidden="true"></i></td>
        </tr>
    </table>
</div>

<div class="div-script-cm">
    <table class="div-item-row-script-cm hide">
        <tr class="tr-cm-draggable">
            <td class="text-center cursor-move move" ><i class="fa fa-th-large colo-mid-dark-gray"></i></td>
            <td class="cm-number-td text-right">2</td>
            <td>
                <select class="22222 form-control select-item input-sm pull-left" name="cmline_item_id[]" >
                    @include("member.load_ajax_data.load_item", ['add_search' => "","_item" => $_cm_item])
                    <option class="hidden" value="" />
                </select>
            </td>
            <td>
                @if($pis)
                    <textarea class="textarea-expand txt-desc" readonly="true" name="cmline_description[]"></textarea>
                @else
                    <textarea class="textarea-expand txt-desc" name="cmline_description[]"></textarea>
                @endif
            </td>
            <td><select class="3333 select-um" name="cmline_um[]"><option class="hidden" value="" /></select></td>
            <td><input class="text-center number-input txt-qty compute" type="text" name="cmline_qty[]"/></td>
            <td><input class="text-right number-input txt-rate compute" type="text" name="cmline_rate[]"/></td>
            <td><input class="text-right number-input txt-amount" type="text" name="cmline_amount[]"/></td>
            <td class="text-center remove-tr cursor-pointer"><i class="fa fa-trash-o" aria-hidden="true"></i></td>
        </tr>
    </table>
</div>
@endsection


@section('script')
<script type="text/javascript">
    @if(Session::has('success'))
        toastr.success('{{Session::get('success')}}');
    @endif
</script>
<script type="text/javascript" src="/assets/member/js/textExpand.js"></script>
<script type="text/javascript" src="/assets/member/js/draggable_row.js"></script>
<script type="text/javascript" src="/assets/member/bootstrap_drawer/cooker.drawer.js"></script>
<script type="text/javascript" src="/assets/member/js/customer_invoice.js"></script>
<script type="text/javascript">
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
.est-style
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