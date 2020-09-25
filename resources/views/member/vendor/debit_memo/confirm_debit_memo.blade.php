<form class="global-submit form-to-submit-add" action="{{$action}}" method="post">
    <input type="hidden" name="_token" value="{{csrf_token()}}">
    <input type="hidden" name="type" value="{{$type}}">
	<div class="modal-header">
		<button type="button" class="close" data-dismiss="modal">&times;</button>
		<h4 class="modal-title">Confirm</h4>
	</div>
	<div class="modal-body add_new_package_modal_body clearfix">
	    <div class="col-md-12">
	        <h3>{{$message}}</h3>
	    </div>
	</div>
	<div class="modal-footer">
	    <div class="col-md-6 col-xs-12"><button class="btn btn-custom-blue form-control">Yes</button></div>
	    <div class="col-md-6 col-xs-12"><button data-dismiss="modal" class="btn btn-def-white btn-custom-white form-control">Cancel</button></div>
	</div>
</form>