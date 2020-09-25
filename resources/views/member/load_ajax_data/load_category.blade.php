@foreach($_category as $key=>$type)
	<option value="{{$type['type_id']}}" type-name="{{$type['type_name']}}" indent="{{$type['type_sub_level']}}" add-search="{{$add_search}}"
	{{ isset($type_id) ?  $type_id == $type['type_id'] ? 'selected' : '' : '' }}> 
	{{$type['type_name']}}
	</option>
	@if(isset($type['sub']))
		@include('member.load_ajax_data.load_category', ['_category' => $type['sub'], 'add_search' => $type['type_name']."|".$add_search])
	@endif
	@if(sizeOf($_category)-1 == $key)
		<option class="hidden" value="" />
	@endif	
@endforeach
