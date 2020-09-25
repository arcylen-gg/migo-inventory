 <table class="table table-bordered table-striped table-condensed">
    <thead style="text-transform: uppercase">
        <tr>
            <th width="10px">NO</th>
            <th >NAME</th>
            <th class="text-center">REFERENCE NUMBER</th>
            <th class="text-center">TRANSACTION DATE</th>
            @if($tab == 'accepted')
                <th class="text-center">REMAINING BALANCE</th>
            @elseif($tab == 'all')
                <th class="text-center">STATUS</th>
            @endif
            <th class="text-center" width="200px">TOTAL PRICE</th>
            <th class="text-center" width="200px"></th>
        </tr>
    </thead>
    <tbody>
        @if(count($_sales_order) > 0)
            @foreach($_sales_order as $key => $so)
                <tr>
                    <td class="text-center">{{ $page == 1 ? $key + 1 : $number + $key + 1 }}</td>
                    <td>
                        {{ucwords($so->company)}} <br>
                        <small> {{ucwords($so->first_name.' '.$so->middle_name.' '.$so->last_name)}} </small>
                    </td>
                    <td class="text-center">{{$so->transaction_refnum != "" ? $so->transaction_refnum : $so->est_id}}</td>
                    <td class="text-center">{{date('F d, Y',strtotime($so->est_date))}}</td>
                    @if($tab == 'accepted')
                        <td class="text-center">{{currency('PHP',$so->balance)}}</td>
                    @elseif($tab == 'all')
                        @if($so->est_status == 'accepted' && $so->balance == $so->est_overall_price)
                        <td class="text-center" style="font-weight: bold;color: #ff3300">OPEN</td>
                        @elseif($so->est_status == 'accepted' && $so->balance < $so->est_overall_price)
                        <td class="text-center" style="font-weight: bold;color: #ff9900">PARTIALLY RECEIVED</td>
                        @else
                        <td class="text-center" style="font-weight: bold;color: green">FULLY RECEIVED</td>
                        @endif
                    @endif
                    <td class="text-center">{{currency('',$so->est_overall_price)}}</td>
                    <td class="text-center">
                        <div class="btn-group">
                            <button type="button" class="btn btn-sm btn-custom-white dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                Action <span class="caret"></span>
                            </button>
                            <ul class="dropdown-menu dropdown-menu-custom">
                                @if($so->customer_archived == 0)
                                <li><a href="/member/transaction/sales_order/create?id={{$so->est_id}}">Edit Sales Order</a></li>
                                @endif
                                <li><a target="_blank" href="/member/transaction/sales_order/print?id={{$so->est_id}}">Print</a></li>
                                @if($status == 'accepted')
                                <li><a target="_blank" href="/member/transaction/sales_invoice/create?so_id={{$so->est_id}}">Sales Invoice</a></li>
                                <li><a target="_blank" href="/member/transaction/sales_receipt/create?so_id={{$so->est_id}}">Sales Receipt</a></li>

                                <li><a class="popup popup-link-reject-transaction" size="md" link="/member/transaction/sales_order/reject?so_id={{$so->est_id}}">Reject SO</a></li>
                                @endif
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
<div class="pull-right">{!! $_sales_order->render() !!}</div>
@if(count($_sales_order) > 0)
<div class="col-md-12">
    <div class="pull-right" style="font-size: 20px;color: green;"><strong>TOTAL : {{ $total_amount }}</strong></div>
</div>
@endif 