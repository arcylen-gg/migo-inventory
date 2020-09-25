<link rel="stylesheet" type="text/css" href="/assets/member/payroll/css/timesheet.css">
<link rel="stylesheet" type="text/css" href="/assets/external/jquery.timeentry.package-2.0.1/jquery.timeentry.css">
<table class="table table-condensed table-bordered timesheet">
	<thead>
		<tr>
			<th rowspan="2" valign="center" class="text-center">Day</th>
			<th rowspan="2" class="text-center">Date</th>
			<th rowspan="2" valign="center" class="text-center">Target Hours</th>
			<th colspan="2" class="text-center">Work Schedule</th>
			<th colspan="2" class="text-center">Break Schedule</th>
			<th rowspan="2" class="text-center">Flexi Time</th>
			<th rowspan="2" class="text-center">Rest Day</th>
			<th rowspan="2" class="text-center">Extra Day</th>
			
		</tr>
		<tr>
			<th class="text-center">Start</th>
			<th class="text-center">End</th>
			<th class="text-center">Start</th>
			<th class="text-center">End</th>
		</tr>
	</thead>
	<tbody>
		@foreach($_day as $key => $day)
		<tr class="editable">
			<td>
				{{date('D', strtotime($day['date']))}}
				<input type="hidden" name="day[]" value="{{$day['date']}}">
			</td>
			<td>
				{{date('d', strtotime($day['date']))}}
			</td>
			<td>
				<input type="number" name="target_hours[]" class="form-control text-center" step="any" value="{{$day['target_hours']}}">
			</td>
			<td class="editable">
				<input type="text" name="work_start[]" class="text-table time-entry" value="{{date('H:i a', strtotime($day['work_start']))}}">
			</td>
			<td class="editable">
				<input type="text" name="work_end[]" class="text-table time-entry" value="{{date('H:i a', strtotime($day['work_end']))}}">
			</td>
			<td class="editable">
				<input type="text" name="break_start[]" class="text-table time-entry" value="{{date('H:i a', strtotime($day['break_start']))}}">
			</td>
			<td class="editable">
				<input type="text" name="break_end[]" class="text-table time-entry" value="{{date('H:i a', strtotime($day['break_end']))}}">
			</td>
			<td class="text-center">
				<input type="checkbox" name="flexi_{{$key}}" value="1" {{$day['flexi'] == 1 ? 'checked="checked"' : ''}}>
			</td>
			<td class="text-center">
				<input type="checkbox" name="rest_day_{{$key}}" class="restday-check" value="1" {{$day['rest_day'] == 1 ? 'checked="checked"' : ''}}>
			</td>
			<td class="text-center">
				<input type="checkbox" name="extra_day_{{$key}}" class="extraday-check" value="1" {{$day['extra_day'] == 1 ? 'checked="checked"' : ''}}>
			</td>
			
		</tr>
		@endforeach
	</tbody>
</table>
<div class="form-group">
	<div class="col-md-12">
		<button class="btn btn-custom-primary pull-right submit-schedule-btn" type="submit">Save</button>
	</div>
</div>
<script type="text/javascript" src="/assets/external/jquery.timeentry.package-2.0.1/jquery.plugin.min.js"></script>
<script type="text/javascript" src="/assets/external/jquery.timeentry.package-2.0.1/jquery.timeentry.min.js"></script>
<script type="text/javascript">

	$(".restday-check").unbind("change");
	$(".restday-check").bind("change", function () {
		var parent = $(this).parents('tr').find('.extraday-check');
		if($(this).is(":checked"))
		{
			parent.prop("checked", false);
		}
	});

	$(".extraday-check").unbind("change");
	$(".extraday-check").bind("change", function () {
		var parent = $(this).parents('tr').find('.restday-check');
		if($(this).is(":checked"))
		{
			parent.prop("checked", false);
		}

	});

	$(".time-entry").timeEntry('destroy');
	$(".time-entry").timeEntry({ampmPrefix: ' ', defaultTime: new Date(0, 0, 0, 0, 0, 0)});
</script>