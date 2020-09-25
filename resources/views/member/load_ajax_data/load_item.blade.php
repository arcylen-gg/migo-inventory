@foreach($_item as $key => $item)
	<option value="{{$item->item_id}}"
	item-sku="{{$item->item_sku}}" item-type="{{$item->item_type_id}}"
	sales-info="{{$item->item_sales_information}}" purchase-info="{{$item->item_purchasing_information}}" 
	price="{{$item->item_price or isset($item->sir_item_price)}}" cost="{{$item->item_cost}}" has-um="{{$item->item_measurement_id}}"
	{{ isset($item_id) ?  $item_id == $item->item_id ? 'selected' : '' : '' }} > {{$item->item_name}}</option>
@endforeach


