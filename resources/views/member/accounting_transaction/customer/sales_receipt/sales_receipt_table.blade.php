 <table class="table table-bordered table-striped table-condensed">
    <thead style="text-transform: uppercase">
        <tr>
            <th width="10px">NO</th>
            <th >NAME</th>
            <th class="text-center">REFERENCE NUMBER</th>
            <th class="text-center">TRANSACTION DATE</th>
            @if($tab == 'undelivered')
                <th class="text-center">AMOUNT DELIVERED</th>
            @elseif($tab == 'all')
                <th class="text-center">STATUS</th>
            @endif
            <th class="text-center" width="200px">TOTAL PRICE</th>
            <th class="text-center" width="200px"></th>
        </tr>
    </thead>
    <tbody>
        @if(count($_sales_receipt) > 0)
            @foreach($_sales_receipt as $key => $sr)
                <tr>
                    <td class="text-center">{{ $page == 1 ? $key + 1 : $number + $key + 1 }}</td>
                    <td>
                        {{ucwords($sr->company)}} <br>
                        <small> {{ucwords($sr->first_name.' '.$sr->middle_name.' '.$sr->last_name)}} </small>
                    </td>
                    <td class="text-center">{{$sr->transaction_refnum != "" ? $sr->transaction_refnum : $sr->new_inv_id}}</td>
                    <td class="text-center">{{date('F d, Y',strtotime($sr->inv_date))}}</td>
                    @if($tab == 'undelivered')
                    <td class="text-center">{{currency('PHP',$sr->inv_overall_price - $sr->balance)}}</td>
                    @elseif($tab == 'all')
                        @if($sr->item_delivered == 0)
                        <td class="text-center" style="font-weight: bold;color: #ff9900">PARTIALLY DELIVERED</td>
                        @else
                        <td class="text-center" style="font-weight: bold;color: green">FULLY DELIVERED</td>
                        @endif
                    @endif
                    <td class="text-center">{{currency('',$sr->inv_overall_price)}}</td>
                    <td class="text-center">
                        <div class="btn-group">
                            <button type="button" class="btn btn-sm btn-custom-white dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                Action <span class="caret"></span>
                            </button>
                            <ul class="dropdown-menu dropdown-menu-custom">
                                @if($sr->archived == 0)
                                <li><a href="/member/transaction/sales_receipt/create?id={{$sr->inv_id}}">Edit Sales Receipt</a></li>
                                @endif
                                <li><a target="_blank" href="/member/transaction/sales_receipt/print?id={{$sr->inv_id}}">Print</a></li>
                                @if($proj == 'migo')
                                <li><a target="_blank" href="/member/transaction/sales_receipt/print?id={{$sr->inv_id}}&ptype=dr">Print DR </a></li>
                                @endif
                                @if($sr->item_delivered == 0)
                                <li><a target="_blank" href="/member/transaction/wis/create?sr_id={{$sr->inv_id}}">Warehouse Issuance Slip</a></li>
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
<div class="pull-right">{!! $_sales_receipt->render() !!}</div>
@if(count($_sales_receipt) > 0)
<div class="col-md-12">
    <div class="pull-right" style="font-size: 20px;color: green;"><strong>TOTAL : {{ $total_amount }}</strong></div>
</div>
@endif 