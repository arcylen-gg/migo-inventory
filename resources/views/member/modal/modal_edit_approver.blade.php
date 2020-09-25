<form class="global-submit" role="form" action="/member/payroll/payroll_admin_dashboard/save_edit_approver" method="post">
	<div class="modal-header">
		<input type="hidden" name="_token" value="{{csrf_token()}}">
		<input type="hidden" name="approver_id" value="{{$approver_id}}">
		
		<h4 class="modal-title">Edit Employee {{ ucfirst($approver_type) }} Approver</h4>
	</div>
	<div class="modal-body clearfix">
		<div class="form-horizontal">
			<div class="form-group">
				<small><b>Employee Name</b></small>
				<h4>{{$approver_info->payroll_employee_display_name}}</h4>
			</div>

			<div class="form-group">
				<small><b>Approver Type</b></small>
				<h4>{{$approver_info->payroll_approver_employee_type}}</h4>
			</div>

			<div class="form-group">
				<small><b>Approver Level</b></small>
				<select class="form-control" name="approver_level">
					<option value="1" {{ $approver_info->payroll_approver_employee_level == 1 ? 'selected':''}} >Level 1</option>
					<option value="2" {{ $approver_info->payroll_approver_employee_level == 2 ? 'selected':''}} >Level 2</option>
					<option value="3" {{ $approver_info->payroll_approver_employee_level == 3 ? 'selected':''}} >Level 3</option>
					<option value="4" {{ $approver_info->payroll_approver_employee_level == 4 ? 'selected':''}} >Level 4</option>
					<option value="5" {{ $approver_info->payroll_approver_employee_level == 5 ? 'selected':''}} >Level 5</option>
				</select>
			</div>
		</div>
	</div>
	<div class="modal-footer">
		<button type="button" class="btn btn-def-white btn-custom-white" data-dismiss="modal">Close</button>
		<button class="btn btn-primary btn-custom-primary" type="Submit">Submit</button>
	</div>
</form>
<script type="text/javascript" src="/assets/js/modal_payroll_group_approver.js"></script>
<script type="text/javascript">
  function reload_page(data)
  {
  	data.element.modal("hide");
    window.location.href = "/member/payroll/payroll_admin_dashboard/employee_approver";
  }
</script>