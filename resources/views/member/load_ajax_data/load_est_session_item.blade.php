@if(count(Session::get('est_item')) > 0)
    @foreach(Session::get('est_item') as $items)
    <tr class="trcount tr-draggable session-item tr-id-{{$items['estline_est_id']}}">
    <td class="text-center cursor-move move"><i class="fa fa-th-large colo-mid-dark-gray"></i></td>
    <td><input type="text" class="for-datepicker" name="invline_service_date[]" value="{{$items['estline_service_date']}}" /></td>
    @if(isset($serial))
    <td>
        <textarea class="txt-serial-number" name="serial_number[]"></textarea>
    </td>
    @endif
    <td class="invoice-number-td text-right">
        1
    </td>
    <td>
        <select class="1111 form-control select-item droplist-item input-sm pull-left" name="invline_item_id[]" >
        @include("member.load_ajax_data.load_item_category", ['add_search' => "", 'item_id' => $items['estline_item_id']])
        <option class="hidden" value="" />
        </select>
    </td>
    <td>
    @if(isset($pis))
        <textarea class="textarea-expand txt-desc" readonly="true" name="invline_description[]" value=""></textarea>
    @else
        <textarea class="textarea-expand txt-desc" name="invline_description[]">{{$items['estline_description']}}</textarea></td>
    @endif
    <td>
        <select class="2222 droplist-um select-um" name="invline_um[]">
        @if($items['estline_um'])
            @include("member.load_ajax_data.load_one_unit_measure", ['item_um_id' => $items['multi_um_id'], 'selected_um_id' => $items['estline_um']])
        @else
            <option class="hidden" value="" />
        @endif
        </select>
    </td>
    <td><input class="text-center number-input txt-qty compute" type="text" value="{{$items['estline_qty']}}" name="invline_qty[]"/></td>
    <td><input class="text-right number-input txt-rate compute" value="{{$items['estline_rate']}}" type="text" name="invline_rate[]"/></td>
    <td><input class="text-right txt-discount compute" value="{{$items['estline_discount']}}" type="text" name="invline_discount[]"/></td>
    <td><textarea class="textarea-expand" type="text" value="{{$items['estline_discount_remark']}}" name="invline_discount_remark[]" ></textarea></td>
    <td><input class="text-right number-input txt-amount" value="{{$items['estline_amount']}}" type="text" name="invline_amount[]"/></td>
    <td class="text-center">
        <input type="hidden" class="invline_taxable" {{$items['taxable'] == 1 ? 'checked' : ''}} name="invline_taxable[]" value="" >
        <input type="checkbox" name="" class="taxable-check compute" value="checked">
    </td>
    <td tr_id="{{$items['estline_est_id']}}" linked_in="no" class="text-center remove-tr cursor-pointer"><i class="fa fa-trash-o" aria-hidden="true"></i></td>
    </tr>
    @endforeach
@endif
<tr class="tr-draggable">
<td class="text-center cursor-move move"><i class="fa fa-th-large colo-mid-dark-gray"></i></td>

<td><input type="text" class="for-datepicker" name="invline_service_date[]"/></td>
@include("member.load_ajax_data.load_td_serial_number")
<td class="invoice-number-td text-right">
    1
</td>
<td>
    <input type="hidden" name="invline_ref_name[]" value="">
    <input type="hidden" name="invline_ref_id[]" value="0">
<select class="1111 form-control select-item droplist-item input-sm pull-left" name="invline_item_id[]" >
    @include("member.load_ajax_data.load_item_category", ['add_search' => ""])
    <option class="hidden" value="" />
</select>
</td>
<td>
@if(isset($pis))
    <textarea class="textarea-expand txt-desc" readonly="true" name="invline_description[]"></textarea>
@else
    <textarea class="textarea-expand txt-desc" name="invline_description[]"></textarea>
@endif
</td>
<td><select class="2222 droplist-um select-um" name="invline_um[]"><option class="hidden" value="" /></select></td>
<td><input class="text-center number-input txt-qty compute" type="text" name="invline_qty[]"/></td>
<td><input class="text-right number-input txt-rate compute" type="text" name="invline_rate[]"/></td>
<td><input class="text-right txt-discount compute" type="text" name="invline_discount[]"/></td>
<td><textarea class="textarea-expand" type="text" name="invline_discount_remark[]" ></textarea></td>
<td><input class="text-right number-input txt-amount" type="text" name="invline_amount[]"/></td>
<td class="text-center">
<input type="hidden" class="invline_taxable" name="invline_taxable[]" value="" >
<input type="checkbox" name="" class="taxable-check compute" value="checked">
</td>
<td class="text-center remove-tr cursor-pointer"><i class="fa fa-trash-o" aria-hidden="true"></i></td>
</tr>
