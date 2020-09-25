@extends('member.layout')
@section('content')

<div class="panel panel-default panel-block panel-title-block" id="top">
    <div class="panel-heading">
        <div>
            <i class="fa fa-tags"></i>
            <h1>
                <span class="page-title">Tours Wallet</span>
                <small>
                    Settings
                </small>
            </h1>
        </div>
    </div> 
</div>
<div class="panel panel-default panel-block panel-title-block col-md-6" id="top">
    <div class="panel-heading">
        <div>
            <form class="global-submit" action="/member/mlm/tours_wallet/update/settings">
            {!! csrf_field() !!}
                <center>Trip Option Account</center>
                <div class="pull-right"><span class="current_balance" style="color: green">Account Balance: {{number_format(isset($account_tours->tour_wallet_a_current_balance) ? $account_tours->tour_wallet_a_current_balance : 0 )}}</span></div>
                <br>
                <label>Url</label>
                <input type="text" class="form-control" value="{{$shop_information->shop_wallet_tours_uri}}" readonly="readonly">
                <label>Account ID</label>
                <input type="text" class="form-control" name="tour_Wallet_a_account_id" value="{{isset($account_tours->tour_Wallet_a_account_id) ? $account_tours->tour_Wallet_a_account_id : '' }}">
                <label>Username</label>
                <input type="text" class="form-control" name="tour_wallet_a_username" value="{{isset($account_tours->tour_wallet_a_username) ? $account_tours->tour_wallet_a_username : '' }}">
                <label>Password</label>
                <input type="password" class="form-control" name="tour_wallet_a_base_password" value="{{isset($account_tours->tour_wallet_a_base_password) ? $account_tours->tour_wallet_a_base_password : '' }}">

                <hr>
                <label>Points (%)</label>
                <input type="number" class="form-control" name="tour_wallet_convertion" value="{{isset($account_tours->tour_wallet_convertion) ? $account_tours->tour_wallet_convertion : 0 }}">
                <hr>
                <button class="btn btn-primary pull-right">Update</button>
            </form>    
        </div>
    </div>
</div>  

<div class="panel panel-default panel-block panel-title-block col-md-12" id="top">
    <div class="panel-heading">
        <div class="clearfix">
            <center>Transacton Logs</center>
            <div class="load-data" target="value-id-1">
                <div id="value-id-1">
                    <table class="table table-bordered table-condensed">
                        <thead>
                            <th>Amount</th>
                            <th>Acount Id</th>
                            <th>Status</th>
                            <th>Date</th>
                            <th>User</th>
                            <th>Points</th>
                        </thead>
                        @foreach($logs as $key => $value)
                            <tr>
                                <td>{{$value->tour_wallet_logs_wallet_amount}}</td>
                                <td>{{$value->tour_wallet_logs_account_id}}</td>
                                <td>
                                    @if($value->tour_wallet_logs_accepted == 1)
                                        Transfered
                                    @else
                                        Not yet transfered
                                    @endif
                                </td>
                                <td>
                                    {{$value->tour_wallet_logs_date}}
                                </td>
                                <td>{{name_format_from_customer_info($value)}}</td>
                                <td>{{$value->tour_wallet_logs_points}}</td>
                            </tr>
                        @endforeach
                    </table>
                    <center>{!! $logs->render() !!}</center>
                </div> 
            </div>
        </div>
    </div>
</div>       
@endsection

@section('script')
<script type="text/javascript">
 function  submit_done (data) {
    if(data.status == 1)
    {
        toastr.success(data.message + ' : ' + data.result);
        $('.current_balance').html("Account Balance: " + data.result);
    }
    else
    {
        toastr.warning(data.message);
    }
 }

</script>
<script type="text/javascript" src="/assets/member/js/paginate_ajax_multiple.js"></script>
@endsection