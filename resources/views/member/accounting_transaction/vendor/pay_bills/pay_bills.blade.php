@extends('member.layout')
@section('content')
<form class="global-submit" action="{{ $action or ''}}" method="post">
    <input type="hidden" name="_token" value="{{csrf_token()}}"> 
    <input type="hidden" class="button-action" name="button_action" value="">
    <input type="hidden" name="pb_id" class="pb-id-class" value="{{Request::input('id')}}">
    <input type="hidden" name="ebvendorid" class="eb-vendor-id" value="{{ $eb->bill_vendor_id or ''}}">
    <input type="hidden" name="ebid" class="eb-id" value="{{Request::input('eb_id')}}">
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
                    <div>
                        <a class="btn btn-custom-white" href="/member/transaction/pay_bills">Cancel</a>
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
                @if(isset($pb))
                <div class="pull-right {{isset($accounting_module) ? ($accounting_module ? '' : 'hidden') : 'hidden'}}">
                    <div class="dropdown">
                        <button class="btn btn-custom-white dropdown-toggle" type="button" data-toggle="dropdown">More
                        <span class="caret"></span></button>
                        <ul class="dropdown-menu">
                            <!-- <li class="dropdown-header">Dropdown header 1</li> -->
                            <li><a href="/member/accounting/journal/entry/bill-payment/{{$pb->paybill_id}}">Transaction Journal</a></li>
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
                    <div style="padding-bottom: 10px; margin-bottom: 10px;">
                        <div class="row clearfix">
                            <div class="col-sm-4">
                                <label>Reference Number</label>
                                <input type="text" class="form-control" name="transaction_refnumber" value="{{ isset($pb->transaction_refnum)? $pb->transaction_refnum : $transaction_refnum }}">
                            </div>
                        </div>
                    </div>
                    <div style="border-bottom: 1px solid #ddd; padding-bottom: 10px; margin-bottom: 10px;">
                        <div class="row clearfix">
                            <div class="col-sm-4">
                                <select class="drop-down-vendor" name="vendor_id" required>
                                    @include("member.load_ajax_data.load_vendor", ['vendor_id' => isset($pb) ? $pb->paybill_vendor_id : ( isset($eb->bill_vendor_id) ? $eb->bill_vendor_id : '')])
                                </select>
                            </div>
                        </div>
                    </div>   
                    <div style="border-bottom: 1px solid #ddd; padding-bottom: 10px; margin-bottom: 10px;">
                     <div class="row clearfix">
                      <div class="col-sm-2">
                                <label>Payment Date</label>
                                <input type="text" name="paybill_date" class="datepicker form-control input-sm" value="{{isset($pb->paybill_date)? date('m/d/Y', strtotime($pb->paybill_date)) : date('m/d/Y')}}" />
                            </div>
                        <div class="col-sm-3">
                            <label>Payment Method</label>
                            <select class="drop-down-payment payment-method" name="paybill_payment_method">
                                @include("member.load_ajax_data.load_payment_method", ['payment_method_id' => isset($pb) ? $pb->paybill_payment_method : ''])
                            </select>
                        </div>
                        <div class="col-sm-2">
                            <label>Reference No</label>
                            <input type="text" class="form-control input-sm paybill-refno" name="paybill_ref_num" value="{{ isset($pb->paybill_ref_num)? $pb->paybill_ref_num : '' }}" />
                        </div>
                        <div class="col-sm-3">
                            <label>Payment Account</label>
                            <select class="drop-down-coa" name="paybill_ap_id" required>
                                @include("member.load_ajax_data.load_chart_account", ['add_search' => "", "account_id" => isset($pb) ? $pb->paybill_ap_id : ''])
                            </select>
                        </div>
                        <div class="col-sm-2 pull-right">
                        	<label>Total Payment</label>
                        	<input type="text" name="paybill_total_amount" class="input-sm form-control amount-received" value="{{isset($pb->paybill_total_amount)? $pb->paybill_total_amount : ''}}" readonly="true">
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
                                        @include('member.accounting_transaction.vendor.pay_bills.load_pay_bills')
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    
                    <div class="row clearfix">
                        <div class="col-sm-6">
                            <label>Memo</label>
                            <textarea class="form-control input-sm textarea-expand" name="vendor_memo" placeholder="">{{ isset($pb->paybill_memo)? $pb->paybill_memo : ''}}</textarea>
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
<script type="text/javascript" src="/assets/member/js/accounting_transaction/vendor/pay_bills.js"></script>
@endsection