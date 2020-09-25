@extends('member.layout')
@section('content')
<div class="panel panel-default panel-block panel-title-block" id="top">
    <div class="panel-heading">
        <div>
            <i class="fa fa-tags"></i>
            <h1>
                <span class="page-title">E-Wallet Transfer Logs</span>
            </h1>
        </div>
        <a class="pull-right btn btn-success" href="/member/mlm/wallet">Back</a>
    </div>
</div>
<div class="panel panel-default panel-block panel-title-block panel-gray col-md-6">
    <div class="tab-content">
    <form class="global-submit" method="post" action="/member/mlm/wallet/transfer/change_settings">
    {!! csrf_field() !!}
        <div id="all-orders" class="tab-pane fade in active">
              <div class="col-md-12">
                <br>
              </div>
              <div class="col-md-12">
                <label>Processing Fee</label>
              <input type="number" class="form-control" name="wallet_log_refill_settings_transfer_processing_fee" value="{{$settings->wallet_log_refill_settings_transfer_processing_fee}}">
              </div>
              <div class="col-md-12">
                <hr>
              </div>
              <div class="col-md-12">
                <button class="btn btn-primary pull-right">Submit</button>
              </div>
              <div class="col-md-12">
                <br>
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
            <th>Transferer</th>
        </thead>
        <tbody>
        @foreach($logs_transfer as $key => $value)
          <tr>
            <td>{{$value->wallet_log_transfer_date}}</td>
            <td>{{$value->wallet_log_transfer_amount}}</td>
            <td>{{$value->wallet_log_transfer_fee}}</td>
            <td>
              {{name_format_from_customer_info($value)}} ({{$value->slot_no}})
            </td>
            <td>
              {{name_format_from_customer_info($logs_recieve[$key])}} ({{$logs_recieve[$key]->slot_no}})
            </td>
          </tr>
        @endforeach
        </tbody>
      </table>
      <center>{!! $logs_transfer->render() !!}</center>
    </div>
    <!-- /.box-body -->
    <div class="box-footer clearfix">
      
    </div>
  </div>
  <!-- /.box -->
</div>  
@endsection

@section('script')


@endsection
