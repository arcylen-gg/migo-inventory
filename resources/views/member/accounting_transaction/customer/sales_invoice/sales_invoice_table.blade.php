 <table class="table table-bordered table-striped table-condensed">
    <thead style="text-transform: uppercase">
        <tr>
            <th width="10px">NO</th>
            <th >NAME</th>
            <th class="text-center">REFERENCE NUMBER</th>
            <th class="text-center">TRANSACTION DATE</th>
            @if($tab == 'open')
                <th class="text-center">AMOUNT DELIVERED</th>
                <th class="text-center">AMOUNT PAID</th>
            @elseif($tab == 'all')
                <th class="text-center">STATUS</th>
            @endif
            <th class="text-center" width="200px">TOTAL PRICE</th>
            @if($tab == 'open')
                <th class="text-center">BALANCE</th>
            @endif
            <th class="text-center" width="200px"></th>
        </tr>
    </thead>
    <tbody>
        @if(count($_sales_invoice) > 0)
            @foreach($_sales_invoice as $key => $si)
                <tr>
                    <td class="text-center">{{ $page == 1 ? $key + 1 : $number + $key + 1 }}</td>
                    <td>
                        {{ucwords($si->company)}} <br>
                        <small> {{ucwords($si->first_name.' '.$si->middle_name.' '.$si->last_name)}} </small>
                    </td>
                    <td class="text-center">{{$si->transaction_refnum != "" ? $si->transaction_refnum : $si->new_inv_id}}</td>
                    <td class="text-center">{{date('F d, Y',strtotime($si->inv_date))}}</td>
                    @if($tab == 'open')
                    <!-- <td class="text-center">{{currency('PHP',$si->balance)}}</td> -->
                    <td class="text-center">{{currency('PHP',$si->inv_overall_price - $si->balance)}}</td>
                    <td class="text-center">{{currency('PHP',$si->inv_payment_applied)}}</td>
                    @elseif($tab == 'all')
                        @if($si->inv_is_paid == 0 && $si->balance < $si->inv_overall_price)
                        <td class="text-center" style="font-weight: bold;color: #ff9900">PARTIALLY RECEIVED</td>
                        @elseif($si->inv_is_paid == 0 && $si->balance == $si->inv_overall_price)
                        <td class="text-center" style="font-weight: bold;color: #ff3300">OPEN</td>
                        @else
                        <td class="text-center" style="font-weight: bold;color: green">FULLY RECEIVED</td>
                        @endif
                    @endif
                    <td class="text-center">{{currency('PHP',$si->inv_overall_price)}}</td>
                    @if($tab == 'open')
                        <td class="text-center">{{currency('PHP',$si->inv_overall_price - $si->inv_payment_applied)}}</td>
                    @endif
                    <td class="text-center">
                        <div class="btn-group">
                            <button type="button" class="btn btn-sm btn-custom-white dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                Action <span class="caret"></span>
                            </button>
                            <ul class="dropdown-menu dropdown-menu-custom">
                                @if($si->archived == 0)
                                <li><a href="/member/transaction/sales_invoice/create?id={{$si->inv_id}}">Edit Sales Invoice</a></li>
                                @endif
                                <li><a target="_blank" href="/member/transaction/sales_invoice/print?id={{$si->inv_id}}">Print</a></li>
                                @if($proj == 'migo')
                                <li><a target="_blank" href="/member/transaction/sales_invoice/print?id={{$si->inv_id}}&ptype=dr">Print DR </a></li>
                                @endif
                                @if($si->item_delivered == 0)
                                <li><a target="_blank" href="/member/transaction/wis/create?si_id={{$si->inv_id}}">Warehouse Issuance Slip</a></li>
                                @endif
                                @if($si->inv_is_paid == 0)
                                <li><a target="_blank" href="/member/transaction/receive_payment/create?si_id={{$si->inv_id}}">Receive Payment</a></li>
                                @endif
                            </ul>
                        </div>
                    </td>
                </tr>                                    
            @endforeach
        @else
            <tr><td colspan="9" class="text-center">NO TRANSACTION YET</td></tr>
        @endif
    </tbody>
</table>
<div class="pull-right">{!! $_sales_invoice->render() !!}</div>
@if(count($_sales_invoice) > 0)
<div class="col-md-12">
    <div class="pull-right" style="font-size: 20px;color: green;"><strong>TOTAL : {{ $total_amount }}</strong></div>
</div>
@endif 