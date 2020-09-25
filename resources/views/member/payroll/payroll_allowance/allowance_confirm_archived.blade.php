<form class="global-submit" role="form" action="{{$action}}" method="POST">
	<div class="modal-header">
		<button type="button" class="close" data-dismiss="modal">&times;</button>
		<h4 class="modal-title">{!!$title!!}</h4>
		<input type="hidden" name="_token" value="{{csrf_token()}}">
		<input type="hidden" name="id" value="{{$id}}">
		<input type="hidden" name="archived" value="{{isset($archived) ? $archived : 0}}">
	</div>
	<div class="modal-body add_new_package_modal_body clearfix">
		<div class="form-group">
	    	<div class="col-md-12">
				<h3>{!!isset($html) ? $html : ''!!}</h3>
			</div> 
		</div>	
	</div>
	<div class="modal-footer">
	    <div class="col-md-6 col-xs-6">
				<button class="btn btn-custom-primary btn-submit form-control" type="submit">Confirm</button>
	    </div>
	    <div class="col-md-6 col-xs-6">
				<button type="button" class="btn btn-custom-white form-control" data-dismiss="modal">Cancel</button>
		</div>
	</div>	
</form>