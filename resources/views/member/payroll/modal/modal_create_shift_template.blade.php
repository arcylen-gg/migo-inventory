<link rel="stylesheet" type="text/css" href="/assets/member/payroll/css/timesheet.css">
<link rel="stylesheet" type="text/css" href="/assets/external/jquery.timeentry.package-2.0.1/jquery.timeentry.css">
<form class="global-submit " role="form" action="/member/payroll/shift_template/modal_save_shift_template" method="post">
	<div class="modal-header">
		<button type="button" class="close" data-dismiss="modal">×</button>
		<h4 class="modal-title">Create Shift Template</h4>
		<input type="hidden" name="_token" value="{{csrf_token()}}">
	</div>
	<div class="modal-body form-horizontal">
		<div class="form-group">
			<div class="col-md-12">
				<small>Shift Code</small>
				<input type="text" name="shift_code_name" class="form-control" placeholder="Shift Code" required>
			</div>
		</div>
		<div class="form-group">
			<div class="col-md-12">
				<table class="table table-condensed table-bordered timesheet">
					<thead>
						<tr>
							<th rowspan="2" valign="center" class="text-center">Day</th>
							<th rowspan="2" valign="center" class="text-center">Working Hours</th>
							<th colspan="2" class="text-center">Work Schedule</th>
							<th class="text-center"></th>
							<th rowspan="2" class="text-center">Flexitime</th>
							<th rowspan="2" valign="center" class="text-center">Break Hours</th>
							<th rowspan="2" class="text-center">Rest Day</th>
							<th rowspan="2" class="text-center">Extra Day</th>
							
						</tr>
						<tr>
							<th class="text-center">Start</th>
							<th class="text-center">End</th>
							<th></th>
						</tr>
					</thead>
					<tbody>
						@foreach($_day as $key => $day)
							<tr class="editable main-con main-time" day="{{$day['day']}}">
								<td>
									{{ $day['day'] }}
									<input type="hidden" name="day[{{$day['day']}}]" value="{{$day['day']}}">
								</td>
								<td>
									@if($day['day'] == "Sun" || $day['day'] == "Sat")
										<input type="number" name="target_hours[{{$day['day']}}]" class="form-control text-center" step="any" value="0">
									@else
										<input type="number" name="target_hours[{{$day['day']}}]" class="form-control text-center" step="any" value="8">
									@endif
								</td>
								
								<td class="editable">
									<input type="text" placeholder="NO TIME" name="work_start[{{$day['day']}}][]" class="text-table time-entry in" >
								</td>
								<td class="editable">
									<input type="text" placeholder="NO TIME" name="work_end[{{$day['day']}}][]" class="text-table time-entry out" >
								</td>
								<td>
									<button type="button" class="btn btn-default add-sub-time"><i class="fa fa-plus"></i></button>
								</td>
								<td class="text-center">
									<input type="checkbox" name="flexitime_{{$day['day']}}" class="flexitime_check" value="1">
								</td>
								<td>
									@if($day['day'] == "Sun" || $day['day'] == "Sat")
										<input type="number" name="break_hours[{{$day['day']}}]" class="form-control text-center" step="any" value="0">
									@else
										<input type="number" name="break_hours[{{$day['day']}}]" class="form-control text-center" step="any" value="0">
									@endif
								</td>
								<td class="text-center">
									<input type="checkbox" name="rest_day_{{$day['day']}}" class="restday-check" value="1">
								</td>
								<td class="text-center">
									<input type="checkbox" name="extra_day_{{$day['day']}}" class="extraday-check" value="1">
								</td>
							</tr>

						@endforeach
					</tbody>
				</table>
			</div>
		</div>

				<div class="form-group">
			<div class="col-md-12">
			<table class="table table-bordered table-condensed">
							<thead>
								<tr>
									<th>Employee Name</th>
									<th></th>
								</tr>
							</thead>
							<tbody class="tbl-tag">

							</tbody>
				</table>
			</div>
		</div>
	</div>
	<div class="modal-footer">
		 <span><a href="#" class="btn btn-custom-primary popup" link="/member/payroll/shift_template/modal_tag_add_shift_employee">Tag Employee</a></span>
		<button type="button" class="btn btn-def-white btn-custom-white" data-dismiss="modal">Close</button>
		<button class="btn btn-primary btn-custom-primary" type="submit">Submit</button>
	</div>
</form>

<script type="text/javascript" src="/assets/member/payroll/js/shift-template.js"></script>