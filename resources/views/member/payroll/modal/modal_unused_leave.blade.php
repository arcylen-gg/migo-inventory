<form class="global-submit " role="form" action="/member/payroll/payroll_process/modal_save_process_leave" method="post">
	<div class="modal-header">
		<button type="button" class="close" data-dismiss="modal">×</button>
		<h4 class="modal-title">Process unused leave</h4>
		<input type="hidden" name="_token" value="{{csrf_token()}}">
		<input type="hidden" name="payroll_employee_id" value="{{$payroll_employee_id}}">
		<input type="hidden" name="payroll_period_company_id" value="{{$payroll_period_company_id}}">
	</div>
	<div class="modal-body form-horizontal">
		<table class="table table-bordered table-condensed">
			<thead>
				<tr>
					<th></th>
					<th>Leave Name</th>
					<th>Capping</th>
					<th>Remaining</th>
					<th>Avail leave</th>
				</tr>
			</thead>
			<tbody>
				@foreach($payable_leave as $leave)
				<tr>
					<td class="text-center" valign="center">
						<input type="checkbox" name="payroll_leave_temp_id[]" value="{{$leave['payroll_leave_temp_id']}}" class="check-avail-leave" {!!$leave['status']!!}>
						<input type="hidden" value="{{$leave['payroll_leave_temp_name']}}" name="payroll_leave_temp_name[]">
					</td>
					<td>
						{{$leave['payroll_leave_temp_name']}}
					</td>
					<td class="text-right">
						{{$leave['payroll_leave_temp_days_cap']}}
					</td>
					<td class="text-right">
						{{$leave['remaining']}}
					</td>
					<td>
						<input type="number" name="process_leave_quantity[]" class="form-control text-right number-avail" max="{{$leave['remaining']}}" min="0" placeholder="0" step="1" value="{{$leave['process_leave_quantity']}}">
					</td>
				</tr>
				@endforeach
			</tbody>
		</table>
	</div>
	<div class="modal-footer">
		<button type="button" class="btn btn-def-white btn-custom-white" data-dismiss="modal">Close</button>
		<button class="btn btn-primary btn-custom-primary" type="submit"">Submit</button>
	</div>
</form>
<script type="text/javascript">

	checkifcheck();
	function checkifcheck()
	{
		$(".check-avail-leave").each(function()
		{
			var avail_input = $(this).parents("tr").find(".number-avail");
			if($(this).is(":checked"))
			{
				avail_input.removeAttr("readonly");
				avail_input.Attr("required", true);
			}
			else
			{
				avail_input.attr("readonly", true);
				avail_input.removeAttr("required");
			}
		});
	}

	$(".check-avail-leave").unbind("change");
	$(".check-avail-leave").bind("change", function()
	{
		checkifcheck();
	});
</script>