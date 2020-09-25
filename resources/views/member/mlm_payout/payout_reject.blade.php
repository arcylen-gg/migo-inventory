<form class="global-submit" method="post" action="/member/mlm/payout/reject-encashment-submit">
	<input type="hidden" name="_token" value="{{ csrf_token() }}">
	<div class="modal-header">
		<button type="button" class="close" data-dismiss="modal">×</button>
		<h4 class="modal-title">REJECT ENCASHMENT</h4>
	</div>
	<div class="modal-body clearfix">
		<div class="form-group text-center">
			<h4>Are you sure you want to reject all encashment that ...</h4>
		</div>
		<div class="form-group">
			<div class="col-md-12">
				<label> Less than amount of : </label>
				<input type="text" class="form-control" name="less_than_amount" value="500">
			</div>
		</div>
		<br>
		<br>
		<div class="form-group"></div>
		<div class="form-group"></div>
		<div class="form-group">
			<div class="col-md-12">
				<label> Type of encashment are :  </label>
				<input type="text" class="form-control" name="encashment_type" value="cheque">
			</div>
		</div>
		<div class="form-group">
			<div class="col-md-12">
				<label> Remarks </label>
				<textarea name="remarks" class="form-control"></textarea>
			</div>
		</div>
	</div>
	<div class="modal-footer">
		<button type="button" class="btn btn-def-white btn-custom-white" data-dismiss="modal">Close</button>
		<button type="submit" class="btn btn-primary btn-custom-primary">Submit</button>
	</div>
</form>
<script type="text/javascript">
	function success_reject(data) 
	{
		if(data.status == 'success')
		{
			toastr.success("Success");
			setInterval(function()
			{
				location.reload();
			},2000)
		}
	}
</script>