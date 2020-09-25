<div class="col-sm-12">
    <table class="digima-table">
        <thead >
            <tr>
                <th style="width: 10px" class="text-center">#</th>
                <th class="text-center" style="width: 100px">ITEM DETAILS</th>
                <th class="text-center" style="width: 300px">ITEM DESCRIPTION</th>
                <th class="text-center" style="width: 100px;">ACTUAL STOCKS</th>
                @if($pis != 0)
                <th class="text-center" style="width: 100px;">SIR Stocks</th>
                <th class="text-center" style="width: 100px;">ON HAND STOCKS</th>
                @endif
            </tr>
        </thead>
        <tbody class="draggable">
            @if(count($warehouse_item_bundle) > 0)
                @if($warehouse_item_bundle != null)
                    @foreach($warehouse_item_bundle as $keys => $w_bundle_item)
                    <tr class="tr-draggable tr-draggable-html count_row">
                        <td>{{$keys+1}}</td>
                        <td>
                            <label class="count-select">
                                <div><strong>{{$w_bundle_item["bundle_item_name"]}}</strong> </div>
                                <small>{{$w_bundle_item["bundle_item_bardcode"]}}</small>
                            </label>
                        </td>
                        <td >
                            <p> {{ $w_bundle_item['bundle_item_description'] }}</p>
                        </td>
                        <td >
                            <label >{{$w_bundle_item["bundle_actual_stocks_um"]}}</label>
                        </td>
                        @if($pis != 0)
                        <td ><a class="popup" link="/warehouse/sir/{{$warehouse->warehouse_id}}/{{$w_bundle_item['bundle_id']}}" size="md">{{$w_bundle_item["total_stock_sir"]}}</a></td>
                        @endif
                        <td >
                            <label >{{$w_bundle_item["bundle_current_stocks_um"]}}</label>
                        </td>
                    </tr>
                    @endforeach
                @endif
            @else
            <tr>
                <td colspan="6" class="text-center">NO ITEM</td>
            </tr>
            @endif
        </tbody>
    </table>
</div>