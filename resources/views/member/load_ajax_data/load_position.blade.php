<!-- @url  -->
@foreach($_position as $key=>$position)
	<option value="{{$position->position_id}}" {{ isset($position_id) ?  $position_id == $position['position_id'] ? 'selected' : '' : '' }}>{{$position->position_name}} </option>
@endforeach
<option class="hidden" value="" />