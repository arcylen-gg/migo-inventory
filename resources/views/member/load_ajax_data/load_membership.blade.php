@if(isset($_membership))
	@if(count($_membership) > 0)
		@foreach($_membership as $membership)
			<option value="{{ $membership->membership_id }}" {{isset($membership_id) ? ($membership->membership_id == $membership_id ? 'selected' : '') : '' }} >{{ $membership->membership_name}}</option>
		@endforeach
	@endif
@endif