<ul class="nav nav-tabs">
	<li class="active"><a data-toggle="tab" href="#active-employee">Active</a></li>
	<li><a data-toggle="tab" href="#zero-employee">Zero Balance</a></li>
	<li><a data-toggle="tab" href="#canceled-employee">Canceled</a></li>
</ul>
<div class="tab-content padding-top-10">
	<div id="active-employee" class="tab-pane fade in active">
		<table class="table table-condensed table-bordered">
			<thead>
				<tr>
					<th>Employee Name</th>
					<th>Balance</th>
					<th width="5%"></th>
				</tr>
			</thead>
			<tbody class="table-employee-tag">
				@foreach($emp['active'] as $active)
				<tr>
					 <td>
						{{$active['deduction']->payroll_employee_title_name.' '.$active['deduction']->payroll_employee_first_name.' '.$active['deduction']->payroll_employee_middle_name.' '.$active['deduction']->payroll_employee_last_name.' '.$active['deduction']->payroll_employee_suffix_name}}
					</td>
					<td class="text-right">
						{{number_format($active['balance'], 2)}}
					</td>
					<td class="text-center">
						<a href="#" class="popup" link="/member/payroll/deduction/deduction_employee_tag/1/{{$active['deduction']->payroll_deduction_employee_id}}" size="sm" ><i class="fa fa-times"></i></a>
					</td>
				</tr>
				@endforeach
			</tbody>
		</table>
	</div>
	<div id="zero-employee" class="tab-pane fade">
		<table class="table table-condensed table-bordered">
			<thead>
				<tr>
					<th>Employee Name</th>
					<th>Balance</th>
					<th width="5%"></th>
				</tr>
			</thead>
			<tbody>
				@foreach($emp['zero'] as $zero)
				<tr>
					<td>
						{{$zero['deduction']->payroll_employee_title_name.' '.$zero['deduction']->payroll_employee_first_name.' '.$zero['deduction']->payroll_employee_middle_name.' '.$zero['deduction']->payroll_employee_last_name.' '.$zero['deduction']->payroll_employee_suffix_name}}
					</td>
					<td class="text-right">
						{{number_format($zero['balance'], 2)}}
					</td>
					<td class="text-center">
						<!-- <a href="#" payroll_deduction_employee_id><i class="fa fa-times"></i></a> -->
					</td>
				</tr>
				@endforeach
			</tbody>
		</table>
	</div>
	<div id="canceled-employee" class="tab-pane fade">
		<table class="table table-condensed table-bordered">
			<thead>
				<tr>
					<th>Employee Name</th>
					<th>Balance</th>
					<th width="5%"></th>
				</tr>
			</thead>
			<tbody>
				@foreach($emp['cancel'] as $cancel)
				<tr>
					<td>
						{{$cancel['deduction']->payroll_employee_title_name.' '.$cancel['deduction']->payroll_employee_first_name.' '.$cancel['deduction']->payroll_employee_middle_name.' '.$cancel['deduction']->payroll_employee_last_name.' '.$cancel['deduction']->payroll_employee_suffix_name}}
					</td>
					<td class="text-right">
						{{number_format($cancel['balance'], 2)}}
					</td>
					<td class="text-center">
						<a href="#" class="popup" link="/member/payroll/deduction/deduction_employee_tag/0/{{$cancel['deduction']->payroll_deduction_employee_id}}" size="sm"><i class="fa fa-refresh"></i></a>
					</td>
				</tr>
				@endforeach
			</tbody>
		</table>
	</div>
</div>