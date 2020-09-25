@if(count($_reorder_point) > 0)
@foreach($_reorder_point as $item)
<tr class="tr-draggable">
    <input type="hidden" name="item_ref_name[]" value=""></td>
            <input type="hidden" name="item_ref_id[]" value=""></td>
    <td class="invoice-number-td text-center">1</td>
    <td>
        <select class="form-control droplist-item input-sm select-item" name="rs_item_id[]" >
            @include("member.load_ajax_data.load_item_category", ['add_search' => "", 'item_id' => $item['item_id']])
        </select>
    </td>
    <td><textarea class="textarea-expand txt-desc" name="rs_item_description[]" readonly="true">{{$item['item_description']}}</textarea></td>
    <td>
        <select class="droplist-item-um select-um {{isset($item['item_um']) ? 'has-value' : ''}}" name="rs_item_um[]">
            @if($item['item_um'])
                @include("member.load_ajax_data.load_one_unit_measure", ['item_um_id' => $item['multi_um_id'], 'selected_um_id' => $item['item_um']])
            @else
                <option class="hidden" value="" />
            @endif
        </select>
    </td>
    <td><input class="text-center number-input txt-remain-qty" type="text" name="rs_rem_qty[]" value="{{$item['item_rem']}}" readonly="true" /></td>
    <td><input class="text-center number-input txt-qty compute" type="text" name="rs_item_qty[]" value="{{$item['item_qty']}}" /></td>
    <td><input class="text-right number-input txt-rate compute" type="text" name="rs_item_rate[]" value="{{$item['item_rate']}}" /></td>
    <td><input class="text-right number-input txt-amount" type="text" name="rs_item_amount[]" value="{{$item['item_amount']}}"/></td>
    <td>
        <select class="droplist-vendor select-vendor" name="rs_vendor_id[]">
            @include('member.load_ajax_data.load_vendor')
        </select>
    </td>
    <td class="text-center remove-tr cursor-pointer"><i class="fa fa-trash-o" aria-hidden="true"></i></td>
</tr>
@endforeach
@endif