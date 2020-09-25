<table class="table table-bordered table-striped table-condensed">
    <thead style="text-transform: uppercase">
        @if(count($logs)>0)
        <tr>
            <!-- <th class="text-center">Plan Code</th>
            <th class="text-center">Point Log Name</th> -->
            <th class="text-center">Log Id</th>
            <th class="text-center">Date</th>
            <th class="text-center">Slot No</th>
            <th class="text-center">Amount</th>
        </tr>
        @else
        <tr>
            <th class="text-center" colspan="4">No Data</th>
        </tr>
        @endif
    </thead>
    <tbody>
        @foreach($logs as $log)
        <tr>
            <td class="text-center">{{$log->wallet_log_id}}</td>
            <td class="text-center">{{date('Y/m/d',strtotime($log->wallet_log_date_created))}}</td>
            <td class="text-center">{{$log->slot_no}}</td>
            <td class="text-center">{{currency('PHP',$log->wallet_log_amount)}}</td>
        </tr>
        @endforeach
    </tbody>
</table>
<div class="pull-right">
    {!! $logs->render() !!}
</div>