@extends('member.layout')
@section('content')

<form class="global-submit form-to-submit-add" action="/member/transaction/purchase_requisition/create-submit" method="post">
<input type="hidden" class="button-action" name="button_action" value="">
<div class="panel panel-default panel-block panel-title-block" id="top">
    <div class="panel-heading">
        <div>
            <i class="fa fa-archive"></i>
            <h1>
                <span class="page-title">CREATE - Requisition Slip</span>
            </h1>
            <div class="dropdown pull-right">
                <div>
                    <a class="btn btn-custom-white" href="/member/transaction/purchase_requisition">Cancel</a>
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
<!-- <div class="panel panel-default panel-block panel-title-block">
    <div class="panel-body form-horizontal">
        <div class="form-group">
            <div class="col-md-12">
                <div class="input-group pos-search">
                  <span style="background-color: #eee" class="input-group-addon button-scan" id="basic-addon1">
                    <i class="fa fa-shopping-cart scan-icon"></i>
                    <i style="display: none;" class="fa fa-spinner fa-pulse fa-fw scan-load"></i>
                  </span>
                  <input type="text" class="form-control event_search_item" placeholder="Enter item name or scan barcode" aria-describedby="basic-addon1">
                  <div class="pos-search-container"></div>
                </div>
            </div>
        </div>
       
    </div>
</div> -->

<input type="hidden" name="_token" value="{{csrf_token()}}">
<div class="panel panel-default panel-block panel-title-block">
    <div class="panel-body form-horizontal">
        <div class="form-group">
            <div class="col-md-6">
                <label>Transaction Number</label>
                <div>
                   <input type="text" class="form-control" name="requisition_slip_number">
                </div>
            </div>
            <div class="col-sm-3 text-right"></div>
            <div class="col-sm-3 text-right">
                <h4><a class="popup popup-link-open-transaction" size="md" link="/member/transaction/purchase_requisition/load-transaction"><i class="fa fa-handshake-o"></i> <span class="count-open-transaction">{{$count_so}}</span> Open Transaction</a></h4>
            </div>
        </div>
        <div class="form-group">
            <div class="col-md-6">
                <label>Remarks</label>
                <div>
                    <textarea class="form-control" name="requisition_slip_remarks"></textarea>
                </div>
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
                                <th style="width: 15px;">#</th>
                                <th class="text-left" style="width: 300px;">Product/Service</th>
                                <th class="text-left" >Description</th>
                                <th class="text-center" width="100px">QTY</th>
                                <th class="text-center" width="100px">U/M</th>
                                <th class="text-center" width="150px">Rate</th>
                                <th class="text-center" width="150px">Amount</th>
                                <th class="text-center" width="200px">Vendor</th>
                                <th width="50px"></th>
                            </tr>
                        </thead>
                        <tbody class="draggable tbody-item">
                            <tr class="tr-draggable">
                                <td class="invoice-number-td text-center">1</td>
                                <td>
                                    <select class="form-control droplist-item input-sm select-item" name="rs_item_id[]" >
                                        @include("member.load_ajax_data.load_item_category", ['add_search' => ""])
                                        <option class="hidden" value="" />
                                    </select>
                                </td>
                                <td><textarea class="form-control txt-desc" name="rs_item_description[]"></textarea></td>
                                <td class="text-center">
                                    <input type="text" class="form-control text-center txt-qty" name="rs_item_qty[]">
                                </td>
                                <td class="text-center">
                                    <select class="form-control droplist-item-um select-um" name="rs_item_um[]"></select>
                                </td>
                                <td class="text-center">
                                    <input type="text" class="form-control text-right txt-rate" name="rs_item_rate[]">
                                </td>
                                <td class="text-center">
                                    <input type="text" class="form-control text-right txt-amount" name="rs_item_amount[]" value="0.00">
                                </td>
                                <td class="text-center">
                                    <select class="form-control droplist-vendor select-vendor" name="rs_vendor_id[]">
                                        @include('member.load_ajax_data.load_vendor')
                                    </select>
                                </td>
                                <td class="text-center remove-tr cursor-pointer"><i class="fa fa-trash-o" aria-hidden="true"></i></td>
                            </tr>
                           <tr class="tr-draggable">
                                <td class="invoice-number-td text-center">2</td>
                                <td>
                                    <select class="form-control droplist-item select-item input-sm" name="rs_item_id[]" >
                                        @include("member.load_ajax_data.load_item_category", ['add_search' => ""])
                                        <option class="hidden" value="" />
                                    </select>
                                </td>
                                <td><textarea class="form-control txt-desc" name="rs_item_description[]"></textarea></td>
                                <td class="text-center">
                                    <input type="text" class="form-control text-center txt-qty" name="rs_item_qty[]">
                                </td>
                                <td class="text-center">
                                    <select class="form-control droplist-item-um select-um" name="rs_item_um[]"></select>
                                </td>
                                <td class="text-center">
                                    <input type="text" class="form-control text-right txt-rate" name="rs_item_rate[]">
                                </td>
                                <td class="text-center">
                                    <input type="text" class="form-control text-right txt-amount" name="rs_item_amount[]" value="0.00">
                                </td>
                                <td class="text-center">
                                    <select class="form-control droplist-vendor select-vendor" name="rs_vendor_id[]">
                                        @include('member.load_ajax_data.load_vendor')
                                    </select>
                                </td>
                                <td class="text-center remove-tr cursor-pointer"><i class="fa fa-trash-o" aria-hidden="true"></i></td>
                            </tr>
                        </tbody>
                    </table>                    
                </div>                
            </div>            
        </div>
        <div class="row clearfix">
            <div class="col-sm-6">
                <label>Memo</label>
                <textarea class="form-control input-sm textarea-expand" name="vendor_memo" ></textarea>
            </div>
            <div class="col-sm-6">                      
                <div class="row">
                    <div class="col-md-7 text-right digima-table-label">
                        Total
                    </div>
                    <div class="col-md-5 text-right digima-table-value total">
                       <input type="hidden" name="bill_total_amount" class="total-amount-input" />
                            PHP&nbsp;<span class="total-amount">0.00</span>
                    </div>
                </div> 
            </div>
        </div>
    </div>
</div>
</form>



<div class="div-script">
    <table class="div-item-row-script-item hide">
       <tr class="tr-draggable">
            <td class="invoice-number-td text-center">1</td>
            <td>
                <select class="form-control droplist-item select-item input-sm" name="rs_item_id[]" >
                    @include("member.load_ajax_data.load_item_category", ['add_search' => ""])
                    <option class="hidden" value="" />
                </select>
            </td>
            <td><textarea class="form-control txt-desc" name="rs_item_description[]"></textarea></td>
            <td class="text-center">
                <input type="text" class="form-control text-center txt-qty" name="rs_item_qty[]">
            </td>
            <td class="text-center">
                <select class="form-control select-um" name="rs_item_um[]"></select>
            </td>
            <td class="text-center">
                <input type="text" class="form-control text-right txt-rate" name="rs_item_rate[]">
            </td>
            <td class="text-center">
                <input type="text" class="form-control text-right txt-amount" name="rs_item_amount[]" value="0.00">
            </td>
            <td class="text-center">
                <select class="form-control select-vendor" name="rs_vendor_id[]">
                    @include('member.load_ajax_data.load_vendor')
                </select>
            </td>
            <td class="text-center remove-tr cursor-pointer"><i class="fa fa-trash-o" aria-hidden="true"></i></td>
        </tr>
    </table>
</div>
@endsection

@section('script')
<!-- <script type="text/javascript" src="/assets/member/js/accounting_transaction/vendor/purchase_requisition.js"></script> -->
<script type="text/javascript" src="/assets/member/js/accounting_transaction/vendor/vendor_requisition_slip.js"></script>
@endsection