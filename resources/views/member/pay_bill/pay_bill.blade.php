@extends('member.layout')
@section('content')
<form class="global-submit" action="{{$action}}" method="post">
    <input type="hidden" name="_token" value="{{csrf_token()}}"> 
    <input type="hidden" class="button-action" name="button_action" value="">
    <div class="panel panel-default panel-block panel-title-block" id="top">
        <div class="panel-heading">
            <div>
                <i class="fa fa-tags"></i>
                <h1>
                    <span class="page-title">Vendor &raquo; Pay Bills</span>
                    <small>
                    <!--Add a product on your website-->
                    </small>
                </h1>
                <!-- <button class="panel-buttons btn btn-custom-white pull-right" onclick="window.location='{{ URL::previous() }}'">Cancel</button>
                <button type="submit" class="panel-buttons btn btn-custom-primary pull-right" data-action="save-and-edit">Save</button>
                <button type="submit" class="panel-buttons btn btn-custom-white pull-right" data-action="save-and-new">Save and New</button> -->
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
                @if(isset($paybill))
                <div class="pull-right">
                    <div class="dropdown">
                        <button class="btn btn-custom-white dropdown-toggle" type="button" data-toggle="dropdown">More
                        <span class="caret"></span></button>
                        <ul class="dropdown-menu">
                            <!-- <li class="dropdown-header">Dropdown header 1</li> -->
                            <li><a href="/member/accounting/journal/entry/bill-payment/{{$paybill->paybill_id}}">Transaction Journal</a></li>
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
        
        <div class="tab-content rcvpymnt-container">
            <div class="row rcvpymnt-load-data">
                <div class="col-md-12" style="padding: 30px;">
                    <!-- START CONTENT -->
                    <div style="border-bottom: 1px solid #ddd; padding-bottom: 10px; margin-bottom: 10px;">
                        <div class="row clearfix">
                            <div class="col-sm-3">
                                <select class="drop-down-vendor" name="paybill_vendor_id" required>
                                    @include("member.load_ajax_data.load_vendor", ['vendor_id' => isset($paybill) ? $paybill->paybill_vendor_id : (isset($v_id) ? $v_id : '')])
                                </select>
                            </div>
                           <!--  <div class="col-sm-4">
                                <button class="btn btn-custom-white btn-sm" data-placement="bottom" data-html="true" id="example" data-content="<form><br><input type='text' class='form-control input-sm' ><br><a style='cursor:pointer' class='pull-left' onclick='$(&quot;#example&quot;).popover(&quot;hide&quot;);'>Cancel</a><a style='cursor:pointer' class='pull-right'>Find</a><br></form>" data-toggle="popover">Find by invoice no.</button>
                            </div> -->
                        </div>
                    </div>
                                    
                    <div style="border-bottom: 1px solid #ddd; padding-bottom: 10px; margin-bottom: 10px;">
                     <div class="row clearfix">
                      <div class="col-sm-2">
                                <label>Payment Date</label>
                                <input type="text" name="paybill_date" class="datepicker form-control input-sm" value="{{$paybill->paybill_date or date('m/d/y')}}" />
                            </div>
                        <div class="col-sm-3">
                            <label>Payment Method</label>
                            <select class="drop-down-payment" name="paybill_payment_method">
                                @include("member.load_ajax_data.load_payment_method", ['payment_method_id' => isset($paybill) ? $paybill->paybill_payment_method : ''])
                            </select>
                        </div>
                        <div class="col-sm-2">
                            <label>Reference No</label>
                            <input type="text" class="form-control input-sm" />
                        </div>
                        <div class="col-sm-3">
                            <label>Payment Account</label>
                            <select class="drop-down-coa" name="paybill_ap_id" required>
                                @include("member.load_ajax_data.load_chart_account", ['add_search' => "", "account_id" => isset($paybill) ? $paybill->paybill_ap_id : ''])
                            </select>
                        </div>
                        <div class="col-sm-2 pull-right">
                        	<label>Total Payment</label>
                        	<input type="text" name="paybill_total_amount" class="input-sm form-control amount-received" value="{{$paybill->paybill_total_amount or ''}}">
                        </div>
                    </div>
                   <!--  <div class="row clearfix">
                        <div class="col-sm-3">
                            <a>Accept Payment in My168shop</a>
                       </div>
                    </div> -->
                    </div>
                    <div class="row clearfix draggable-container">
                        <div class="table-responsive">
                            <div class="col-sm-12">
                                <table class="digima-table">
                                    <thead >
                                        <tr>
                                            <th style="width: 15px;"></th>
                                            <th>Description</th>
                                            <th style="width: 150px;" >Due Date</th>
                                            <th class="text-right" style="width: 120px;" class="text-right">Original Amount</th>
                                            <th class="text-right" style="width: 120px;">Balance Due</th>
                                            <th class="text-right" style="width: 120px;">Payment</th>
                                        </tr>
                                    </thead>
                                    <tbody class="tbody-item">
                                        @include('member.pay_bill.load_pay_bill_items')
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    
                    <div class="row clearfix">
                        <div class="col-sm-6">
                            <label>Memo</label>
                            <textarea class="form-control input-sm textarea-expand" name="paybill_memo" placeholder=""></textarea>
                        </div>
                        <div class="col-sm-6">
                            <div class="row">
                                <div class="col-md-7 text-right digima-table-label">
                                    Amount to Apply
                                </div>
                                <div class="col-md-5 text-right digima-table-value total">
                                    <input type="hidden" name="paybill_total_amount" class="amount-to-apply" />
                                    <span class="amount-apply">PHP 0.00</span>
                                </div>
                            </div> 
                            <div class="row">
                              <div class="col-md-7 text-right digima-table-label">
                                    Amount to Credit
                                </div>
                                <div class="col-md-5 text-right digima-table-value red">
                                    <input type="hidden" name="amount_to_credit" class="amount-to-credit" />
                                    <span class="amount-credit">PHP 0.00</span>
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
@endsection


@section('script')
<script type="text/javascript">
    $(".chosen-select").chosen({no_results_text: "The customer doesn't exist."});
    $('[data-toggle="popover"]').popover(); 
</script>
<script type="text/javascript" src="/assets/member/js/textExpand.js"></script>
<script type="text/javascript" src="/assets/member/js/pay_bill.js"></script>
@endsection