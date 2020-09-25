
@foreach($_name as $key=>$name)
	<option value="{{$name->id}}" reference="{{$name->reference}}" email="{{$name->email}}" ctr-wc="{{$name->ctr_wc}}" address="{{$name->street.' '.$name->city.' '.$name->zipcode}}" {{ isset($name_id) ?  ($name_id == $name->id && $ref_name == $name->reference ? 'selected' : '') : '' }}>{{substr($name->reference,0,1)}} : {{$name->company != '' ? $name->company : $name->first_name." ".$name->middle_name ." ".$name->last_name}}</option>
	@if(sizeOf($_name)-1 == $key)
		<option class="hidden" value="" />
	@endif
@endforeach