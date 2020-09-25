@extends('member.layout')
@section('css')
<style type="text/css">
	.checkbox
	{
		display: inline-block;
		margin-left: 10px;
	}
	.approver-type
	{
		border: 1px solid #ddd;
		padding: 5px;
	}
</style>
@endsection('css')
@section('content')
<form class="global-submit" action="/member/payroll/payroll_admin_dashboard/save_approver" method="post">
<div class="panel panel-default panel-block panel-title-block" id="top">
    <div class="panel-heading">
        <div>
            <i class="fa fa-tags"></i>
            <h1>
                <span class="page-title">Admin Dashboard &raquo; Create Access Group</span>
                <small>
                Create Access Group.
                </small>
            </h1>
            <input type="hidden" name="_token" value="{{csrf_token()}}">
        </div>
    </div>
</div>

<div class="panel panel-default panel-block panel-title-block">
	<div class="panel-body">
	   	<div class="form-horizontal">
			<div class="approver-type">
				
			   <div class="form-group">

					<div class="col-md-3">
						<div class="checkbox" style="margin-right: 100px;">
		                  <label><input type="checkbox" name="dashboard" value="1" checked>Dashboard</label>
		                </div>

		                <div class="checkbox" style="margin-right: 100px;">
		                <label><input type="checkbox" name="profile" value="1" checked>Profile</label>
		                </div>

		                <div class="checkbox" style="margin-right: 100px;">
		                <label><input type="checkbox" name="timekeeping" value="1" checked>Time Keeping</label>
		                </div>
		                <div class="checkbox" style="margin-left:20px;">
		                    <label><input type="checkbox" name="timesheet" value="1" checked>Timesheet</label>
		                </div>
		                <div class="checkbox" style="margin-left:20px;">
		                     <label><input type="checkbox" name="payslip" value="1" checked>Payslip</label>
		                </div>
		                <div class="checkbox">
		                    <label><input type="checkbox" name="leavemanagement" value="1" checked>Leave Management</label>
		                </div>
		                <div class="checkbox">
		                    <label><input type="checkbox" name="overtimemanagement" value="1" checked>Overtime Management</label>
		                </div>
		                <div class="checkbox">
		                    <label><input type="checkbox" name="obmanagement" value="1" checked>OB Management</label>
		                </div>
		                <div class="checkbox">
		                <label><input type="checkbox" name="approveraccess" value="1" checked>Approver Access</label>
		                </div>
		                <div class="checkbox">
		                   <label><input type="checkbox"  style="margin-left:20px;" name="leavemanagementapprover" value="1" checked>Leave Management</label>
		                </div>
		                <div class="checkbox">
		              	 <label><input type="checkbox"  style="margin-left:20px;" name="overtimemanagementapprover" value="1" checked>Overtime Management</label>
		                </div>
		                <div class="checkbox">
		                <label><input type="checkbox"  style="margin-left:20px;" name="obmanagementapprover" value="1" checked>OB Management</label>
		                </div>
		                <div class="checkbox">
		                <label><input type="checkbox"  style="margin-left:20px;" name="rfpmanagementapprover" value="1" checked>RFP Management</label>
		                </div>
		            </div>

				</div>


					<div class="form-group" style="margin-top: 10px;">
					  <div class="col-md-4">
					    <small><b>Filter Company</b></small>
					    <select class="form-control change-filter change-filter-company">
					      <option value="0">Select Company</option>
					      @foreach($_company as $company)
					      <option value="{{$company->payroll_company_id}}">{{$company->payroll_company_name}}</option>
					      @endforeach
					    </select> 
					  </div>
					  <div class="col-md-4">
					    <small><b>Filter Department</b></small>
					    <select class="form-control change-filter change-filter-department"  >
					      <option value="0" >Select Department</option>
					      @foreach($_department as $department)
					      <option value="{{$department->payroll_department_id}}">{{$department->payroll_department_name}}</option>
					      @endforeach
					    </select>
					  </div>
					  <div class="col-md-4">
					    <small><b>Filter Job Title</b></small>
					    <select class="form-control change-filter change-filter-job-title">
					      <option value="0">Select Job Title</option>
					    </select>
					  </div>
					</div>
			</div>
			
			
			
			
			
			<div class="form-group">
				<div class="col-md-12">
					<span><b>Select Employee</b></span>
				</div>
			</div>
			<div class="table-employee-tag">
				
			</div>
	   	</div>
		<button type="submit" class="btn btn-custom-primary">Save</button>
	</div>
</div>
</form>
@endsection('content')

@section('script')
<script type="text/javascript" src="/assets/member/js/payroll/create_approver.js"></script>
<script type="text/javascript">
	function success_saving_import()
	{	
		window.location.href = "/member/payroll/payroll_admin_dashboard/employee_approver";
	}
</script>
@endsection('script')