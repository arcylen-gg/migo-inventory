<form class="global-submit" role="form" action="/member/payroll/jobtitlelist/modal_save_jobtitle" method="POST">
	<div class="modal-header">
		<button type="button" class="close" data-dismiss="modal">&times;</button>
		<h4 class="modal-title">Create Job Title</h4>
		<input type="hidden" name="_token" value="{{csrf_token()}}">
	</div>
	<div class="modal-body form-horizontal">
		<div class="form-group">
			<div class="col-md-12">
				<small>Department</small>
				<select  class="form-control" required name="payroll_jobtitle_department_id">
					<option value="">Select Department</option>
					@foreach($_department as $department)
					<option value="{{$department->payroll_department_id}}" {{$selected == $department->payroll_department_id ? 'selected="selected"' : ''}}>{{$department->payroll_department_name}}</option>
					@endforeach
				</select>
			</div>
		</div>
		<div class="form-group">
			<div class="col-md-12">
				<small>Job Title Name</small>
				<input type="text" name="payroll_jobtitle_name" required class="form-control" placeholder="Job Title Name">
			</div>
		</div>
	</div>
	<div class="modal-footer">
		<button type="button" class="btn btn-custom-white" data-dismiss="modal">Close</button>
		<button class="btn btn-custom-primary btn-submit" type="submit">Save</button>
	</div>
</form>