<form class="global-submit form-horizontal" role="form" action="/member/utilities/edit-user" method="post">
	<input type="hidden" name="_token" value={{csrf_token()}}>
	<input type="hidden" name="user_id" value={{$user->user_id}}>
	<div class="modal-header">
		<button type="button" class="close" data-dismiss="modal">×</button>
		<h4 class="modal-title">Edit User</h4>
	</div>
	<div class="modal-body clearfix">
		<div class="col-md-12">
			<div class="form-group">
			  	<label for="position_name">Email</label>
			  	<input type="text" class="form-control" name="user_email" value="{{$user->user_email}}">
			</div>
			<div class="form-group">
			  	<label for="position_name">Password</label>
			  	<input type="password" class="form-control" name="user_password" value="{{$user_password}}">
			</div>
			<div class="form-group">
			  	<label for="position_name">First Name</label>
			  	<input type="text" class="form-control" name="user_first_name" value="{{$user->user_first_name}}">
			</div>
			<div class="form-group">
			  	<label for="position_name">Last Name</label>
			  	<input type="text" class="form-control" name="user_last_name" value="{{$user->user_last_name}}">
			</div>
			<div class="form-group">
			  	<label for="position_name">Contact #</label>
			  	<input type="text" class="form-control" name="user_contact_number" value="{{$user->user_contact_number}}">
			</div>
			<div class="form-group">
			  	<label for="position_rank">Rank</label>
			  	<select class="form-control" name="user_level">
			  		@foreach($_rank as $rank)
			  			<option value="{{$rank['position_id']}}" {{$user->user_level == $rank['position_id'] ? 'selected' : ''}}>{{$rank['position_name']}} </option>
			  		@endforeach
			  	</select>
			</div>
			<div class="form-group">
			  	<label>Warehouse Included</label>
			</div>
			<div class="form-group">
			  	@foreach($_warehouse as $warehouse)
			  		<div class="col-md-12">
			  			<input value="{{$warehouse->warehouse_id}}" {{isset($warehouse_user[$warehouse->warehouse_id]) ? 'checked' : ''}} type="checkbox" id="warehouse_{{$warehouse->warehouse_id}}" name="warehouse_id[]" >
			  			<label for="warehouse_{{$warehouse->warehouse_id}}">{{$warehouse->warehouse_name}}</label>
			  		</div>
			  	@endforeach
			</div>
		</div>
	</div>
	<div class="modal-footer">
		<button type="button" class="btn btn-def-white btn-custom-white" data-dismiss="modal">Close</button>
		<button class="btn btn-primary btn-custom-primary" type="submit">Update</button>
	</div>
</form>

@section('script')

@endsection