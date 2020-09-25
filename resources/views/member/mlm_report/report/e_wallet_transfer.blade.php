<div class="col-md-12">
  <div class="box">
    <div class="box-header with-border">
      <h3 class="box-title">E-Wallet Transfer Report</h3>
    </div>
    <!-- /.box-header -->
    <div class="box-body">
      <table class="table table-bordered">
        <thead>
          <tr>
            <th>Sender's Name</th>
            <th>Slot</th>
            <th>Reciever's Name</th>
            <th>Slot</th>
            <th>Amount Transfered</th>
            <th>Processing Fee</th>
            <th>Date/Time</th>
          </tr>  
        </thead>
        <tbody>
        <?php $p_fee = 0; 
              $p_amount = 0;
        ?>
        @foreach($logs_transfer as $key => $value)
          <tr>
            <td>{{name_format_from_customer_info($logs_recieve[$key])}}</td>
            <td>({{$logs_recieve[$key]->slot_no}})</td>
            <td>{{name_format_from_customer_info($value)}}</td>
            <td>({{$value->slot_no}})</td>
            <td>{{$value->wallet_log_transfer_amount}}</td>
            <?php $p_amount += $value->wallet_log_transfer_amount; ?>
            <td>{{$value->wallet_log_transfer_fee}}</td>
            <?php $p_fee += $value->wallet_log_transfer_fee; ?>
            <td>{{$value->wallet_log_transfer_date}}</td>
          </tr>
        @endforeach
        <tr>
          <td colspan="20">
            Total Number of transaction: {{count($logs_transfer)}}
          </td>
        </tr>
        <tr>
          <td colspan="20">
            Total Processing Fee Colected: {{currency('PHP', $p_fee)}}
          </td>
        </tr>
        <tr>
          <td colspan="20">
            Total E-Wallet Amount Transfered: {{currency('PHP', $p_amount)}}
          </td>
        </tr>
        </tbody>

      </table>
    </div>
    <!-- /.box-body -->
    <div class="box-footer clearfix">
      
    </div>
  </div>
  <!-- /.box -->
</div>  