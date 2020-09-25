@if(count(Session::get('po_item')) > 0)
    @foreach(Session::get('po_item') as $items)
    <tr class="trcount tr-draggable tr-id-{{$items['poline_po_id']}}">
        <td class="text-center cursor-move move"><i class="fa fa-th-large colo-mid-dark-gray"></i>
            <input type="hidden" class="poline_id" name="itemline_ref_name[]" value="purchase_order">
            <input type="hidden" class="itemline_po_id" name="itemline_ref_id[]" value="{{$items['poline_po_id']}}">
        </td>
        <td class="invoice-number-td text-right">1</td>
        <td>
            <select class="1111 form-control select-item droplist-item input-sm pull-left" name="itemline_item_id[]" >
                @include("member.load_ajax_data.load_item_category", ['add_search' => "", 'item_id' => $items['poline_item_id']])
            </select>
        </td>
        <td>
            @if(isset($pis))
                <textarea class="textarea-expand txt-desc" name="itemline_description[]" readonly="true">{{$items['poline_description']}}</textarea>
            @else
                <textarea class="textarea-expand txt-desc" name="itemline_description[]">{{$items['poline_description']}}</textarea>
            @endif
        </td>
        <td>
            <select class="2222 droplist-um select-um" name="itemline_um[]"><option class="hidden" value="" />
                @if($items['poline_um'])
                    @include("member.load_ajax_data.load_one_unit_measure", ['item_um_id' => $items['multi_um_id'], 'selected_um_id' => $items['poline_um']])
                @else
                    <option class="hidden" value="" />
                @endif
            </select>
        </td>
        <td><input class="text-center number-input txt-qty compute" type="text" name="itemline_qty[]" value="{{$items['poline_qty']}}" /></td>
        <td><input class="text-right number-input txt-rate compute" type="text" name="itemline_rate[]" value="{{$items['poline_rate']}}" />
        </td>
        <td><input class="text-right number-input txt-amount" type="text" name="itemline_amount[]" value="{{$items['poline_amount']}}"/>
        </td>
        @include("member.load_ajax_data.load_td_serial_number");
        <td tr_id="{{$items['poline_po_id']}}" linked_in="no" class="text-center remove-tr cursor-pointer"><i class="fa fa-trash-o" aria-hidden="true"></i></td>
    </tr>
    @endforeach
@endif
<tr class="tr-draggable">
    <td class="text-center cursor-move move"><i class="fa fa-th-large colo-mid-dark-gray"></i></td>
        <input type="text" class="hidden poline_id" name="poline_id[]">
        <input type="text" class="hidden itemline_po_id" name="itemline_po_id[]">
    </td>
    <td class="invoice-number-td text-right">1</td>
    <td>
        <input type="hidden" class="poline_id" name="itemline_ref_name[]">
        <input type="hidden" class="itemline_po_id" name="itemline_ref_id[]">
        <select class="1111 form-control select-item droplist-item input-sm pull-left" name="itemline_item_id[]" >
            @include("member.load_ajax_data.load_item_category", ['add_search' => ""])
        </select>
    </td>
    <td>
        @if(isset($pis))
            <textarea class="textarea-expand txt-desc" readonly="true" name="itemline_description[]"></textarea>
        @else
            <textarea class="textarea-expand txt-desc" name="itemline_description[]"></textarea>
        @endif
    </td>
    <td><select class="2222 droplist-um select-um" name="itemline_um[]"><option class="hidden" value="" /></select></td>
    <td><input class="text-center number-input txt-qty compute" type="text" name="itemline_qty[]"/></td>
    <td><input class="text-right number-input txt-rate compute" type="text" name="itemline_rate[]"/></td>
    <td><input class="text-right number-input txt-amount" type="text" name="itemline_amount[]"/></td>
    @include("member.load_ajax_data.load_td_serial_number")
    <td class="text-center remove-tr cursor-pointer"><i class="fa fa-trash-o" aria-hidden="true"></i></td>
</tr>