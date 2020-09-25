<label>Slot No</label>
<select name="membership_code" class="form-control chosen-select slot_id_container" onChange="bar_code_membership_code(this)">
  @foreach($_slot as $slot)
    <option value="{{$slot->slot_no}}">{{$slot->slot_no}}</option>
  @endforeach
</select> 
<small style="color:gray;">List of slots.</small>