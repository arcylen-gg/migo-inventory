@extends('mlm.layout')
@section('content')
<?php 
$data['title'] = 'Wallet Transfer';
$data['icon'] = 'icon-money';
?>
@include('mlm.header.index', $data)
<div class="col-md-6">
  <div class="box">
    <div class="box-header with-border">
      <h3 class="box-title">Transfer Request</h3>
    </div>
    <!-- /.box-header -->
    <form class="global-submit" action="/mlm/transfer/submit" method="post">
    {!! csrf_field() !!}
    <div class="box-body">
      <div class="col-md-12 customer_body">
          
      </div>
      <div class="col-md-12">
          <label>Slot/Membership Code (Reciever)</label>
          <input type="text" class="form-control" onchange="get_customer(this)">
      </div>
      <div class="col-md-12">
        <label>Amount</label>
        <input type="number" class="form-control" name="wallet_log_transfer_amount">
      </div>
      <div class="col-md-12">
        <label>Processing Fee</label>
        <input type="number" class="form-control" value="{{$settings->wallet_log_refill_settings_transfer_processing_fee}}" readonly>
      </div>
    </div>
    <div class="box-footer clearfix">
        <div class="col-md-12">
            <label>Password</label>
            <input type="password" class="form-control" name="password">
        </div>
        
      <div class="col-md-12">
        <br>
          <button class="btn btn-primary pull-right">Transfer</button>
      </div>
    </div>
    </form>
  </div>
</div>   
<div class="col-md-12">
  <div class="box">
    <div class="box-header with-border">
      <h3 class="box-title">Transfer Logs</h3>
    </div>
    <!-- /.box-header -->
    <div class="box-body">
      <table class="table table-bordered">
        <thead>
            <th>Date</th>
            <th>Amount</th>
            <th>Fee</th>
            <th>Reciever</th>
            <th>Name</th>
        </thead>
        <tbody>
            @foreach($logs_transfer as $key => $value)
                <tr>
                    <td>{{$value->wallet_log_transfer_date }}</td>
                    <td>{{$value->wallet_log_transfer_amount}}</td>
                    <td>{{$value->wallet_log_transfer_fee}}</td>
                    <td>{{$value->slot_no}}</td>
                    <td>{{name_format_from_customer_info($value)}}</td>
                </tr>
            @endforeach
        </tbody>
        <tbody>
        </tbody>
      </table>
    </div>
    <div class="box-footer clearfix">
      
    </div>
  </div>
</div> 
<div class="col-md-12">
  <div class="box">
    <div class="box-header with-border">
      <h3 class="box-title">Recieve Logs</h3>
    </div>
    <!-- /.box-header -->
    <div class="box-body">
      <table class="table table-bordered">
        <thead>
            <th>Date</th>
            <th>Amount</th>
            <th>Fee</th>
            <th>Sender Slot</th>
            <th></th>
        </thead>
        <tbody>
        @foreach($logs_recieve as $key => $value)
            <tr>
                <td>{{$value->wallet_log_transfer_date }}</td>
                <td>{{$value->wallet_log_transfer_amount}}</td>
                <td>{{$value->wallet_log_transfer_fee}}</td>
                <td>{{$value->slot_no}}</td>
                <td>{{name_format_from_customer_info($value)}}</td>
            </tr>
        @endforeach    
        </tbody>
        <tbody>
        </tbody>
      </table>
    </div>
    <div class="box-footer clearfix">
      
    </div>
  </div>
</div>                            
@endsection

@section('js')
<script type="text/javascript">
  function get_customer(ito)
  {
    $('.customer_body').html('Getting Customer Info ..');
    $('.customer_body').load('/mlm/transfer/get/customer/' + ito.value);
  }
  function submit_done(data)
  {
    if(data.status == 'success')
    {
        toastr.success(data.message);
        location.reload();
    }
    else
    {
        toastr.warning(data.message);
    }
  }
</script>
@endsection 