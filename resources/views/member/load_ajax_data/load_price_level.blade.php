@if(count($_price_level) > 0)
	@foreach($_price_level as $price_level)
		<option {{isset($price_level_id) ? ($price_level_id == $price_level->price_level_id ? 'selected': ''): ''}} value="{{$price_level->price_level_id}}">{{$price_level->price_level_name}}</option>
	@endforeach
@else
<option value="">No Price Level</option>
@endif