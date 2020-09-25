@extends('member.payroll2.employee_dashboard.layout')
@section('content')
<div>
	<ol class="breadcrumb">
		<li class="breadcrumb-item">
			<a href="/employee">Dashboard</a>
		</li>
		<li class="breadcrumb-item active">{{ $page }}</li>

	</ol>
</div>
<div class="pull-right">
	<a href="javascript:" onClick="action_load_link_to_modal('/edit_employee_profile', 'lg')">
	<button type="button"><i class="fa fa-pencil-square-o" aria-hidden="true"></i></button></a>
</div>
<br>
<div class="row">
	<!-- <div class="center-block">
		<img src="{{ $company->payroll_company_logo }}" alt="{{ $company->payroll_company_name }}" height="120" class="center-block">
	</div> -->
	<div class="col-md-12">
		<h4 class="text-center">{{ $employee->payroll_employee_display_name }}</h4>
		<p class="text-center">{{ $employee->payroll_jobtitle_name }}</p>
	</div>
</div>
{{csrf_field()}}
@if(session()->has('warning'))
<div class="alert alert-success">
	<strong>Updated Successfully!</strong>
</div>
@endif
<ul class="nav nav-tabs employee-profile" id="employee-profile" role="tablist">
	<li class="nav-item">
		<a class="nav-link active" data-toggle="tab" href="#personal_information" role="tab" aria-controls="personal_information" aria-selected="true">Personal Information</a>
	</li>
	<li class="nav-item">
		<a class="nav-link" data-toggle="tab" href="#job_information" role="tab" aria-controls="job_information" aria-selected="false">Job Information</a>
	</li>
	<li class="nav-item">
		<a class="nav-link" data-toggle="tab" href="#government_information" role="tab" aria-controls="government_information" aria-selected="false">Government Information</a>
	</li>
</ul>
<div class="tab-content">
	<div class="tab-pane active" id="personal_information" role="tabpanel" aria-labelledby="personal_information">
		<div class="row">
			<div class="col-md-6">
				<small>Full Name</small>
				<input class="form-control" value="{{ $employee->payroll_employee_display_name }}" disabled>
			</div>
			<div class="col-md-3">
				<small>Gender</small>
				<input class="form-control" value="{{ $employee->payroll_employee_gender }}" disabled>
			</div>
			<div class="col-md-3">
				<small>Birthdate</small>
				<input class="form-control" value="{{ date('M d, Y',strtotime($employee->payroll_employee_birthdate)) }}" disabled>
			</div>
		</div>
		<div class="row">
			<div class="col-md-12">
				<small>Address</small>
				<input class="form-control" value="{{ $employee->payroll_employee_street.' '.$employee->payroll_employee_city.' '.$employee->payroll_employee_state }}" disabled>
			</div>
		</div>
		<div class="row">
			<div class="col-md-6">
				<small>Contact Number</small>
				<input class="form-control" value="{{ $employee->payroll_employee_contact }}" disabled>
			</div>
			<div class="col-md-6">
				<small>Email Address</small>
				<input class="form-control" value="{{ $employee->payroll_employee_email }}" disabled>
			</div>
		</div>
	</div>
	<div class="tab-pane" id="job_information" role="tabpanel"  aria-labelledby="job_information">
		<div class="row">
			<div class="col-md-6">
				<small>Company Name</small>
				<input class="form-control" value="{{ $employee->payroll_company_name }}" disabled>
			</div>
			<div class="col-md-6">
				<small>Location</small>
				<input class="form-control" value="{{ $company->payroll_company_address }}" disabled>
			</div>
		</div>
		<div class="row">
			<div class="col-md-2">
				<small>Employee No.</small>
				<input class="form-control" value="{{ $employee->payroll_employee_number }}" disabled>
			</div>
			<div class="col-md-5">
				<small>Title</small>
				<input class="form-control" value="{{ $employee->payroll_jobtitle_name }}" disabled>
			</div>
			<div class="col-md-5">
				<small>Department</small>
				<input class="form-control" value="{{ $employee->payroll_department_name }}" disabled>
			</div>
		</div>
		<div class="row">
			<div class="col-md-4">
				<small>Employment Status</small>
				<input class="form-control" value="{{ $employee->payroll_employee_contract_status }}" disabled>
			</div>
			<div class="col-md-4">
				<small>Shift</small>
				<input class="form-control" value="{{ $employee->payroll_employee_title_name }}" disabled>
			</div>
			<div class="col-md-4">
				<small>Start Date</small>
				<input class="form-control" value="{{ date('M d, Y',strtotime($startdate->payroll_employee_contract_date_hired)) }}" disabled>
			</div>
		</div>
	</div>
	<div class="tab-pane" id="government_information" role="tabpanel"  aria-labelledby="government_information">
		<div class="row">
			<div class="col-md-6">
				<small>TIN No.</small>
				<input class="form-control" value="{{ $employee->payroll_employee_tin }}" disabled>
			</div>
			<div class="col-md-6">
				<small>SSS No.</small>
				<input class="form-control" value="{{ $employee->payroll_employee_sss }}" disabled>
			</div>
		</div>
		<div class="row">
			<div class="col-md-6">
				<small>PAGIBIG No.</small>
				<input class="form-control" value="{{ $employee->payroll_employee_pagibig }}" disabled>
			</div>
			<div class="col-md-6">
				<small>PHILHEATH No.</small>
				<input class="form-control" value="{{ $employee->payroll_employee_philhealth }}" disabled>
			</div>
		</div>
	</div>
</div>
</form>
@endsection

@section('script')
<script type="text/javascript" src="/assets/employee/js/employee_profile.js"></script>
@endsection