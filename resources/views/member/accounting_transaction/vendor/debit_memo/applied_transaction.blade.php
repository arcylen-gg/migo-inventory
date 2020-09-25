@if(count($_get_item) > 0)
    @foreach($_get_item as $key => $items)
        @foreach($items['item_details'] as $key_item => $item_details)
            @if($item_details['item_qty'] != 0)
                <tr class="tr-draggable">
                    <td class="invoice-number-td text-right">1</td>
                    <td>
                        <input type="hidden" name="item_ref_name[]" value="enter_bills">
                        <input type="hidden" name="item_ref_id[]" value="{{$item_details['itemline_id']}}">
                        <select class="1111 form-control select-item droplist-item input-sm pull-left" name="item_id[]" >
                            @include("member.load_ajax_data.load_item_category", ['add_search' => "", 'item_id' => $item_details['item_id']])
                        </select>
                    </td>
                    <td>
                        <textarea class="textarea-expand txt-desc" name="item_description[]" readonly="true">{{$item_details['item_description']}}</textarea>
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
                        <select class="2222 droplist-um select-um" name="item_um[]"><option class="hidden" value="" />
                            @if($item_details['item_um'])
                            @include("member.load_ajax_data.load_one_unit_measure", ['item_um_id' => $item_details['multi_um_id'], 'selected_um_id' => $item_details['item_um']])
                            @else
                            <option class="hidden" value="" />
                            @endif
                        </select>
                    </td>
                    <td><input class="text-center number-input txt-qty compute" type="text" name="item_qty[]" value="{{$item_details['item_qty']}}" /> </td>
                    <td><input class="text-right number-input txt-rate compute" type="text" name="item_rate[]" value="{{$item_details['item_rate']}}" /></td>
                    @if($item_details['item_discount_type'] == 'percent')
                        <td><input class="text-right txt-discount compute" type="text" name="item_discount[]" value="{{$item_details['item_discount'] * 100}}%" /></td>
                    @elseif($item_details['item_discount_type'] == 'fixed' && $item_details['item_discount'] != '' || $item_details['item_discount'] != 0 )
                        <input type="hidden" class="txt-orig-qty number-input txt-qty compute" name='' value="{{$item_details['orig_qty']}}" />
                        <input type="hidden" class="txt-orig-disc number-input txt-qty compute" name='' value="{{$item_details['item_discount']}}" />
                        <input type="hidden" class="if-fixed" name='' value="fixed" />
                        <td><input class="text-right txt-discount disc compute" type="text" name="item_discount[]" value="{{$item_details['item_discount']}}" /></td>
                    @elseif($item_details['item_discount_type'] == 'fixed' && $item_details['item_discount'] == '' || $item_details['item_discount'] == 0 )
                        <td><input class="text-right txt-discount" type="text" name="item_discount[]" value="{{$item_details['item_discount']}}" /></td>
                    @endif
                        <td><input class="text-right number-input txt-amount" type="text" name="item_amount[]" value="{{$item_details['item_amount']}}"/></td>
                        <td class="text-center">
                        <input type="hidden" class="taxable-input" name="item_taxable[]" value="{{$item_details['taxable']}}">
                        <input type="checkbox" class="taxable-check compute" {{$item_details['taxable'] == 1 ? 'checked' : ''}} value="1">
                        </td>
                        <td class="text-center remove-tr cursor-pointer"><i class="fa fa-trash-o" aria-hidden="true"></i></td>
               </tr>
            @endif
        @endforeach
    @endforeach
@endif
<input type="hidden" class="itemline-remarks" name="" value="{!! $remarks or '' !!}">
<input type="hidden" class="itemline-tax" name="" value="{!! $tax or '' !!}">
<input type="hidden" class="itemline-disc-type" name="" value="{!! $disc_type or '' !!}">
<input type="hidden" class="itemline-disc-percentage" name="" value="{!! $disc_percentage or '' !!}">