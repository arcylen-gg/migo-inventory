 @if(count($_reorder_point) > 0)
 @foreach($_reorder_point as $item)
 <tr class="tr-draggable">
    <input type="hidden" name="item_ref_name[]" value=""></td>
    <input type="hidden" name="item_ref_id[]" value=""></td>
    <td class="invoice-number-td text-right">1</td>
    <td><input type="text" class="for-datepicker" name="item_servicedate[]" /></td>
    <td>                                                                            
        <select class="form-control select-item droplist-item input-sm pull-left" name="item_id[]" required>
            @include("member.load_ajax_data.load_item_category", ['add_search' => "", 'item_id' => $item['item_id']])
        </select>                                                                       
    </td>
    <td>
        <textarea class="textarea-expand txt-desc" name="item_description[]">{{$item['item_description']}}</textarea>
    </td>
    @if($check_settings == 1)
    <td>
        <select class="form-control droplist-sub-warehouse select-sub-warehouse input-sm" name="item_sub_warehouse[]" >
            @include('member.warehousev2.load_sub_warehouse_v2_select')
            <option class="hidden" value="" />
        </select>
    </td>
    @endif
    <td>
        <select class="1111 droplist-um select-um {{isset($item['item_um']) ? 'has-value' : ''}}" name="item_um[]">
            @if($item['item_um'])
                @include("member.load_ajax_data.load_one_unit_measure", ['item_um_id' => $item['multi_um_id'], 'selected_um_id' => $item['item_um']])
            @else
                <option class="hidden" value="" />
            @endif
        </select>
    </td>
    <td><input class="text-center number-input txt-qty compute" type="text" name="item_qty[]" value="{{$item['item_qty']}}" /></td>
    <td><input class="text-right number-input txt-rate compute" type="text" name="item_rate[]" value="{{$item['item_rate']}}" /></td>
    <td><input class="text-right txt-discount compute" type="text" name="item_discount[]" value="" /></td>
    <td><textarea class="textarea-expand" type="text" name="item_remark[]"></textarea></td>
    <td><input class="text-right number-input txt-amount" type="text" name="item_amount[]" value="{{$item['item_amount']}}" /></td>
    <td class="text-center">
        <input type="hidden" class="poline_taxable" name="item_taxable[]" value="" >
        <input type="checkbox" name="" class="taxable-check">
    </td>
    <td class="text-center remove-tr cursor-pointer"><i class="fa fa-trash-o" aria-hidden="true"></i></td>
</tr>
@endforeach
@endif