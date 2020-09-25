<form class="global-submit" role="form" action="/member/payroll/departmentlist/modal_update_department" method="POST">
	<div class="modal-header">
		<button type="button" class="close" data-dismiss="modal">&times;</button>
		<h4 class="modal-title">Create Department</h4>
		<input type="hidden" name="_token" value="{{csrf_token()}}">
		<input type="hidden" name="payroll_department_id" value="{{$deparmtent->payroll_department_id}}">
	</div>
	<div class="modal-body form-horizontal">
		<div class="form-group">
			<div class="col-md-12">
				<small>Department Name</small>
				<input type="text" name="payroll_department_name" class="form-control view-form" required value="{{$deparmtent->payroll_department_name}}" {{$action == 'view' ? 'disabled':''}}>
			</div>
		</div>
	</div>
	<div class="modal-footer">
		<button type="button" class="btn btn-custom-white" data-dismiss="modal">Close</button>
		<button class="btn btn-custom-primary btn-submit {{$action == 'view' ? 'display-none':''}}" type="submit">Update</button>
		<button class="btn btn-danger btn-edit {{$action == 'view' ? '':'display-none'}}" type="button">Edit</button>
	</div>
</form>