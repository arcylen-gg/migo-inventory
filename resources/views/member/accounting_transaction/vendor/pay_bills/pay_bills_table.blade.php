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
        @if(count($_pay_bills) > 0)
            @foreach($_pay_bills as $key => $pb)
                <tr>
                    <td class="text-center">{{ $page == 1 ? $key + 1 : $number + $key + 1 }}</td> 
                    <td>
                        {{ucwords($pb->vendor_company)}} <br>
                        <small> {{ucwords($pb->vendor_title_name.' '.$pb->vendor_first_name.' '.$pb->vendor_middle_name.' '.$pb->vendor_last_name.' '.$pb->vendor_suffix_name)}} </small>
                    </td>
                    <td class="text-center">{{$pb->transaction_refnum == "" ? $pb->paybill_id : $pb->transaction_refnum}}</td>
                    <td class="text-center">{{date('F d, Y',strtotime($pb->paybill_date))}}</td>
                    <td class="text-center">{{currency('PHP',$pb->paybill_total_amount)}}</td>
                    <td class="text-center">
                        <div class="btn-group">
                            <button type="button" class="btn btn-sm btn-custom-white dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                Action <span class="caret"></span>
                            </button>
                            <ul class="dropdown-menu dropdown-menu-custom">
                                @if($pb->archived == 0)
                                <li><a href="/member/transaction/pay_bills/create?id={{$pb->paybill_id}}">Edit</a></li>
                                @endif
                                <li><a href="/member/transaction/pay_bills/print?id={{$pb->paybill_id}}">Print</a></li>
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
<div class="pull-right">{!! $_pay_bills->render() !!}</div>