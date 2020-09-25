<div class="panel panel-default panel-block panel-title-block col-md-12" id="top">
    <div class="panel-heading">
        <div class="clearfix">
            <center>Transacton Logs</center>
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