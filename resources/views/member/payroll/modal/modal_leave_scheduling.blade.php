<!-- Change action, name and function-->
<link rel="stylesheet" type="text/css" href="/assets/member/payroll/css/timesheet.css">
<link rel="stylesheet" type="text/css" href="/assets/external/jquery.timeentry.package-2.0.1/jquery.timeentry.css">
<form class="global-submit " role="form" action="/member/payroll/leave_schedule/v2/save_schedule_leave_tagv2" method="post">
	<div class="modal-header">
		<button type="button" class="close" data-dismiss="modal">×</button>
		<h4 class="modal-title">Leave Schedule</h4>
		<input type="hidden" name="_token" value="{{csrf_token()}}">
	</div>
	<div class="modal-body form-horizontal">
		<div class="form-group">

			<div class="col-md-6">
				<small>Leave Name</small>
				<select class="form-control select-leave" name="leave_reason" id="leavetempid" required>
					<option value="">Select Leave</option>
					@foreach($_leave_name as $leave)
					<option value="{{$leave->payroll_leave_temp_id}}">{{$leave->payroll_leave_temp_name}}</option>
					@endforeach
				</select>
			</div>

			<div class="col-md-6">
				<small>Date Created</small>
				<input type="text" name="payroll_leave_date_created" class="date_picker form-control" required>
			</div>
		</div>

		<div class="form-group">
			<div class="col-md-6">
				<div class="checkbox">
					<label><input type="checkbox" class="single_date_only" name="single_date_only" value="1" checked>Single date only</label>
				</div>
			</div>

			<div class="col-md-6">
				<small>With Pay?</small>
				<div class="form-control padding-b-37">
					<div class="col-md-6">
						<div class="radio">
							<label><input type="radio" class="payroll_leave_temp_with_pays" id="pay" name="payroll_leave_temp_with_pays" value="1" checked >Yes</label>
						</div>
					</div>
					<div class="col-md-6">
						<div class="radio">
							<label><input type="radio" class="payroll_leave_temp_with_pays" id="pays" name="payroll_leave_temp_with_pays" value="0" >No</label>
						</div>
					</div>					
				</div>		
			</div>

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
				<small>&nbsp;</small>
				<button class="btn btn-custom-primary btn-block popup employee-tag" type="button" link="/member/payroll/leave_schedule/v2/leave_schedule_tag_employeev2/0/1">Tag Employee</button>
			</div>
		</div>
		<div class="form-group">
			<div class="col-md-12">
				<table class="table table-condensed table-bordered">
					<thead>
						<tr>
							<th class="text-center">Employee name</th>
							<!-- <th class="text-center">Whole day</th> -->
							<th class="text-center">Used Leave</th>
							<th class="text-center">Remaining Leave</th>
							<th class="text-center">Apply Leave</th>
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
		if($('#pay').is(':checked'))
	    {
	    	var leave_pay_value = $('#pay').val();
	    }
	    else
	    {
	    	var leave_pay_value = $('#pays').val();
	    }

		var link = "/member/payroll/leave_schedule/v2/leave_schedule_tag_employeev2/" + $(this).val() +"/" + leave_pay_value;
		 $(".employee-tag").attr('link',link);
	});

	   $('input[type=radio][name=payroll_leave_temp_with_pays]').change(function() {

	    var leave  = $(".select-leave").val();

	    if(leave == "")
	    {
	    	leave = 0;
	    }

		var link = "/member/payroll/leave_schedule/v2/leave_schedule_tag_employeev2/" + leave +"/" + $(this).val();
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


	/* CALL A FUNCTION BY NAME */
	function executeFunctionByName(functionName, context /*, args */) {
	  var args = [].slice.call(arguments).splice(2);
	  var namespaces = functionName.split(".");
	  var func = namespaces.pop();
	  for(var i = 0; i < namespaces.length; i++) {
	    context = context[namespaces[i]];
	  }
	  return context[func].apply(context, args);
	}
</script>