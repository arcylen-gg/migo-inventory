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
        @if(count($_estimate_quotation) > 0)
            @foreach($_estimate_quotation as $key => $eq)
                <tr>
                    <td class="text-center">{{ $page == 1 ? $key + 1 : $number + $key + 1 }}</td>
                    <td>
                        {{ucwords($eq->company)}} <br>
                        <small> {{ucwords($eq->first_name.' '.$eq->middle_name.' '.$eq->last_name)}} </small>
                    </td>
                    <td class="text-center">{{$eq->transaction_refnum != "" ? $eq->transaction_refnum : $eq->est_id}}</td>
                    <td class="text-center">{{date('F d, Y',strtotime($eq->est_date))}}</td>
                    @if($tab == 'accepted')
                    <td class="text-center">{{currency('PHP',$eq->balance)}}</td>
                    @elseif($tab == 'all')
                        @if($eq->est_status == 'pending')
                        <td class="text-center" style="font-weight: bold;color: red">PENDING</td>
                        @elseif($eq->est_status == 'accepted' && $eq->balance == $eq->est_overall_price)
                        <td class="text-center" style="font-weight: bold;color: #ff3300">OPEN</td>
                        @elseif($eq->est_status == 'accepted' && $eq->balance < $eq->est_overall_price)
                        <td class="text-center" style="font-weight: bold;color: #ff9900">PARTIALLY RECEIVED</td>
                        @elseif($eq->est_status == 'closed')
                        <td class="text-center" style="font-weight: bold;color: green">FULLY RECEIVED</td>
                        @elseif($eq->est_status == 'rejected')
                        <td class="text-center" style="font-weight: bold;color: #0099ff">REJECTED</td>
                        @endif
                    @endif
                    <td class="text-center">{{currency('',$eq->est_overall_price)}}</td>
                    <td class="text-center">
                        <div class="btn-group">
                            <button type="button" class="btn btn-sm btn-custom-white dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                Action <span class="caret"></span>
                            </button>
                            <ul class="dropdown-menu dropdown-menu-custom">
                                @if($eq->archived == 0)
                                <li><a href="/member/transaction/estimate_quotation/create?id={{$eq->est_id}}">Edit Estimate & Quotation</a></li>
                                @endif
                                <li><a target="_blank" href="/member/transaction/estimate_quotation/print?id={{$eq->est_id}}">Print</a></li>
                                <li><a class="popup" size="md" link="/member/transaction/estimate_quotation/update-status?id={{$eq->est_id}}">Update Status</a></li>
                                
                                @if($status == 'accepted')
                                <li><a target="_blank" href="/member/transaction/sales_invoice/create?eq_id={{$eq->est_id}}">Sales Invoice</a></li>
                                <li><a target="_blank" href="/member/transaction/sales_receipt/create?eq_id={{$eq->est_id}}">Sales Receipt</a></li>
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
<div class="pull-right">{!! $_estimate_quotation->render() !!}</div>
@if(count($_estimate_quotation) > 0)
<div class="col-md-12">
    <div class="pull-right" style="font-size: 20px;color: green;"><strong>TOTAL : {{ $total_amount }}</strong></div>
</div>
@endif 