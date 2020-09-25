@if(isset($_proposal))
	@if(count($_proposal) > 0)
		@foreach($_proposal as $proposal)
			<option {{isset($item_proposal) ? ($item_proposal == $proposal ? 'selected' : '') : ''}} value="{{$proposal}}">{{$proposal}}</option>
		@endforeach
	@endif
@endif