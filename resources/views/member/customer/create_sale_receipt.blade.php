@extends('member.layout')
@section('content')
<div class="panel panel-default panel-block panel-title-block" id="top">
    <div class="panel-heading">
        <div>
            <i class="fa fa-tags"></i>
            <h1>
                <span class="page-title">Customer &raquo; Sales Receipt</span>
                <small>
                <!--Add a product on your website-->
                </small>
            </h1>
            <button type="submit" class="panel-buttons btn btn-primary pull-right">Save and Send</button>
            <a href="/member/product/list" class="panel-buttons btn btn-custom-white pull-right">Save</a>
        </div>
    </div>
</div>

<div class="panel panel-default panel-block panel-title-block panel-gray">
    <ul class="nav nav-tabs">
        <li class="active cursor-pointer"><a class="cursor-pointer" data-toggle="tab" href="#pending-codes"><i class="fa fa-star"></i> Sales Receipt Information</a></li>
        <!--<li class="cursor-pointer"><a class="cursor-pointer" data-toggle="tab" href="#used-codes"><i class="fa fa-list"></i> Activities</a></li>-->
    </ul>
    <div class="tab-content">
        <div class="row">
            <div class="col-md-12" style="padding: 30px;">
                <!-- START CONTENT -->
                <div style="border-bottom: 1px solid #ddd; padding-bottom: 10px; margin-bottom: 10px;">
                    <div class="row clearfix">
                        <div class="col-sm-3">
                            <select class="form-control chosen-select input-sm pull-left" data-placeholder="Select a Customer" style="width: calc(100% - 40px);">
                                <option value=""></option>
                                <option value="1">Guillermo Tabligan</option>
                                <option value="2">Edward Guevarra</option>
                                <option value="3">Luke Glenn Jordan</option>
                            </select>
                            <button class="pull-right btn btn-custom-white btn-sm popup" link="/member/customer/modalcreatecustomer" size="lg" data-toggle="modal" data-target="#global_modal" type="button"><i class="fa fa-plus"></i></button>
                        </div>
                        <div class="col-sm-4">
                            <input type="text" class="form-control input-sm" placeholder="E-Mail (Separate E-Mails with comma)" name=""/>
                        </div>
                    </div>
                </div>
                
                <div style="border-bottom: 1px solid #ddd; padding-bottom: 10px; margin-bottom: 10px;">
                    <div class="row clearfix">
                        <div class="col-sm-3">
                            <label>Billing Address</label>
                            <textarea class="form-control input-sm textarea-expand" name="" placeholder=""></textarea>
                        </div>
                        <div class="col-sm-2">
                            <label>Sales Receipt Date</label>
                            <input type="text" class="datepicker form-control input-sm"/>
                        </div>
                    </div>
                </div>
                
                <div style="border-bottom: 1px solid #ddd; padding-bottom: 10px; margin-bottom: 10px;">
                 <div class="row clearfix">
                    <div class="col-sm-3">
                        <label>Payment Method</label>
                        <select class="form-control pull-left chosen-select" style="width: calc(100% - 40px);">
                            <option value=""></option>
                            <option value="1">Cash</option>
                            <option value="2">Cash Express - Send Money</option>
                            <option value="3">Check</option>
                            <option value="4">Credit Card</option>
                            <option value="5">Direct Deposit</option>
                            <option value="6">Fund Transfer</option>
                            <option value="7">Online</option>
                        </select>
                         <button class="pull-right btn btn-custom-white btn-sm" type="button"><i class="fa fa-plus"></i></button>
                    </div>
                    <div class="col-sm-2">
                        <label>Reference No</label>
                        <input type="text" class="form-control input-sm"/>
                    </div>
                    <div class="col-sm-3">
                        <label>Deposit to</label>
                         <select class="form-control pull-left chosen-select" style="width: calc(100% - 40px);">
                            <option value=""></option>
                            <option value="1">BDO - PHP</option>
                            <option value="2">DMSPH Bank Account - PHP</option>
                        </select>
                         <button class="pull-right btn btn-custom-white btn-sm" type="button"><i class="fa fa-plus"></i></button>
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
                                        <th style="width: 30px;"></th>
                                        <th style="width: 150px;">Service Date</th>
                                        <th style="width: 15px;" class="text-right">#</th>
                                        <th style="width: 200px;">Product/Service</th>
                                        <th>Description</th>
                                        <th style="width: 40px;">Qty</th>
                                        <th style="width: 120px;">Rate</th>
                                        <th style="width: 120px;">Amount</th>
                                    </tr>
                                </thead>
                                <tbody class="draggable">
                                    <tr class="tr-draggable">
                                        <td class="text-center cursor-move move"><i class="fa fa-th-large colo-mid-dark-gray"></i></td>
                                        <td><input type="text" class="datepicker" name=""/></td>
                                        <td class="invoice-number-td text-right">1</td>
                                        <td><input type="text" name=""/></td>
                                        <td><textarea class="textarea-expand"></textarea></td>
                                       <td><input class="text-center number-input txt-qty" type="text" name="qty[]"/></td>
                                        <td><input class="text-right number-input txt-rate" type="text" name="rate[]"/></td>
                                        <td><input class="text-right number-input txt-amount" type="text" name="amount[]"/></td>
                                    </tr>
                                    <tr class="tr-draggable">
                                        <td class="text-center cursor-move move"><i class="fa fa-th-large colo-mid-dark-gray"></i></td>
                                        <td><input type="text" class="datepicker"  name=""/></td>
                                        <td class="invoice-number-td text-right">2</td>
                                        <td><input type="text" name=""/></td>
                                        <td><textarea class="textarea-expand"></textarea></td>
                                         <td><input class="text-center number-input txt-qty" type="text" name="qty[]"/></td>
                                        <td><input class="text-right number-input txt-rate" type="text" name="rate[]"/></td>
                                        <td><input class="text-right number-input txt-amount" type="text" name="amount[]"/></td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                
                <div class="row clearfix">
                    <div class="col-sm-3">
                        <label>Message Displayed on Invoice</label>
                        <textarea class="form-control input-sm textarea-expand" name="" placeholder=""></textarea>
                    </div>
                    <div class="col-sm-3">
                        <label>Statement Memo</label>
                        <textarea class="form-control input-sm textarea-expand" name="" placeholder=""></textarea>
                    </div>
                    <div class="col-sm-6">
                        <div class="row">
                            <div class="col-md-7 text-right digima-table-label">
                                Sub Total
                            </div>
                            <div class="col-md-5 text-right digima-table-value">
                                PHP 150,000.00
                            </div>
                        </div> 
                        <div class="row">
                            <div class="col-md-7 text-right digima-table-label">
                                <div class="row">
                                    <div class="col-sm-6 col-sm-offset-4  padding-lr-1">
                                        <select class="form-control input-sm">  
                                            <option value="1">Discount percentage</option>
                                            <option value="1">Discount value</option>
                                        </select>
                                    </div>
                                    <div class="col-sm-2  padding-lr-1"><input class="form-control input-sm text-right" type="text" name=""></div>
                                </div>
                            </div>
                            <div class="col-md-5 text-right digima-table-value">
                                PHP 10,000.00
                            </div>
                        </div> 
                        <div class="row">
                            <div class="col-md-7 text-right digima-table-label">
                                <div class="row">
                                    <div class="col-sm-4 col-sm-offset-8  padding-lr-1">
                                        <select class="form-control input-sm">  
                                            <option value="1">No Tax</option>
                                            <option value="2">Vat (12%)</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-5 text-right digima-table-value">
                                PHP 600.00
                            </div>
                        </div> 
                        <div class="row">
                            <div class="col-md-7 text-right digima-table-label">
                                Total
                            </div>
                            <div class="col-md-5 text-right digima-table-value total">
                                PHP 148,400.00
                            </div>
                        </div> 
                    </div>
                </div>
                
                <!-- END CONTENT -->
            </div>
        </div>
    </div>
</div>
@endsection


@section('script')
<script type="text/javascript">
    $(".chosen-select").chosen({no_results_text: "The customer doesn't exist."});
</script>
<script type="text/javascript" src="/assets/member/js/textExpand.js"></script>
<script type="text/javascript" src="/assets/member/js/draggable_row.js"></script>
<script type="text/javascript" src="/assets/member/js/sales_receipt.js"></script>
@endsection