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
        @if(count($_receive_payment) > 0)
            @foreach($_receive_payment as $key => $rp)
                <tr>
                    <td class="text-center">{{ $page == 1 ? $key + 1 : $number + $key + 1 }}</td>
                    <td>
                        {{ucwords($rp->company)}} <br>
                        <small> {{ucwords($rp->first_name.' '.$rp->middle_name.' '.$rp->last_name)}} </small>
                    </td>
                    <td class="text-center">{{$rp->transaction_refnum != "" ? $rp->transaction_refnum : $rp->rp_id}}</td>
                    <td class="text-center">{{date('F d, Y',strtotime($rp->rp_date))}}</td>
                    <td class="text-center">{{currency('',$rp->rp_total_amount)}}</td>
                    <td class="text-center">
                        <div class="btn-group">
                            <button type="button" class="btn btn-sm btn-custom-white dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                Action <span class="caret"></span>
                            </button>
                            <ul class="dropdown-menu dropdown-menu-custom">
                                @if($rp->archived == 0)
                                <li><a href="/member/transaction/receive_payment/create?id={{$rp->rp_id}}">Edit Receive Payment</a></li>
                                @endif
                                <li><a target="_blank" href="/member/transaction/receive_payment/print?id={{$rp->rp_id}}">Print</a></li>
                            </ul>
                        </div>
                    </td>
                </tr>                                    
            @endforeach
        @else
            <tr><td colspan="7" class="text-center">NO TRANSACTION YET</td></tr>
        @endif
    </tbody>
</table>
<div class="pull-right">{!! $_receive_payment->render() !!}</div>