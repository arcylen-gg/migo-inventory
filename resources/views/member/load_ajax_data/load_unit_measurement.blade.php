<!-- @url '/member/item/load_um' -->
 
@foreach($_um as $key=>$um)
	<option value="{{$um->um_id}}" abbrev="{{$um->multi_abbrev}}" {{ isset($um_id) ?  $um_id == $um['um_id'] ? 'selected' : '' : '' }}>{{$um->um_name}} :{{$um->multi_abbrev}}</option>
	@if(sizeOf($_um)-1 == $key)
		<option class="hidden" value="" />
	@endif
@endforeach