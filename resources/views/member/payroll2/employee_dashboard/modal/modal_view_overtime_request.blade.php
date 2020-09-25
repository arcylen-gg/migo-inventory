<style type="text/css">
.row
{
	margin-top: 10px;
}
</style>
<div class="modal-header">
    <h5 class="modal-title">{{ $page }}</h5>
    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
    <span aria-hidden="true">&times;</span>
    </button>
    <input type="hidden" name="_token" id="_token" value="{{ csrf_token() }}">

</div>
<div class="modal-body form-horizontal">
	<!-- <div class="row">
		<div class="col-md-6" class="text-center">
			<a href="#" class="popup" link="/employee_overtime_view_shift" size="lg"><button class="btn btn-link">View Shift Schedule</button></a>
		</div>
	</div> -->
	<div class="row">
		<div class="col-md-6">
			<small>Overtime Date</small>
			<input class="form-control" type="text" value="{{date('F d, Y',strtotime($request_info->payroll_request_overtime_date))}}" disabled>
		</div>
		<div class="col-md-6">
			<small>Overtime Type</small>
			<input class="form-control" type="text" value="{{$request_info->payroll_request_overtime_type}}" disabled>
		</div>
	</div>
	<div class="row">
			<div class="col-md-3">
				<small>Regular Time In</small>
				<input class="form-control" type="text" value="{{date('h:i A',strtotime($request_info->payroll_request_regular_time_in))}}" disabled>
			</div>
			<div class="col-md-3">
				<small>Regular Time Out</small>
				<input class="form-control" type="text" value="{{date('h:i A',strtotime($request_info->payroll_request_regular_time_out))}}" disabled>
			</div>
			<div class="col-md-3">
				<small>Over Time In</small>
				<input class="form-control" type="text" value="{{date('h:i A',strtotime($request_info->payroll_request_overtime_time_in))}}" disabled>
			</div>
			<div class="col-md-3">
				<small>Over Time Out</small>
				<input class="form-control" type="text" value="{{date('h:i A',strtotime($request_info->payroll_request_overtime_time_out))}}" disabled>
			</div>
	</div>
	<div class="row">
        <div class="col-sm-12">
        <small>Remarks</small>
        	<input class="form-control" type="text" value="{{$request_info->payroll_request_overtime_remark}}" disabled>
        </div>
    </div>
    <div class="row">
        <div class="col-sm-6">
        <small>Status</small>
        	<input class="form-control" type="text" value="{{$request_info->payroll_request_overtime_status}}" disabled>
        </div>
        <div class="col-sm-6">
        <small>Status Level</small>
        	<input class="form-control" type="text" value="Level {{$request_info->payroll_request_overtime_status_level}}" disabled>
        </div>
    </div>
    <div class="row">
        <div class="col-sm-12">
        <small>Approver Group Name</small>
        	<input class="form-control" type="text" value="{{$approver_group_info->payroll_approver_group_name}}" disabled>
        </div>
    </div>
    <div class="row">
        <div class="col-sm-12">
        <small>Approver Group List</small>
        	@foreach($_group_approver as $level => $group_approver)
			<ul style="list-style-type: none;">
				<li>Level {{$level}} Approver/s
					<ul>
						@foreach($group_approver as $key => $employee_approver)
						<li>
							{{$employee_approver->payroll_employee_first_name}} {{$employee_approver->payroll_employee_last_name}}
						</li>
						@endforeach
					</ul>
				</li>
			</ul>
        	@endforeach
        </div>
    </div>
 
</div>
<div class="modal-footer">
	<button type="button" data-dismiss="modal" class="btn btn-primary btn-md">Exit</button>
<!-- 	<button type="submit"  class="btn btn-primary btn-md">Submit</button> -->
</div>
