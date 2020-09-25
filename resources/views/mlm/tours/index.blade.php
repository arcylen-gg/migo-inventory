@extends('mlm.layout')
@section('content')
@section('content')
<?php 
$data['title'] = 'Airline Wallet';
$data['sub'] = '';
$data['icon'] = 'fa fa-plane';

?>
<style type="text/css">
  .panel-heading
  {
    background-color: white !important;
  }  
</style>
@include('mlm.header.index', $data)
<div class="panel panel-default panel-block panel-title-block col-md-12" id="top">
    <div class="panel-heading">
        <div class="clearfix">
        	<form class="global-submit" action="/mlm/wallet/tours/update">
                <center></center>
                <div class="pull-right"><span class="current_balance" style="color: green">Account Balance: {{number_format(isset($account_tours->tour_wallet_a_current_balance) ? $account_tours->tour_wallet_a_current_balance : 0 )}}</span></div>
                <hr>
        	       {!! csrf_field() !!}
            	<label>Airline Ticketing Account ID</label>
            	<input type="text" class="form-control" name="tour_Wallet_a_account_id" value="{{isset($account_tours->tour_Wallet_a_account_id) ? $account_tours->tour_Wallet_a_account_id : ''}}">

				<hr>
            	<button class="btn btn-primary pull-right">Update</button>
            </form>	
        </div>
    </div>
</div>
@if(isset($account_tours->tour_Wallet_a_account_id))
<div class="panel panel-default panel-block panel-title-block col-md-12" id="top">
    <div class="panel-heading">
        <div class="clearfix">
            <center>Transaction Logs</center>
            <table class="table table-bordered table-condensed">
                <thead>
                    <th>Amount</th>
                    <th>Acount Id</th>
                    <th>Status</th>
                    <th>Date</th>
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
                    </tr>
                @endforeach
            </table>
        </div>
    </div>
</div>        
<div class="panel panel-default panel-block panel-title-block col-md-12" id="top">
    <div class="panel-heading">
        <div class="clearfix">
        <form class="global-submit" method="post" action="/mlm/wallet/tours/transfer">
        {!! csrf_field() !!}
            <center>Transfer Wallet to Airline Wallet</center>
            <label>Wallet Amount</label>
            <input type="number" class="form-control" value="0" name="wallet_amount">
            <label>Password</label>
            <input type="password" class="form-control" placeholder="*****" name="password">
            <hr>
            <button class="btn btn-primary pull-right" onclick="$(this).addClass('hide')">Transfer</button>
            </div>
        </form>
    </div>
</div>       
@endif

@endsection
@section('js')
<script type="text/javascript">
 function  submit_done (data) {
     // body...
    if(data.status == 1)
    {
        if(data.result)
        {
            toastr.success(data.message + ' : ' + data.result);
            $('.current_balance').html("Account Balance: " + data.result);
             
        }
        else
        {
            toastr.success(data.message);
        }
        location.reload();
    }
    else
    {
        toastr.warning(data.message);
    }
 }
</script>
@endsection