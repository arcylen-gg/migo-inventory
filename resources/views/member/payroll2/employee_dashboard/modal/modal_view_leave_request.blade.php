<div class="modal-header">
    <h5 class="modal-title">{{ $page }}</h5>
    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
    <span aria-hidden="true">&times;</span>
    </button>
    <input type="hidden" name="_token" id="_token" value="{{ csrf_token() }}">

</div>
<div class="modal-body form-horizontal">
	  <div class="row">
 			    <div class="col-md-6">
 			    	<small>Type of Leave</small>
		      		<input class="form-control" type="text" class="form-control" name="payroll_request_leave_type" value="{{$request_info->payroll_request_leave_type}}" disabled>
		  		</div>

		  		<div class="col-md-6">
			  		<small>Date Filed</small>
			      	<input class="form-control" type="date" class="form-control" name="payroll_request_leave_date_filed" value="{{$request_info->payroll_request_leave_date_filed}}" disabled>
				</div>
        </div>

        <div class="row">
 			    <div class="col-md-3">
					<small>Available Leave</small>
				   	<input class="form-control" type="text" class="form-control" style="width: 80px;" disabled>
		  		</div>

		  		 <div class="col-md-3">
					<small>Unused Leave</small>
				   	<input class="form-control" type="text" class="form-control" style="width: 80px;" disabled>
		  		</div>

		  		 <div class="col-md-6">
					<small>Reliever</small>
					  <input class="form-control" type="text" class="form-control" name="payroll_request_leave_id_reliever" value="{{$reliever[0][
					  	'payroll_employee_display_name']}}" disabled>
		  		</div>
        </div>

         <div class="row">
 			    <div class="col-md-3">
					<small>Used Leave</small>
				   	<input class="form-control" type="text" class="form-control" style="width: 80px;" disabled>
		  		</div>

		  		 <div class="col-md-3">
					<small>Hours</small>
				   	<input class="text-center form-control break time-entry time-target time-entry-24 is-timeEntry" type="text" name="payroll_request_leave_total_hours" value="{{date('H:i',strtotime($request_info->payroll_request_leave_total_hours))}}"  placeholder="00:00" style="width: 80px;" disabled>
		  		</div>

		  		 <div class="col-md-6">
		
		  		</div>
        </div>

        <div class="row">
        	<div class="col-md-6">
        		<br>	
				<div class="checkbox">
					<label><input type="checkbox" class="single_date_only" name="single_date_only" value="1" checked>Single date only</label>
				</div>
			</div>
        </div>
        <div class="row">
		  		<div class="col-md-6">
			  		<small>Schedule Date Start</small>
			      	<input class="form-control" type="date" class="form-control" name="payroll_request_leave_date" value="{{$request_info->payroll_request_leave_date}}" disabled>
				</div>

		  		<div class="col-md-6">
			  		<small>Schedule Date End</small>
			      	<input class="form-control payroll_schedule_leave_end" type="date" class="form-control" name="payroll_request_leave_date_end" disabled>
				</div>
        </div>
        <br>
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
