@extends('mlm.layout')
@section('content')
@section('content')
<?php 
$data['title'] = 'E-Money Wallet';
$data['sub'] = '';
$data['icon'] = 'fa fa-money';
?>
<style type="text/css">
.panel-heading
{
    background-color: white !important;
}  
</style>
@include('mlm.header.index', $data)
<div class="panel panel-default panel-block panel-title-block" id="top">
    <div class="panel-heading" style="padding-left: 0; padding-right: 0;">
        <div class="clearfix">
            <form method="post" action="/mlm/wallet/vmoney/transfer">
                {!! csrf_field() !!}
                <h3 style="margin-top: 0; margin-bottom: 20px; width: 100%; background-color: #00c0ef; color: #fff; margin-top: -10px; padding: 7.5px 15px;" class="text-left">Transfer Wallet to E-Money Wallet</h3>
                @if (session('success'))
                    <div style="margin-top: -20px; border-radius: 0;" class="alert alert-success">
                        {{ session('success') }}
                    </div>
                @endif
                @if (session('error'))
                    <div style="margin-top: -20px; border-radius: 0;" class="alert alert-danger">
                        {{ session('error') }}
                    </div>
                @endif
                <div style="padding: 0 15px;">
                    <div class="form-group">
                      <label>E-Money Email Address</label>
                      <input type="email" class="form-control" name="vmoney_email" value="{{ $customer_info->email }}" required>
                    </div>
                    <div class="row clearfix">
                        <div class="col-md-4">
                            <div class="form-group">
                              <label>Amount to convert ( Current Wallet: PHP. {{ number_format($wallet, 2) }} )</label>
                              <input step="any" type="number" class="form-control current-wallet" value="{{ $minimum }}" name="wallet_amount" min="{{ $minimum }}" max="{{ $wallet }}">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Convenience Fee</label>
                                <input class="form-control convenience-fee" type="number" disabled readonly value="0" percent="{{ $percent }}" fixed="{{ $fixed }}">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Tax</label>
                                <input class="form-control tax-fee" type="number" disabled readonly value="0">
                            </div>
                        </div>
                    </div>
                    <div class="row clearfix">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label>Total Amount</label>
                                <input class="form-control total-fee" type="number" disabled readonly value="0">
                            </div>
                        </div>
                    </div>
                    <button class="btn btn-primary pull-right" type="submit">Proceed</button>
                </div>
            </form>
        </div>
    </div>
</div>    
<div class="panel panel-default panel-block panel-title-block" id="top">
    <div class="panel-heading">
        <div class="clearfix">
            <table style="margin: 0;" class="table table-bordered table-condensed">
                <thead>
                    <th>Transaction ID</th>
                    <th>Merchant Reference</th>
                    <th>Amount</th>
                    <th>Tax</th>
                    <th>Convenience Fee</th>
                    <th>Total Amount</th>
                    <th>Recipient</th>
                    <th>Status</th>
                    <th>Date</th>
                </thead>
                <tbody>
                  @if(isset($_logs) && count($_logs) > 0)
                    @foreach($_logs as $logs)
                      <tr>
                          <td>{{ $logs->txnId }}</td>
                          <td>{{ $logs->merchantRef }}</td>
                          <td>PHP. {{ number_format($logs->vmoney_wallet_logs_amount, 2) }}</td>
                          <td>PHP. {{ number_format($logs->tax, 2) }}</td>
                          <td>PHP. {{ number_format($logs->fee, 2) }}</td>
                          <td>PHP. {{ number_format($logs->vmoney_wallet_logs_amount + $logs->tax + $logs->fee, 2) }}</td>
                          <td>{{ $logs->vmoney_wallet_logs_email }}</td>
                          <td>{{ $logs->message }}</td>
                          <td>{{ date("F d, Y", strtotime($logs->vmoney_wallet_logs_date)) }}</td>
                      </tr>
                    @endforeach
                  @else
                    <tr>
                      <td class="text-center" colspan="6">No Transaction</td>
                    </tr>
                  @endif
                </tbody>
            </table>
        </div>
    </div>
</div>        
@endsection
@section('js')
<script type="text/javascript" src="/assets/member/js/vmoney.js"></script>
@endsection