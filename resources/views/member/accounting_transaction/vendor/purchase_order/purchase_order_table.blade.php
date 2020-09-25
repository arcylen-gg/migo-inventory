<table class="table table-bordered table-striped table-condensed">
    <thead style="text-transform: uppercase">
        <tr>
            <th width="10px">NO</th>
            <th >NAME</th>
            <th class="text-center">REFERENCE NUMBER</th>
            <th class="text-center">TRANSACTION DATE</th>
            @if($tab == 'open')
                <th class="text-center">REMAINING BALANCE</th>
            @elseif($tab == 'all')
                <th class="text-center">STATUS</th>
            @endif
            <th class="text-center" width="200px">TOTAL PRICE</th>
            <th class="text-center" width="200px"></th>
        </tr>
    </thead>
    <tbody>
        @if(count($_purchase_order) > 0)
            @foreach($_purchase_order as $key => $po)
                <tr>
                    <td class="text-center">{{ $page == 1 ? $key + 1 : $number + $key + 1 }}</td>
                    <td>
                        {{ucwords($po->vendor_company)}} <br>
                        <small> {{ucwords($po->vendor_title_name.' '.$po->vendor_first_name.' '.$po->vendor_middle_name.' '.$po->vendor_last_name.' '.$po->vendor_suffix_name)}} </small>
                    </td>
                    <td class="text-center">{{$po->transaction_refnum == "" ? $po->po_id : $po->transaction_refnum}}</td>
                    <td class="text-center">{{date('F d, Y',strtotime($po->po_date))}}</td>
                    @if($tab == 'open')
                    <td class="text-center">{{currency('PHP',$po->balance)}}</td>
                    @elseif($tab == 'all')
                        @if($po->po_is_billed == 0 && $po->balance < $po->po_overall_price )
                        <td class="text-center" style="font-weight: bold;color: #ff9900">PARTIALLY RECEIVED</td>
                        @elseif($po->po_is_billed == 0 && $po->balance == $po->po_overall_price)
                        <td class="text-center" style="font-weight: bold;color: #ff3300">OPEN</td>
                        @else
                        <td class="text-center" style="font-weight: bold; color: green">FULLY RECEIVED</td>
                        @endif
                    @endif
                    <td class="text-center">{{currency('PHP',$po->po_overall_price)}}</td>
                    <td class="text-center">
                        <div class="btn-group">
                            <button type="button" class="btn btn-sm btn-custom-white dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                Action <span class="caret"></span>
                            </button>
                            <ul class="dropdown-menu dropdown-menu-custom clearfix">

                                @if($po->po_is_billed == 0 && $po->archived == 0)
                                <li class="clearfix"><a href="/member/transaction/purchase_order/create?id={{$po->po_id}}">Edit</a></li>
                                @endif
                                <li class="clearfix"><a target="_blank" href="/member/transaction/purchase_order/print?id={{$po->po_id}}">Print</a></li>
                                @if($status == 'open')
                                <li class="clearfix"><a target="_blank" href="/member/transaction/receive_inventory/create?po_id={{$po->po_id}}">Receive Inventory</a></li>
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
<div class="pull-right">{!! $_purchase_order->render() !!}</div>
@if(count($_purchase_order) > 0)
<div class="col-md-12">
    <div class="pull-right" style="font-size: 20px;color: green;"><strong>TOTAL : {{ $total_amount }}</strong></div>
</div>
@endif 