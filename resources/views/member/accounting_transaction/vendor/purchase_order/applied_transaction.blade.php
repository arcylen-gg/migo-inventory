@if(count($_transactions) > 0)
    @foreach($_transactions as $transaction)
     <tr class="tr-draggable">
        <input type="hidden" name="item_ref_name[]" value="$transaction['refname']"></td>
        <input type="hidden" name="item_ref_id[]" value="$transaction['refid']"></td>
        <td class="invoice-number-td text-center">1</td>
         <td><input type="text" class="for-datepicker" name="item_servicedate[]"/>{{$transaction['service_date'] != '1970-01-01' ? $transaction['service_date'] : ''}}</td>
        <td>
            <select class="form-control droplist-item input-sm select-item" name="item_id[]" >
                @include("member.load_ajax_data.load_item_category", ['add_search' => "", 'item_id' => $transaction['item_id']])
                <option class="hidden" value="" />
            </select>
        </td>
        <td>
            <textarea class="textarea-expand txt-desc" name="item_description[]" readonly="true">{{$transaction['item_description']}}</textarea>
        </td>
        @if($check_settings == 1)
        <td>
            <select class="form-control droplist-sub-warehouse select-sub-warehouse input-sm" name="item_sub_warehouse[]" >
                @include('member.warehousev2.load_sub_warehouse_v2_select')
                <option class="hidden" value="" />
            </select>
        </td>
        @endif
        <td><select class="2222 droplist-um select-um {{isset($transaction['multi_id']) ? 'has-value' : ''}}" name="item_um[]">
                @if($transaction['item_um'])
                    @include("member.load_ajax_data.load_one_unit_measure", ['item_um_id' => $transaction['multi_um_id'], 'selected_um_id' => $transaction['item_um']])
                @else
                    <option class="hidden" value="" />
                @endif
            </select>
        </td>

        <td><input class="text-center number-input txt-qty compute" type="text" name="item_qty[]" value="{{$transaction['item_qty']}}" /></td>
        <td><input class="text-right number-input txt-rate compute" type="text" name="item_rate[]" value="{{$transaction['item_rate']}}" /></td>
        <td><input type="text" class="form-control text-right txt-discount" name="item_discount[]" value="{{$transaction['item_discount_type'] == 'fixed' ?  $transaction['item_discount'] : $transaction['item_discount'] * 100 .'%'}}"/></td>></td>
        <td><textarea class="textarea-expand" type="text" name="item_remark[]">{{$transaction['item_remarks']}}</textarea></td>
        <td><input class="text-right number-input txt-amount" type="text" name="item_amount[]" value="{{$transaction['item_amount']}}"/></td>
        
        <td class="text-center">
            <input type="hidden" name="item_taxable[]" class="taxable-input" value="{{$transaction['taxable']}}">
            <input type="checkbox" class="taxable-check compute" {{$transaction['taxable'] == 1 ? 'checked' : ''}} value="1">
        </td>
        <td class="text-center remove-tr cursor-pointer"><i class="fa fa-trash-o" aria-hidden="true"></i></td>
    </tr>
    @endforeach
@endif
<input type="hidden" class="so-remarks" name="" value="{!! $remarks or '' !!}">
<input type="hidden" class="so-tax" name="" value="{!! $tax or '' !!}">