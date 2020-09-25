@extends('member.layout')
@section('content')
<div class="panel panel-default panel-block panel-title-block" id="top">
    <div class="panel-heading">
        <div>
            <i class="fa fa-money"></i>
            <h1>
                <span class="page-title">Encashment</span>
                <small>
                    You can set your encashment settings and process encashment in this tab.
                </small>
            </h1>
            <a href="/member/mlm/encashment/currency" target="_blank" class="panel-buttons btn btn-primary pull-right btn-custom-primary">Set currency</a>
        </div>
    </div>
</div>  
<form class="global-submit" action="/member/mlm/encashment/update/settings" method="post">
{!! csrf_field() !!}
<input type="hidden" value="{{$encashment_settings->enchasment_settings_id}}" name="enchasment_settings_id">
<div class="panel panel-default panel-block panel-title-block panel-gray col-md-5" style="margin-right: 10px">
    <div class="tab-content">
        <div id="all-orders" class="tab-pane fade in active">
            <div class="panel-heading">
                <center>Settings</center>
                <hr>
                <div class="row clearfix">
                    <div class="col-md-12" style="margin-bottom: 15px;">
                        <label>Encashment</label>
                        <select class="form-control" name="enchasment_settings_auto">
                            <option value="0" @if($encashment_settings->enchasment_settings_auto == 0) selected @endif>Auto</option>
                            <option value="1" @if($encashment_settings->enchasment_settings_auto == 1) selected @endif>Manual</option>
                        </select>
                        <span style="color:gray;">
                            <small>By choosing auto, the users, cannot request encashment. Only the admin can process all encashment</small>
                        </span>
                    </div>
                    <div class="col-md-12" style="margin-bottom: 15px;">
                        <div class="row clearfix">
                            <div class="col-md-6">
                                <label>Tax</label>
                                <input type="number" class="form-control" name="enchasment_settings_tax" value="{{$encashment_settings->enchasment_settings_tax}}">
                            </div>
                            <div class="col-md-6">
                                <label>Type</label>
                                <select class="form-control" name="enchasment_settings_tax_type">
                                    <option value="0" @if($encashment_settings->enchasment_settings_tax_type == 0) selected @endif>Fixed Amount</option>
                                    <option value="1" @if($encashment_settings->enchasment_settings_tax_type == 1) selected @endif >Percentage</option>
                                </select>
                            </div>
                            <div class="col-md-12">
                                <span style="color:gray;">
                                    <small>Just put 0 in tax if you don't want tax in encashment</small>
                                </span>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-12" style="margin-bottom: 15px;">
                        <div class="row clearfix">
                            <div class="col-md-6">
                                <label>Processing Fee</label>
                                <input type="number" class="form-control" name="enchasment_settings_p_fee" value="{{$encashment_settings->enchasment_settings_p_fee}}">
                            </div>
                            <div class="col-md-6">
                                <label>Type</label>
                                <select class="form-control" name="enchasment_settings_p_fee_type">
                                    <option value="0" @if($encashment_settings->enchasment_settings_p_fee_type == 0) selected @endif>Fixed Amount</option>
                                    <option value="1" @if($encashment_settings->enchasment_settings_p_fee_type == 1) selected @endif>Percentage</option>
                                </select>
                            </div>
                            <div class="col-md-12">
                                <span style="color:gray;">
                                    <small>Just put 0 in processing fee if you don't want processing fees in encashment</small>
                                </span>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-12" style="margin-bottom: 15px;">
                        <div class="row clearfix">
                            <div class="col-md-12">
                                <label>Minimum</label>
                                <input type="number" class="form-control" value="{{$encashment_settings->enchasment_settings_minimum}}" name="enchasment_settings_minimum">
                            </div>
                            <div class="col-md-12">
                                <span style="color:gray;">
                                    <small>If the amount did not make it to the minimum requirements, the amount will be added to the next cutoff</small>
                                </span>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-12" style="margin-bottom: 15px;">
                        <div class="row clearfix">
                            <div class="col-md-12">
                                <label>Gateway</label>
                                <select class="form-control enchasment_settings_type" name="enchasment_settings_type">
                                    @foreach($payout_gateway as $key => $value)
                                    <option value="{{$key}}" {{$encashment_settings->enchasment_settings_type == $key ? 'selected' : '' }}>{{$value}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                    @if($shop_id == 1)
                    <div class="col-md-12">
                        <hr>
                        <div class="row clearfix">
                            <div class="clearfix" style="margin-bottom: 15px;">
                                <div class="col-md-6">
                                    <label>V-Money Enable</label>
                                    <select class="form-control" name="vmoney_enable">
                                        <option value="1" {{ $vmoney_enable == 1 ? "selected" : "" }}>On</option>
                                        <option value="0" {{ $vmoney_enable == 0 ? "selected" : "" }}>Off</option>
                                    </select>
                                    <div>
                                        <span style="color:gray;">
                                            <small>Enabling of V-Money encashment</small>
                                        </span>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <label>V-Money Environment</label>
                                    <select class="form-control" name="vmoney_environment">
                                        <option value="1" {{ $vmoney_environment == 1 ? "selected" : "" }}>Production</option>
                                        <option value="0" {{ $vmoney_environment == 0 ? "selected" : "" }}>Sandbox</option>
                                    </select>
                                    <div>
                                        <span style="color:gray;">
                                            <small>V-Money merchant account credentials</small>
                                        </span>
                                    </div>
                                </div>
                            </div>
                            <div class="clearfix" style="margin-bottom: 15px;">
                                <div class="col-md-6">
                                    <label>V-Money Percentage Fee</label>
                                    <div class="input-group">
                                      <input type="number" step="any" class="form-control" name="vmoney_percent_fee" min="0" max="100" value="{{ $vmoney_percent_fee }}">
                                      <span class="input-group-addon" style="color: #333; background-color: #fff;">%</span>
                                    </div>
                                    <div>
                                        <span style="color:gray;">
                                            <small>Convenience Percentage Fee</small>
                                        </span>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <label>V-Money Fixed Fee</label>
                                    <input class="form-control" step="any" type="number" name="vmoney_fixed_fee" min="0" value="{{ $vmoney_fixed_fee }}">
                                    <div>
                                        <span style="color:gray;">
                                            <small>Convenience Fixed Fee</small>
                                        </span>
                                    </div>
                                </div>
                            </div>
                            <div class="clearfix">
                                <div class="col-md-12">
                                    <label>V-Money Minimum Encashment</label>
                                    <input type="number" step="any" class="form-control" name="vmoney_minimum_encashment" min="0" value="{{ $vmoney_minimum_encashment }}">
                                    <div>
                                        <span style="color:gray;">
                                            <small>Change the V-Money minimum encashment</small>
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endif
                    <div class="col-md-12">
                        <hr>
                        <button class="btn btn-primary pull-right">Update Settings</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
</form>
@if($encashment_settings->enchasment_settings_auto == 0)
<form class="global-submit" action="/member/mlm/encashment/process/all" method="post">
{!! csrf_field() !!}
<div class="panel panel-default panel-block panel-title-block panel-gray col-md-6" >
    <div class="tab-content">
        <div id="all-orders" class="tab-pane fade in active">
            <div class="panel-heading">
                <center>Process Encashment</center>
                <hr>
                <div class="col-md-6">
                    <label>From</label>
                <input type="text" class="form-control" name="enchasment_process_from" value="{{$from}}" readonly></input>
                </div>
                <div class="col-md-6">
                    <label>To</label>
                    <input type="date" class="form-control" name="enchasment_process_to">   
                </div>
                <div class="col-md-12">
                <hr>
                    <button class="btn btn-primary pull-right col-md-4">Process</button>
                </div>
            </div>
        </div>
    </div>
</div>
</form>
@endif
<div class="panel panel-default panel-block panel-title-block panel-gray col-md-6" >
    <div class="tab-content">
        <div id="all-orders" class="tab-pane fade in active">
            <div class="panel-heading append_encashment_settings_type">
            </div>
        </div>
    </div>
</div>  
<style type="text/css">
    .info-box{
            display: block;
            min-height: 90px;
            background: #fff;
            width: 100%;
            box-shadow: 0 1px 1px rgba(0,0,0,0.1);
            border-radius: 2px;
            margin-bottom: 15px;
    }
    .info-box-number
    {
            display: block;
            font-weight: bold;
            font-size: 18px;
    }
    .info-box-icon
    {
            border-top-left-radius: 2px;
            border-top-right-radius: 0;
            border-bottom-right-radius: 0;
            border-bottom-left-radius: 2px;
            display: block;
            float: left;
            height: 90px;
            width: 90px;
            text-align: center;
            font-size: 45px;
            line-height: 90px;
            background: rgba(0,0,0,0.2);
    }
    .bg-primary
    {
        background-color: #76b6ec !important;
    }
    .info-box-text
    {
        display: block;
        font-size: 14px;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }
    .info-box-content
    {
            padding: 5px 10px;
            margin-left: 90px;
    }
</style>

@if($encashment_settings->enchasment_settings_auto == 0)
<div class="panel panel-default panel-block panel-title-block panel-gray col-md-12" >
    <div class="tab-content">
        <div id="all-orders" class="tab-pane fade in active">
            <div class="panel-heading">
                <center>Encashment Processed</center>
                <hr>
                <div class"table-responsive">
                    <table class="table table-condensed">
                        <thead>
                            <th>Process ID</th>
                            <th>From</th>
                            <th>To</th>
                            <th>Tax</th>
                            <th>Processing Fee</th>
                            <th>Sum</th>
                            <th></th>
                        </thead>
                        <tbody>
                        @if(count($encashment_process) != 0)
                            @foreach($encashment_process as $key => $value)
                                <tr>
                                    <td>{{$value->encashment_process}}</td>
                                    <td>{{$value->enchasment_process_from}}</td>
                                    <td>{{$value->enchasment_process_to}}</td>
                                    <td>
                                        @if($value->enchasment_process_tax_type == 0)
                                            {{$value->enchasment_process_tax}}
                                        @else 
                                            {{$value->enchasment_process_tax}}%
                                        @endif
                                    </td>
                                    <td>
                                        {{$value->enchasment_process_p_fee}}
                                        @if($value->enchasment_process_p_fee_type == 1)
                                        %
                                        @endif
                                    </td>
                                    <td>{{$value->encashment_process_sum}}</td>
                                    <td><a class="btn btn-primary" href="/member/mlm/encashment/view/{{$value->encashment_process}}">View</a></td>
                                </tr>
                            @endforeach
                        @else
                            <tr>
                                <td colspan="5"><center>No Record Found.</center></td>
                            </tr>
                        @endif    
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@else

<div class="col-md-12">
    <div class="col-md-4 col-sm-6 col-xs-12">
      <div class="info-box">
        <span class="info-box-icon bg-primary"><i class="fa fa-money"></i></span>

        <div class="info-box-content">
          <span class="info-box-text">NOT REQUESTED</span>
          <span class="info-box-number">{{number_format($not_encashed)}}</span>
        </div>
        <!-- /.info-box-content -->
      </div>
      <!-- /.info-box -->
    </div> 
    <div class="col-md-4 col-sm-6 col-xs-12">
      <div class="info-box">
        <span class="info-box-icon bg-primary"><i class="fa fa-money"></i></span>

        <div class="info-box-content">
          <span class="info-box-text">REQUESTED</span>
          <span class="info-box-number">{{number_format($not_encashed_requested * (-1))}}</span>
        </div>
        <!-- /.info-box-content -->
      </div>
      <!-- /.info-box -->
    </div> 
    <div class="col-md-4 col-sm-6 col-xs-12">
      <div class="info-box">
        <span class="info-box-icon bg-primary"><i class="fa fa-money"></i></span>

        <div class="info-box-content">
          <span class="info-box-text">PROCESSED</span>
          <span class="info-box-number">{{number_format($not_encashed_encashed * (-1))}}</span>
        </div>
        <!-- /.info-box-content -->
      </div>
      <!-- /.info-box -->
    </div> 
</div>
<div class="panel panel-default panel-block panel-title-block panel-gray col-md-12" >
    <div class="tab-content">
        <div id="all-orders" class="tab-pane fade in active">
            <div class="panel-heading table-responsive">
            <center>Requested Encashment</center>
            <div class="search-filter-box">
                <div class="col-md-2" style="padding: 10px">

                    <select class="form-control request_dropdown" onchange="change_request(this)">
                        <option value="0">Requested</option>
                        <option value="1">Processed</option>
                        <!-- <option value="2">Denied</option> -->
                    </select>
                </div>
                <div class="col-md-4 col-md-offset-2" style="padding: 10px">
                    <div class="input-group">
                        <span style="background-color: #fff; cursor: pointer;" class="input-group-addon" id="basic-addon1"><i class="fa fa-search"></i></span>
                        <input type="text" class="form-control search_filter_slot" placeholder="Search By Slot" aria-describedby="basic-addon1" onkeyup="search_slot(this)">
                    </div>
                </div>
                <div class="col-md-4" style="padding: 10px">
                    <div class="input-group">
                        <span style="background-color: #fff; cursor: pointer;" class="input-group-addon" id="basic-addon1"><i class="fa fa-search"></i></span>
                        <input type="text" class="form-control search_filter_customer" placeholder="Search By Customer" aria-describedby="basic-addon1" onkeyup="search_by_customer(this)">
                    </div>
                </div>  
            </div>
                <div class="load-data" target="encashment_table" slot="50" orderby="asc">
                    <div id="encashment_table">
                    <table class="table table-bordered table-condensed">
                        <thead>
                            <td></td>
                            <th>Slot</th>
                            <th>Name</th>
                            <th class="hide">From</th>
                            <th class="hide">To</th>
                            <th>Date Requested</th>
                            <th>Tax</th>
                            <th>Processing Fee</th>
                            <th>Amount</th>
                            <th>Total</th>
                            <th>Status</th>
                            <th>View</th>
                        </thead>
                        <tbody>
                        @if(count($history) >= 1)
                          @foreach($history as $key => $value)
                          <?php 

                          $currency = $value->encashment_process_currency;
                          $convertion = $value->encashment_process_currency_convertion;
                          $tax_converted = $value->enchasment_process_tax * $convertion;
                          $process_fee = $value->enchasment_process_p_fee * $convertion;
                          $taxed = $value->encashment_process_taxed * $convertion;
                          $total = $value->wallet_log_amount * $convertion;
                          $denied = $value->wallet_log_denied_amount * $convertion;

                          ?>
                            <tr>
                              <td>
                              @if($value->encashment_process_type == 0)
                                <form class="global-submit" id="form_id_{{$value->wallet_log_id}}" role="form" method="post" action="/member/mlm/encashment/add/to/list">
                                    <input type="checkbox" name="add_to_list" onchange="$('#form_id_{{$value->wallet_log_id}}').submit();" {{$value->wallet_log_selected == 1 ? 'checked' : ''}}>
                                    <input type="hidden" name="wallet_log_id" value="{{$value->wallet_log_id}}">
                                    {!! csrf_field() !!}
                                </form>
                              @endif
                                
                              </td>
                              <td><a href="javascript:" class="popup" link="/member/mlm/slot/view/{{$value->slot_id}}" size="lg">{{$value->slot_no}}</a></td>
                              <td><a href="javascript:" class="popup" link="/member/customer/customeredit/{{$value->customer_id}}">{{ name_format_from_customer_info($value) }}</a></td>
                              <td class="hide">{{$value->enchasment_process_from}}</td>
                              <td class="hide">{{$value->enchasment_process_to}}</td>
                              <td>{{$value->wallet_log_date_created}}</td>
                              @if($value->enchasment_process_tax_type == 0)
                              <td>{{currency($currency, $tax_converted)}}</td>
                              @else
                              <td>{{$value->enchasment_process_tax}}%</td>
                              @endif

                              @if($value->enchasment_process_p_fee_type == 0)
                              <td>{{currency($currency, $process_fee)}}</td>
                              @else
                              <td>{{$value->enchasment_process_p_fee}}%</td>
                              @endif

                              <td>{{currency($currency, $taxed)}}</td>
                              <td>{{currency($currency, $total * -1)}}</td>

                              @if($value->encashment_process_type == 0)
                              <td class="alert alert-warning">Pending</td>
                              @else
                              <td class="bg-primary">Processed</td>
                              @endif

                              <td><a class="btn btn-primary" href="/member/mlm/encashment/view/{{$value->encashment_process}}">View</a></td> </tr>
                          @endforeach
                          @if($request != 1)
                          <tr>
                              <td>
                                  
                              </td>
                              <td colspan="20">
                                  <a class="btn btn-info col-md-1" href="/member/mlm/encashment/view/all/selected" target="_blank">Summary</a> 
                                  <span class="alert-warning col-md-12">
                                    This will show all checked encashment request
                                  </span>
                              </td>
                          </tr>
                          <tr>
                              <td>
                
                              </td>
                              <td colspan="20">
                                <form class="global-submit" method="post" action="/member/mlm/encashment/add/to/list/date">
                                <div class="col-md-6">
                                    <label><small style="color: gray">From:</small></label>
                                    <input type="date" class="form-control" name="from">
                                </div>
                                <div class="col-md-6">
                                    <label><small style="color: gray">To:</small></label>
                                    <input type="date" class="form-control" name="to"> 
                                </div>  
                                <div class="col-md-12">
                                    <label><small style="color: gray">Get all requested encashment by date range</small></label><br>
                                    <button class="btn btn-primary">Get</button>
                                </div>  
                                  
                              </td>
                          </tr>
                          @endif


                        @else
                        <tr>
                          <td colspan="40">
                            <center>No Encashment History.</center>
                          </td>
                        </tr>
                        @endif
                        </tbody>
                    </table>
                  <center>{!! $history->render() !!}</center>
                    </div> 
                </div>
            </div>
        </div>
    </div>
</div>            
@endif

@endsection
@section('script')
<script type="text/javascript">
function submit_done(data)
{
    if(data.status == 'success')
    {
        toastr.success(data.message);
        location.reload();
    }
    else if(data.status =='warning')
    {
        toastr.warning(data.message);
    }
    else if(data.status == 'success_new')
    {
        toastr.success(data.message);
        encashmet_type();  
        change_request(); 
    }
}
encashmet_type();
function encashmet_type() {
    var enchasment_settings_type = $('.enchasment_settings_type').val();
    $('.append_encashment_settings_type').html('<div style="margin: 100px auto;" class="loader-16-gray"></div>');
    $('.append_encashment_settings_type').load('/member/mlm/encashment/view/type/' + enchasment_settings_type);
}
var request = '0';
var slot = '0';
var customer = '0';
var link = '/member/mlm/encashment';
function change_request(search)
{
    request = $('.request_dropdown').val();
    var link_load = link + '?request=' + request;
    // console.log(link_load);
    $('#encashment_table').html('<div style="margin: 100px auto;" class="loader-16-gray"></div>');
    $('#encashment_table').load(link_load + ' #encashment_table');
}
function search_slot(search)
{
    slot = $('.search_filter_slot').val();
    var link_load = link + '?request=' + request + '&slots=' + slot;
    $('#encashment_table').html('<div style="margin: 100px auto;" class="loader-16-gray"></div>');
    $('#encashment_table').load(link_load + ' #encashment_table');
}
function search_by_customer(search)
{
    customer = $('.search_filter_customer').val();
    var link_load = link + '?request=' + request + '&customer=' + customer;
    $('#encashment_table').html('<div style="margin: 100px auto;" class="loader-16-gray"></div>');
    $('#encashment_table').load(link_load + ' #encashment_table');
}
</script>
<script type="text/javascript" src="/assets/member/js/paginate_ajax_multiple.js"></script>
@endsection
