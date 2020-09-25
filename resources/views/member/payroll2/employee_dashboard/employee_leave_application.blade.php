<link rel="stylesheet" type="text/css" href="/assets/member/payroll/css/timesheet.css">

<form class="global-submit" role="form" action="/leave/save_leave" method="POST">
<ol class="breadcrumb">
    <li class="breadcrumb-item">
      <a href="/employee">Dashboard </a>
    </li>
    <input type="hidden" name="_token" id="_token" value="{{csrf_token()}}">
   <li class="breadcrumb-item active">&nbsp/&nbsp{{ $page }}</li>
</ol>
	<div class="modal-body form-horizontal">

        <div class="row">
 			    <div class="col-md-6">
 			    	<small>Type of Leave</small>
					<select class="form-control user-error" id="leave_type" name="payroll_request_leave_type" aria-invalid="true" required>
						<option value="">Select Leave Type</option>
					@foreach($leave_type as $leave)
		    		<option value="{{ $leave->payroll_leave_temp_id }}"> {{ $leave->payroll_leave_temp_name }} </option>
		    		@endforeach
					</select>
					<label>Status of Leave</label>
		  		</div>

		  		<div class="col-md-6">
			  		<small>Date Filed</small>
			      	<input class="form-control" type="date" class="form-control" name="payroll_request_leave_date_filed" required>
				</div>
        </div>

        <div class="row">
 			    <div class="col-md-3">
					<small>Available Leave</small>
				   	<input class="form-control" type="text" class="form-control" id="total" style="width: 80px;" disabled>
		  		</div>

		  		 <div class="col-md-3">
					<small>Unused Leave</small>
				   	<input class="form-control" type="text" class="form-control" id="remaining" style="width: 80px;" disabled>
		  		</div>

		  		 <div class="col-md-6">
					<small>Reliever</small>
					<select class="form-control" name="payroll_request_leave_id_reliever" required>
						<option value="">Select Employee</option>
						@foreach($employees_info as $employee)
							<option value="{{$employee->payroll_employee_id}}">{{$employee->payroll_employee_first_name}} {{$employee->payroll_employee_middle_name}} {{$employee->payroll_employee_last_name}}</option> 
						@endforeach

					</select>
		  		</div>
        </div>

         <div class="row">
 			    <div class="col-md-3">
					<small>Used Leave</small>
				   	<input class="form-control" type="text" name="used" id="used" class="form-control used" style="width: 80px;" disabled>
		  		</div>

		  		 <div class="col-md-3">
					<small>Hours</small>
				   	<input class="text-center form-control break time-entry time-target time-entry-24 is-timeEntry" type="text" name="payroll_request_leave_total_hours" placeholder="00:00" style="width: 80px;">
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
			      	<input class="form-control" type="date" class="form-control" name="payroll_request_leave_date" required>
				</div>

		  		<div class="col-md-6">
			  		<small>Schedule Date End</small>
			      	<input class="form-control payroll_schedule_leave_end" type="date" class="form-control" name="payroll_request_leave_date_end" disabled="">
				</div>
        </div>
        <br>
       	<div class="row">
	        <div class="col-sm-12">
	        <small>Remarks</small>
	        	<textarea class="form-control" rows="4" name="remark" placeholder="Type Remarks" required></textarea>
	        </div>
	    </div>
        <br>

	    <div class="row">
	    	<div class="col-sm-12">
	    		<small for="approver_group">Select Group Approver</small>
		    	<select class="form-control approver_group_leave" id="approver_group" name="approver_group" required>
		    		<option value=""> Select Group Approver </option>
		    		@foreach($_group_approver as $group_approver)
		    		<option value="{{ $group_approver->payroll_approver_group_id }}"> {{ $group_approver->payroll_approver_group_name }} </option>
		    		@endforeach
		    	</select>
	    	</div>
	    </div> 
	    
	    <div class="row">
	    	<div class="col-sm-12 approver_group_list_leave">
	    		
	    	</div>
	    </div>    

	</div>
	<div class="modal-footer">
		<button type="button" class="btn btn-primary btn-md" data-dismiss="modal">Cancel</button>
		<button type="submit" class="btn btn-primary btn-md">Submit</button>
	</div>
</form>
<script>
	var data = {};

		function reload(data)
		{
			data.element.modal("hide");
			location.reload();
		}
	
		$(".time-entry").timeEntry('destroy');
		$(".time-entry-24").timeEntry('destroy');
		$(".time-entry.time-in").timeEntry({ampmPrefix: ' ', defaultTime: new Date(0, 0, 0, 0, 0, 0)});
		$(".time-entry.time-out").timeEntry({ampmPrefix: ' ', defaultTime: new Date(0, 0, 0, 12, 0, 0)});
		$(".time-entry-24").timeEntry({unlimitedHours: true, defaultTime: new Date(0, 0, 0, 0, 0, 0)});

		$(".single_date_only").unbind("change");
		$(".single_date_only").bind("change",function()
		{
			if($(this).is(':checked'))
			{
				$(".payroll_schedule_leave_end").attr("disabled","true");
				$(".payroll_schedule_leave_end").removeAttr("required");
			}
			else
			{
				$(".payroll_schedule_leave_end").removeAttr("disabled");
				$(".payroll_schedule_leave_end").attr("required",true);
			}
		});

		$("#leave_type").on("change",function()
		{
			data.leave_temp_id = $(this).val();
			data._token = $('#_token').val();

			$.ajax(
			{
				url: '/get_leave_hours',
				type: 'post',
				data: data,
				success : function(data)
				{
					result = JSON.parse(data);
					if(result[0].total_leave_consume == null)
					{
						$("#used").val('0.00');
						$("#total").val(result[0].payroll_leave_temp_hours);
						$("#remaining").val(result[0].payroll_leave_temp_hours);
					}
					else
					{
						$("#used").val(result[0].total_leave_consume);
						$("#total").val(result[0].payroll_leave_temp_hours);
						$("#remaining").val(result[0].remaining_leave);
					}
			

				}
			});
		});
</script>
<script type="text/javascript" src="/assets/employee/js/employee_overtime_application.js"></script>