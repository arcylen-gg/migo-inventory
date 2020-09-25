@foreach($item as $variant)
<tr>
    <td class="text-center">
        <img src="{{$variant['image_path']}}" class="img-50-50"></img>
    </td>
    <td>
        <a href="#">{{$variant['product_name']}}</a><Br>
        <a href="#">{{$variant['variant_name']}}</a>
    </td>
    <td class="text-center">
        {{$variant['variant_sku']}}
    </td>
    <td class="text-center">
        {{$variant['variant_allow_oos_purchase'] == 1?'Continue selling':'Stop selling'}}
    </td>
    <td class="text-center">
        <a href=#>0</a>
    </td>
    <td class="text-center">
        <span id="quantity-{{$variant['variant_id']}}">{{$variant['variant_inventory_count']}}</span>
    </td>
    <td>
        <div class="input-group">
            <span class="input-group-btn">
                <button class="btn btn-custom-white btn-add" id="add-{{$variant['variant_id']}}" data-content="{{$variant['variant_id']}}" disabled>Add</button>
            </span>
            <span class="input-group-btn">
                <button class="btn btn-custom-white border-radius-0 btn-set" id="set-{{$variant['variant_id']}}" data-content="{{$variant['variant_id']}}">Set</button>
            </span>
            <input type="text" class="form-control text-right" id="txt-quantity-{{$variant['variant_id']}}" value="0">
            <span class="input-group-btn">
                <button class="btn btn-custom-primary btn-save" id="save-{{$variant['variant_id']}}" data-content="{{$variant['variant_id']}}" data-trigger="add">Save</button>
            </span>
        </div>
    </td>
</tr>
@endforeach