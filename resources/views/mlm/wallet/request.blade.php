@extends('mlm.layout')
@section('content')
<?php 
$data['title'] = 'Request Wallet Refill';
$data['sub'] = '';
$data['icon'] = 'fa fa-retweet';
$data['button'][0] = '<a href="/mlm/refill" class="small-box-footer pull-right">Refill Logs<i class="fa fa-arrow-circle-left"></i></a>';
?>  
@include('mlm.header.index', $data) 
<div class="col-md-6">
  <div class="box">
    <div class="box-header with-border">
      <h3 class="box-title">Request Form</h3>
    </div>
    <!-- /.box-header -->

    <form method="post" action="/mlm/refill/request/submit" enctype="multipart/form-data"> 
    {!! csrf_field() !!}
      <div class="box-body">
        <div class="col-md-12"> 
          <label>Amount Paid</label>
          <input type="number" class="form-control" name="wallet_log_refill_amount_paid" required>
        </div>  
        <div class="col-md-12">
          <label>Processing Fee</label>
          <input type="number" class="form-control" readonly name="wallet_log_refill_settings_processings_fee" value="{{$settings->wallet_log_refill_settings_processings_fee}}">
        </div>
        <div class="col-md-12">
          <label>Proof of Payment</label>
          <input type="file" class="form-control" name="wallet_log_refill_attachment" max-size="1024">
        </div>
        <div class="col-md-12">
        <label>Remarks</label>
        <textarea class="form-control" name="wallet_log_refill_remarks" required></textarea>
        
        </div>
        <div class="col-md-12">
          <label>Password</label>
          <input type="password" class="form-control" name="password" required>
        </div>
        <div class="col-md-12">
        <hr>
        <button class="btn btn-primary pull-right">Submit</button>
        </div>
      </div>
      <!-- /.box-body -->
      <div class="box-footer clearfix">
      </div>
    </form>
  </div>
  <!-- /.box -->
</div>           
@endsection
@section('script')

@endsection