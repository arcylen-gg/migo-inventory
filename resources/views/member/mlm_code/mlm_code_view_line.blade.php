@if(isset($membership_package))
    @foreach($membership_package as $key => $value)
        <tr>
            <td>
            	{!! $membership_package[$key] !!}
            </td>
            <td class="text-left">
            	{!! $membership_type[$key] !!}
            </td>
            <td class="text-left">
            	<input style="width: 100px" type="text" class="form-control quantity_container" name="quantity[]" value="{{$mem_array[$key]['quantity']}}">
            </td>
            <td class="text-right price_container">
            	{{currency('PHP', $mem_array[$key]['price'])}}
            </td>
            <td class="text-right subtotal_container" style="font-weight: bold;">
                {{currency('PHP', $mem_array[$key]['total'])}}
            </td>
            <td style="font-size: 20px;">
            	<a href="javascript:">
            		<i class="fa fa-trash" onClick="remove_line({{$key}})"></i>
            	</a>
            </td>
        </tr>
    @endforeach
@else
    <tr>
        <td colspan="6"> <center>No Package. Click add lines to add package.</center></td>
    </tr>
@endif
    