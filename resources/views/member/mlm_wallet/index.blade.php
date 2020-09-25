@extends('member.layout')
@section('content')
<div class="panel panel-default panel-block panel-title-block" id="top">
    <div class="panel-heading">
        <div>
            <i class="fa fa-tags"></i>
            <h1>
                <span class="page-title">E-Wallet</span>
                <small>
                   This is for viewing and adjusting wallet of customer/slots.
                </small>
            </h1>
        </div>
        <a class="pull-right btn btn-warning" href="/member/mlm/wallet/transfer">Wallet Transfer</a>
        <a class="pull-right btn btn-success" href="/member/mlm/wallet/adjust">Adjust Wallet</a>
        <a class="pull-right btn btn-primary" href="/member/mlm/wallet/refill">Wallet Refill Request</a>
    </div>
</div>
<div class="panel panel-default panel-block panel-title-block panel-gray col-md-7">
    <div class="tab-content">
        <div id="all-orders" class="tab-pane fade in active">
            <div class="panel-heading">
                <center>Wallet Logs</center>
                <div class="input-group">
                    <span style="background-color: #fff; cursor: pointer;" class="input-group-addon" id="basic-addon1"><i class="fa fa-search"></i></span>
                    <input type="text" class="form-control search_name input-lg" onChange="search_name_function(this)"placeholder="Search by Slot" aria-describedby="basic-addon1">
                </div>
                <div class="load-data" target="wallet_logs_paginate">
                    <div id="wallet_logs_paginate">
                        <div class="table-responsive">
                            <table class="table table-condensed table-bordered">
                                <thead>
                                    <th>NAME</th>
                                    <th>USERNAME</th>
                                    <th>SLOT</th>
                                    <th>CURRENT WALLET</th>
                                    <th></th>
                                </thead>
                                <tbody>
                                    @if(count($wallet_log) >= 1)
                                        @foreach($wallet_log as $key => $value)
                                        <tr>
                                            <td>{{name_format_from_customer_info($value)}}</td>
                                            <td>{{$value->mlm_username}}</td>
                                            <td>{{$value->slot_no}}</td>
                                            <td>{{$value->sum_wallet}}</td>
                                            <td><a class="btn btn-primary" href="javascript:" onClick="show_breakdown({{$value->wallet_log_slot}})">Break Down</a></td>
                                        </tr>
                                        @endforeach
                                    @else
                                    <tr>
                                        <td colspan="3"><center>No Wallet Log Available.</center></td>
                                    </tr>
                                    @endif
                                </tbody>
                            </table>
                            <center>{!! $wallet_log->render() !!}</center>
                        </div>
                    </div> 
                </div>
                
            </div>
        </div>
    </div>
</div>
<div class="panel panel-default panel-block panel-title-block panel-gray col-md-5">
    <div class="tab-content">
        <div id="all-orders" class="tab-pane fade in active">
            <div class="panel-heading">
                <center>Breakdown</center>

                <div class="table-responsive load_breakdown">
                    
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('script')
<script>
    function show_breakdown (slot_id) {
        $('.load_breakdown').html('<center><div class="loader-16-gray"></div></center>');
        $('.load_breakdown').load('/member/mlm/wallet/breakdown/' +slot_id);
    }
    function search_name_function(ito)
    {
        var search = $(ito).val();

        if(search != null)
        {
            var link = '/member/mlm/wallet?search=' + search + ' #wallet_logs_paginate';
            console.log(link);
            $('#wallet_logs_paginate').html('<center><div class="loader-16-gray"></div></center>');
            $('#wallet_logs_paginate').load(link);
        }
    }
</script>
<script type="text/javascript" src="/assets/member/js/paginate_ajax_multiple.js"></script>
@endsection
