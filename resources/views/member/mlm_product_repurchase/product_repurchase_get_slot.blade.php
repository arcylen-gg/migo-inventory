<label>Slot</label>
<select class="form-control chosen-slot_id input-sm pull-left" name="slot_id" data-placeholder="Select a slot" onChange="if (typeof change_slot == 'function'){change_slot(this);}">
    <option value=""></option>
	@if(count($_slot) != 0)
		@foreach($_slot as $slot)
			<option value="{{$slot->slot_id}}">{{$slot->slot_no}} ({{$slot->membership_name}})</option>
		@endforeach
	@endif
</select>