@foreach($_manufacturer as $key => $manufacturer)
	<option value="{{$manufacturer->manufacturer_id}}" {{ isset($manufacturer_id) ?  $manufacturer_id == $manufacturer->manufacturer_id ? 'selected' : '' : '' }}>{{$manufacturer->manufacturer_name}} </option>
@endforeach