<ul class="nav nav-tabs">
	<li class="active"><a data-toggle="tab" href="#active-emp"><i class="fa fa-star"></i>&nbsp;Active</a></li>
	<li><a data-toggle="tab" href="#archived-emp"><i class="fa fa-trash-o"></i>Archived</a></li>
</ul>
<div class="tab-content padding-top-10">
	<div id="active-emp" class="tab-pane fade in active">
		<table class="table table-bordered table-condensed">
			<thead>
				<tr>
					<th>Employee Name</th>
					<th>Leave Hours</th>
					<th width="5%"></th>
				</tr>
			</thead>
			<tbody>
				@foreach($_active as $active)
				<tr>
					<td>
						{{$active->payroll_employee_title_name.' '.$active->payroll_employee_first_name.' '.$active->payroll_employee_middle_name.' '.$active->payroll_employee_last_name.' '.$active->payroll_employee_suffix_name}}
					</td>
					<input type="hidden" name="employee_tag[]" value="'{{$active->payroll_employee_id}}'">
					<input type="hidden" name="payroll_leave_employee_id" value="{{$active->payroll_leave_employee_id}}">
					<td class="text-center edit-data zerotogray" width="25%">
						<input type="text" name="leave_hours_{{$active->payroll_employee_id}}" placeholder="00:00" class="text-center form-control break time-entry time-target time-entry-24 is-timeEntry" value="{{$active->payroll_leave_temp_hours}}">
					</td>

					<td class="text-center">
						<a href="#" class="popup" size="sm" link="/member/payroll/leave/modal_archived_leave_employee/1/{{$active->payroll_leave_employee_id}}"><i class="fa fa-times"></i></a>
					</td>
				</tr>
				@endforeach
			</tbody>
		</table>
	</div>
	<div id="archived-emp" class="tab-pane fade">
		<table class="table table-bordered table-condensed">
			<thead>
				<tr>
					<th>Employee Name</th>
					<th>Leave Hours</th>
					<th></th>

				</tr>
			</thead>
			<tbody>
				@foreach($_archived as $archived)
				<tr>
					<td>
						{{$archived->payroll_employee_title_name.' '.$archived->payroll_employee_first_name.' '.$archived->payroll_employee_middle_name.' '.$archived->payroll_employee_last_name.' '.$archived->payroll_employee_suffix_name}}
					</td>
					<td>{{$archived->payroll_leave_temp_hours}}</td>
					<td class="text-center">
						<a href="#" class="popup" size="sm" link="/member/payroll/leave/modal_archived_leave_employee/0/{{$archived->payroll_leave_employee_id}}"><i class="fa fa-refresh"></i></a>
					</td>
				</tr>
				@endforeach
			</tbody>
		</table>
	</div>
	</div>
</div>