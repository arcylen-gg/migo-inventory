<table class="table table-bordered table-striped table-condensed">
    <thead style="text-transform: uppercase">
        @if(count($refills)>0)
        <tr>
            <th class="text-center">Date Requested</th>
            @if($status!=0)
                @if($status==1)
                <th class="text-center">Date Approved</th>
                @else if($status==2)
                <th class="text-center">Date Rejected</th>
                @endif
            @endif
            <th class="text-center">Refill Amount</th>
            <th class="text-center" width="120px">Amount Paid</th>
            <th class="text-center" width="120px">Remark</th>
            <th class="text-center">Attachment</th>
        </tr>
        @else
        <tr>
            <th class="text-center" colspan="6">No Data</th>
        </tr>
        @endif
    </thead>
    <tbody>
        @foreach($refills as $refill)
        <tr id="{{$refill->wallet_log_refill_id}}">
            <td class="text-center">{{$refill->wallet_log_refill_date}}</td>
            @if($status!=0)
            <td class="text-center">{{$refill->wallet_log_refill_date_approved}}</td>
            @endif
            <td class="text-center">{{$refill->wallet_log_refill_amount}}</td>
            <td class="text-center">{{$refill->wallet_log_refill_amount_paid}}</td>
            <td class="text-center" width="250px">
                @if($refill->wallet_log_remarks!="")
                {{$refill->wallet_log_remarks}}
                @else
                No Remarks
                @endif
            </td>
            <td class="text-center">
                @if($refill->wallet_log_refill_attachment!="")
                <a href="{{$refill->wallet_log_refill_attachment}}">View</a>
                @else
                <a class="action-upload" href="javascript:">Upload</a>
                @endif
            </td>
        </tr>
        @endforeach
    </tbody>
</table>
<!-- <script type="text/javascript" src="/themes/{{ $shop_theme }}/js/wallet_refill.js"></script> -->
