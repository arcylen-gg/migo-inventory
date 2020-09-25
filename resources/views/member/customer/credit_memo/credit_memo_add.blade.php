@extends('member.layout')
@section('content')
<form class="global-submit form-to-submit-transfer" role="form" action="{{$action}}" method="POST" >
    <input type="hidden" name="_token" value="{{csrf_token()}}">
    <input type="hidden" name="sir_id" value="{{$sir_id or ''}}">
    <input type="hidden" name="action_button" class="button-action" value="">
    <input type="hidden" name="credit_memo_id" value="{{Request::input('id')}}" >
    <div class="panel panel-default panel-block panel-title-block" id="top">
        <div class="panel-heading">
            <div>
                <i class="fa fa-tags"></i>
                <h1>
                    <span class="page-title">Create Credit Memo</span>
                    <small>
                    
                    </small>
                </h1>
                <!-- <a href="/member/customer/credit_memo/list" class="panel-buttons btn btn-custom-white pull-right">Cancel</a>
                <button type="submit" class="panel-buttons btn btn-custom-primary pull-right">Save</button> -->
                <div class="dropdown pull-right">
                    <div>
                        <a class="btn btn-custom-white" href="/member/customer/credit_memo/list">Cancel</a>
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
                @if(isset($cm))
                <div class="pull-right">
                    <div class="dropdown">
                        <button class="btn btn-custom-white dropdown-toggle" type="button" data-toggle="dropdown">More
                        <span class="caret"></span></button>
                        <ul class="dropdown-menu">
                            <!-- <li class="dropdown-header">Dropdown header 1</li> -->
                            <li><a href="/member/accounting/journal/entry/credit-memo/{{$cm->cm_id}}">Transaction Journal</a></li>
                            <!-- <li class="divider"></li> -->
                            <!-- <li class="dropdown-header">Dropdown header 2</li> -->
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
                    <div style="border-bottom: 1px solid #ddd; padding-bottom: 10px; margin-bottom: 10px;">
                        <div class="row clearfix">
                            <div class="col-sm-4">
                                <select class="form-control droplist-customer input-sm pull-left" name="cm_customer_id" data-placeholder="Select a Customer" required>
                                    @include('member.load_ajax_data.load_customer', ['customer_id' => isset($cm->cm_customer_id) ? $cm->cm_customer_id : '']);
                                </select>
                            </div>
                            <div class="col-sm-4">
                                <input type="text" class="form-control input-sm customer-email" name="cm_customer_email" placeholder="E-Mail (Separate E-Mails with comma)" value="{{$cm->cm_customer_email or ''}}"/>
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
                        <div class="col-sm-2">
                            <label>Date</label>
                            <input type="text" class="datepicker form-control input-sm" name="cm_date" value="{{isset($cm->cm_date) ? $cm->cm_date : date('m/d/y')}}"/>
                        </div>
                    </div>
                    
                    <div class="row clearfix draggable-container">
                        <div class="table-responsive">
                            <div class="col-sm-12">
                                <table class="digima-table">
                                    <thead >
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
                                            @include("member.load_ajax_data.load_th_serial_number")
                                            <!-- <th style="width: 10px;">Tax</th> -->
                                            <th width="10"></th>
                                        </tr>
                                    </thead>
                                    <tbody class="draggable tbody-item">     
                                        @if(isset($cm))
                                            @foreach($_cmline as $cmline)
                                                <tr class="tr-draggable">
                                                    <td class="text-center cursor-move move"><i class="fa fa-th-large colo-mid-dark-gray"></i></td>

                                                    <td class="invoice-number-td text-right">1</td>
                                                    <td>
                                                        <select class="form-control select-item droplist-item input-sm pull-left {{$cmline->cmline_item_id}}" name="cmline_item_id[]" required>
                                                            @include("member.load_ajax_data.load_item_category", ['add_search' => "", 'item_id' => $cmline->cmline_item_id])
                                                        </select>
                                                    </td>
                                                    @if($pis)
                                                        <td><textarea class="textarea-expand txt-desc" name="cmline_description[]" value="{{$cmline->cmline_service_date}}" readonly></textarea></td>
                                                    @else
                                                        <td><textarea class="textarea-expand txt-desc" name="cmline_description[]" value="{{$cmline->cmline_service_date}}"></textarea></td>
                                                    @endif
                                                    <td>
                                                        <select class="1111 droplist-um select-um {{isset($cmline->multi_id) ? 'has-value' : ''}}" name="cmline_um[]">
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
                                                    @if(isset($serial)) 
                                                    <td>
                                                        <textarea name="serial_number[]">{{$cmline->serial_number}}</textarea>
                                                    </td>
                                                    @endif
                                                    <td class="text-center remove-tr cursor-pointer"><i class="fa fa-trash-o" aria-hidden="true"></i></td>
                                                </tr>
                                            @endforeach
                                        @else                                
                                            <tr class="tr-draggable">
                                                <td class="text-center cursor-move move"><i class="fa fa-th-large colo-mid-dark-gray"></i></td>

                                                <td class="invoice-number-td text-right">1</td>
                                                <td>
                                                    <select class="1111 form-control select-item droplist-item input-sm pull-left" name="cmline_item_id[]" >
                                                        @include("member.load_ajax_data.load_item_category", ['add_search' => ""])
                                                        <option class="hidden" value="" />
                                                    </select>
                                                </td>
                                                @if($pis)
                                                    <td><textarea class="textarea-expand txt-desc" name="cmline_description[]" readonly></textarea></td>
                                                @else
                                                    <td><textarea class="textarea-expand txt-desc" name="cmline_description[]"></textarea></td>
                                                @endif
                                                <td><select class="2222 droplist-um select-um" name="cmline_um[]"><option class="hidden" value="" /></select></td>
                                                <td><input class="text-center number-input txt-qty compute" type="text" name="cmline_qty[]"/></td>
                                                <td><input class="text-right number-input txt-rate compute" type="text" name="cmline_rate[]"/></td>
                                               <!--  <td><input class="text-right txt-discount compute" type="text" name="cmline_discount[]"/></td>
                                                <td><textarea class="textarea-expand" type="text" name="cmline_discount_remark[]" ></textarea></td> -->
                                                <td><input class="text-right number-input txt-amount" type="text" name="cmline_amount[]"/></td>
                                               <!--  <td class="text-center">
                                                    <input type="hidden" class="cmline_taxable" name="cmline_taxable[]" value="" >
                                                    <input type="checkbox" name="" class="taxable-check" value="checked">
                                                </td> -->
                                                @include("member.load_ajax_data.load_td_serial_number")
                                                <td class="text-center remove-tr cursor-pointer"><i class="fa fa-trash-o" aria-hidden="true"></i></td>
                                            </tr>
                                                
                                            <tr class="tr-draggable">
                                                <td class="text-center cursor-move move" ><i class="fa fa-th-large colo-mid-dark-gray"></i></td>
                                                <td class="invoice-number-td text-right">2</td>
                                                <td>
                                                    <select class="22222 form-control select-item droplist-item input-sm pull-left" name="cmline_item_id[]" >
                                                        @include("member.load_ajax_data.load_item_category", ['add_search' => ""])
                                                        <option class="hidden" value="" />
                                                    </select>
                                                </td>
                                                @if($pis)
                                                    <td><textarea class="textarea-expand txt-desc" name="cmline_description[]" readonly></textarea></td>
                                                @else
                                                    <td><textarea class="textarea-expand txt-desc" name="cmline_description[]"></textarea></td>
                                                @endif
                                                <td><select class="3333 droplist-um select-um" name="cmline_um[]"><option class="hidden" value="" /></select></td>
                                                <td><input class="text-center number-input txt-qty compute" type="text" name="cmline_qty[]"/></td>
                                                <td><input class="text-right number-input txt-rate compute" type="text" name="cmline_rate[]"/></td>
                                               <!--  <td><input class="text-right txt-discount compute" type="text" name="cmline_discount[]"/></td>
                                                <td><input class="text-right number-input" type="text" name="cmline_discount_remark[]"/></td> -->
                                                <td><input class="text-right number-input txt-amount" type="text" name="cmline_amount[]"/></td>
                                               <!--  <td class="text-center">
                                                    <input type="hidden" class="cmline_taxable" name="cmline_taxable[]" value="" >
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
                            <label>Customer Message</label>
                            <textarea class="form-control input-sm textarea-expand" name="cm_message" placeholder="">{{$cm->cm_message or ''}}</textarea>
                        </div>
                        <div class="col-sm-3">
                            <label>Statement Memo</label>
                            <textarea class="form-control input-sm textarea-expand" name="cm_memo" placeholder="">{{$cm->cm_memo or ''}}</textarea>
                        </div>
                        <div class="col-sm-6">
                            <div class="row">
                                <div class="col-md-7 text-right digima-table-label">
                                    Total
                                </div>
                                <div class="col-md-5 text-right digima-table-value">
                                    <input type="hidden" name="subtotal_price" class="subtotal-amount-input" />
                                    PHP&nbsp;<span class="sub-total">0.00</span>
                                    <input type="hidden" name="overall_price" class="total-amount-input" />
                                    <span class="hidden total-amount">0.00</span>
                                </div>
                            </div> 
                            <div class="row">
                                <div class="col-md-7 text-right digima-table-label">
                                    Remaining Credit
                                </div>
                                <div class="col-md-5 text-right digima-table-value total">
                                    <span class="applied-amount">{{currency('PHP',isset($total_applied_credit) ? $total_applied_credit : 0 )}}</span>
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
            <td class="invoice-number-td text-right">2</td>
            <td>
                <select class="form-control select-item input-sm pull-left" name="cmline_item_id[]">
                    @include("member.load_ajax_data.load_item_category", ['add_search' => ""])
                    <option class="hidden" value="" />
                </select>
            </td>
            @if($pis)
                <td><textarea class="textarea-expand txt-desc" name="cmline_description[]" readonly></textarea></td>
            @else
                <td><textarea class="textarea-expand txt-desc" name="cmline_description[]"></textarea></td>
            @endif
            <td><select class="select-um" name="cmline_um[]"><option class="hidden" value="" /></select></td>
            <td><input class="text-center number-input txt-qty compute" type="text" name="cmline_qty[]"/></td>
            <td><input class="text-right number-input txt-rate compute" type="text" name="cmline_rate[]"/></td>
    <!--         <td><input class="text-right txt-discount compute" type="text" name="cmline_discount[]"/></td>
            <td><textarea class="textarea-expand" type="text" name="cmline_discount_remark[]" ></textarea></td> -->
            <td><input class="text-right number-input txt-amount" type="text" name="cmline_amount[]"/></td>
       <!--      <td class="text-center">
                <input type="hidden" class="cmline_taxable" name="cmline_taxable[]" value="" >
                <input type="checkbox" name="" class="taxable-check" value="checked">
            </td> -->
            <td class="text-center remove-tr cursor-pointer"><i class="fa fa-trash-o" aria-hidden="true"></i></td>
        </tr>
    </table>
</div>
@endsection
@section('script')
<script type="text/javascript" src="/assets/member/js/textExpand.js"></script>
<script type="text/javascript" src="/assets/member/js/draggable_row.js"></script>
<script type="text/javascript" src="/assets/member/js/credit_memo.js"></script>
@endsection
@section('css')
 <style type="text/css">
.txt-desc[readonly]
{
    box-shadow: none !important; 
    outline: none !important; 
    border: 0 !important;
}
</style>
@endsection