<div class="col-sm-12">
    <table class="digima-table">
        <thead >
            <tr>
                <th class="text-center" style="width: 40px">Product ID</th>
                <th style="width: 150px;">Product Name</th>
                <th style="width: 100px;">Product SKU</th>
                <th style="width: 100px;">Current Stocks</th>
                @if($pis != 0)
                <th style="width: 100px;">SIR Stocks</th>
                @endif
            </tr>
        </thead>
        <tbody class="draggable">
            @if($warehouse_item != null)
            @foreach($warehouse_item as $key => $w_item)
            <tr class="tr-draggable tr-draggable-html count_row">
                <td class="text-center"> {{$w_item->product_id}}</td>
                <td class="">
                    <label class="count-select">{{$w_item->product_name}}</label>
                </td>
                <td><label class="sku-txt sku-txt{{$key+1}}">{{$w_item->product_sku}}</label></td>
                <td class="text-center">
                    @if($w_item->product_warehouse_stocks <= $w_item->product_reorder_point)
                    <label style="color: red">{{$w_item->product_qty_um}}</label>
                    @else
                    <label >{{$w_item->product_qty_um}}</label>
                    @endif
                </td>
                @if($pis != 0)
                <td class="text-center"><a class="popup" link="/warehouse/sir/{{$warehouse->warehouse_id}}/{{$w_item->product_id}}" size="md">{{$w_item->total_stock_sir}}</a></td>
                @endif
            </tr>
            @endforeach
            @endif
        </tbody>
    </table>
</div>
<div class="text-center pull-right">
    {!!$warehouse_item->render()!!}
</div>