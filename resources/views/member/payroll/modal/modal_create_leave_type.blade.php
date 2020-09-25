<!-- Change action and function-->
<form class="global-submit" role="form" action="/member/payroll/leave/v2/modal_save_leave_type" method="POST">
	<div class="modal-header">
		<button type="button" class="close" data-dismiss="modal">&times;</button>
		<h4 class="modal-title">Create Leave Type</h4>
		<input type="hidden" name="_token" value="{{csrf_token()}}">
	</div>
	<div class="modal-body form-horizontal">
		<div class="form-group">
			<div class="col-md-12">
				<small>Leave Name</small>
				<input type="text" name="payroll_leave_type_name" class="form-control" required>
			</div>
		</div>
	</div>	

	<div class="modal-footer">
		<button type="button" class="btn btn-custom-white" data-dismiss="modal">Close</button>
		<button class="btn btn-custom-primary btn-submit" type="submit">Save</button>
	</div>
</form>
<script type="text/javascript" src="/assets/member/js/payroll/modal_create_leave_temp.js"></script>