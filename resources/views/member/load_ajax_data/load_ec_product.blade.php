@foreach($_ec_product as $ec_product)
	<option value="{{$ec_product->eprod_id}}" {{isset($product_id) ? $product_id == $ec_product->eprod_id ? 'selected' : '' : ''}}>{{$ec_product->eprod_name}}</option>
@endforeach