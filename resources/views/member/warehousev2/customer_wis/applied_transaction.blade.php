@if(count($_transactions) > 0)
    @if($check_barcode == 1 && $project == 'fieldmen')
    @else
        @foreach($_transactions as $key => $transaction)
        <tr class="tr-draggable">
            <td class="invoice-number-td text-center">1</td>
            <td>
                <select class="form-control droplist-item select-item input-sm" name="item_id[]" >
                    @include("member.load_ajax_data.load_item_category", ['add_search' => "", 'item_id' => $transaction['item_id']])
                </select>
            </td>
            <td><textarea class="form-control txt-desc" name="item_description[]">{{$transaction['item_description']}}</textarea></td>
            @if($check_settings == 1)
            <td>
                <select class="form-control droplist-sub-warehouse select-sub-warehouse input-sm" name="item_sub_warehouse[]" >
                    @include('member.warehousev2.load_sub_warehouse_v2_select', ['_bin_warehouse' => $transaction['_bin_item_warehouse']])
                    <option class="hidden" value="" />
                </select>
            </td>
            @endif
            <td><select class="2222 droplist-um select-um {{isset($transaction['multi_id']) ? 'has-value' : ''}}" name="item_um[]" >
                    @if($transaction['item_um'])
                        @include("member.load_ajax_data.load_one_unit_measure", ['item_um_id' => $transaction['multi_um_id'], 'selected_um_id' => $transaction['item_um']])
                    @else
                        <option class="hidden" value="" />
                    @endif
                </select>
            </td>
            <td><input class="form-control number-input txt-qty text-center compute" type="text" name="item_qty[]" value="{{$transaction['item_qty']}}" /></td>
            <td><input class="text-right number-input txt-rate" type="text" name="item_rate[]" value="{{$transaction['item_rate']}}" /></td>
            <td><input class="text-right number-input txt-amount" type="text" name="item_amount[]" value="{{$transaction['item_amount']}}" /></td>
            <td class="text-center remove-tr cursor-pointer">
                <i class="fa fa-trash-o" aria-hidden="true"></i>
                <input type="hidden" name="item_refname[]" value="{{$transaction['refname']}}">
                <input type="hidden" name="item_refid[]" value="{{$transaction['refid']}}">
            </td>
        </tr>
        @endforeach
    @endif
@endif
<input type="hidden" class="inv-remarks" name="" value="{!! $remarks or '' !!}">