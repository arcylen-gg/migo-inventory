@if(count($_transactions) > 0)
    @foreach($_transactions as $transaction)
     <tr class="tr-draggable">
        <input type="hidden" name="item_ref_name[]" value="sales_order"></td>
        <input type="hidden" name="item_ref_id[]" value="$transaction['so_id']"></td>
        <td class="invoice-number-td text-center">1</td>
        <td>
            <select class="form-control droplist-item input-sm select-item" name="rs_item_id[]" >
                @include("member.load_ajax_data.load_item_category", ['add_search' => "", 'item_id' => $transaction['item_id']])
                <option class="hidden" value="" />
            </select>
        </td>
        <td><textarea class="textarea-expand txt-desc" name="rs_item_description[]" readonly="true">{{$transaction['item_description']}}</textarea></td>
        <td><select class="droplist-item-um select-um {{isset($transaction['multi_id']) ? 'has-value' : ''}}" name="rs_item_um[]">
                @if($transaction['item_um'])
                    @include("member.load_ajax_data.load_one_unit_measure", ['item_um_id' => $transaction['multi_um_id'], 'selected_um_id' => $transaction['item_um']])
                @else
                    <option class="hidden" value="" />
                @endif
            </select>
        </td>
        <td><input class="text-center number-input txt-remain-qty" type="text" name="" value="{{$transaction['item_invty_count']}}" readonly="true" /></td>
        <td><input class="text-center number-input txt-qty compute" type="text" name="rs_item_qty[]" value="{{$transaction['item_qty']}}" /></td>
        <td><input class="text-right number-input txt-rate compute" type="text" name="rs_item_rate[]" value="{{$transaction['item_rate']}}" /></td>
        <td><input class="text-right number-input txt-amount" type="text" name="rs_item_amount[]" value="{{$transaction['item_amount']}}"/></td>
        <td class="text-center">
            <select class="form-control droplist-vendor select-vendor" name="rs_vendor_id[]">
                @include('member.load_ajax_data.load_vendor')
            </select>
        </td>
        <td class="text-center remove-tr cursor-pointer"><i class="fa fa-trash-o" aria-hidden="true"></i></td>
    </tr>
    @endforeach
@endif
<input type="hidden" class="so-remarks" name="" value="{!! $remarks or '' !!}">