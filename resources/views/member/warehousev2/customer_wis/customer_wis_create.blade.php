@extends('member.layout')
@section('content')

<form class="global-submit form-to-submit-add" action="{{$action}}" method="post">
    <input type="hidden" class="button-action" name="button_action" value="">

    <input type="hidden" name="cust_wis_id" value="{{ $wis->cust_wis_id or '' }}">
    <input type="hidden" name="" class="sales-id" value="{{ Request::input('ids') }}">
    <input type="hidden" name="sales_id" class="" value="{{ Request::input('si_id')}}">
    <input type="hidden" name="" class="monthly-budget-input" value="{{$monthly_budget or ''}}">
    <input type="hidden" name="c_id" class="c-id" value="{{$c_id or ''}}"/>
    <input type="hidden" class="transaction-status" disabled="false" value="">
    <input type="hidden" name="" class="barcode-check" value="{{$check_barcode or ''}}">
    <input type="hidden" name="" class="project-name" value="{{$project or ''}}">
<div class="panel panel-default panel-block panel-title-block" id="top">
    <div class="panel-heading">
        <div>
            <i class="fa fa-archive"></i>
            <h1>
                <span class="page-title">CREATE - Warehouse Issuance Slip</span>
            </h1>
            <div class="dropdown pull-right">
                <div class="hidden" style="width: 200px;padding: 10px;background-color: #DFF2BF;color:  #4F8A10; font-size: 20px; font-weight: bold; text-align: center;">POSTED</div>
                <div>
                    <a class="btn btn-custom-white" href="/member/transaction/wis">Cancel</a>
                    <button class="btn btn-primary dropdown-toggle" type="button" data-toggle="dropdown">Select Action
                    <span class="caret"></span></button>
                    <ul class="dropdown-menu  dropdown-menu-custom">
                      <li><a class="select-action" code="sclose">Save & Close</a></li>
                      <li><a class="select-action" code="sedit">Save & Edit</a></li>
                      <li><a class="select-action" code="sprint">Save & Print</a></li>
                      <li><a class="select-action" code="snew">Save & New</a></li>
                      <li><a class="select-action" code="sconfirm">Save & Confirm WIS</a></li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>

<input type="hidden" name="_token" value="{{csrf_token()}}">
<div class="panel panel-default panel-block panel-title-block">
    <div class="panel-body form-horizontal">
        <div class="form-group">
            <div class="col-md-4">
                <label>WIS Number</label>
                <input type="text" name="cust_wis_number" class="form-control" required value="{{isset($wis) ? $wis->transaction_refnum : $transaction_refnum}}">
            </div>
        </div>
        <div class="form-group">
            <div class="col-md-4">
                <select class="form-control droplist-customer input-sm pull-left" name="customer_id" data-placeholder="Select a Customer" required>
                    @include('member.load_ajax_data.load_customer', ['customer_id' => isset($wis) ? $wis->destination_customer_id : (isset($applied) ? $applied->inv_customer_id : (isset($c_id) ? $c_id : '')) ])
                </select>
            </div>
            <div class="col-md-4">
                 <input type="text" class="form-control input-sm customer-email" name="customer_email" placeholder="E-Mail (Separate E-Mails with comma)" value="{{isset($wis) ? $wis->cust_email : (isset($applied) ? $applied->inv_customer_email : '')}}"/>
            </div>
            <div class="col-sm-4 text-right open-transaction" style="display: none;">
                <h4><a class="popup popup-link-open-transaction" size="md" link="/member/customer/wis/load-transaction?customer_id="><i class="fa fa-handshake-o"></i> <span class="count-open-transaction">0</span> Open Transaction</a></h4>
            </div>
        </div>
        <div class="form-group">
            <div class="col-md-4">
                <label>Ship to</label>
                <div>
                    <textarea required class="form-control customer-billing-address" name="customer_address" value="">{{ isset($wis->destination_customer_address) ? $wis->destination_customer_address : (isset($applied) ? $applied->inv_customer_billing_address : '')}}</textarea>
                </div>
            </div>
            <div class="col-md-4">
                <label>Truck</label>
                <div> 
                    <select class="form-control select-truck" name="truck_id">
                        @include("member.load_ajax_data.load_truck", ['truck_id' => isset($wis) ? $wis->cust_wis_truck_id : ''])
                    </select>                    
                </div>
            </div>
            <div class="col-md-4">
                <label>Delivery Date</label>
                <input type="text" name="delivery_date" class="datepicker form-control input-sm" value="{{ isset($wis->cust_delivery_date) == ''? date('m/d/Y') : $wis->cust_delivery_date }}">
            </div>
        </div>
        <div class="form-group hide">
            <div class="col-md-12">
                <div class="load-item-table-pos-s"></div>
            </div>
        </div>

        <div class="form-group draggable-container">
            <div class="col-md-12">
                <div class="table">
                    <table class="digima-table">
                        <thead>
                            <tr>
                                <th style="width: 10px"" class="text-right">#</th>
                                @if($check_settings == 1)
                                    <th style="width: 200px">Product/Service</th>
                                    <th style="width: 300px">Description</th>
                                    <th style="width: 250px">Bin</th>
                                    <th style="width: 50px">U/M</th>
                                    <th style="width: 80px">Qty</th>
                                    <th style="width: 150px">Rate</th>
                                    <th style="width: 150px">Amount</th>
                                @else
                                    <th style="width: 200px">Product</th>
                                    <th style="">Description</th>
                                    <th style="">U/M</th>
                                    <th style="">Qty</th>
                                    <th style="">Rate</th>
                                    <th style="">Amount</th>
                                @endif
                                <th width="10"></th>
                            </tr>
                        </thead>
                        @include("member.accounting_transaction.loading_items")
                        <tbody class="applied-transaction-list">
                        </tbody>
                        <tbody class="draggable tbody-item">
                            @if(isset($wis))
                                @foreach($_wisline as $key => $wisline)
                                    <tr class="tr-draggable">
                                        <td class="invoice-number-td text-center">1</td>
                                        @if($check_barcode == '1')
                                        <td class="item-select-td">
                                            <input class="form-control input-sm pull-left item-textbox hidden" value="{{$wisline->item_barcode}}" onkeypress="event_search($(this), event)" type="text"/>
                                            <select class="1111 form-control select-item droplist-item input-sm pull-left item-select {{$wisline->itemline_item_id}}" name="item_id[]" required >
                                                @include("member.load_ajax_data.load_item_category", ['add_search' => "", 'item_id' => $wisline->itemline_item_id])
                                                <option class="hidden" value="" />
                                            </select>
                                        </td>
                                        @else
                                        <td>
                                            <select class="1111 form-control select-item droplist-item input-sm pull-left " name="item_id[]" >
                                                @include("member.load_ajax_data.load_item_category", ['add_search' => "", 'item_id' => $wisline->itemline_item_id])
                                                <option class="hidden" value="" />
                                            </select>
                                            
                                        </td>
                                        @endif
                                        <td><textarea class="form-control txt-desc" name="item_description[]">{{$wisline->itemline_description}}</textarea></td>
                                        @if($check_settings == 1)
                                        <td>
                                            <select class="form-control droplist-sub-warehouse select-sub-warehouse input-sm" name="item_sub_warehouse[]" >
                                                @include('member.warehousev2.load_sub_warehouse_v2_select', ['_bin_warehouse' => $_bin_item_warehouse[$key]])
                                                <option class="hidden" value="" />
                                            </select>
                                        </td>
                                        @endif
                                        <td>
                                            <select class="2222 droplist-um select-um {{isset($wisline->multi_id) ? 'has-value' : ''}}" name="item_um[]">
                                                @if($wisline->itemline_um)
                                                    @include("member.load_ajax_data.load_one_unit_measure", ['item_um_id' => $wisline->multi_um_id, 'selected_um_id' => $wisline->itemline_um])
                                                @else
                                                    <option class="hidden" value="" />
                                                @endif
                                            </select>
                                        </td>
                                        <td><input class="form-control number-input txt-qty text-center compute" type="text" name="item_qty[]" value="{{ $wisline->itemline_orig_qty}}" /></td>
                                        <td><input class="text-right number-input txt-rate" type="text" name="item_rate[]" value="{{ $wisline->itemline_rate}}" /></td>
                                        <td><input class="text-right number-input txt-amount" type="text" name="item_amount[]" value="{{ $wisline->itemline_amount}}" /></td>
                                        <td class="text-center remove-tr cursor-pointer">
                                            <i class="fa fa-trash-o" aria-hidden="true"></i>
                                            <input type="hidden" name="item_refname[]" value="{{$wisline->itemline_refname}}">
                                            <input type="hidden" name="item_refid[]" value="{{$wisline->itemline_refid}}">
                                        </td>
                                    </tr>
                                @endforeach
                            @endif
                            <tr class="tr-draggable">
                                <td class="invoice-number-td text-center">1</td>
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
                                <td><textarea class="form-control txt-desc" name="item_description[]"></textarea></td>
                                @if($check_settings == 1)
                                <td>
                                    <select class="form-control droplist-sub-warehouse select-sub-warehouse input-sm" name="item_sub_warehouse[]" >
                                        @include('member.warehousev2.load_sub_warehouse_v2_select')
                                        <option class="hidden" value="" />
                                    </select>
                                </td>
                                @endif
                                <td><select class="2222 droplist-um select-um" name="item_um[]"><option class="hidden" value="" /></select></td>
                                <td><input class="form-control number-input txt-qty text-center compute" type="text" name="item_qty[]"/></td>
                                <td><input class="text-right number-input txt-rate" type="text" name="item_rate[]"/></td>
                                <td><input class="text-right number-input txt-amount" type="text" name="item_amount[]"/></td>
                                <td class="text-center remove-tr cursor-pointer">
                                    <i class="fa fa-trash-o" aria-hidden="true"></i>
                                    <input type="hidden" name="item_refname[]">
                                    <input type="hidden" name="item_refid[]">
                                </td>
                            </tr>
                            <tr class="tr-draggable">
                                <td class="invoice-number-td text-center">2</td>
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
                                <td><textarea class="form-control txt-desc" name="item_description[]"></textarea></td>
                                @if($check_settings == 1)
                                <td>
                                    <select class="form-control droplist-sub-warehouse select-sub-warehouse input-sm" name="item_sub_warehouse[]" >
                                        @include('member.warehousev2.load_sub_warehouse_v2_select')
                                        <option class="hidden" value="" />
                                    </select>
                                </td>
                                @endif
                                <td><select class="2222 droplist-um select-um" name="item_um[]"><option class="hidden" value="" /></select></td>
                                <td><input class="form-control number-input txt-qty text-center compute" type="text" name="item_qty[]"/></td>
                                <td><input class="text-right number-input txt-rate" type="text" name="item_rate[]"/></td>
                                <td><input class="text-right number-input txt-amount" type="text" name="item_amount[]"/></td>
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
            <div class="col-md-5">
                <label>Remarks</label>
                <div>
                    <textarea required class="form-control remarks-wis" name="cust_wis_remarks">{{ isset($wis->cust_wis_remarks)? $wis->cust_wis_remarks : ''}}</textarea>
                </div>
            </div>
            <div class="col-md-7">
                <div class="row">
                    <div class="col-md-7 text-right digima-table-label">
                      Total
                    </div>
                    <div class="col-md-5 text-right digima-table-value total">
                        <input type="hidden" name="overall_price" class="total-amount-input" />
                        PHP&nbsp;<span class="total-amount">0.00</span>
                    </div>
                </div>
                @include("member.warehousev2.customer_wis.monthly_budget")
            </div>
        </div>
    </div>
</div>
</form>


<div class="div-item-offset">
    <table class="div-item-row-script-offset-item hide">
        <tr class="offset-listing">
            <td class="text-center"><i class="remove-btn-offset cursor-pointer fa fa-times" style="color: red"></i></td>
            <td class="text-left">
                <input type="hidden" class="offset-input-itemid" name="budgetline_item_id[]">
                <input type="hidden" class="offset-input-itemprice" name="budgetline_item_amount[]">
                <span class="offset-itemname"></span></td>
            <td class="text-right"><span class="offset-itemprice"></span></td>
        </tr>
    </table>
</div>
<div class="div-script">
    <table class="div-item-row-script-item hide">
        <tr class="tr-draggable">
            <td class="invoice-number-td text-center">2</td>
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
            <td><textarea class="form-control txt-desc" name="item_description[]"></textarea></td>
            @if($check_settings == 1)
            <td>
                <select class="form-control droplist-sub-warehouse select-sub-warehouse input-sm" name="item_sub_warehouse[]" >
                    @include('member.warehousev2.load_sub_warehouse_v2_select')
                    <option class="hidden" value="" />
                </select>
            </td>
            @endif
            <td><select class="2222 select-um select-um" name="item_um[]"><option class="hidden" value="" /></select></td>
            <td><input class="form-control number-input txt-qty text-center compute" type="text" name="item_qty[]"/></td>
            <td><input class="text-right number-input txt-rate" type="text" name="item_rate[]"/></td>
            <td><input class="text-right number-input txt-amount" type="text" name="item_amount[]"/></td>
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
<script type="text/javascript" src="/assets/member/js/warehouse/customer_wis_create.js"></script>
@endsection