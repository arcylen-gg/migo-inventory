@if(count($_transactions) > 0)
    @foreach($_transactions as $transaction)
    <tr class="tr-draggable">
        <td class="invoice-number-td text-right">
            1
        </td>
        <td><input type="text" class="for-datepicker" name="item_servicedate[]"/>{{$transaction['service_date'] != '1970-01-01' ? $transaction['service_date'] : ''}}</td>
        <td>
            <select class="1111 form-control select-item droplist-item input-sm pull-left" name="item_id[]" >
                @include("member.load_ajax_data.load_item_category", ['add_search' => "", 'item_id' => $transaction['item_id']])
                <option class="hidden" value="" />
            </select>
        </td>
        <td>
            <textarea class="textarea-expand txt-desc" name="item_description[]">{{$transaction['item_description']}}</textarea>
        </td>
        <td><select class="2222 droplist-um select-um {{isset($transaction['multi_id']) ? 'has-value' : ''}}" name="item_um[]">
                @if($transaction['item_um'])
                    @include("member.load_ajax_data.load_one_unit_measure", ['item_um_id' => $transaction['multi_um_id'], 'selected_um_id' => $transaction['item_um']])
                @else
                    <option class="hidden" value="" />
                @endif
            </select>
        </td>
        <td><input class="text-center number-input txt-qty compute" value="{{$transaction['item_qty']}}" type="text" name="item_qty[]"/></td>
        <td><input class="text-right number-input txt-rate compute" type="text" value="{{$transaction['item_rate']}}" name="item_rate[]"/></td>
        <td><input class="text-right txt-discount compute" type="text" name="item_discount[]"  value="{{$transaction['item_discount']}}{{$transaction['item_discount_type'] == 'fixed' ?  '' : '%'}}"/></td>
        <td><textarea class="textarea-expand" type="text" name="item_remarks[]">{{$transaction['item_remarks']}}</textarea></td>
        <td><input class="text-right number-input txt-amount" type="text" name="item_amount[]" value="{{$transaction['item_amount']}}" /></td>
        <td class="text-center">
            <input type="hidden" name="item_taxable[]" class="taxable-input compute" {{$transaction['taxable']}} value="1">
            <input type="checkbox" class="taxable-check compute" {{$transaction['taxable'] == 1 ? 'checked' : ''}} value="1">
        </td>
        <td class="text-center remove-tr cursor-pointer">
            <i class="fa fa-trash-o" aria-hidden="true"></i>
            <input type="hidden" name="item_refname[]" value="{{$transaction['refname']}}">
            <input type="hidden" name="item_refid[]" value="{{$transaction['refid']}}">
        </td>
    </tr>
    @endforeach
@endif
<input type="hidden" class="so-remarks" name="" value="{!! $remarks or '' !!}">
