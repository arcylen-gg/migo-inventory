@if(isset($item_list))
    @foreach($item_list as $key => $value)

        <tr>
            <td>
            	{!! $item_list[$key] !!}
            </td>
            <td class="text-left">
            	<input style="width: 100px" type="text" class="form-control quantity_container" name="quantity[]" value="{{$item_array[$key]['quantity']}}">
            </td>
            <td class="text-right price_container">
            	{{currency('PHP', $item_array[$key]['price'])}}
            </td>
            <td class="text-right price_container">
                {{currency('PHP', $item_array[$key]['membership_discount'])}}
            </td>
            <td class="text-right subtotal_container" style="font-weight: bold;">
                {{currency('PHP', $item_array[$key]['membership_discounted_price_total'])}}
            </td>
            <td style="font-size: 20px;">
            	<a href="javascript:">
            		<i class="fa fa-trash" onClick="remove_line({{$key}})"></i>
            	</a>
            </td>
        </tr>

        @if(isset($item_array[$key]['item_serial']))
            @if($item_array[$key]['item_serial'] != null)
                <tr>
                    <td colspan="40">
                    <input type="hidden" name="item_serial_enable" value="1">
                        <center>Input Serial Number</center>
                @for($i = 0; $i < $item_array[$key]['quantity']; $i++ )
                    <div class="col-md-3"><input type="text" class="form-control col-md-3 class_item_serial class_item_serial_{{$i}}" serial_key="{{$i}}" value="{{ old('item_serial['.$key.']['.$i.']') }}" name="item_serial[{{$key}}][{{$i}}]" required></div>
                @endfor
                    </td>
                </tr>
            @endif
        @endif
    @endforeach
@else
    <tr>
        <td colspan="6"> <center>No Item. Click add lines to add item.</center></td>
    </tr>
@endif
    