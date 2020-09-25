 <table class="table table-bordered table-striped table-condensed">
    <thead style="text-transform: uppercase">
        <tr>
            <th width="10px">NO</th>
            <th >NAME</th>
            <th class="text-center">REFERENCE NUMBER</th>
            <th class="text-center">TRANSACTION DATE</th>
            <th class="text-center" width="200px">TOTAL PRICE</th>
            <th class="text-center" width="200px"></th>
        </tr>
    </thead>
    <tbody>
        @if(count($_credit_memo) > 0)
            @foreach($_credit_memo as $key => $cm)
                <tr>
                    <td class="text-center">{{ $page == 1 ? $key + 1 : $number + $key + 1 }}</td>
                    <td>
                        {{ucwords($cm->company)}} <br>
                        <small> {{ucwords($cm->first_name.' '.$cm->middle_name.' '.$cm->last_name)}} </small>
                    </td>
                    <td class="text-center">{{$cm->transaction_refnum != "" ? $cm->transaction_refnum : $cm->cm_id}}</td>
                    <td class="text-center">{{date('F d, Y',strtotime($cm->cm_date))}}</td>
                    <td class="text-center">{{currency('',$cm->cm_amount)}}</td>
                    <td class="text-center">
                        <div class="btn-group">
                            <button type="button" class="btn btn-sm btn-custom-white dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                Action <span class="caret"></span>
                            </button>
                            <ul class="dropdown-menu dropdown-menu-custom">
                                @if($cm->archived == 0)
                                <li><a href="/member/transaction/credit_memo/create?id={{$cm->cm_id}}">Edit Credit Memo</a></li>
                                @endif
                                <li><a target="_blank" href="/member/transaction/credit_memo/print?id={{$cm->cm_id}}">Print</a></li>
                                @if($cm->cm_used_ref_name == 'retain_credit')
                                <li><a href="/member/transaction/receive_payment/create?type=credit_memo&cm_id={{$cm->cm_id}}" target="_blank">Apply to Invoice</a></li>
                                <li><a href="/member/transaction/write_check/create?type=credit_memo&cm_id={{$cm->cm_id}}" target="_blank">Give Refund</a></li>
                                @endif
                            </ul>
                        </div>
                    </td>
                </tr>                                    
            @endforeach
        @else
            <tr><td colspan="6" class="text-center">NO TRANSACTION YET</td></tr>
        @endif
    </tbody>
</table>
<div class="pull-right">{!! $_credit_memo->render() !!}</div>
@if(count($_credit_memo) > 0)
<div class="col-md-12">
    <div class="pull-right" style="font-size: 20px;color: green;"><strong>TOTAL : {{ $total_amount }}</strong></div>
</div>
@endif 