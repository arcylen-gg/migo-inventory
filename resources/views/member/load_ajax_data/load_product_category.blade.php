@foreach($_product as $key => $type)
	<option value="type_{{$type['type_id']}}" indent="{{$type['type_sub_level']}}" add-search="{{$add_search}}" disabled>{{$type['type_name']}}</option>
	@if($type['Product'])
		@foreach($type['Product'] as $product)
			<option value="{{$product['evariant_id']}}" indent="{{$type['type_sub_level']+1}}" add-search="{{$add_search."|".$type['type_name']}}" 
					price="{{$product['evariant_price']}}"
					{{ isset($variant_id) ?  $variant_id == $product['evariant_id'] ? 'selected' : '' : '' }} >{{$product['product_new_name']}}</option>
		@endforeach
	@endif
	@if($type['subcategory'])
		@include('member.load_ajax_data.load_product_category', ['_product' => $type['subcategory'], 'add_search' => $type['type_name']."|".$add_search])
	@endif
	@if(sizeOf($_product)-1 == $key)
		<option class="hidden" value="" />
	@endif
@endforeach