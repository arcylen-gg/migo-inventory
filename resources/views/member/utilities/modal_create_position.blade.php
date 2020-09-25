<form class="global-submit form-horizontal" role="form" action="/member/utilities/create-position" method="post">
	<input type="hidden" name="_token" value={{csrf_token()}}>
	<div class="modal-header">
		<button type="button" class="close" data-dismiss="modal">×</button>
		<h4 class="modal-title">Create user Position</h4>
	</div>
	<div class="modal-body clearfix">
		<div class="col-md-12">
			<div class="form-group">
			  	<label for="position_name">Name</label>
			  	<input type="text" class="form-control" name="position_name">
			</div>
			@if($is_developer)
				<div class="form-group">
				  	<label for="position_shop_id">Shop</label>
				  	<select class="form-control" name="position_shop_id">
				  		@foreach($_shop as $shop)
				  			<option value="{{$shop->shop_id}}"> {{$shop->shop_key}} </option>
				  		@endforeach
				  	</select>
				</div>
			@endif
			<div class="form-group">
			  	<label for="position_rank">Rank</label>
			  	<select class="form-control" name="position_rank">
			  		@foreach($_rank as $rank)
			  			<option value="{{$rank['position_rank']}}">Rank {{$rank['position_rank']}} </option>
			  		@endforeach
			  	</select>
			</div>
		</div>
	</div>
	<div class="modal-footer">
		<button type="button" class="btn btn-def-white btn-custom-white" data-dismiss="modal">Close</button>
		<button class="btn btn-primary btn-custom-primary" type="submit">Create</button>
	</div>
</form>

@section('script')

@endsection