@extends('member.layout')
@section('content')
<form class="global-submit form-to-submit-transfer" role="form" action="{{ $action or ''}}" method="POST" >
    <input type="hidden" name="_token" value="{{csrf_token()}}">
    <input type="hidden" class="button-action" name="button_action" value="">
    <div class="panel panel-default panel-block panel-title-block" id="top">
        <div class="panel-heading">
            <div>
                <i class="fa fa-tags"></i>
                <h1>
                    <span class="page-title">Create {{$type or ''}}</span>
                    <small>
                    
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
                @if(isset($db))
                <div class="pull-right">
                    <div class="dropdown">
                        <button class="btn btn-custom-white dropdown-toggle" type="button" data-toggle="dropdown">More
                        <span class="caret"></span></button>
                        <ul class="dropdown-menu">
                            <li><a href="/member/accounting/journal/entry/debit-memo/{{$db->db_id}}">Transaction Journal</a></li>
                            <li><a href="#">Void</a></li>
                        </ul>
                    </div>
                </div>
                @endif 
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
                    <div style="padding-bottom: 10px; margin-bottom: 10px;">
                        <div class="row clearfix">
                            <div class="col-sm-4">
                                <label>Reference Number</label>
                                <input type="text" class="form-control" name="transaction_refnumber" value="BO20171225-0001">
                            </div>
                        </div>
                    </div>
                    <div style="border-bottom: 1px solid #ddd; padding-bottom: 10px; margin-bottom: 10px;">
                        <div class="row clearfix">
                            <div class="col-sm-4">
                                <select class="form-control droplist-vendor input-sm pull-left" name="vendor_id" data-placeholder="Select a Vendor" required>
                                    @include('member.load_ajax_data.load_vendor', ['vendor_id' => isset($db->db_vendor_id) ? $db->db_vendor_id : ''])
                                </select>
                            </div>
                            <div class="col-sm-4">
                                <input type="text" class="form-control input-sm vendor-email" name="vendor_email" placeholder="E-Mail (Separate E-Mails with comma)" value="{{$db->db_vendor_email or ''}}"/>
                            </div>
                        </div>
                    </div>
                    
                    <div class="row clearfix">
                        <div class="col-sm-2">
                            <label>Date</label>
                            <input type="text" class="datepicker form-control input-sm" name="transaction_date" value="{{isset($db->db_date) ? $db->db_date : date('m/d/y')}}"/>
                        </div>
                    </div>
                    
                    <div class="row clearfix draggable-container">
                        <div class="table-responsive">
                            <div class="col-sm-12">
                                <table class="digima-table">
                                    <thead >
                                        <tr>
                                            <th style="width: 15px;" class="text-right">#</th>
                                            <th style="width: 180px;">Product/Service</th>
                                            <th>Description</th>
                                            <th style="width: 120px;">U/M</th>
                                            <th style="width: 70px;">Qty</th>
                                            <th style="width: 100px;">Rate</th>
                                            <!-- <th style="width: 100px;">Discount</th> -->
                                            <!-- <th style="width: 100px;">Remark</th> -->
                                            <th style="width: 100px;">Amount</th>
                                            @include("member.load_ajax_data.load_th_serial_number")
                                            <!-- <th style="width: 10px;">Tax</th> -->
                                            <th width="10"></th>
                                        </tr>
                                    </thead>
                                    <tbody class="draggable tbody-item">     
                                        @if(isset($db))
                                            @foreach($_dbline as $dbline)
                                                <tr class="tr-draggable">
                                                    <td class="invoice-number-td text-right">1</td>
                                                    <td>
                                                        <select class="form-control select-item droplist-item input-sm pull-left {{$dbline->dbline_item_id}}" name="item_id[]" required>
                                                            @include("member.load_ajax_data.load_item_category", ['add_search' => "", 'item_id' => $dbline->dbline_item_id])
                                                        </select>
                                                    </td>
                                                    <td>
                                                        <textarea class="textarea-expand txt-desc" name="item_description[]">{{$dbline->dbline_description}}</textarea>
                                                    </td>
                                                    <td>
                                                        <select class="1111 droplist-um select-um {{isset($dbline->multi_id) ? 'has-value' : ''}}" name="item_um[]">
                                                            @if($dbline->dbline_um)
                                                                @include("member.load_ajax_data.load_one_unit_measure", ['item_um_id' => $dbline->multi_um_id, 'selected_um_id' => $dbline->dbline_um])
                                                            @else
                                                                <option class="hidden" value="" />
                                                            @endif
                                                        </select>
                                                    </td>
                                                    <td><input class="text-center number-input txt-qty compute" type="text" name="item_qty[]" value="{{$dbline->dbline_qty}}" /></td>
                                                    <td><input class="text-right number-input txt-rate compute" type="text" name="item_rate[]" value="{{$dbline->dbline_rate}}" /></td>
                                                   <!-- <td><input class="text-right txt-discount compute" type="text" name="dbline_discount[]" value="" /> </td>
                                                    <td><textarea class="textarea-expand" type="text" name="dbline_discount_remark[]" value=""></textarea></td> -->
                                                    <td><input class="text-right number-input txt-amount" type="text" name="item_amount[]" value="{{$dbline->dbline_amount}}" /></td>
                                                   <!--  <td class="text-center">
                                                        <input type="hidden" class="dbline_taxable" name="dbline_taxable[]" value="" >
                                                        <input type="checkbox" name="" class="taxable-check" >
                                                    </td> -->
                                                    @if(isset($serial)) 
                                                    <td>
                                                        <textarea class="txt-serial-number" name="item_serialnumber[]">{{$dbline->serial_number}}</textarea>
                                                    </td>
                                                    @endif
                                                    <td class="text-center remove-tr cursor-pointer"><i class="fa fa-trash-o" aria-hidden="true"></i></td>
                                                </tr>
                                            @endforeach
                                        @else                                
                                            <tr class="tr-draggable">
                                                <td class="invoice-number-td text-right">1</td>
                                                <td>
                                                    <select class="1111 form-control select-item droplist-item input-sm pull-left" name="item_id[]" >
                                                        @include("member.load_ajax_data.load_item_category", ['add_search' => ""])
                                                        <option class="hidden" value="" />
                                                    </select>
                                                </td>
                                                <td>
                                                    <textarea class="textarea-expand txt-desc" name="item_description[]"></textarea>
                                                </td>
                                                <td><select class="2222 droplist-um select-um" name="item_um[]"><option class="hidden" value="" /></select></td>
                                                <td><input class="text-center number-input txt-qty compute" type="text" name="item_qty[]"/></td>
                                                <td><input class="text-right number-input txt-rate compute" type="text" name="item_rate[]"/></td>
                                               <!--  <td><input class="text-right txt-discount compute" type="text" name="dbline_discount[]"/></td>
                                                <td><textarea class="textarea-expand" type="text" name="dbline_discount_remark[]" ></textarea></td> -->
                                                <td><input class="text-right number-input txt-amount" type="text" name="item_amount[]"/></td>
                                               <!--  <td class="text-center">
                                                    <input type="hidden" class="dbline_taxable" name="dbline_taxable[]" value="" >
                                                    <input type="checkbox" name="" class="taxable-check" value="checked">
                                                </td> -->
                                                @include("member.load_ajax_data.load_td_serial_number")
                                                <td class="text-center remove-tr cursor-pointer"><i class="fa fa-trash-o" aria-hidden="true"></i></td>
                                            </tr>
                                                
                                            <tr class="tr-draggable">
                                                <td class="invoice-number-td text-right">2</td>
                                                <td>
                                                    <select class="22222 form-control select-item droplist-item input-sm pull-left" name="item_id[]" >
                                                        @include("member.load_ajax_data.load_item_category", ['add_search' => ""])
                                                        <option class="hidden" value="" />
                                                    </select>
                                                </td>
                                                <td>
                                                    <textarea class="textarea-expand txt-desc" name="item_description[]"></textarea>
                                                </td>
                                                <td><select class="3333 droplist-um select-um" name="item_um[]"><option class="hidden" value="" /></select></td>
                                                <td><input class="text-center number-input txt-qty compute" type="text" name="item_qty[]"/></td>
                                                <td><input class="text-right number-input txt-rate compute" type="text" name="item_rate[]"/></td>
                                               <!--  <td><input class="text-right txt-discount compute" type="text" name="dbline_discount[]"/></td>
                                                <td><input class="text-right number-input" type="text" name="dbline_discount_remark[]"/></td> -->
                                                <td><input class="text-right number-input txt-amount" type="text" name="item_amount[]"/></td>
                                               <!--  <td class="text-center">
                                                    <input type="hidden" class="dbline_taxable" name="dbline_taxable[]" value="" >
                                                    <input type="checkbox" name="" class="taxable-check" value="checked">
                                                </td> -->
                                                @include("member.load_ajax_data.load_td_serial_number")
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
                            <label>Vendor Message</label>
                            <textarea class="form-control input-sm textarea-expand" name="vendor_message" placeholder="">{{$db->db_message or ''}}</textarea>
                        </div>
                        <div class="col-sm-3">
                            <label>Statement Memo</label>
                            <textarea class="form-control input-sm textarea-expand" name="vendor_memo" placeholder="">{{$db->db_memo or ''}}</textarea>
                        </div>
                        <div class="col-sm-6">
                            <div class="row">
                                <div class="col-md-7 text-right digima-table-label">
                                    Total
                                </div>
                                <div class="col-md-5 text-right digima-table-value">
                                    <input type="hidden" name="subtotal_price" class="subtotal-amount-input" />
                                    PHP&nbsp;<span class="sub-total">0.00</span>
                                </div>
                            </div> 
                         <!--    <div class="row">
                                <div class="col-md-7 text-right digima-table-label">
                                    <div class="row">
                                        <div class="col-sm-6 col-sm-offset-3  padding-lr-1">
                                            <label>EWT</label>
                                        </div>
                                        <div class="col-sm-3  padding-lr-1">
                                             <input class="form-control input-sm text-right ewt_value number-input" type="text" name="ewt"> 
                                            <select class="form-control input-sm ewt-value compute" name="ewt">  
                                                <option value="0" {{isset($db) ? $db->ewt == 0 ? 'selected' : '' : ''}}></option>
                                                <option value="0.01" {{isset($db) ? $db->ewt == 0.01 ? 'selected' : '' : ''}}>1%</option>
                                                <option value="0.02" {{isset($db) ? $db->ewt == 0.02 ? 'selected' : '' : ''}}>2%</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-5 text-right digima-table-value">
                                    PHP&nbsp;<span class="ewt-total">0.00</span>
                                </div>
                            </div> -->
                           <!--  <div class="row">
                                <div class="col-md-7 text-right digima-table-label">
                                    <div class="row">
                                        <div class="col-sm-6 col-sm-offset-4  padding-lr-1">
                                            <select class="form-control input-sm compute discount_selection" name="db_discount_type">  
                                                <option value="percent" {{isset($db) ? $db->db_discount_type == 'percent' ? 'selected' : '' : ''}}>Discount percentage</option>
                                                <option value="value" {{isset($db) ? $db->db_discount_type == 'value' ? 'selected' : '' : ''}}>Discount value</option>
                                            </select>
                                        </div>
                                        <div class="col-sm-2  padding-lr-1">
                                            <input class="form-control input-sm text-right number-input discount_txt compute" type="text" name="db_discount_value" value="{{$db->db_discount_value or ''}}">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-5 text-right digima-table-value">
                                    PHP&nbsp;<span class="discount-total">0.00</span>
                                </div>
                            </div>  -->
                           <!--  <div class="row">
                                <div class="col-md-7 text-right digima-table-label">
                                    <div class="row">
                                        <div class="col-sm-4 col-sm-offset-8  padding-lr-1">
                                            <select class="form-control input-sm tax_selection compute" name="taxable">  
                                                <option value="0" {{isset($db) ? $db->taxable == 0 ? 'selected' : '' : ''}}>No Tax</option>
                                                <option value="1" {{isset($db) ? $db->taxable == 1 ? 'selected' : '' : ''}}>Vat (12%)</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-5 text-right digima-table-value">
                                    PHP&nbsp;<span class="tax-total">0.00</span>
                                </div>
                            </div>  -->
                            <div class="row">
                                <div class="col-md-7 text-right digima-table-label">
                                    Remaining Total
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
            <td>
                <select class="form-control select-item input-sm pull-left" name="item_id[]">
                    @include("member.load_ajax_data.load_item_category", ['add_search' => ""])
                    <option class="hidden" value="" />
                </select>
            </td>
            <td><textarea class="textarea-expand txt-desc" name="item_description[]"></textarea></td>
            <td><select class="select-um" name="item_um[]"><option class="hidden" value="" /></select></td>
            <td><input class="text-center number-input txt-qty compute" type="text" name="item_qty[]"/></td>
            <td><input class="text-right number-input txt-rate compute" type="text" name="item_rate[]"/></td>
    <!--         <td><input class="text-right txt-discount compute" type="text" name="dbline_discount[]"/></td>
            <td><textarea class="textarea-expand" type="text" name="dbline_discount_remark[]" ></textarea></td> -->
            <td><input class="text-right number-input txt-amount" type="text" name="dbline_amount[]"/></td>
       <!--      <td class="text-center">
                <input type="hidden" class="dbline_taxable" name="dbline_taxable[]" value="" >
                <input type="checkbox" name="" class="taxable-check" value="checked">
            </td> -->
            @include("member.load_ajax_data.load_td_serial_number")
            <td class="text-center remove-tr cursor-pointer"><i class="fa fa-trash-o" aria-hidden="true"></i></td>
        </tr>
    </table>
</div>
@endsection

@section('script')
<script type="text/javascript" src="/assets/member/js/accounting_transaction/vendor/debit_memo.js"></script>
@endsection