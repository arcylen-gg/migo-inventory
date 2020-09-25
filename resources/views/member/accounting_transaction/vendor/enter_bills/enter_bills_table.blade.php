<table class="table table-bordered table-striped table-condensed">
    <thead style="text-transform: uppercase">
        <tr>
            <th width="10px">NO</th>
            <th >NAME</th>
            <th class="text-center">REFERENCE NUMBER</th>
            <th class="text-center">TRANSACTION DATE</th>
            @if($tab == 'open')
                <th class="text-center">AMOUNT PAID</th>
            @elseif($tab == 'all')
                <th class="text-center">STATUS</th>
            @endif
            <th class="text-center" width="200px">TOTAL PRICE</th>
            <th class="text-center" width="200px"></th>
        </tr>
    </thead>
    <tbody>
        @if(count($_enter_bills) > 0)
            @foreach($_enter_bills as $key => $eb)
                <tr>
                    <td class="text-center">{{ $page == 1 ? $key + 1 : $number + $key + 1 }}</td>
                    <td>
                        {{ucwords($eb->vendor_company)}} <br>
                        <small> {{ucwords($eb->vendor_title_name.' '.$eb->vendor_first_name.' '.$eb->vendor_middle_name.' '.$eb->vendor_last_name.' '.$eb->vendor_suffix_name)}} </small>
                    </td>
                    <td class="text-center">{{$eb->transaction_refnum == "" ? $eb->bill_id : $eb->transaction_refnum}}</td>
                    <td class="text-center">{{date('F d, Y',strtotime($eb->bill_date))}}</td>
                    @if($tab == 'open')
                    <td class="text-center">{{currency('PHP',$eb->bill_applied_payment)}}</td>
                    @elseif($tab == 'all')
                        @if($eb->bill_is_paid == 0 && $eb->bill_applied_payment == 0)
                        <td class="text-center" style="font-weight: bold;color: #ff3300">NO PAYMENT MADE</td>
                        @elseif($eb->bill_is_paid == 0 && $eb->bill_applied_payment > 0)
                        <td class="text-center" style="font-weight: bold;color: #ff9900">PARTIALLY PAID</td>
                        @else
                        <td class="text-center" style="font-weight: bold;color: green">FULLY PAID</td>
                        @endif
                    @endif
                    <td class="text-center">{{currency('PHP',$eb->bill_total_amount)}}</td>
                    <td class="text-center">
                        <div class="btn-group">
                            <button type="button" class="btn btn-sm btn-custom-white dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                Action <span class="caret"></span>
                            </button>
                            <ul class="dropdown-menu dropdown-menu-custom">
                                @if($eb->archived == 0)
                                <li><a href="/member/transaction/enter_bills/create?id={{$eb->bill_id}}">Edit</a></li>
                                @endif
                                <li><a target="_blank" href="/member/transaction/enter_bills/print?id={{$eb->bill_id}}">Print</a></li>
                                @if($status == 'open')
                                <li><a target="_blank" href="/member/transaction/pay_bills/create?eb_id={{$eb->bill_id}}">Pay Bill</a></li>
                                @endif
                            </ul>
                        </div>
                    </td>
                </tr>                                    
            @endforeach
        @else
            <tr><td colspan="5" class="text-center">NO TRANSACTION YET</td></tr>
        @endif
    </tbody>
</table>
<div class="pull-right">{!! $_enter_bills->render() !!}</div>
@if(count($_enter_bills) > 0)
<div class="col-md-12">
    <div class="pull-right" style="font-size: 20px;color: green;"><strong>TOTAL : {{ $total_amount }}</strong></div>
</div>
@endif 