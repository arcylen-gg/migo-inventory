<form class="global-submit" role="form" action="{{$action}}" method="POST">
	<div class="modal-header">
		<button type="button" class="close" data-dismiss="modal">&times;</button>
		<h4 class="modal-title">Tag Employee</h4>
		<input type="hidden" name="_token" value="{{csrf_token()}}">
		<input type="hidden" name="company_id" value="{{$_company->payroll_company_id}}">
	</div>
	<div class="modal-body form-horizontal">
		<div class="form-group">
			<div class="col-md-12">
				<h4>{{$_company->payroll_company_name}}</h4>
			</div>
		</div>
		<div class="form-group">
			<div class="col-md-6">
				<small>Select Department</small>
				<select class="form-control change-filter change-filter-department">
					<option value="0">Select Department</option>
					@foreach($_department as $department)
					<option value="{{$department->payroll_department_id}}">{{$department->payroll_department_name}}</option>
					@endforeach
				</select>
			</div>


			<div class="col-md-6">
				<small>Select Job Title</small>
				<select class="form-control change-filter change-filter-job-title">
					<option value="0">Select Job Title</option>
				</select>
			</div>
		</div>
		<div class="form-group">
			<div class="col-md-12">
				<ul class="list-group employee-tag-list">

				  <li class="list-group-item padding-3-10"><div class="checkbox"><label><input type="checkbox" name="" class="check-all-tag">Check All</label></div></li>
				  @foreach($_employee as $employee)
				  <li class="list-group-item padding-3-10">
			  		<div class="checkbox">
			  		<label><input type="checkbox" name="employee_tag[]" class="check-tag"  value="{{$employee->payroll_employee_id}}" {{$employee->checked}}>{{$employee->payroll_employee_title_name . ' '.$employee->payroll_employee_first_name . ' ' . $employee->payroll_employee_middle_name . ' ' . $employee->payroll_employee_last_name  . ' ' . $employee->payroll_employee_suffix_name }}</label>
	  				</div>
	  			   </li>
				  @endforeach
				</ul>
			</div>
		</div>
	</div>
	<div class="modal-footer">
		<button type="button" class="btn btn-custom-white" data-dismiss="modal">Close</button>
		<button class="btn btn-custom-primary btn-submit" type="submit">Tag</button>
		
	</div>
</form>

<script type="text/javascript">
	$(".check-all-tag").unbind("change");
	$(".check-all-tag").bind("change", function()
	{
		if($(this).is(':checked'))
		{
			$(".check-tag").prop("checked", true);
		}	
		else
		{
			$(".check-tag").prop("checked", false);
		}
	});
	function submit_done(data)
	{
		if(data.status == 'success')
		{
        	toastr.success("Success");
        	$('.check-'+data.company_id).prop("checked", true);
        	data.element.modal("hide");
		}
	}
</script>

<script type="text/javascript" src="/assets/member/js/payroll/holiday_tag_employee.js"></script>