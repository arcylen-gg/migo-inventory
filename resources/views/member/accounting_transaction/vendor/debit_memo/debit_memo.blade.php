@extends('member.layout')
@section('content')
<form class="global-submit" role="form" action="{{ $action or ''}}" method="post" >
    <input type="hidden" name="_token" value="{{csrf_token()}}">
    <input type="hidden" class="button-action" name="button_action" value="">
    <input type="hidden" name="dm_id" value="{{ $dm->db_id or ''}}">
    <input type="hidden" name="" class="po-id" value="{{ Request::input('po_id') or ''}}">
    <input type="hidden" class="transaction-status" disabled="false" value="">
    <div class="panel panel-default panel-block panel-title-block" id="top">
        <div class="panel-heading">
            <div>
                <i class="fa fa-tags"></i>
                <h1>
                    <span class="page-title">{{$page or ''}}</span>
                    <small>
                    
                    </small>
                </h1>
                <div class="dropdown pull-right">
                    <div class="hidden" style="width: 200px;padding: 10px;background-color: #DFF2BF;color:  #4F8A10; font-size: 20px; font-weight: bold; text-align: center;">POSTED</div>
                    <div>
                        <a class="btn btn-custom-white" href="/member/transaction/debit_memo">Cancel</a>
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
                @if(isset($dm))
                <div class="pull-right {{isset($accounting_module) ? ($accounting_module ? '' : 'hidden') : 'hidden'}}">
                    <div class="dropdown">
                        <button class="btn btn-custom-white dropdown-toggle" type="button" data-toggle="dropdown">More
                        <span class="caret"></span></button>
                        <ul class="dropdown-menu">
                            <li><a href="/member/accounting/journal/entry/debit-memo/{{$dm->db_id}}">Transaction Journal</a></li>
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
                <div class="col-md-12" style="padding: 30px;">
                    <!-- START CONTENT -->
                    <div style="padding-bottom: 10px; margin-bottom: 10px;">
                        <div class="row clearfix">
                            <div class="col-sm-4">
                                <label>Reference Number</label>
                                <input type="text" class="form-control" name="transaction_refnumber" value="{{ isset($dm->transaction_refnum) ? $dm->transaction_refnum : $transaction_refnum }}">
                            </div>
                        </div>
                    </div>
                    <div style="border-bottom: 1px solid #ddd; padding-bottom: 10px; margin-bottom: 10px;">
                        <div class="row clearfix">
                            <div class="col-sm-4">
                                <select class="form-control droplist-vendor input-sm pull-left" name="vendor_id" data-placeholder="Select a Vendor" required>
                                    @include('member.load_ajax_data.load_vendor', ['vendor_id' => isset($dm->db_vendor_id) ? $dm->db_vendor_id : (isset($po->po_vendor_id) ? $po->po_vendor_id : '')])
                                </select>
                            </div>
                            <div class="col-sm-4">
                                <input type="text" class="form-control input-sm vendor-email" name="vendor_email" placeholder="E-Mail (Separate E-Mails with comma)" value="{{isset($dm->db_vendor_email)? $dm->db_vendor_email : ''}}"/>
                            </div>
                            <div class="col-sm-4 text-right open-transaction" style="display: none;">
                                <h4><a class="popup popup-link-open-transaction" size="md" link="/member/transaction/debit_memo/load-transaction?vendor="><i class="fa fa-handshake-o"></i> <span class="count-open-transaction">0</span> Open Transaction</a></h4>
                            </div>
                        </div>
                    </div>
                    
                    <div class="row clearfix">
                        <div class="col-sm-2">
                            <label>Date</label>
                            <input type="text" class="datepicker form-control input-sm" name="transaction_date" value="{{isset($dm->db_date) ? date('m/d/Y', strtotime($dm->db_date)) : date('m/d/Y')}}"/>
                        </div>
                    </div>
                    
                    <div class="row clearfix draggable-container">
                        <div class="table-responsive">
                            <div class="col-sm-12">
                                <table class="digima-table">
                                    <thead >
                                        <tr>
                                            <th style="width: 15px;">#</th>
                                            <th style="width: 300px;">Product/Service</th>
                                            <th>Description</th>
                                            @if($check_settings == 1)
                                            <th style="width: 200px;">Bin</th>
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
                                        @if(isset($dm))
                                            @foreach($_dmline as $key => $dmline)
                                                <tr class="tr-draggable">
                                                    <td class="invoice-number-td text-right">1</td>
                                                    @if($check_barcode == '1')
                                                    <td class="item-select-td">
                                                        <input class="form-control input-sm pull-left item-textbox hidden" value="{{$dmline->item_barcode}}" onkeypress="event_search($(this), event)" type="text"/>
                                                        <select class="1111 form-control select-item droplist-item input-sm pull-left item-select {{$dmline->dbline_item_id}}" name="item_id[]" required >
                                                            @include("member.load_ajax_data.load_item_category", ['add_search' => "", 'item_id' => $dmline->dbline_item_id])
                                                            <option class="hidden" value="" />
                                                        </select>
                                                    </td>
                                                    @else
                                                    <td>
                                                        <select class="1111 form-control select-item droplist-item input-sm pull-left " name="item_id[]" >
                                                            @include("member.load_ajax_data.load_item_category", ['add_search' => "", 'item_id' => $dmline->dbline_item_id])
                                                            <option class="hidden" value="" />
                                                        </select>
                                                        
                                                    </td>
                                                    @endif
                                                    <td>
                                                        <textarea class="textarea-expand txt-desc" name="item_description[]">{{$dmline->dbline_description}}</textarea>
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
                                                        <select class="1111 droplist-um select-um {{isset($dmline->multi_id) ? 'has-value' : ''}}" name="item_um[]">
                                                            @if($dmline->dbline_um)
                                                                @include("member.load_ajax_data.load_one_unit_measure", ['item_um_id' => $dmline->multi_um_id, 'selected_um_id' => $dmline->dbline_um])
                                                            @else
                                                                <option class="hidden" value="" />
                                                            @endif
                                                        </select>
                                                    </td>
                                                    <td><input class="text-center number-input txt-qty compute" type="text" name="item_qty[]" value="{{$dmline->dbline_qty}}" /></td>
                                                    <td><input class="text-right number-input txt-rate compute" type="text" name="item_rate[]" value="{{$dmline->dbline_rate}}" /></td>
                                                    @if($dmline->dbline_discounttype == 'fixed')
                                                        <td><input class="text-right txt-discount compute" type="text" name="item_discount[]" value="{{$dmline->dbline_discount}}" /></td>
                                                    @else
                                                        <td><input class="text-right txt-discount compute" type="text" name="item_discount[]" value="{{$dmline->dbline_discount * 100}}%" /></td>
                                                    @endif
                                                    <td><input class="text-right number-input txt-amount" type="text" name="item_amount[]" value="{{$dmline->dbline_amount}}" /></td>
                                                    @if(isset($serial)) 
                                                    <td>
                                                        <textarea class="txt-serial-number" name="item_serialnumber[]">{{$dmline->serial_number}}</textarea>
                                                    </td>
                                                    @endif
                                                    <td class="text-center">
                                                        <input type="hidden" name="item_taxable[]" class="taxable-input" value="{{$dmline->dbline_taxable}}" >
                                                        <input type="checkbox" class="taxable-check" {{$dmline->dbline_taxable == 1 ? 'checked' : ''}}>
                                                    </td>
                                                    <td class="text-center remove-tr cursor-pointer"><i class="fa fa-trash-o" aria-hidden="true"></i></td>
                                                    
                                                    <input type="hidden" name="item_ref_name[]" value="{{$dmline->dbline_refname}}">
                                                    <input type="hidden" name="item_ref_id[]" value="{{$dmline->dbline_refid}}">
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
                                            <td><input class="text-center number-input txt-qty compute" type="text" name="item_qty[]"/></td>
                                            <td><input class="text-right number-input txt-rate compute" type="text" name="item_rate[]"/></td>
                                            <td><input class="text-right txt-discount compute" type="text" name="item_discount[]"/></td>
                                            <td><input class="text-right number-input txt-amount" type="text" name="item_amount[]"/></td>
                                            <td class="text-center">
                                                <input type="hidden" name="item_taxable[]" class="taxable-input" value="">
                                                <input type="checkbox" class="taxable-check compute" value="1">
                                            </td>
                                            <td class="text-center remove-tr cursor-pointer"><i class="fa fa-trash-o" aria-hidden="true"></i></td>
                                            <input type="hidden" name="item_ref_name[]">
                                            <input type="hidden" name="item_ref_id[]">
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
                                            <td><input class="text-right number-input txt-rate compute" type="text" name="item_rate[]"/></td>
                                            <td><input class="text-right txt-discount compute" type="text" name="item_discount[]"/></td>
                                            <td><input class="text-right number-input txt-amount" type="text" name="item_amount[]"/></td>
                                            @include("member.load_ajax_data.load_td_serial_number")
                                            <td class="text-center">
                                                <input type="hidden" name="item_taxable[]" class="taxable-input" value="">
                                                <input type="checkbox" class="taxable-check compute" value="1">
                                            </td>
                                            <td class="text-center remove-tr cursor-pointer"><i class="fa fa-trash-o" aria-hidden="true"></i></td>
                                            <input type="hidden" name="item_ref_name[]">
                                            <input type="hidden" name="item_ref_id[]">
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    
                    <div class="row clearfix">
                        <div class="col-sm-3">
                            <label>Vendor Message</label>
                            <textarea class="form-control input-sm textarea-expand remarks-dm" name="vendor_message" placeholder="">{{ isset($dm->db_message)? $dm->db_message : ''}}</textarea>
                        </div>
                        <div class="col-sm-3">
                            <label>Statement Memo</label>
                            <textarea class="form-control input-sm textarea-expand" name="vendor_memo" placeholder="">{{ isset($dm->db_memo) ? $dm->db_memo : ''}}</textarea>
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
                                            <select class="form-control input-sm compute discount_selection disc-type-dm" name="vendor_discounttype">  
                                                <option value="percent" {{isset($dm) ? $dm->db_discount_type == 'percent' ? 'selected' : '' : ''}}>Discount percentage</option>
                                                <option value="value" {{isset($dm) ? $dm->db_discount_type == 'value' ? 'selected' : '' : ''}}>Discount value</option>
                                            </select>
                                        </div>
                                        <div class="col-sm-2  padding-lr-1">
                                            <input class="form-control input-sm text-right number-input discount_txt compute disc-percentage-dm" type="text" name="vendor_discount" value="{{$dm->db_discount_value or ''}}">
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
                                            <select class="form-control input-sm tax_selection compute tax-dm" name="vendor_tax">  
                                                <option value="0" {{isset($dm) ? $dm->taxable == 0 ? 'selected' : '' : ''}}>No Tax</option>
                                                <option value="1" {{isset($dm) ? $dm->taxable == 1 ? 'selected' : '' : ''}}>Input Vat (12%)</option>
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
            <td class="invoice-number-td text-right">2</td>
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
            <td><select class="select-um" name="item_um[]"><option class="hidden" value="" /></select></td>
            <td><input class="text-center number-input txt-qty compute" type="text" name="item_qty[]"/></td>
            <td><input class="text-right number-input txt-rate compute" type="text" name="item_rate[]"/></td>
            <td><input class="text-right txt-discount compute" type="text" name="item_discount[]"/></td>
            <td><input class="text-right number-input txt-amount" type="text" name="item_amount[]"/></td>
            <td class="text-center">
                <input type="hidden" name="item_taxable[]" class="taxable-input" value="">
                <input type="checkbox" class="taxable-check compute" value="1">
            </td>
            <td class="text-center remove-tr cursor-pointer"><i class="fa fa-trash-o" aria-hidden="true"></i></td>
            <input type="hidden" name="item_ref_name[]">
            <input type="hidden" name="item_ref_id[]">
        </tr>
    </table>
</div>
@endsection

@section('script')
<script type="text/javascript" src="/assets/member/js/accounting_transaction/vendor/debit_memo.js"></script>
@endsection