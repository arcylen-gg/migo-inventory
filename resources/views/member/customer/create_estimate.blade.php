@extends('member.layout')
@section('content')
<div class="panel panel-default panel-block panel-title-block" id="top">
    <div class="panel-heading">
        <div>
            <i class="fa fa-tags"></i>
            <h1>
                <span class="page-title">Customer &raquo; Estimate</span>
                <small>
                <!--Add a product on your website-->
                </small>
            </h1>
            <button type="submit" class="panel-buttons btn btn-primary pull-right">Save and Send</button>
            <a href="/member/product/list" class="panel-buttons btn btn-default pull-right">Save</a>
        </div>
    </div>
</div>

<div class="panel panel-default panel-block panel-title-block panel-gray">
    <ul class="nav nav-tabs">
        <li class="active cursor-pointer"><a class="cursor-pointer" data-toggle="tab" href="#pending-codes"><i class="fa fa-star"></i> Estimate Information</a></li>
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
                            <button class="pull-right btn btn-default btn-sm" type="button"><i class="fa fa-plus"></i></button>
                        </div>
                        <div class="col-sm-4">
                            <input type="text" class="form-control input-sm" placeholder="E-Mail (Separate E-Mails with comma)" name=""/>
                        </div>
                    </div>
                    <div class="row clearfix">
                        <div class="col-sm-3 text-center">
                            <a style="cursor:pointer" id="popover" data-html='true'>Pending </a>
                            <div id="popover-content" class="hide popover-class">
                             <div class='row' >
                                <div class='col-sm-6'>
                                    <select id='status-select' class='form-control input-sm'>
                                        <option>Pending</option>
                                        <option>Accepted</option>
                                        <option>Closed</option>
                                        <option>Rejected</option>
                                    </select>
                                </div>
                              </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="row clearfix">
                    <div class="col-sm-3">
                        <label>Billing Address</label>
                        <textarea class="form-control input-sm" name="" placeholder=""></textarea>
                    </div>
                    <div class="col-sm-2">
                        <label>Estimate Date</label>
                        <input type="text" class="datepicker form-control input-sm"/>
                    </div>
                    <div class="col-sm-2">
                        <label>Expiration Date</label>
                        <input type="text" class="datepicker form-control input-sm"/>
                    </div>
                </div>
                
                <div class="row clearfix">
                    <div class="table-responsive">
                        <div class="col-sm-12">
                            <table class="digima-table">
                                <thead >
                                    <tr>
                                        <th style="width: 15px;"></th>
                                        <th style="width: 120px;">Service Date</th>
                                        <th style="width: 15px;" class="text-right">#</th>
                                        <th style="width: 200px;">Product/Service</th>
                                        <th>Description</th>
                                        <th style="width: 40px;">Qty</th>
                                        <th style="width: 120px;">Rate</th>
                                        <th style="width: 120px;">Amount</th>
                                    </tr>
                                </thead>
                                <tbody >
                                    <tr>
                                        <td class="text-center cursor-move move"><i class="fa fa-reorder"></i></td>
                                        <td><input type="text" class="datepicker" name=""/></td>
                                        <td class="text-right">1</td>
                                        <td><input type="text" name=""/></td>
                                        <td><textarea></textarea></td>
                                        <td><input class="text-center" type="text" name=""/></td>
                                        <td><input class="text-right" type="text" name=""/></td>
                                        <td><input class="text-right" type="text" name=""/></td>
                                    </tr>
                                    <tr>
                                        <td class="text-center cursor-move move"><i class="fa fa-reorder"></i></td>
                                        <td><input type="text" class="datepicker"  name=""/></td>
                                        <td class="text-right">2</td>
                                        <td><input type="text" name=""/></td>
                                        <td><textarea></textarea></td>
                                        <td><input class="text-center" type="text" name=""/></td>
                                        <td><input class="text-right" type="text" name=""/></td>
                                        <td><input class="text-right" type="text" name=""/></td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                
                <div class="row clearfix">
                    <div class="col-sm-3">
                        <label>Message Displayed on Invoice</label>
                        <textarea class="form-control input-sm" name="" placeholder=""></textarea>
                    </div>
                    <div class="col-sm-3">
                        <label>Statement Memo</label>
                        <textarea class="form-control input-sm" name="" placeholder=""></textarea>
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
<style type="text/css">
    .popover-class
    {
     width:auto;   
    }
</style>
@endsection


@section('script')
<script type="text/javascript">
    $(".chosen-select").chosen({no_results_text: "The customer doesn't exist."});
    $('#popover').popover({ 
    html : true,
    content: function() {
      return $("#popover-content").html();
    }
});
</script>
@endsection