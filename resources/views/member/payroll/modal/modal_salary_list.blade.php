<form class="global-submit" role="form" action="/member/payroll/departmentlist/modal_update_department" method="POST">
	<div class="modal-header">
		<button type="button" class="close" data-dismiss="modal">&times;</button>
		<h4 class="modal-title">Salary History</h4>
		<input type="hidden" name="_token" value="{{csrf_token()}}">
		
	</div>
	<div class="modal-body ">
		<div class="modal-salary-list">
			<ul class="nav nav-tabs">
			  <li class="active"><a data-toggle="tab" href="#active-list"><i class="fa fa-star"></i>&nbsp;Active</a></li>
			  <li><a data-toggle="tab" href="#archived-list"><i class="fa fa-trash-o"></i>&nbsp;Archived</a></li>
			</ul>

			<div class="tab-content">
			  <div id="active-list" class="tab-pane fade in active">
			    <table class="table table-condensed table-bordered table-hover">
			    	<thead>
			    		<tr>
			    			<th>Monthly</th>
			    			<th>Daily</th>
			    			<th>Hourly</th>
			    			<th>COLA (Daily)</th>
			    			<th>COLA (Monthly)</th>
			    			<th>Mimimum Wage</th>
			    			<th>Taxable</th>
			    			<th>SSS</th>
			    			<th>Pagibig</th>
			    			<th>Philhealth</th>
			    			<th>Effective Date</th>
			    			<th class="text-center">Action</th>
			    		</tr>
			    	</thead>
			    	@foreach($_active as $active)
			    	<tr>
			    		<td class="text-right">
			    			{{number_format($active->payroll_employee_salary_monthly, 2)}}
			    		</td>
			    		<td class="text-right">
			    			{{number_format($active->payroll_employee_salary_daily, 2)}}
			    		</td>
			    		<td class="text-right">
			    			{{number_format($active->payroll_employee_salary_hourly, 2)}}
			    		</td>
			    		<td class="text-right">
			    			{{number_format($active->payroll_employee_salary_cola, 2)}}
			    		</td>
			    		<td class="text-right">
			    			{{number_format($active->monthly_cola, 2)}}
			    		</td>
			    		<td class="text-center">
			    			{{$active->payroll_employee_salary_minimum_wage == 1 ? 'Yes':'No'}}
			    		</td>
			    		<td class="text-right">
			    			{{number_format($active->payroll_employee_salary_taxable, 2)}}
			    		</td>
			    		<td class="text-right">
			    			{{number_format($active->payroll_employee_salary_sss, 2)}}
			    		</td>
			    		<td class="text-right">
			    			{{number_format($active->payroll_employee_salary_pagibig, 2)}}
			    		</td>
			    		<td class="text-right">
			    			{{number_format($active->payroll_employee_salary_philhealth, 2)}}
			    		</td>
			    		<td>
			    			{{date('M d, Y', strtotime($active->payroll_employee_salary_effective_date))}}
			    		</td>
			    		<td class="text-center">
			    			<div class="dropdown">
								<button class="btn btn-custom-white dropdown-toggle btn-xs" type="button" data-toggle="dropdown">Action
								<span class="caret"></span></button>
								<ul class="dropdown-menu dropdown-menu-custom">
									<li>
										<a href="#" class="popup" link="/member/payroll/employee_list/modal_edit_salary_adjustment/{{$active->payroll_employee_salary_id}}"><i class="fa fa-pencil"></i>&nbsp;Edit</a>
									</li>
									<li>
										<a href="#" class="popup" link="/member/payroll/employee_list/modal_archived_salary/1/{{$active->payroll_employee_salary_id}}" size="sm"><i class="fa fa-trash-o"></i>&nbsp;Archived</a>
									</li>
								</ul>
							</div>
			    		</td>
			    	</tr>
			    	@endforeach
			    </table>
			  </div>
			  <div id="archived-list" class="tab-pane fade">
			    <table class="table table-condensed table-bordered table-hover">
			    	<thead>
			    		<tr>
			    			<th>Monthly</th>
			    			<th>Daily</th>
			    			<th>COLA (Daily)</th>
			    			<th>COLA (Monthly)</th>
			    			<th>Mimimum Wage</th>
			    			<th>Taxable</th>
			    			<th>SSS</th>
			    			<th>Pagibig</th>
			    			<th>Philhealth</th>
			    			<th>Effective Date</th>
			    			<th class="text-center">Action</th>
			    		</tr>
			    	</thead>
			    	@foreach($_archived as $archived)
			    	<tr>
			    		<td class="text-right">
			    			{{number_format($archived->payroll_employee_salary_monthly, 2)}}
			    		</td>
			    		<td class="text-right">
			    			{{number_format($archived->payroll_employee_salary_daily, 2)}}
			    		</td>
			    		<td class="text-right">
			    			{{number_format($archived->payroll_employee_salary_cola, 2)}}
			    		</td>
			    		<td class="text-right">
			    			{{number_format($archived->monthly_cola, 2)}}
			    		</td>
			    		<td class="text-center">
			    			{{$archived->payroll_employee_salary_minimum_wage == 1 ? 'Yes':'No'}}
			    		</td>
			    		<td class="text-right">
			    			{{number_format($archived->payroll_employee_salary_taxable, 2)}}
			    		</td>
			    		<td class="text-right">
			    			{{number_format($archived->payroll_employee_salary_sss, 2)}}
			    		</td>
			    		<td class="text-right">
			    			{{number_format($archived->payroll_employee_salary_pagibig, 2)}}
			    		</td>
			    		<td class="text-right">
			    			{{number_format($archived->payroll_employee_salary_philhealth, 2)}}
			    		</td>
			    		<td>
			    			{{date('M d, Y', strtotime($archived->payroll_employee_salary_effective_date))}}
			    		</td>
			    		<td class="text-center">
			    			<div class="dropdown">
								<button class="btn btn-custom-white dropdown-toggle btn-xs" type="button" data-toggle="dropdown">Action
								<span class="caret"></span></button>
								<ul class="dropdown-menu dropdown-menu-custom">
									<li>
										<a href="#" class="popup" link="/member/payroll/employee_list/modal_edit_salary_adjustment/{{$archived->payroll_employee_salary_id}}"><i class="fa fa-pencil"></i>&nbsp;Edit</a>
									</li>
									<li>
										<a href="#" class="popup" link="/member/payroll/employee_list/modal_archived_salary/0/{{$archived->payroll_employee_salary_id}}" size="sm"><i class="fa fa-refresh"></i>&nbsp;Restore</a>
									</li>
								</ul>
							</div>
			    		</td>
			    	</tr>
			    	@endforeach
			    </table>
			  </div>
			
			</div>
		</div>
	</div>
	<div class="modal-footer">
		<button type="button" class="btn btn-custom-white" data-dismiss="modal">Close</button>
	</div>
</form>