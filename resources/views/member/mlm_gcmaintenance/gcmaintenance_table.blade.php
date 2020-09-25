@if($_payout)
    <table class="table table-bordered table-striped table-condensed">
        <thead style="text-transform: uppercase">
            <tr>
                <th class="text-center">Process Date</th>
                <th class="text-center">Slot No</th>
                <th class="text-center">Customer Name</th>
                <th class="text-center" width="200px">Method</th>
                <th class="text-right" width="150px">Total</th>
            </tr>
        </thead>
        <tbody>
            @foreach($_payout as $payout)
            <tr>
                <td class="text-center">{{ $payout->display_date }}</td>
                <td class="text-center">{{ $payout->slot_no }}</td>
                <td class="text-center">{{ $payout->first_name }} {{ $payout->last_name }}</td>
                <td class="text-center">{{ $payout->wallet_log_plan }}</td>
                <td class="text-right"><b>{{ $payout->display_wallet_log_amount }}</b></td>
            </tr>
            @endforeach
        </tbody>
        <tfoot>
            <tr>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <th class="text-right" width="150px">{{ $total_payout }}</th>
            </tr>
        </tfoot>
    </table>
    <div class="pull-right">{!! $__payout->render() !!}</div>
@else
    <div class="text-center" style="padding: 100px 0;">NO RESULT FOUND</div>
@endif