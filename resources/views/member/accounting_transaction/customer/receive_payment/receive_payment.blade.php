@extends('member.layout')
@section('content')
<form class="global-submit" action="{{$action or ''}}" method="post">
  <div class="panel panel-default panel-block panel-title-block">
    <input type="hidden" class="button-action" name="button_action" value="">
    <input type="hidden" class="rp-id-class" name="rp_id" value="{{Request::input('id')}}">
    <input type="hidden" name="_token" id="_token" value="{{csrf_token()}}"/>
    <input type="hidden" name="c_id" class="c-id" value="{{$c_id or ''}}"/>
    <input type="hidden" class="si-id" value="{{Request::input('si_id')}}"/>

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
        <div class="tab-content rcvpymnt-container">
            <div class="row rcvpymnt-load-data">
                <div class="col-md-12" style="padding: 30px;">
                    <div style="padding-bottom: 10px; margin-bottom: 10px;">
                        <div class="row clearfix">
                            <div class="col-sm-4">
                                <label>Reference Number</label>
                                <input type="text" class="form-control" name="transaction_refnumber" value="{{isset($receive_payment->transaction_refnum)? $receive_payment->transaction_refnum : $transaction_refnum}}">
                            </div>
                        </div>
                    </div>
                    <!-- START CONTENT -->
                    <div style="border-bottom: 1px solid #ddd; padding-bottom: 10px; margin-bottom: 10px;">
                        <div class="row clearfix">
                            <div class="col-sm-4">
                                <select class="drop-down-customer" name="customer_id" required>
                                    @include("member.load_ajax_data.load_customer", ['customer_id' => (isset($receive_payment) ? $receive_payment->rp_customer_id : ($c_id ? $c_id : ''))])
                                </select>
                            </div>
                            <div class="col-sm-4">
                                <input type="text" class="form-control input-sm customer-email" name="customer_email" placeholder="E-Mail (Separate E-Mails with comma)" value="{{$receive_payment->rp_customer_email or ''}}"/>
                            </div>                          
                            <!-- <div class="col-sm-4">
                                <button class="btn btn-custom-white btn-sm" data-placement="bottom" data-html="true" id="example" data-content="<form><br><input type='text' class='form-control input-sm' ><br><a style='cursor:pointer' class='pull-left' onclick='$(&quot;#example&quot;).popover(&quot;hide&quot;);'>Cancel</a><a style='cursor:pointer' class='pull-right'>Find</a><br></form>" data-toggle="popover">Find by invoice no.</button>
                            </div> -->
                            <!-- <div class="pull-right col-sm-6 text-right">
                                <h4><a class="popup popup-link-credit" size="md" link="/member/customer/receive_payment/apply_credit"><i class="fa fa-address-card"></i> Apply Available Credits</a></h4>
                            </div> -->
                            <div class="col-sm-4 text-right open-transaction" style="display: none;">
                                <h4><a class="popup popup-link-open-transaction" size="md" link="/member/transaction/receive_payment/load-credit"><i class="fa fa-handshake-o"></i> <span class="count-open-transaction">0</span> Available Credits</a></h4>
                            </div>
                        </div>
                    </div>
                                
                    <div style="border-bottom: 1px solid #ddd; padding-bottom: 10px; margin-bottom: 10px;">
                        <div class="row clearfix">
                            <div class="col-sm-2">
                                    <label>Payment Date</label>
                                    <input type="text" class="datepicker form-control input-sm" name="transaction_date" value="{{isset($receive_payment) ? dateFormat($receive_payment->rp_date) : date('m/d/y')}}" />
                                </div>
                            <div class="col-sm-3">
                                <label>Payment Method</label>
                                <select class="drop-down-payment" name="transaction_payment_method">
                                    @include("member.load_ajax_data.load_payment_method", ['payment_method_id' => isset($receive_payment) ? $receive_payment->rp_payment_method : ''])
                                </select>
                            </div>
                            <div class="col-sm-2">
                                <label>Reference No</label>
                                <input type="text" class="form-control input-sm rcvpymnt-refno" name="transaction_ref_no" id="rcvpymnt-refno" value="{{$receive_payment->rp_payment_ref_no or ''}}" />
                            </div>
                            <div class="col-sm-3">
                                <label>Deposit to</label>
                                <select class="drop-down-coa" name="rp_ar_account" required>
                                    @include("member.load_ajax_data.load_chart_account", ['add_search' => "", "account_id" => isset($receive_payment) ? $receive_payment->rp_ar_account : ''])
                                </select>
                            </div>
                            <div class="col-sm-2 pull-right">
                                <label>Amount Received</label>
                                <input type="text" class="input-sm form-control amount-received" value="{{$receive_payment->rp_total_amount or (isset($cm_data) ? $cm_data->cm_amount : '' )}}" readonly="true">
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
                                            <th style="width: 15px;"><input type="checkbox" class="check-all" name=""></th>
                                            <th>Description</th>
                                            <th style="width: 150px;" >Due Date</th>
                                            <th class="text-right" style="width: 120px;" class="text-right">Original Amount</th>
                                            <th class="text-right" style="width: 120px;">Balance Due</th>
                                            <th class="text-right" style="width: 120px;">Payment</th>
                                        </tr>
                                    </thead>
                                    @include("member.accounting_transaction.loading_items")
                                    <tbody class="tbody-item">
                                        @include('member.receive_payment.load_receive_payment_items')
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                        
                    <div class="row clearfix">
                        <div class="col-sm-6">
                            <label>Memo</label>
                            <textarea class="form-control input-sm textarea-expand" name="customer_memo" placeholder="">{{$receive_payment->rp_memo or ''}}</textarea>
                        </div>
                        <div class="col-sm-6">
                            <div class="row">
                                <div class="col-md-7 text-right digima-table-label">
                                    Amount to Apply
                                </div>
                                <div class="col-md-5 text-right digima-table-value total">
                                    <input type="hidden" name="rp_total_amount" class="amount-to-apply" />
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
                            <div class="load-applied-credit">
                                @include("member.accounting_transaction.customer.receive_payment.load_applied_cm")
                            </div>
                            <div class="row">
                                <div class="col-md-7 text-right digima-table-label">
                                    <h4><strong>Total Amount Paid</strong></h4>
                                </div>
                                <div class="col-md-5 text-right digima-table-value total">
                                    <input type="hidden" name="total_amount_paid" class="total-amount-paid" />
                                    <h4><strong class="total-amount-paid-span" style="color: green">PHP 0.00</strong></h4>
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
</form>
@endsection


@section('script')
<script type="text/javascript" src="/assets/member/js/accounting_transaction/customer/receive_payment.js"></script>
@endsection