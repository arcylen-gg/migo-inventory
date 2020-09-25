<!-- @url  -->
@foreach($_terms as $key=>$terms)
	<option value="{{$terms->terms_id}}" days="{{$terms->terms_no_of_days}}" {{ isset($terms_id) ?  $terms_id == $terms->terms_id ? 'selected' : '' : '' }}>{{$terms->terms_name}} </option>
@endforeach
<option class="hidden" value="" />