<div class="modal-header">
    <button type="button" class="close" data-dismiss="modal">×</button>
    <h4 class="modal-title">DISTRIBUTED INCOME BY <b>SLOT NO. {{ $slot_info->slot_no }}</b></h4>
</div>
<div class="modal-body clearfix">
    <div class="table-responsive">
        <table class="table table-bordered table-striped table-condensed">
            <thead style="text-transform: uppercase">
                <tr> 
                    <th class="text-center" width="250px">COMPLAN</th>
                    <th class="text-center" width="200px">DATE</th>
                    <th class="text-center" width="200px">DISTRIBUTED TO</th>
                    <th class="text-right" width="120px">AMOUNT</th>
                </tr>
            </thead>
            <tbody>
                @foreach($_wallet as $wallet)
                <tr>
                    <td class="text-center">{{ $wallet->wallet_log_plan }}</td>
                    <td class="text-center">{{ $wallet->display_date }}</td>
                    <td class="text-center">{{ $wallet->distributed_to }}</td>
                    <td class="text-right text-bold">{{ $wallet->display_amount }}</td>
                </tr>
                @endforeach
            </tbody>
            <tfoot>
                <tr>
                    <td class="text-right" colspan="3">TOTAL DISTRIBUTE</td>
                    <td class="text-right text-bold" style="color: #00c5ed">{{ $log_total }}</td>
                </tr>
            </tfoot>
        </table>
    </div>
</div>