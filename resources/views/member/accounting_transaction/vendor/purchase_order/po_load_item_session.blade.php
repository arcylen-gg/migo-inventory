@if(count($_po) > 0)
    @foreach($_po as $items)
    <tr class="tr-draggable">
            <input type="hidden" class="poline_id" name="item_ref_name[]" value="purchase_order">
            <input type="hidden" class="itemline_po_id" name="item_ref_id[]" value="{{$items['poline_po_id']}}">
        <td class="invoice-number-td text-right">1</td>
        <td>
            <select class="1111 form-control select-item droplist-item input-sm pull-left" name="item_id[]" >
                @include("member.load_ajax_data.load_item_category", ['add_search' => "", 'item_id' => $items['item_id']])
            </select>
        </td>
        <td>
            <textarea class="textarea-expand txt-desc" name="item_description[]" readonly="true">{{$items['item_description']}}</textarea>
        </td>
        <td>
            <select class="2222 droplist-um select-um" name="item_um[]"><option class="hidden" value="" />
                @if($items['item_um'])
                    @include("member.load_ajax_data.load_one_unit_measure", ['item_um_id' => $items['item_um'], 'selected_um_id' => $items['item_um']])
                @else
                    <option class="hidden" value="" />
                @endif
            </select>
        </td>
        <td><input class="text-center number-input txt-qty compute" type="text" name="item_qty[]" value="{{$items['item_qty']}}" /></td>
        <td><input class="text-right number-input txt-rate compute" type="text" name="item_rate[]" value="{{$items['item_rate']}}" />
        </td>
        <td><input class="text-right number-input txt-amount" type="text" name="item_amount[]" value="{{$items['item_amount']}}"/>
        </td>
        @include("member.load_ajax_data.load_td_serial_number");
        <td tr_id="{{$items['poline_po_id']}}" linked_in="no" class="text-center remove-tr cursor-pointer"><i class="fa fa-trash-o" aria-hidden="true"></i></td>
    </tr>
    @endforeach
@endif
<input type="hidden" class="inv-remarks" name="" value="{!! $remarks or '' !!}">