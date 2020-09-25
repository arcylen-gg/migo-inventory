@foreach($_um as $key=>$um)
	<option value="{{$um->id}}" {{ isset($id_um) ? ($id_um == $um->id ? 'selected' : '') : '' }}>{{$um->um_name}} ({{$um->um_abbrev}})</option>
@endforeach