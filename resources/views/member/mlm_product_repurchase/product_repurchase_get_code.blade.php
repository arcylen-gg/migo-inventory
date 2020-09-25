<label>Product Code</label>
<select class="form-control chosen-product_code_id input-sm pull-left" name="item_code_id" data-placeholder="Select a product code" onchange="change_product_code_get_info(this)">
    <option value=""></option>
	@if(count($product_code) != 0)
		@foreach($product_code as $item)
			<option value="{{$item->item_code_id}}">{{$item->item_activation_code}}</option>
		@endforeach
	@endif
</select>