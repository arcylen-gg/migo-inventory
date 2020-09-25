<div class="modal-header">
	<button type="button" class="close" data-dismiss="modal">×</button>
	<h4 class="modal-title">Reject</h4>
</div>
<form class="global-submit" action="{{$action or ''}}" method="post">
	<input type="hidden" name="_token" value="{{csrf_token()}}">
	<input type="hidden" name="est_id" value="{{$est_id}}">
	<div class="modal-body">
		<div class="row">
			<div class="clearfix modal-body">
				<div class="form-group">
					<h3 class="">Are you sure you want to reject {{$sales_order_reject->transaction_refnum}}?</h3>
				</div>
			</div>
		</div>
	</div>
	<div class="modal-footer">
		<button type="button" class="btn btn-def-white btn-custom-white" data-dismiss="modal">Close</button>
		<button class="btn btn-primary btn-custom-primary" type="submit">Yes</button>
	</div>
</form>