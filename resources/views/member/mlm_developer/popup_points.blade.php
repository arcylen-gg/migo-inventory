<div class="modal-header">
    <button type="button" class="close" data-dismiss="modal">×</button>
    <h4 class="modal-title">POINTS SUMMARY FOR <b>SLOT NO. {{ $slot_info->slot_no }}</b></h4>
</div>
<div class="modal-body clearfix">
    <div class="table-responsive">
        <table class="table table-bordered table-striped table-condensed">
            <thead style="text-transform: uppercase">
                <tr> 
                    <th class="text-center" width="250px">COMPLAN</th>
                    <th class="text-center" width="250px">FROM</th>
                   <!--  <th class="text-center" width="200px">DATE</th> -->
                    <th class="text-center" width="200px">RUNNING BALANCE</th>
                    <th class="text-right" width="120px">AMOUNT</th>
                </tr>
            </thead>
            <tbody>
                @foreach($_points as $points)
                <tr>
                    <td class="text-center">{{ $points->points_log_complan }}</td>
                    <td class="text-center">{{ $points->points_log_from }}</td>
                   <!--  <td class="text-center">{{ $points->display_date }}</td> -->
                    <td class="text-center">{{ $points->running_balance }}</td>
                    <td class="text-right text-bold">{{ $points->display_amount }}</td>
                </tr>
                @endforeach
            </tbody>
            <tfoot>
                <tr>
                    <td class="text-right" colspan="3">ENDING BALANCE</td>
                    <td class="text-right text-bold" style="color: #00c5ed">{{ $log_total }}</td>
                </tr>
            </tfoot>
        </table>
    </div>
</div>