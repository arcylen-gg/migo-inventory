<!-- @url '/member/vendor/load_vendor' -->

@foreach($_vendor as $key => $vendor)

	<option value="{{$vendor['vendor_id']}}" ctr-ref-num="{{$vendor['ctr_pb_refnum']}}" ctr-ri="{{$vendor['ctr_ri']}}" ctr-eb="{{$vendor['ctr_eb']}}" ctr-wc="{{$vendor['ctr_wc']}}" ctr-db="{{$vendor['ctr_db']}}" email="{{$vendor['vendor_email']}}" billing-address="{{ $vendor['ven_billing_street'] }}" {{ isset($vendor_id) ?  $vendor_id == $vendor['vendor_id'] ? 'selected' : '' : '' }}
	@if(count($vendor['tag_item']) > 0)
		@foreach($vendor['tag_item'] as $tag_item)
			item_id_{{$tag_item->tag_item_id}}='{{$tag_item->tag_item_quotation}}' 
		@endforeach
	@endif
	@if(count($vendor['orig_item']) > 0)
		@foreach($vendor['orig_item'] as $orig_item)
			orig_item_id_{{$orig_item->item_id}}='{{$orig_item->item_cost}}'
		@endforeach
	@endif
	>{{$vendor['vendor_company'] != "" ? $vendor['vendor_company'] : ucwords($vendor['vendor_title_name'].' '.$vendor['vendor_first_name'].' '.$vendor['vendor_middle_name'].' '.$vendor['vendor_last_name']) }} </option>

	@if(sizeOf($_vendor)-1 == $key)
		<option class="hidden" value="" />
	@endif
@endforeach 
