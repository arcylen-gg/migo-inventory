<table class="table table-condensed table-bordered table-hover">
	<thead>
		<tr>
			<th width="2%" class="text-center">
				<div class="checkbox">
					<label><input type="checkbox" class="select-all"  value="select_all"></label>
				</div>
			</th>
			<th>Employee Name</th>
			
		</tr>
	</thead>
	<tbody >
			@foreach($_employee as $employee)
			<tr>
				<td>
					<div class="checkbox">
						<label><input type="checkbox" name="selected_employee[]" class="select-all" value="{{$employee->payroll_employee_id}}"></label>
					</div>
				</td>
				<td>
					{{$employee->payroll_employee_display_name}}
				</td>
			</tr>
			@endforeach
	</tbody>
</table>

