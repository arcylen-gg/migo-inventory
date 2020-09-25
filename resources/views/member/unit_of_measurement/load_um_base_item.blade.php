@if(isset($sub) && isset($base))
<div class="col-md-6">
    <label>{{$sub->multi_name." (".$sub->multi_abbrev.")"}}</label>
	<input type="text" class="form-control input-sm" name="base_qty" disabled value="{{$base->unit_qty}}">
</div>
<div class="col-md-6">
    <label>{{$base->multi_name." (".$base->multi_abbrev.")"}}</label>
	<input type="text" class="form-control input-sm" name="sub_qty" value="{{$sub->unit_qty}}">
</div>    
@endif