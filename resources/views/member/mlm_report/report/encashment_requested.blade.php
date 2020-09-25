<div class="col-md-12 col-sm-6 col-xs-12">
  <div class="info-box">
    <span class="info-box-icon bg-primary"><i class="fa fa-money"></i></span>

    <div class="info-box-content">
      <span class="info-box-text">Encashment</span>
      <span class="info-box-number">Requested</span>
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
            <div style="overflow-x:auto;">
                <small>
                <table class="table table-condensed table-bordered">
                    <thead>
                        <tr>
                            <th>Day Requested</th>
                            <th>Slot</th>
                            <th>Name</th>
                            <th>Amount Requested</th>
                            <th>Processing Fee</th>
                            <th>Tax</th>
                            <th>Total</th>
                            <th>Bank</th>
                            <th>Branch</th>
                            <th>Account <br>Name</th>
                            <th>Account <br>No.</th>
                            <th>Tin Number</th>
                        </tr>
                    </thead>
                    <tbody>
                    @foreach($by_day as $key => $value)
                    <tr>
                        <td>{{$key}}</td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>
                        @foreach($value as $key2 => $value2)
                            <tr>
                                <td></td>
                                <td>{{$value2->slot_no}}</td>
                                <td>{{name_format_from_customer_info($value2)}}</td>
                                
                                <?php 
                                    $req = $by_day[$key][$key2]->wallet_log_amount * (-1);
                                    $p_fee_type = $by_day[$key][$key2]->enchasment_process_p_fee_type;
                                    $p_fee = $by_day[$key][$key2]->enchasment_process_p_fee;
                                    $tax_p =    $by_day[$key][$key2]->enchasment_process_tax_type;
                                    $tax = $by_day[$key][$key2]->enchasment_process_tax;
                                    if($p_fee_type == 0)
                                    {
                                        $value2 = $req - $p_fee;
                                    }
                                    else
                                    {
                                        $p_fee = ($req * $p_fee)/100;
                                        $req = $req - $p_fee;
                                    }

                                    if($tax_p == 0)
                                    {
                                        $req = $req - $tax;
                                    }
                                    else
                                    {
                                        $tax = ($req * $tax)/100;
                                        $req = $req-$tax;
                                    }
                                ?>
                                <td>{{currency('PHP', $by_day[$key][$key2]->wallet_log_amount * -1 )}}</td>
                                <td>{{currency('PHP', $p_fee)}}</td>
                                <td>{{currency('PHP', $tax)}}</td>
                                <td>{{currency('PHP', $req)}}</td>
                                <?php $value2 = $by_day[$key][$key2]; ?>
                                @if($value2->encashment_type == 0)
                                    <td>{{$value2->bank_name}}</td>
                                    <td>{{$value2->bank_account_branch}}</td>
                                    <td>{{$value2->bank_account_name}}</td>
                                    <td>{{$value2->bank_account_number}}</td>
                                @elseif($value2->encashment_type == 1)
                                    <td colspan="4">
                                        Name on cheque: {{$value2->cheque_name}}
                                    </td>
                                @endif
                                <td>
                                    {{$value2->tin_number}}
                                </td>

                            </tr>
                        @endforeach
                    @endforeach
                    </tbody>
                </table>
                </small>
            </div>
        </div>
    </div>
</div>    