<form class="global-submit" role="form" action="/member/payroll/employee_list/modal_save_contract" method="POST">
	<div class="modal-header">
		<button type="button" class="close" data-dismiss="modal">&times;</button>
		<h4 class="modal-title">Create New Contract</h4>
		<input type="hidden" name="_token" value="{{csrf_token()}}">
		<input type="hidden" name="payroll_employee_id" value="{{$employee_id}}">
	</div>
	<div class="modal-body form-horizontal">
		<div class="form-group">
			<div class="col-md-12">
				<small>Department Name</small>
				<select class="form-control create-department-list" name="payroll_department_id" required>
					<option value="">Select Department</option>
					@foreach($_department as $department)
					<option value="{{$department->payroll_department_id}}">{{$department->payroll_department_name}}</option>
					@endforeach
				</select>
			</div>
		</div>
		<div class="form-group">
			<div class="col-md-12">
				<small>Job Title</small>
				<select class="form-control create-jobtitle-list" name="payroll_jobtitle_id" required>
					<option value="">Select Job Title</option>
				</select>
			</div>
		</div>
		<div class="form-group">
			<div class="col-md-6">
				<small>Effective Date</small>
				<input type="text" name="payroll_employee_contract_date_hired" class="form-control datepicker indent-13" required>
				<i class="fa fa-calendar pos-absolute top-30 margin-left-6 color-dark-gray" aria-hidden="true"></i>
			</div>
			<div class="col-md-6">
				<small>Date End</small>
				<input type="text" name="payroll_employee_contract_date_end" class="form-control datepicker indent-13">
				<i class="fa fa-calendar pos-absolute top-30 margin-left-6 color-dark-gray" aria-hidden="true"></i>
			</div>
		</div>
		<div class="form-group">
			<div class="col-md-6">
				<small>Payroll Group</small>
				<select class="form-control" name="payroll_group_id" required>
					<option value="">Select Group</option>
					@foreach($_group as $group)
					<option value="{{$group->payroll_group_id}}">{{$group->payroll_group_code}}</option>
					@endforeach
				</select>
			</div>
			<div class="col-md-6">
				<small>Employment Status</small>
				<select class="form-control" required name="payroll_employee_contract_status">
					<option value="">Select Status</option>
					@foreach($employement_status as $status)
					<option value="{{$status->payroll_employment_status_id}}">{{$status->employment_status}}</option>
					@endforeach
				</select>
			</div>
		</div>
	</div>
	<div class="modal-footer">
		<button type="button" class="btn btn-custom-white" data-dismiss="modal">Close</button>
		<button class="btn btn-custom-primary btn-submit" type="submit">Save</button>
	</div>
</form>
<script type="text/javascript">
			function reload(data)
		{
			data.element.modal("hide");
			location.reload();
		}
</script>
<script type="text/javascript" src="/assets/member/js/payroll/modal_create_company_details.js"></script>