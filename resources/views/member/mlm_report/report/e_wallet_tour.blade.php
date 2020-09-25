<div class="col-md-12 col-sm-6 col-xs-12">
  <div class="info-box">
    <span class="info-box-icon bg-primary"><i class="fa fa-money"></i></span>

    <div class="info-box-content">
      <span class="info-box-text">E-Wallet -> Tour Wallet Report'</span>
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
            <table class="table table-condensed table-bordered">
                <thead>
                    <tr>
                        <th>V.I.P</th>
                        <th>Slot</th>
                        <th>Amount Converted</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $total = 0; ?>
                    @foreach($logs as $key => $value)
                    <tr>
                        <td>{{name_format_from_customer_info($value)}}</td>
                        <td>{{$value->slot_no}}</td>
                        <td>{{currency('PHP', $value->tour_wallet_logs_wallet_amount)}}</td>
                        <?php 
                        $total += $value->tour_wallet_logs_wallet_amount;
                        ?>
                    </tr>
                    @endforeach
                    <tr>
                        <td colspan="20">
                            Total Number of Transactions: {{count($logs)}}
                        </td>
                    </tr>
                    <tr>
                        <td colspan="20">
                            Total Amount Converted: {{currency('PHP', $total)}}
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>    