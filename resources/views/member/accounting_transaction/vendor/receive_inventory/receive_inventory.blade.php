@extends('member.layout')
@section('content')

<form class="global-submit" role="form" action="{{ $action or ''}}" method="POST" >
    <input type="hidden" class="token" name="_token" value="{{csrf_token()}}" >
    <input type="hidden" class="button-action" name="button_action" value="">
    <input type="hidden" name="ri_id" value="{{ $ri->ri_id or ''}}">
    <input type="hidden" name="item_new_cost" class="item-new-cost" value="{{ $item_new_cost }}">
    <input type="hidden" name="po_id" class="po-id" value="{{$po_id or ''}}">
    <input type="hidden" name="po_vendor_id" class="po-vendor-id" value="{{isset($po->po_vendor_id) ? $po->po_vendor_id :''}}">
    <input type="hidden" class="transaction-status" disabled="false" value="">
    <input type="hidden" class="current-warehouse" disabled="false" value="{{$warehouse_id}}">
<div class="drawer-overlay">
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
                        @if(isset($ri))
                        <a class="btn btn-custom-white hidden" href="/member/transaction/receive_inventory">Post</a>
                        @endif
                        <a class="btn btn-custom-white" href="/member/transaction/receive_inventory">Cancel</a>
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
                                            <input type="text" class="form-control" name="transaction_refnumber" value="{{ isset($ri->transaction_refnum)? $ri->transaction_refnum : $transaction_refnum}}">
                                        </div>
                                    </div>
                                </div>
                                <div style="border-bottom: 1px solid #ddd; padding-bottom: 10px; margin-bottom: 10px;">
                                    <div class="row clearfix">
                                        <div class="col-sm-4">
                                            <select class="form-control droplist-vendor input-sm pull-left" name="vendor_id">
                                                 @include('member.load_ajax_data.load_vendor', ['vendor_id' => isset($ri->ri_vendor_id) ? $ri->ri_vendor_id : (isset($po->po_vendor_id) ? $po->po_vendor_id :'')])
                                            </select>
                                        </div>
                                        <div class="col-sm-4">
                                            <input type="text" class="form-control input-sm vendor-email" name="vendor_email" placeholder="E-Mail (Separate E-Mails with comma)" value="{{$ri->ri_vendor_email or ''}}"/>
                                        </div>
                                        <div class="col-sm-4 text-right open-transaction" style="display: none;">
                                            <h4><a class="popup popup-link-open-transaction" size="md" link="/member/transaction/receive_inventory/load-transaction?vendor_id="><i class="fa fa-handshake-o"></i> <span class="count-open-transaction">0</span> Open Transaction</a></h4>
                                        </div>
                                    </div>
                                </div>
                                
                                <div style="border-bottom: 1px solid #ddd; padding-bottom: 10px; margin-bottom: 10px;">
                                    <div class="row clearfix">
                                        <div class="col-sm-3">
                                            <label>Mailing Address</label>
                                            <textarea class="form-control input-sm textarea-expand" name="vendor_address" placeholder="">{{isset($ri) ? $ri->ri_mailing_address : (isset($po->po_billing_address) ? $po->po_billing_address :'')}}</textarea>
                                        </div>              
                                        <div class="col-sm-2">
                                        <label>Terms</label>
                                            <select class="form-control select-item input-sm droplist-terms" name="vendor_terms"><!-- term-ri -->
                                                @include("member.load_ajax_data.load_terms", ['terms_id' => isset($ri) ? $ri->ri_terms_id : ''])
                                            </select>
                                        </div>
                                        <div class="col-sm-2">
                                            <label>Billing Date</label>
                                            <input type="text" class="form-control input-sm datepicker" value="{{isset($ri) ? date('m/d/Y', strtotime($ri->ri_date)) : date('m/d/Y')}}" name="transaction_date">
                                        </div>
                                        <div class="col-sm-2">
                                            <label>Due Date</label>
                                            <input type="text" class="form-control input-sm datepicker" value="{{isset($ri) ? date('m/d/Y', strtotime($ri->ri_due_date)) : date('m/d/Y')}}" name="transaction_duedate">
                                        </div>
                                    </div>
                                </div>
                                <div class="row clearfix draggable-container">
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
                                                                <i class="fa fa-icon fa-check tax-icon"></i> Tax</label>
                                                        </th>
                                                        <th width="10"></th>
                                                    </tr>
                                                </thead>
                                                @include("member.accounting_transaction.loading_items")
                                                <tbody class="applied-po-transaction-list">
                                                </tbody>
                                                <tbody class="draggable tbody-item">
                                                    @if(isset($ri))
                                                        @foreach($_riline as $key => $riline)
                                                            <tr class="tr-draggable">
                                                                <td class="invoice-number-td text-right">1</td>
                                                                <input type="hidden" name="item_ref_name[]" value="{{ $riline->riline_ref_name or ''}}">
                                                                <input type="hidden" name="item_ref_id[]" value="{{ $riline->riline_ref_id or ''}}">
                                                                @if($check_barcode == '1')
                                                                <td class="item-select-td">
                                                                    <input class="form-control input-sm pull-left item-textbox hidden" value="{{$riline->item_barcode}}" onkeypress="event_search($(this), event)" type="text"/>
                                                                    <select class="1111 form-control select-item droplist-item input-sm pull-left item-select {{$riline->poline_item_id}}" name="item_id[]" required >
                                                                        @include("member.load_ajax_data.load_item_category", ['add_search' => "", 'item_id' => $riline->riline_item_id])
                                                                        <option class="hidden" value="" />
                                                                    </select>
                                                                </td>
                                                                @else
                                                                <td>
                                                                    <select class="1111 form-control select-item droplist-item input-sm pull-left " name="item_id[]" >
                                                                        @include("member.load_ajax_data.load_item_category", ['add_search' => "", 'item_id' => $riline->riline_item_id])
                                                                        <option class="hidden" value="" />
                                                                    </select>
                                                                    
                                                                </td>
                                                                @endif
                                                                <td>
                                                                    <textarea class="textarea-expand txt-desc" name="item_description[]">{{$riline->riline_description}}</textarea>
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
                                                                    <select class="droplist-um select-um" name="item_um[]">
                                                                    @if($riline->riline_um)
                                                                        @include("member.load_ajax_data.load_one_unit_measure", ['item_um_id' => $riline->multi_um_id, 'selected_um_id' => $riline->riline_um])
                                                                    @else
                                                                        <option class="hidden" value="" />
                                                                    @endif
                                                                    </select>
                                                                </td>
                                                                <td><input class="text-center number-input txt-qty compute" type="text" name="item_qty[]" value="{{ $riline->riline_qty  }}" /></td>

                                                                <td>
                                                                    <input class="text-right number-input txt-rate new-cost compute" type="text" name="item_rate[]" value="{{ $riline->riline_rate }}" />
                                                                </td>

                                                                @if($riline->riline_discounttype == 'fixed')
                                                                    <td><input class="text-right txt-discount compute" type="text" name="item_discount[]" value="{{$riline->riline_discount}}" /></td>
                                                                @else
                                                                    <td><input class="text-right txt-discount compute" type="text" name="item_discount[]" value="{{$riline->riline_discount * 100}}%" /></td>
                                                                @endif
                                                                <td><input class="text-right number-input txt-amount" type="text" name="item_amount[]" value="{{ $riline->riline_amount }}" /></td>
                                                                @include("member.load_ajax_data.load_td_serial_number")
                                                                <td class="text-center">
                                                                    <input type="hidden" name="item_taxable[]" class="taxable-input" value="{{$riline->riline_taxable}}">
                                                                    <input type="checkbox" class="taxable-check"  value="1" {{$riline->riline_taxable == 1 ? 'checked' : ''}}>
                                                                </td>
                                                                <td class="text-center remove-tr cursor-pointer"><i class="fa fa-trash-o" aria-hidden="true"></i></td>
                                                            </tr>
                                                        @endforeach
                                                    @endif
                                                    <tr class="tr-draggable">
                                                        <td class="invoice-number-td text-right">1</td>
                                                        <input type="hidden" name="item_ref_name[]">
                                                        <input type="hidden" name="item_ref_id[]">

                                                        @if($check_barcode == '1')
                                                        <td class="item-select-td">
                                                            <input class="form-control input-sm pull-left item-textbox hidden" onkeypress="event_search($(this), event)" type="text" />
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
                                                        <td><select class="droplist-um select-um" name="item_um[]"><option class="hidden" value="" /></select></td>
                                                        <td><input class="text-center number-input txt-qty compute" type="text" name="item_qty[]"/></td>
                                                        <td>
                                                            <input class="text-right number-input txt-rate new-cost compute" type="text" name="item_rate[]"/>
                                                        </td>
                                                        <td><input class="text-right txt-discount compute" type="text" name="item_discount[]"/></td>
                                                        <td><input class="text-right number-input txt-amount" type="text" name="item_amount[]"/></td>
                                                        <td class="text-center">
                                                            <input type="hidden" name="item_taxable[]" class="taxable-input" value="">
                                                            <input type="checkbox" class="taxable-check compute tax_value" value="1">
                                                        </td>
                                                            @include("member.load_ajax_data.load_td_serial_number")
                                                        <td class="text-center remove-tr cursor-pointer"><i class="fa fa-trash-o" aria-hidden="true"></i></td>
                                                    </tr>
                                                    <tr class="tr-draggable">
                                                        <td class="invoice-number-td text-right">2</td>
                                                        <input type="hidden" name="item_ref_name[]">
                                                        <input type="hidden" name="item_ref_id[]">

                                                        @if($check_barcode == '1')
                                                        <td class="item-select-td">
                                                            <input class="form-control input-sm pull-left item-textbox hidden" onkeypress="event_search($(this), event)" type="text" />
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
                                                        <td><select class="droplist-um select-um" name="item_um[]"><option class="hidden" value="" /></select></td>
                                                        <td><input class="text-center number-input txt-qty compute" type="text" name="item_qty[]"/></td>
                                                        <td>
                                                            <input class="text-right number-input txt-rate new-cost compute" type="text" name="item_rate[]"/>
                                                        </td>
                                                        <td><input class="text-right txt-discount compute" type="text" name="item_discount[]"/></td>
                                                        <td><input class="text-right number-input txt-amount" type="text" name="item_amount[]"/></td>
                                                        <td class="text-center">
                                                            <input type="hidden" name="item_taxable[]" class="taxable-input" value="">
                                                            <input type="checkbox" class="taxable-check compute tax_value" value="1">
                                                        </td>
                                                            @include("member.load_ajax_data.load_td_serial_number")
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
                                        <textarea class="form-control input-sm remarks-ri textarea-expand" name="vendor_remarks" >{{isset($ri->ri_remarks)? $ri->ri_remarks : ''}}</textarea>
                                    </div>
                                    <div class="col-sm-3">
                                        <label>Memo</label>
                                        <textarea class="form-control input-sm textarea-expand" name="vendor_memo" >{{isset($ri->ri_memo)? $ri->ri_memo : ''}}</textarea>
                                    </div>
                                    <div class="col-sm-6">                      
                                        <div class="row">
                                            <div class="col-md-7 text-right digima-table-label">
                                                Sub Total
                                            </div>
                                            <div class="col-md-5 text-right digima-table-value">
                                                <input type="hidden" name="vendor_subtotal" class="subtotal-amount-input" />
                                                PHP&nbsp;<span class="sub-total">0.00</span>
                                            </div>
                                        </div> 
                                        <!-- <div class="row">
                                            <div class="col-md-7 text-right digima-table-label">
                                                Total Discount
                                            </div>
                                            <div class="col-md-5 text-right digima-table-value">
                                                <input type="hidden" name="vendor_discount" class="subtotal-amount-input" />
                                                PHP&nbsp;<span class="discount-total">0.00</span>
                                            </div>
                                        </div>  -->
                                        <div class="row">
                                            <div class="col-md-7 text-right digima-table-label">
                                                <div class="row">
                                                    <div class="col-sm-6 col-sm-offset-4  padding-lr-1">
                                                        <select class="form-control input-sm compute discount_selection disc-type-ri" name="vendor_discounttype">  
                                                            <option value="percent" {{isset($ri) ? $ri->ri_discount_type == 'percent' ? 'selected' : '' : ''}}>Discount percentage</option>
                                                            <option value="value" {{isset($ri) ? $ri->ri_discount_type == 'value' ? 'selected' : '' : ''}}>Discount value</option>
                                                        </select>
                                                    </div>
                                                    <div class="col-sm-2  padding-lr-1">
                                                        <input class="form-control input-sm text-right number-input discount_txt compute disc-percentage-ri" type="text" name="vendor_discount" value="{{$ri->ri_discount_value or ''}}">
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
                                                        <select class="form-control input-sm tax_selection compute tax-ri" name="vendor_tax">  
                                                            <option value="0" {{isset($ri) ? $ri->taxable == 0 ? 'selected' : '' : ''}}>No Tax</option>
                                                            <option value="1" {{isset($ri) ? $ri->taxable == 1 ? 'selected' : '' : ''}}>Input Vat (12%)</option>
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
            </div>
        </div>
    </div>
</div>
</form>
<div class="div-script">
    <table class="div-item-row-script hide">
       <tr class="tr-draggable">
            <input type="text" class="hidden poline_id" name="poline_id[]">
            <input type="text" class="hidden itemline_po_id" name="itemline_po_id[]">
            <td class="invoice-number-td text-right">1</td>
            <input type="hidden" class="poline_id" name="item_ref_name[]">
            <input type="hidden" class="itemline_po_id" name="item_ref_id[]">

            @if($check_barcode == '1')
            <td class="item-select-td">
                <input class="form-control input-sm pull-left item-textbox hidden" onkeypress="event_search($(this), event)" type="text" />
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
            <td>
                <input class="text-right number-input txt-rate new-cost compute" type="text" name="item_rate[]"/>
            </td>
            <td><input class="text-right txt-discount compute" type="text" name="item_discount[]"/></td>
            <td><input class="text-right number-input txt-amount" type="text" name="item_amount[]"/></td>
            <td class="text-center">
                <input type="hidden" name="item_taxable[]" class="taxable-input" value="">
                <input type="checkbox" class="taxable-check compute tax_value" value="1">
            </td>
            @include("member.load_ajax_data.load_td_serial_number")
            <td class="text-center remove-tr cursor-pointer"><i class="fa fa-trash-o" aria-hidden="true"></i></td>
        </tr>
    </table>
</div>
@endsection
@section('script')
<script type="text/javascript" src="/assets/member/js/accounting_transaction/vendor/receive_inventory.js"></script>
@endsection