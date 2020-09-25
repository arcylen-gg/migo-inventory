<!-- @url '/member/item/load_one_um' -->
 
@foreach($_um_multi as $indx => $um)
	@if(isset($item_um_id))
		@if($item_um_id == $um->um_id)
			<option value="{{$um->multi_id}}" abbrev="{{$um->multi_abbrev}}" qty="{{$um->unit_qty}}" {{$selected_um_id == $um->multi_id ? 'selected' : ''}}>{{$um->multi_name}} ({{$um->multi_abbrev}})</option>
		@endif
	@else
		<option value="{{$um->multi_id}}" abbrev="{{$um->multi_abbrev}}" qty="{{$um->unit_qty}}" {{$indx == 0 ? 'selected' : ''}}>{{$um->multi_name}} ({{$um->multi_abbrev}})</option>
	@endif
	@if(sizeOf($_um_multi)-1 == $indx)
		<option class="hidden" value="" />
	@endif
@endforeach