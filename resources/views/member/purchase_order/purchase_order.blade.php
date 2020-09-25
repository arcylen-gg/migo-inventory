@extends('member.layout')
@section('content')
<form class="global-submit form-to-submit-transfer load-po-container" role="form" action="{{$action}}" method="POST" >
    <input type="hidden" name="_token" value="{{csrf_token()}}" >
    <input type="hidden" class="button-action" name="button_action" value="">
    <input type="hidden" name="po_id" value="{{$po->po_id or ''}}" >
    <div class="panel panel-default panel-block panel-title-block" id="top">
        <div class="panel-heading">
            <div>
                <i class="fa fa-tags"></i>
                <h1>
                    <span class="page-title">Purchase Order</span>
                    <small>
                    
                    </small>
                </h1>
                <!-- <button class="panel-buttons btn btn-custom-white pull-right" onclick="window.location='{{ URL::previous() }}'">Cancel</button>
                <button type="submit" class="panel-buttons btn btn-custom-primary pull-right" data-action="save-and-edit">Save and Edit</button>
                <button type="submit" class="panel-buttons btn btn-custom-white pull-right" data-action="save-and-new">Save and New</button>
             -->
            </div>
            <div class="dropdown pull-right">
                <div>
                    <a class="btn btn-custom-white" href="/member/vendor/purchase_order/list">Cancel</a>
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
                    <div style="border-bottom: 1px solid #ddd; padding-bottom: 10px; margin-bottom: 10px;">
                        <div class="row clearfix">
                            <div class="col-sm-4">
                                <select class="form-control droplist-vendor input-sm pull-left" name="po_vendor_id" data-placeholder="Select a Customer" required>
                                    @include('member.load_ajax_data.load_vendor', ['vendor_id' => isset($po->po_vendor_id) ? $po->po_vendor_id : (isset($v_id) ? $v_id : '')]);
                                </select>
                            </div>
                            <div class="col-sm-4">
                                <input type="text" class="form-control input-sm customer-email" name="po_vendor_email" placeholder="E-Mail (Separate E-Mails with comma)" value="{{$po->po_vendor_email or ''}}"/>
                            </div>
                        </div>
                    </div>
                    
                    <div class="row clearfix">
                        <div class="col-sm-3">
                            <label>Billing Address</label>
                            <textarea class="form-control input-sm textarea-expand po-billing-address" name="po_billing_address" placeholder="">{{$po->po_billing_address or ($po->ven_billing_street ." ".$po->ven_billing_city)}}</textarea>
                        </div>
                        <div class="col-sm-2">  
                            <label>Terms</label>
                            <select class="form-control input-sm droplist-terms" name="po_terms_id">
                                    @include("member.load_ajax_data.load_terms", ['terms_id' => isset($po->po_terms_id) ? $po->po_terms_id : (isset($terms) ? $terms : '')])
                                </select>
                        </div>
                        <div class="col-sm-2">
                            <label>P.O Date</label>
                            <input type="text" class="datepicker form-control input-sm" name="po_date" value="{{$po->po_date or date('m/d/y')}}"/>
                        </div>
                        <div class="col-sm-2">
                            <label>Due Date</label>
                            <input type="text" class="datepicker form-control input-sm" name="po_due_date" value="{{$po->po_due_date or date('m/d/y')}}" />
                        </div>
                    </div>
                    
                    <div class="row clearfix draggable-container">
                        <div class="table-responsive">
                            <div class="col-sm-12">
                                <table class="digima-table">
                                    <thead >
                                        <tr>
                                            <th style="width: 10px;" ></th>
                                            <th style="width: 120px;">Service Date</th>
                                            <th style="width: 15px;" class="text-right">#</th>
                                            <th style="width: 180px;">Product/Service</th>
                                            <th>Description</th>
                                            <th style="width: 120px;">U/M</th>
                                            <th style="width: 70px;">Qty</th>
                                            <th style="width: 100px;">Rate</th>
                                            <th style="width: 100px;">Discount</th>
                                            <th style="width: 100px;">Remark</th>
                                            <th style="width: 100px;">Amount</th>
                                            <th style="width: 10px;">Tax</th>
                                            <th width="10"></th>
                                        </tr>
                                    </thead>
                                    <tbody class="draggable tbody-item">     
                                        @if(isset($po))
                                            @foreach($_poline as $poline)
                                                <tr class="tr-draggable">
                                                    <td class="text-center cursor-move move"><i class="fa fa-th-large colo-mid-dark-gray"></i></td>

                                                    <td><input type="text" class="for-datepicker" name="poline_service_date[]" value="{{$poline->poline_service_date}}" /></td>

                                                    <td class="invoice-number-td text-right">1</td>
                                                    <td>
                                                        <select class="form-control select-item droplist-item input-sm pull-left {{$poline->poline_item_id}}" name="poline_item_id[]" required>
                                                            @include("member.load_ajax_data.load_item_category", ['add_search' => "", 'item_id' => $poline->poline_item_id])
                                                        </select>
                                                    </td>
                                                    <td>
                                                        @if($pis)
                                                            <textarea class="textarea-expand txt-desc" name="poline_description[]" readonly="true" >{{$poline->poline_description}}</textarea>
                                                        @else
                                                            <textarea class="textarea-expand txt-desc" name="poline_description[]" >{{$poline->poline_description}}</textarea>
                                                        @endif
                                                    </td>
                                                    <td>
                                                        <select class="1111 droplist-um select-um {{isset($poline->poline_um) ? 'has-value' : ''}}" name="poline_um[]">
                                                            @if($poline->poline_um)
                                                                @include("member.load_ajax_data.load_one_unit_measure", ['item_um_id' => $poline->multi_um_id, 'selected_um_id' => $poline->poline_um])
                                                            @else
                                                                <option class="hidden" value="" />
                                                            @endif
                                                        </select>
                                                    </td>
                                                    <td><input class="text-center number-input txt-qty compute" type="text" name="poline_qty[]" value="{{$poline->poline_qty}}" /></td>
                                                    <td><input class="text-right number-input txt-rate compute" type="text" name="poline_rate[]" value="{{$poline->poline_rate}}" /></td>
                                                    @if($poline->poline_discounttype == 'fixed')
                                                    <td><input class="text-right txt-discount compute" type="text" name="poline_discount[]" value="{{$poline->poline_discount}}" /></td>
                                                    @else
                                                    <td><input class="text-right txt-discount compute" type="text" name="poline_discount[]" value="{{($poline->poline_discount)*100}}%" /></td>
                                                    @endif
                                                    <td><textarea class="textarea-expand" type="text" name="poline_discount_remark[]">{{$poline->poline_discount_remark}}</textarea></td>
                                                    <td><input class="text-right number-input txt-amount" type="text" name="poline_amount[]" value="{{$poline->poline_amount}}" /></td>
                                                    <td class="text-center">
                                                        <input type="hidden" class="poline_taxable" name="poline_taxable[]" value="{{$poline->taxable}}" >
                                                        <input type="checkbox" name="" class="taxable-check" {{$poline->taxable == 1 ? 'checked' : ''}}>
                                                    </td>
                                                    <td class="text-center remove-tr cursor-pointer"><i class="fa fa-trash-o" aria-hidden="true"></i></td>
                                                </tr>
                                            @endforeach
                                        @else                                
                                            <tr class="tr-draggable">
                                                <td class="text-center cursor-move move"><i class="fa fa-th-large colo-mid-dark-gray"></i></td>

                                                <td><input type="text" class="for-datepicker" name="poline_service_date[]"/></td>

                                                <td class="invoice-number-td text-right">1</td>
                                                <td>
                                                    <select class="1111 form-control select-item droplist-item input-sm pull-left" name="poline_item_id[]" >
                                                        @include("member.load_ajax_data.load_item_category", ['add_search' => ""])
                                                        <option class="hidden" value="" />
                                                    </select>
                                                </td>
                                                <td>
                                                    @if($pis)
                                                         <textarea class="textarea-expand txt-desc" name="poline_description[]" readonly="true" ></textarea>
                                                    @else
                                                        <textarea class="textarea-expand txt-desc" name="poline_description[]"></textarea>
                                                    @endif
                                                </td>

                                                <td><select class="2222 droplist-um select-um" name="poline_um[]"><option class="hidden" value="" /></select></td>
                                                <td><input class="text-center number-input txt-qty compute" type="text" name="poline_qty[]"/></td>
                                                <td><input class="text-right number-input txt-rate compute" type="text" name="poline_rate[]"/></td>
                                                <td><input class="text-right txt-discount compute" type="text" name="poline_discount[]"/></td>
                                                <td><textarea class="textarea-expand" type="text" name="poline_discount_remark[]"></textarea></td>
                                                <td><input class="text-right number-input txt-amount" type="text" name="poline_amount[]"/></td>
                                                <td class="text-center">
                                                    <input type="hidden" class="poline_taxable" name="poline_taxable[]" value="" >
                                                    <input type="checkbox" name="" class="taxable-check" value="checked">
                                                </td>
                                                <td class="text-center remove-tr cursor-pointer"><i class="fa fa-trash-o" aria-hidden="true"></i></td>
                                            </tr>
                                                
                                            <tr class="tr-draggable">
                                                <td class="text-center cursor-move move" ><i class="fa fa-th-large colo-mid-dark-gray"></i></td>
                                                <td><input type="text" class="datepicker" name="poline_service_date[]"/></td>
                                                <td class="invoice-number-td text-right">2</td>
                                                <td>
                                                    <select class="22222 form-control select-item droplist-item input-sm pull-left" name="poline_item_id[]" >
                                                        @include("member.load_ajax_data.load_item_category", ['add_search' => ""])
                                                        <option class="hidden" value="" />
                                                    </select>
                                                </td>
                                                <td>
                                                    @if($pis)
                                                         <textarea class="textarea-expand txt-desc" name="poline_description[]" readonly="true" ></textarea>
                                                    @else
                                                        <textarea class="textarea-expand txt-desc" name="poline_description[]"></textarea>
                                                    @endif
                                                </td>
                                                <td><select class="3333 droplist-um select-um" name="poline_um[]"><option class="hidden" value="" /></select></td>
                                                <td><input class="text-center number-input txt-qty compute" type="text" name="poline_qty[]"/></td>
                                                <td><input class="text-right number-input txt-rate compute" type="text" name="poline_rate[]"/></td>
                                                <td><input class="text-right txt-discount compute" type="text" name="poline_discount[]"/></td>
                                                <td><textarea class="textarea-expand" type="text" name="poline_discount_remark[]"></textarea></td>
                                                <td><input class="text-right number-input txt-amount" type="text" name="poline_amount[]"/></td>
                                                <td class="text-center">
                                                    <input type="hidden" class="poline_taxable" name="poline_taxable[]" value="" >
                                                    <input type="checkbox" name="" class="taxable-check" value="checked">
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
                            <label>Message Displayed on P.O</label>
                            <textarea class="form-control input-sm textarea-expand" name="po_message" placeholder="">{{isset($po->po_message)? $po->po_message : ''}}</textarea>
                        </div>
                        <div class="col-sm-3">
                            <label>Statement Memo</label>
                            <textarea class="form-control input-sm textarea-expand" name="po_memo" placeholder="">{{isset($po->po_memo)? $po->po_memo : ''}}</textarea>
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
                                                <option value="0" {{isset($po) ? $po->ewt == 0 ? 'selected' : '' : ''}}></option>
                                                <option value="0.01" {{isset($po) ? $po->ewt == 0.01 ? 'selected' : '' : ''}}>1%</option>
                                                <option value="0.02" {{isset($po) ? $po->ewt == 0.02 ? 'selected' : '' : ''}}>2%</option>
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
                                            <select class="form-control input-sm compute discount_selection" name="po_discount_type">  
                                                <option value="percent" {{isset($po) ? $po->po_discount_type == 'percent' ? 'selected' : '' : ''}}>Discount percentage</option>
                                                <option value="value" {{isset($po) ? $po->po_discount_type == 'value' ? 'selected' : '' : ''}}>Discount value</option>
                                            </select>
                                        </div>
                                        <div class="col-sm-2  padding-lr-1">
                                            <input class="form-control input-sm text-right number-input discount_txt compute" type="text" name="po_discount_value" value="{{isset($po->po_discount_value)? $po->po_discount_value : ''}}">
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
                                                <option value="0" {{isset($po) ? $po->taxable == 0 ? 'selected' : '' : ''}}>No Tax</option>
                                                <option value="1" {{isset($po) ? $po->taxable == 1 ? 'selected' : '' : ''}}>Vat (12%)</option>
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
            <td class="text-center cursor-move move" ><i class="fa fa-th-large colo-mid-dark-gray"></i></td>
            <td><input type="text" class="for-datepicker"  name="poline_service_date[]"/></td>
            <td class="invoice-number-td text-right">2</td>
            <td>
                <select class="form-control select-item input-sm pull-left" name="poline_item_id[]">
                    @include("member.load_ajax_data.load_item_category", ['add_search' => ""])
                    <option class="hidden" value="" />
                </select>
            </td>
            <td>
                @if($pis)
                     <textarea class="textarea-expand txt-desc" name="poline_description[]" readonly="true" ></textarea>
                @else
                    <textarea class="textarea-expand txt-desc" name="poline_description[]"></textarea>
                @endif
            </td>

            <td><select class="select-um" name="poline_um[]"><option class="hidden" value="" /></select></td>
            <td><input class="text-center number-input txt-qty compute" type="text" name="poline_qty[]"/></td>
            <td><input class="text-right number-input txt-rate compute" type="text" name="poline_rate[]"/></td>
            <td><input class="text-right txt-discount compute" type="text" name="poline_discount[]"/></td>
            <td><textarea class="textarea-expand" type="text" name="poline_discount_remark[]"></textarea></td>
            <td><input class="text-right number-input txt-amount" type="text" name="poline_amount[]"/></td>
            <td class="text-center">
                <input type="hidden" class="poline_taxable" name="poline_taxable[]" value="" >
                <input type="checkbox" name="" class="taxable-check" value="checked">
            </td>
            <td class="text-center remove-tr cursor-pointer"><i class="fa fa-trash-o" aria-hidden="true"></i></td>
        </tr>
    </table>
</div>
@endsection


@section('script')
<script type="text/javascript" src="/assets/member/js/textExpand.js"></script>
<script type="text/javascript" src="/assets/member/js/draggable_row.js"></script>
<script type="text/javascript" src="/assets/member/js/purchase_order.js"></script>
@endsection