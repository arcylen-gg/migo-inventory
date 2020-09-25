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
					<th></th>
				</tr>
			</thead>
			<tbody>
				@foreach($_active as $active)
				<tr>
					<td>
						{{$active->payroll_employee_title_name.' '.$active->payroll_employee_first_name.' '.$active->payroll_employee_middle_name.' '.$active->payroll_employee_last_name.' '.$active->payroll_employee_suffix_name}}
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
					<th></th>
				</tr>
			</thead>
			<tbody>
				@foreach($_archived as $archived)
				<tr>
					<td>
						{{$archived->payroll_employee_title_name.' '.$archived->payroll_employee_first_name.' '.$archived->payroll_employee_middle_name.' '.$archived->payroll_employee_last_name.' '.$archived->payroll_employee_suffix_name}}
					</td>
					<td class="text-center">
						<a href="#" class="popup" size="sm" link="/member/payroll/leave/modal_archived_leave_employee/0/{{$archived->payroll_leave_employee_id}}"><i class="fa fa-refresh"></i></a>
					</td>
				</tr>
				@endforeach
			</tbody>
		</table>
	</div>
	</div>/
</div>