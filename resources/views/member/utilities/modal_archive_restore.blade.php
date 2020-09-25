<form class="global-submit form-horizontal" role="form" action="{{$action}}" method="post">
	<input type="hidden" name="_token" value="{{csrf_token()}}">
	<input type="hidden" name="id" value="{{$user->user_id or $position->position_id}}">
	<div class="modal-header" style="text-align: center !important;">
		<button type="button" class="close" data-dismiss="modal">×</button>
		@if($title == 'archived-user')
			<h4 class="modal-title">Delete this User ({{$user->user_first_name or ''}}) ?</h4>
		@elseif($title == 'restored-user')
			<h4 class="modal-title">Restore this User ({{$user->user_first_name or ''}}) ?</h4>
		@elseif($title == 'archived-position')
			<h4 class="modal-title">Delete this Position ({{$position->position_name or ''}}) ?</h4>
		@elseif($title == 'restored-position')
			<h4 class="modal-title">Restore this Position ({{$position->position_name or ''}}) ?</h4>
		@endif
	</div>
	<div class="modal-footer" style="text-align: center !important;">
		<button type="button" class="btn btn-def-white btn-custom-white" data-dismiss="modal">Close</button>
		<button class="btn btn-primary btn-custom-primary" type="submit">Confirm</button>
	</div>
</form>