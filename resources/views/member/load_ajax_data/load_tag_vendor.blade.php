@foreach($_tag_vendor as $key =>$vendor)
	<option value="{{$vendor['tag_vendor_id']}}" {{ isset($vendor_id) ?  $vendor_id == $vendor['vendor_id'] ? 'selected' : '' : '' }}>{{$vendor['vendor_company'] != "" ? $vendor['vendor_company'] : ucwords($vendor['vendor_title_name'].' '.$vendor['vendor_first_name'].' '.$vendor['vendor_middle_name'].' '.$vendor['vendor_last_name']) }} </option>
@if(sizeOf($_item)-1 == $key)
	<option class="hidden" value="" />
@endif
@endforeach 
