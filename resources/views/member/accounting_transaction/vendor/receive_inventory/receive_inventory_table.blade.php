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
        @if(count($_receive_inventory) > 0)
            @foreach($_receive_inventory as $key => $ri)
                <tr>
                    <td class="text-center">{{ $page == 1 ? $key + 1 : $number + $key + 1 }}</td>
                    <td>
                        {{ucwords($ri->vendor_company)}} <br>
                        <small> {{ucwords($ri->vendor_title_name.' '.$ri->vendor_first_name.' '.$ri->vendor_middle_name.' '.$ri->vendor_last_name.' '.$ri->vendor_suffix_name)}} </small>
                    </td>
                    <td class="text-center">{{$ri->transaction_refnum == "" ? $ri->ri_id : $ri->transaction_refnum}}</td>
                    <td class="text-center">{{date('F d, Y',strtotime($ri->ri_date))}}</td>
                    <td class="text-center">{{currency('PHP',$ri->ri_total_amount)}}</td>
                    <td class="text-center">
                        <div class="btn-group">
                            <button type="button" class="btn btn-sm btn-custom-white dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                Action <span class="caret"></span>
                            </button>
                            <ul class="dropdown-menu dropdown-menu-custom">
                                @if($ri->archived == 0)
                                <li><a href="/member/transaction/receive_inventory/create?id={{$ri->ri_id}}">Edit</a></li>
                                @endif
                                <li><a target="_blank" href="/member/transaction/receive_inventory/print?id={{$ri->ri_id}}">Print</a></li>
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
<div class="pull-right">{!! $_receive_inventory->render() !!}</div>