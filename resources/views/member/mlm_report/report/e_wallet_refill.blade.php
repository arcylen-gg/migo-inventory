<div class="col-md-12 col-sm-6 col-xs-12">
  <div class="info-box">
    <span class="info-box-icon bg-primary"><i class="fa fa-money"></i></span>

    <div class="info-box-content">
      <span class="info-box-text">E-Wallet Refill Report</span>
    </div>
    <!-- /.info-box-content -->
  </div>
  <!-- /.info-box -->
</div> 
<br>
<br>
<br>
<br>
<br>
<div class="panel panel-default panel-block panel-title-block" id="top">
    <div class="panel-heading">
        <div>
            <table class="table table-condensed table-bordred">
                <thead>
                    <tr>
                        <th>V.I.P. Name</th>
                        <th>Slot</th>
                        <th>Amount Paid</th>
                        <th>Processing Fee</th>
                        <th>Amount Credited <br> E-Wallet</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    <?php 
                    $total_fee = 0; 
                    $total_all = 0; 
                    $total_credited = 0;
                    $status[0] = 'Pending';
                    $status[1] = 'Approved';
                    $status[2] = 'Denied';
                    $count[0] = 0;
                    $count[1] = 0;
                    $count[2] = 0;
                    ?>
                    @foreach($all_refill as $key => $value)
                        <tr>
                            <td>{{name_format_from_customer_info($value)}}</td>
                            <td>{{$value->slot_no}}</td>
                            <td>{{currency('PHP', $value->wallet_log_refill_amount_paid)}}</td>
                            <td>{{currency('PHP', $value->wallet_log_refill_processing_fee)}}</td>
                            <td>{{currency('PHP', $value->wallet_log_refill_amount)}}</td>
                            <?php 
                            $total_fee += $value->wallet_log_refill_processing_fee; 
                            $total_all += $value->wallet_log_refill_amount_paid;
                            $total_credited += $value->wallet_log_refill_amount;
                            $count[$value->wallet_log_refill_approved]++;
                            ?>

                            <td>
                                {{$status[$value->wallet_log_refill_approved]}}
                            </td>
                        </tr>
                    @endforeach
                    @foreach($status as $key => $value)
                        <tr>
                            <td colspan="20">
                                {{$value}} {{$count[$key]}}
                            </td>
                        </tr>
                    @endforeach
                    <tr>
                        <td colspan="20">
                            Total No. of transaction : {{count($all_refill)}}
                        </td>
                    </tr>
                    <tr>
                        <td colspan="20">
                            Total E-wallet Credited : {{currency('PHP', $total_credited)}}
                        </td>
                    </tr>
                    <tr>
                        <td colspan="20">
                            Total Processing fee colected : {{currency('PHP', $total_fee)}}
                        </td>
                    </tr>
                    <tr>
                        <td>
                            Total E-wallet Refill Amount  : {{currency('PHP', $total_all)}}
                        </td>
                    </tr>
                    
                </tbody>
            </table>
        </div>
    </div>
</div>    