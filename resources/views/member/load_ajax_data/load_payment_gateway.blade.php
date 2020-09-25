@foreach($_gateway as $key=>$gateway)
	<option value="{{$gateway->gateway_code_name}}-{{$gateway->reference_id}}" reference-name="{{$gateway->gateway_code_name}}" {{ isset($reference_id) ?  ($reference_id == $gateway['reference_id'] && $reference_name == $gateway["gateway_code_name"]) ? 'selected' : '' : '' }}>{{$gateway->display_name}} </option>
	}
@endforeach
<option class="hidden" value="" />