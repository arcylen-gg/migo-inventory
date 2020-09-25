@if(count($payout)>0)
    <table class="table table-bordered table-striped table-condensed">
        <thead style="text-transform: uppercase">
            <tr>
                <th class="text-center">Payout Date</th>
                <th class="text-center">Slot No</th>
                <th class="text-center">Customer</th>
                <th class="text-center" width="120px">Method</th>
                <th class="text-right" width="150px">Amount</th>
                <th class="text-right" width="120px">Tax</th>
                <th class="text-right" width="130px">Service</th>
                <th class="text-right" width="120px">Other</th>
                <th class="text-right" width="150px">Total</th>
            </tr>
        </thead>
        <tbody>
            @foreach($payout as $p)
            <tr>
                <td class="text-center">{{ $p->display_date }}</td>
                <td class="text-center">{{ $p->slot_no }}</td>
                <td class="text-center">{{ $p->first_name }} {{ $p->last_name }}</td>
                <td class="text-center">{{ $p->wallet_log_plan }}</td>
                <td class="text-right">{{ $p->display_wallet_log_request }}</td>
                <td class="text-right">{{ $p->display_wallet_log_tax }}</td>
                <td class="text-right">{{ $p->display_wallet_log_service_charge }}</td>
                <td class="text-right">{{ $p->display_wallet_log_other_charge }}</td>
                <td class="text-right"><b>{{ $p->display_wallet_log_amount }}</b></td>
            </tr>
            @endforeach
        </tbody>
        <tfoot>
            <tr>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <th class="text-right" width="120px">{{ $total_request }}</th>
                <th class="text-right" width="120px">{{ $total_tax }}</th>
                <th class="text-right" width="130px">{{ $total_service }}</th>
                <th class="text-right" width="120px">{{ $total_other }}</th>
                <th class="text-right" width="150px">{{ $total_payout }}</th>
            </tr>
        </tfoot>
    </table>
    
@else
    <div class="text-center" style="padding: 100px 0;"><b>NO DATA</b></div>
@endif
