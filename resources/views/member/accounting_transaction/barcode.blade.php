@if(isset($po))
    @foreach($_poline as $poline)
        <tr class="tr-draggable">
            <input type="hidden" name="item_ref_name[]" value="{{$poline->poline_refname}}"></td>
            <input type="hidden" name="item_ref_id[]" value="{{$poline->poline_refsname}}"></td>
            <td class="invoice-number-td text-right">1</td>
            <td><input type="text" class="for-datepicker" name="item_servicedate[]" value="{{ $poline->poline_service_date != '0000-00-00 00:00:00'? date('m/d/Y',strtotime($poline->poline_service_date)) : ''}}" /></td>
            <td>                                                                            
                <select class="form-control select-item droplist-item input-sm pull-left {{$poline->poline_item_id}}" name="item_id[]" required>
                    @include("member.load_ajax_data.load_item_category", ['add_search' => "", 'item_id' => $poline->poline_item_id])
                </select>                                                                       
            </td>
            <td>
                <textarea class="textarea-expand txt-desc" name="item_description[]">{{$poline->poline_description}}</textarea>
            </td>
            <td>
                <select class="1111 droplist-um select-um {{isset($poline->poline_um) ? 'has-value' : ''}}" name="item_um[]">
                    @if($poline->poline_um)
                        @include("member.load_ajax_data.load_one_unit_measure", ['item_um_id' => $poline->multi_um_id, 'selected_um_id' => $poline->poline_um])
                    @else
                        <option class="hidden" value="" />
                    @endif
                </select>
            </td>
            <td><input class="text-center number-input txt-qty compute" type="text" name="item_qty[]" value="{{$poline->poline_orig_qty}}" /></td>
            <td><input class="text-right number-input txt-rate compute" type="text" name="item_rate[]" value="{{$poline->poline_rate}}" /></td>
            @if($poline->poline_discounttype == 'fixed')
                <td><input class="text-right txt-discount compute" type="text" name="item_discount[]" value="{{$poline->poline_discount}}" /></td>
            @else
                <td><input class="text-right txt-discount compute" type="text" name="item_discount[]" value="{{$poline->poline_discount * 100}}%" /></td>
            @endif
            <td><textarea class="textarea-expand" type="text" name="item_remark[]">{{$poline->poline_discount_remark}}</textarea></td>
            <td><input class="text-right number-input txt-amount" type="text" name="item_amount[]" value="{{$poline->poline_amount}}" /></td>
            <td class="text-center">
                <input type="hidden" class="poline_taxable" name="item_taxable[]" value="{{$poline->taxable}}" >
                <input type="checkbox" name="" class="taxable-check" {{$poline->taxable == 1 ? 'checked' : ''}}>
            </td>
            <td class="text-center remove-tr cursor-pointer"><i class="fa fa-trash-o" aria-hidden="true"></i></td>
        </tr>
    @endforeach
@endif


<tr class="tr-draggable">
    <input type="hidden" name="item_ref_name[]"></td>
    <input type="hidden" name="item_ref_id[]"></td>
    <td class="invoice-number-td text-right">1</td>
    <td><input type="text" class="for-datepicker" name="item_servicedate[]"/></td>
    <td><input class="form-control select-item droplist-item input-sm pull-left" type="textbox" name="item_id[]" /></td>
    <td><textarea class="textarea-expand txt-desc" name="item_description[]"></textarea></td>
    <td><select class="2222 droplist-um select-um" name="item_um[]"><option class="hidden" value="" /></select></td>
    <td><input class="text-center number-input txt-qty compute" type="textbox" name="item_qty[]" /></td>
    <td><input class="text-right number-input txt-rate compute" type="text" name="item_rate[]" value=""/></td>
    <td><input class="text-right txt-discount compute" type="text" name="item_discount[]"/></td>
    <td><textarea class="textarea-expand" type="text" name="item_remark[]" ></textarea></td>
    <td><input class="text-right number-input txt-amount" type="text" name="item_amount[]"/></td>
    <td class="text-center">
        <input type="checkbox" name="item_taxable[]" class="taxable-check compute" data-value="" value="1">
    </td>
    <td class="text-center remove-tr cursor-pointer"><i class="fa fa-trash-o" aria-hidden="true"></i></td>
</tr>
<tr class="tr-draggable">
    <input type="hidden" name="item_ref_name[]"></td>
    <input type="hidden" name="item_ref_id[]"></td>
    <td class="invoice-number-td text-right">2</td>
    <td><input type="text" class="for-datepicker" name="item_servicedate[]" /></td>
    <td><input class="form-control select-item droplist-item input-sm pull-left" type="text" name="item_id[]" /></td>
    <td><textarea class="textarea-expand txt-desc" name="item_description[]"></textarea></td>
    <td><select class="3333 droplist-um select-um" name="item_um[]"><option class="hidden" value="" /></select></td>
    <td><input class="text-center number-input txt-qty compute" type="text" name="item_qty[]"/></td>
    <td><input class="text-right number-input txt-rate compute" type="text" name="item_rate[]" value="" /></td>
    <td><input class="text-right txt-discount compute" type="text" name="item_discount[]"/></td>
    <td><textarea class="text-right number-input" type="text" name="item_remark[]"></textarea></td>
    <td><input class="text-right number-input txt-amount" type="text" name="item_amount[]"/></td>
    <td class="text-center">
        <input type="checkbox" name="item_taxable[]" class="taxable-check compute" data-value="" value="1">
    </td>
    <td class="text-center remove-tr cursor-pointer"><i class="fa fa-trash-o" aria-hidden="true"></i></td>
</tr>