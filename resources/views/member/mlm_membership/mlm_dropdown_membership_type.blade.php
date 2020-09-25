@if($multiple == true)
	<select style="width: 100px" class="form-control membership_type" name="membership_type[]">
		<option value="PS" @if($selected) @if($selected =="PS") selected @endif  @endif>PS</option>
		<option value="FS" @if($selected) @if($selected =="FS") selected @endif  @endif>FS</option>
		<option value="CD" @if($selected) @if($selected =="CD") selected @endif  @endif>CD</option>
	</select>
@else
	<select style="width: 100px" class="form-control" name="membership_type">
		<option value="PS" @if($selected) @if($selected =="PS") selected @endif  @endif>PS</option>
		<option value="FS" @if($selected) @if($selected =="FS") selected @endif  @endif>FS</option>
		<option value="CD" @if($selected) @if($selected =="CD") selected @endif  @endif>CD</option>
	</select>
@endif
