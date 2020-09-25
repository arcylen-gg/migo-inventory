@extends('member.layout')
@section('content')

<form class="global-submit" action="{{$action or ''}}" method="post">
<div class="panel panel-default panel-block panel-title-block">
    <input type="hidden" class="button-action" name="button_action" value="">
    <input type="hidden" name="credit_memo_id" value="{{Request::input('id')}}">
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
                    <a class="btn btn-custom-white" href="/member/transaction/credit_memo">Cancel</a>
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
                                <input type="text" class="form-control" name="transaction_refnumber" value="{{isset($credit_memo) ? $credit_memo->transaction_refnum : $transaction_refnum}}">
                            </div>
                        </div>
                    </div>
                    <div style="border-bottom: 1px solid #ddd; padding-bottom: 10px; margin-bottom: 10px;">
                        <div class="row clearfix">
                            <div class="col-sm-4">
                                <select class="form-control droplist-customer input-sm pull-left" name="customer_id" data-placeholder="Select a Customer" required>
                                    @include('member.load_ajax_data.load_customer', ['customer_id' => isset($credit_memo) ? $credit_memo->cm_customer_id : (isset($c_id) ? $c_id : '') ]);
                                </select>
                            </div>
                            <div class="col-sm-4">
                                <input type="text" class="form-control input-sm customer-email" name="customer_email" placeholder="E-Mail (Separate E-Mails with comma)" value="{{$credit_memo->cm_customer_email or ''}}"/>
                            </div> 
                            <div class="col-sm-4">
                                <div class="pull-right">
                                    <select class="form-control" name="use_credit">
                                      <option value="retain_credit">Retain as Available Credit</option>
                                      <option value="refund">Give a Refund</option>
                                      <option value="apply">Apply to an Invoice</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>                          
                    <div class="row clearfix">
                        <div class="col-sm-3">
                            <label>Billing Address</label>
                            <textarea class="form-control input-sm textarea-expand customer-billing-address" name="customer_address" placeholder="">{{$credit_memo->cm_customer_billing_address or ''}}</textarea>
                        </div>
                        <div class="col-sm-2">
                            <label>Date</label>
                            <input type="text" class="datepicker form-control input-sm" name="transaction_date" value="{{$credit_memo->cm_date or date('m/d/y')}}"/>
                        </div>
                    </div>
                    
                    <div class="row clearfix draggable-container">
                        <div class="table-responsive">
                            <div class="col-sm-12">
                                <table class="digima-table">
                                    <thead>
                                        <tr>
                                            <th style="" class="text-right">#</th>
                                            <th style="width: 300px">Product/Service</th>
                                            <th style="">Description</th>
                                            @if($check_settings == 1)
                                                <th style="">BIN</th>
                                            @endif
                                            <th style="">U/M</th>
                                            <th style="">Qty</th>
                                            <th style="">Rate</th>
                                            <th style="">Amount</th>
                                            <th width="10"></th>
                                        </tr>
                                    </thead>
                                    <tbody class="draggable tbody-item">   
                                        @if(isset($credit_memo))
                                            @foreach($credit_memo_item as $key => $cm_item)
                                            <tr class="tr-draggable">
                                                <td class="invoice-number-td text-right">1</td>
                                                @if($check_barcode == '1')
                                                <td class="item-select-td">
                                                    <input class="form-control input-sm pull-left item-textbox hidden" value="{{$cm_item->item_barcode}}" onkeypress="event_search($(this), event)" type="text"/>
                                                    <select class="1111 form-control select-item droplist-item input-sm pull-left item-select {{$cm_item->cmline_item_id}}" name="item_id[]" required >
                                                        @include("member.load_ajax_data.load_item_category", ['add_search' => "", 'item_id' => $cm_item->cmline_item_id])
                                                        <option class="hidden" value="" />
                                                    </select>
                                                </td>
                                                @else
                                                <td>
                                                    <select class="1111 form-control select-item droplist-item input-sm pull-left " name="item_id[]" >
                                                        @include("member.load_ajax_data.load_item_category", ['add_search' => "", 'item_id' => $cm_item->cmline_item_id])
                                                        <option class="hidden" value="" />
                                                    </select>
                                                    
                                                </td>
                                                @endif
                                                <td><textarea class="textarea-expand txt-desc" name="item_description[]">{{$cm_item->cmline_description}}</textarea></td>
                                                @if($check_settings == 1)
                                                <td>
                                                    <select class="form-control droplist-sub-warehouse select-sub-warehouse input-sm" name="item_sub_warehouse[]" >
                                                        @include('member.warehousev2.load_sub_warehouse_v2_select', ['_bin_warehouse' => $_bin_item_warehouse[$key]])
                                                        <option class="hidden" value="" />
                                                    </select>
                                                </td>
                                                @endif
                                                <td><select class="droplist-um select-um {{isset($cm_item->multi_id) ? 'has-value' : ''}}" name="item_um[]">
                                                    @if($cm_item->invline_um)
                                                        @include("member.load_ajax_data.load_one_unit_measure", ['item_um_id' => $cm_item->multi_um_id, 'selected_um_id' => $cm_item->cmline_um])
                                                    @else
                                                        <option class="hidden" value="" />
                                                    @endif
                                                </select></td>
                                                <td><input class="text-center number-input txt-qty compute" value="{{$cm_item->cmline_qty}}" type="text" name="item_qty[]"/></td>
                                                <td><input class="text-right number-input txt-rate compute" value="{{$cm_item->cmline_rate}}" type="text" name="item_rate[]"/></td>
                                                <td><input class="text-right number-input txt-amount" value="{{$cm_item->cmline_amount}}" type="text" name="item_amount[]"/></td>
                                                <td class="text-center remove-tr cursor-pointer"><i class="fa fa-trash-o" aria-hidden="true"></i></td>
                                            </tr>
                                            @endforeach
                                        @endif                              
                                        <tr class="tr-draggable">
                                            <td class="invoice-number-td text-right">1</td>
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
                                            @if($check_settings == 1)
                                            <td>
                                                <select class="form-control droplist-sub-warehouse select-sub-warehouse input-sm" name="item_sub_warehouse[]" >
                                                    @include('member.warehousev2.load_sub_warehouse_v2_select')
                                                    <option class="hidden" value="" />
                                                </select>
                                            </td>
                                            @endif
                                            <td><select class="droplist-um select-um" name="item_um[]"><option class="hidden" value="" /></select></td>
                                            <td><input class="text-center number-input txt-qty compute" type="text" name="item_qty[]"/></td>
                                            <td><input class="text-right number-input txt-rate compute" type="text" name="item_rate[]"/></td>
                                            <td><input class="text-right number-input txt-amount" type="text" name="item_amount[]"/></td>
                                            <td class="text-center remove-tr cursor-pointer"><i class="fa fa-trash-o" aria-hidden="true"></i></td>
                                        </tr>
                                                
                                        <tr class="tr-draggable">
                                            <td class="invoice-number-td text-right">2</td>
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
                                            @if($check_settings == 1)
                                            <td>
                                                <select class="form-control droplist-sub-warehouse select-sub-warehouse input-sm" name="item_sub_warehouse[]" >
                                                    @include('member.warehousev2.load_sub_warehouse_v2_select')
                                                    <option class="hidden" value="" />
                                                </select>
                                            </td>
                                            @endif
                                            <td><select class="droplist-um select-um" name="item_um[]"><option class="hidden" value="" /></select></td>
                                            <td><input class="text-center number-input txt-qty compute" type="text" name="item_qty[]"/></td>
                                            <td><input class="text-right number-input txt-rate compute" type="text" name="item_rate[]"/></td>
                                            <td><input class="text-right number-input txt-amount" type="text" name="item_amount[]"/></td>
                                            <td class="text-center remove-tr cursor-pointer"><i class="fa fa-trash-o" aria-hidden="true"></i></td>
                                        </tr>
                                                
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    <div class="row clearfix">
                        <div class="col-sm-3">
                            <label>Message Displayed on Credit Memo</label>
                            <textarea class="form-control input-sm textarea-expand" name="customer_message" placeholder="">{{$credit_memo->cm_message or ''}}</textarea>
                        </div>
                        <div class="col-sm-3">
                            <label>Statement Memo</label>
                            <textarea class="form-control input-sm textarea-expand" name="customer_memo" placeholder="">{{$credit_memo->cm_memo or ''}}</textarea>
                        </div>
                        <div class="col-sm-6">
                            <!-- <div class="row">
                                <div class="col-md-7 text-right digima-table-label">
                                    Sub Total
                                </div>
                                <div class="col-md-5 text-right digima-table-value">
                                    <input type="hidden" name="subtotal_price" class="subtotal-amount-input" />
                                    PHP&nbsp;<span class="sub-total">0.00</span>
                                </div>
                            </div>  -->
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

<div class="div-script">
    <table class="div-item-row-script hide">
        <tr class="tr-draggable">
            <td class="invoice-number-td text-right">1</td>
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
            <td><input class="text-right number-input txt-rate compute" type="text" name="item_rate[]"/></td>
            <td><input class="text-right number-input txt-amount" type="text" name="item_amount[]"/></td>
            <td class="text-center remove-tr cursor-pointer"><i class="fa fa-trash-o" aria-hidden="true"></i></td>
        </tr>                                                
    </table>
</div>
@endsection
@section('script')
<script type="text/javascript" src="/assets/member/js/accounting_transaction/customer/credit_memo.js"></script>
@endsection