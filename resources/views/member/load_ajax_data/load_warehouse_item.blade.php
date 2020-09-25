@foreach($warehouse_list as $key => $product)
	<option value="{{$product->product_id}}" product-id="{{$product->product_id}}" product-sku="{{$product->product_sku}}" source-qty="{{$product->product_source_qty}}" 
			show-current-qty="{{$product->product_qty_um}}" current-qty="{{$product->product_current_qty}}" reorder-point="{{$product->product_reorder_point}}" 
			{{ isset($product_id) ?  $product_id == $product->product_id ? 'selected' : '' : '' }}>{{$product->product_name}}</option>
@endforeach