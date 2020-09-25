<form class="global-submit form-horizontal" role="form" action="/member/item/token/update-token" method="post">
	{{ csrf_field()  }}
	<div class="modal-header">
		<button type="button" class="close" data-dismiss="modal">×</button>
		<h4 class="modal-title">{{$page}}</h4>
	</div>
	<div class="modal-body clearfix">
		<div class="form-group">
	        <div class="col-md-12">
	            <label for="basic-input">Token Name</label>
				<input id="basic-input" value="{{$token_name}}" class="form-control" name="token_name" placeholder="Token Name">
	        	<input type="hidden" name="token_id" value="{{$token_id}}">
	        </div>
	    </div>

	</div>
	<div class="modal-footer">
		<button type="button" class="btn btn-def-white btn-custom-white" data-dismiss="modal">Close</button>
		<button class="btn btn-primary btn-custom-primary" type="submit">Update</button>
	</div>
</form>
<script type="text/javascript">
	function success(data)
	{
		toastr.success('Success');
		data.element.modal('hide');
		new_token.action_load_table();
	}
	function error_name()
	{
		toastr.error("Invalid Token Name");
	}
</script>