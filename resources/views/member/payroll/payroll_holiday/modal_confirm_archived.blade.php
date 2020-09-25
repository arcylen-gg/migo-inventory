<form class="global-submit" role="form" action="{{$action}}" method="POST">
	<div class="modal-header">
		<button type="button" class="close" data-dismiss="modal">&times;</button>
		<h4 class="modal-title">{!!$title!!}</h4>
		<input type="hidden" name="_token" value="{{csrf_token()}}">
		<input type="hidden" name="id" value="{{$id}}">
		<input type="hidden" name="archived" value="{{isset($archived) ? $archived : 0}}">
	</div>
	<div class="modal-body form-horizontal">
		{!!isset($html) ? $html : ''!!}
		<div class="form-group">
			<div class="col-md-6 text-center">
				<button type="button" class="btn btn-custom-white" data-dismiss="modal">Cancel</button>
			</div>
			<div class="col-md-6 text-center">
				<button class="btn btn-custom-primary btn-submit" type="submit">Confirm</button>
			</div>
		</div> 
		
	</div>
</form>