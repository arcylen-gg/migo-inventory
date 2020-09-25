<tr class="tr-draggable">
    <td class="text-center cursor-move move"><i class="fa fa-th-large colo-mid-dark-gray"></i></td>
    <td class="invoice-number-td text-right">1</td>
    <td>
        <select class="1111 form-control select-item droplist-item input-sm pull-left" name="itemline_item_id[]" >
            @include("member.load_ajax_data.load_item_category", ["add_search" => ""])
        </select>
    </td>
    <td><textarea class="textarea-expand txt-desc" name="poline_description[]"></textarea></td>
    <td><select class="2222 droplist-um select-um" name="poline_um[]"><option class="hidden" value="" /></select></td>
    <td><input class="text-center number-input txt-qty compute" type="text" name="poline_qty[]"/></td>
    <td><input class="text-right number-input txt-rate compute" type="text" name="poline_rate[]"/></td>
    <td><input class="text-right number-input txt-amount" type="text" name="poline_amount[]"/></td>
    <td class="text-center remove-tr cursor-pointer"><i class="fa fa-trash-o" aria-hidden="true"></i></td>
</tr>