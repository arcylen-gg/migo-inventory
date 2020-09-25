<table class="table table-bordered table-condensed pos-table">
    <thead>
        <tr>
            <th class="text-left" >ITEM SKU</th>
            <th class="text-center" width="180px">REMAINING QTY</th>
            <th class="text-center" width="180px">ISSUED QUANTITY</th>
            <th class="text-center"></th>
            <th width="50px"></th>
        </tr>
    </thead>
    @if(count($_wis_item) > 0)
        <tbody  class="item-cart-pos">
            @foreach($_wis_item as $item)
            <tr class="item-info" item_id="{{ $item['item_id'] }}">
                <td class="text-left">
                    <div class="item-name">{{ $item['item_name'] }}</div>
                    <div class="item-sku">{{ $item['item_sku'] }}</div>
                </td>
                <td class="text-center">{{ $item['warehouse_qty'] }}</td>
                <td class="text-center">
                    <input type="text" class="form-control text-right quantity-item" item-id="{{$item['item_id']}}" name="item_quantity[{{$item['item_id']}}]" value="{{ $item['item_quantity'] }}">
                </td>
                <td class="text-center">
                    @if(count($item['item_serial']) > 0)
                    <a class="popup" link="/member/item/warehouse/wis/view-serial/{{$item['item_id']}}">View Serial(s) - ({{count($item['item_serial'])}})</a>
                    @else
                    <a href="javascript:">No Serial</a>
                    @endif
                </td>
                <td class="text-center red-button remove-item-from-cart"><i class="fa fa-close fa-fw"></i></td>
            </tr>
            @endforeach
        </tbody>
    @else
        <tbody>
            <tr>
                <td colspan="6" class="text-center">NO ITEM YET</td>
            </tr>
        </tbody>
        <input class="table-amount-due" type="hidden" value="PHP 0.00">
        <input class="table-grand-total" type="hidden" value="PHP 0.00">
    @endif
</table>