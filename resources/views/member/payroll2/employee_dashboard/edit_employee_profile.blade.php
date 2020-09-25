<ol class="breadcrumb">
    <li class="breadcrumb-item">
      <a href="/employee">Update Information</a>
    </li>
</ol>
<form class="form-horizontal" method="post" action="/update_employee_profile?payroll_employee_id={{$employee->payroll_employee_id}}">
{{csrf_field()}}
@if(session()->has('error'))
    <div class="alert alert-danger">
      <strong>Warning!</strong> {!! session('error') !!}
    </div>
@endif

<div class="modal-body form-horizontal">
	<div class="row">
	    <div class="col-md-6">
			<small>Full Name</small>
			<input class="form-control" name="name" value="{{ $employee->payroll_employee_display_name }}">
	  	</div>
	  	<div class="col-md-3">
	      	<small>Gender</small>
			<input class="form-control" name="gender" value="{{ $employee->payroll_employee_gender }}">
		</div>
	  	<div class="col-md-3">
	      	<small>Birthdate</small>
			<input class="form-control" name="birthdate" type="date" value="{{ $employee->payroll_employee_birthdate }}">
		</div>
	</div>
	<div class="row">
		<div class="col-md-6">
			<small>Address: Street</small>
			<input class="form-control" name="street" value="{{ $employee->payroll_employee_street }}">
	  	</div>
	  	<div class="col-md-3">
			<small>City</small>
			<input class="form-control" name="city" value="{{ $employee->payroll_employee_city }}">
	  	</div>
	  	<div class="col-md-3">
			<small>State</small>
			<input class="form-control" name="state" value="{{ $employee->payroll_employee_state }}">
	  	</div>
	</div>
	<div class="row">
		<div class="col-md-6">
			<small>Contact Number</small>
			<input class="form-control" name="number" value="{{ $employee->payroll_employee_contact }}">
	  	</div>
		<div class="col-md-6">
			<small>Email Address</small>
			<input class="form-control" name="email" type="email" value="{{ $employee->payroll_employee_email }}" disabled>
	  	</div>
	</div>
	<div class="row">
		<div class="col-md-6">
			<small>TIN No.</small>
			<input class="form-control" name="tin" value="{{ $employee->payroll_employee_tin }}" disabled>
	  	</div>
		<div class="col-md-6">
			<small>SSS No.</small>
			<input class="form-control" name="sss" value="{{ $employee->payroll_employee_sss }}">
	  	</div>
	</div>
	<div class="row">
		<div class="col-md-6">
			<small>PAGIBIG No.</small>
			<input class="form-control" name="pagibig" value="{{ $employee->payroll_employee_pagibig }}">
	  	</div>
		<div class="col-md-6">
			<small>PHILHEATH No.</small>
			<input class="form-control" name="philhealth" value="{{ $employee->payroll_employee_philhealth }}">
	  	</div>
	</div>
</div>
<div class="modal-footer">
	<button type="submit" class="btn btn-primary btn-xs">Update</button>
	<a href="/employee_profile">
		<button type="button" class="btn btn-primary btn-xs">Cancel</button>
	</a>
</div>  
</form>


