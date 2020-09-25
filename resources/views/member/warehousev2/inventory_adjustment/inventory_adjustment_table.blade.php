 <table class="table table-bordered table-striped table-condensed">
    <thead style="text-transform: uppercase">
        <tr>
            <th width="10px">NO</th>
            <th >WAREHOUSE NAME</th>
            <th class="text-center">REFERENCE NUMBER</th>
            <th class="text-center">TRANSACTION DATE</th>
            <th class="text-center" width="200px">TOTAL PRICE</th>
            <th class="text-center" width="200px"></th>
        </tr>
    </thead>
    <tbody>
        @if(count($_inventory_adjustment) > 0)
            @foreach($_inventory_adjustment as $key => $adj)
                <tr>
                    <td class="text-center">{{ $page == 1 ? $key + 1 : $number + $key + 1 }}</td>
                    <td>
                        {{ucwords($adj->warehouse_name)}} 
                    </td>
                    <td class="text-center">{{$adj->transaction_refnum != "" ? $adj->transaction_refnum : $adj->inventory_adjustment_id}}</td>
                    <td class="text-center">{{date('F d, Y',strtotime($adj->date_created))}}</td>
                    <td class="text-center">{{currency('',$adj->adjustment_amount)}}</td>
                    <td class="text-center">
                        <div class="btn-group">
                            <button type="button" class="btn btn-sm btn-custom-white dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                Action <span class="caret"></span>
                            </button>
                            <ul class="dropdown-menu dropdown-menu-custom">
                                <li><a href="/member/item/warehouse/inventory_adjustment/create?id={{$adj->inventory_adjustment_id}}">Edit Inventory Adjustment</a></li>
                                <li><a target="_blank" href="/member/item/warehouse/inventory_adjustment/print?id={{$adj->inventory_adjustment_id}}">Print</a></li>
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
<div class="pull-right">{!! $_inventory_adjustment->render() !!}</div>