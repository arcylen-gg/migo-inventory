<link rel="stylesheet" type="text/css" href="/assets/member/payroll/css/timesheet.css">
<link rel="stylesheet" type="text/css" href="/assets/external/jquery.timeentry.package-2.0.1/jquery.timeentry.css">
<form class="global-submit " role="form" action="/member/payroll/leave_schedule/save_schedule_leave_tag" method="post">
	<div class="modal-header">
		<button type="button" class="close" data-dismiss="modal">×</button>
		<h4 class="modal-title">Leave Schedule</h4>
		<input type="hidden" name="_token" value="{{csrf_token()}}">
	</div>
	<div class="modal-body form-horizontal">
		<div class="form-group">
			<div class="col-md-12">
				<div class="checkbox">
					<label><input type="checkbox" class="single_date_only" name="single_date_only" value="1" checked>Single date only</label>
				</div>
			</div>
		</div>
		<div class="form-group">
			<div class="col-md-6">
				<small>Schedule date Start</small>
				<input type="text" name="payroll_schedule_leave" class="date_picker form-control" required>
			</div>
			<div class="col-md-6">
				<small>Schedule date End</small>
				<input type="text" name="payroll_schedule_leave_end" class="date_picker form-control payroll_schedule_leave_end" disabled>
			</div>
		</div>
		<div class="form-group">
			<div class="col-md-6">
				<small>Leave Name</small>
				<select class="form-control select-leave" name="leave_reason" required>
					<option value="">Select leave</option>
					@foreach($_leave as $leave)
					<option value="{{$leave->payroll_leave_temp_id}}">{{$leave->payroll_leave_temp_name}}</option>
					@endforeach
				</select>
			</div>
			<div class="col-md-6">
				<small>&nbsp;</small>
				<button class="btn btn-custom-primary btn-block popup employee-tag" type="button" link="/member/payroll/leave_schedule/leave_schedule_tag_employee/0">Tag Employee</button>
			</div>
		</div>
		<div class="form-group">
			<div class="col-md-12">
				<table class="table table-condensed table-bordered">
					<thead>
						<tr>
							<th class="text-center">Employee name</th>
							<!-- <th class="text-center">Whole day</th> -->
							<th class="text-center">Hours</th>
							<th></th>
						</tr>
					</thead>
					<tbody class="table-employee-tag"></tbody>
				</table>
			</div>
		</div>
	</div>
	<div class="modal-footer">
		<button type="button" class="btn btn-def-white btn-custom-white" data-dismiss="modal">Close</button>
		<button class="btn btn-primary btn-custom-primary" type="submit"">Submit</button>
	</div>
</form>

<script type="text/javascript" src="/assets/external/jquery.timeentry.package-2.0.1/jquery.plugin.min.js"></script>
<script type="text/javascript" src="/assets/external/jquery.timeentry.package-2.0.1/jquery.timeentry.min.js"></script>

<script type="text/javascript">
	$(".date_picker").datepicker();
	$(".select-leave").unbind("change");
	$(".select-leave").bind("change", function()
	{
		var link = "/member/payroll/leave_schedule/leave_schedule_tag_employee/" + $(this).val();
		 $(".employee-tag").attr('link',link);
	});

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

		function reload(data)
		{
			data.element.modal("hide");
			location.reload();
		}
</script>