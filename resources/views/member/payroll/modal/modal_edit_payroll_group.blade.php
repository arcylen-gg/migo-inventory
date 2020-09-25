<link rel="stylesheet" type="text/css" href="/assets/member/payroll/css/timesheet.css">
<link rel="stylesheet" type="text/css" href="/assets/external/jquery.timeentry.package-2.0.1/jquery.timeentry.css">
<form class="global-submit form-horizontal" role="form" action="/member/payroll/payroll_group/modal_update_payroll_group" method="post">
	<div class="modal-header">
		<button type="button" class="close" data-dismiss="modal">×</button>
		<h4 class="modal-title">Edit Payroll</h4>
		<input type="hidden" name="_token" value="{{csrf_token()}}">
		<input type="hidden" name="payroll_group_id" id="group_id" value="{{$group->payroll_group_id}}">
	</div>
	<div class="modal-body clearfix">
		
		
		<div class="form-group">
			<div class="col-md-12">

				<ul class="nav nav-tabs nav-tabs-custom">
					<li class="active"><a data-toggle="tab" href="#basic">Basic</a></li>
					<li><a data-toggle="tab" href="#deduction-basis">Deduction Basis</a></li>
					<li><a data-toggle="tab" href="#over-time-rates">Over Time Rates</a></li>
					<li><a data-toggle="tab" href="#shifting">Shifting</a></li>
					<li><a data-toggle="tab" href="#employeetagging">Employee</a></li>
				</ul>

				<div class="tab-content tab-content-custom tab-pane-div margin-bottom-0">
					<div id="basic" class="tab-pane fade in active form-horizontal">
						<div class="form-group">
							<div class="col-md-4">
								<small>Payroll Code</small>
								<input type="text" name="payroll_group_code" class="form-control" value="{{$group->payroll_group_code}}">
							</div>
						</div>
						<div class="form-group">
							<div class="col-md-4">
								<small>Salary Computation</small>
								<div class="panel panel-default">
									<div class="panel-body">
										<div class="radio">
											<label><input type="radio" name="payroll_group_salary_computation" value="Flat Rate" {{$group->payroll_group_salary_computation == 'Flat Rate' ? 'checked':''}}>Flat Rate</label>
										</div>
										<div class="radio">
											<label><input type="radio" name="payroll_group_salary_computation" value="Daily Rate" {{$group->payroll_group_salary_computation == 'Daily Rate' ? 'checked':''}}>Daily Rate</label>
										</div>
										<div class="radio">
											<label><input type="radio" name="payroll_group_salary_computation" value="Monthly Rate" {{$group->payroll_group_salary_computation == 'Monthly Rate' ? 'checked':''}}>Monthly Rate</label>
										</div>
										<div class="radio">
											<label><input type="radio" name="payroll_group_salary_computation" value="Hourly Rate" {{$group->payroll_group_salary_computation == 'Hourly Rate' ? 'checked':''}}>Hourly Rate</label>
										</div>
									</div>
								</div>
							</div>
							<div class="col-md-4">
								<small>Payroll Period</small>
								<div class="panel panel-default">
									<div class="panel-body">
										@foreach($_period as $period)
										<div class="radio">
											<label><input type="radio" name="payroll_group_period" value="{{$period->payroll_tax_period}}" {{$group->payroll_group_period == $period->payroll_tax_period ? 'checked':''}}>{{$period->payroll_tax_period}}</label>
										</div>
										@endforeach
										
									</div>
								</div>
							</div>
							<div class="col-md-4">
								<small>COLA Basis</small>
								<div class="panel-default panel">
									<div class="panel-body">
										<label>Compute COLA Basis</label>
										<div class="radio">
											<label><input type="radio" name="payroll_group_cola_basis" value="Monthly Fixed" {{$group->payroll_group_cola_basis == 'Monthly Fixed' ? 'checked':''}}>Monthly Fixed</label>
										</div>
										<div class="radio">
											<label><input type="radio" name="payroll_group_cola_basis" value="Pro Rated Monthly" {{$group->payroll_group_cola_basis == 'Pro Rated Monthly' ? 'checked':''}}>Pro Rated Monthly</label>
										</div>
										<div class="radio">
											<label><input type="radio" name="payroll_group_cola_basis" value="Daily Fixed" {{$group->payroll_group_cola_basis == 'Daily Fixed' ? 'checked':''}}>Daily Fixed</label>
										</div>
										<div class="radio">
											<label><input type="radio" name="payroll_group_cola_basis" value="Daily Computation" {{$group->payroll_group_cola_basis == 'Daily Computation' ? 'checked':''}}>Daily Computation</label>
										</div>
									</div>
								</div>
							</div>
						</div>
						<div class="form-group">
							<div class="col-md-4">
								<small>Display Rate</small>
								<div class="panel panel-default">
									<div class="panel-body">
										<div class="checkbox">
											<label><input type="checkbox" name="display_monthly_rate" {{$group->display_monthly_rate == 1 ? 'checked':''}} value="1">Display Monthly rate</label>
										</div>
										<div class="checkbox">
											<label><input type="checkbox" name="display_daily_rate" {{$group->display_daily_rate == 1 ? 'checked':''}}  value="1">Display Daily rate</label>
										</div>
									</div>
								</div>
							</div>
							<div class="col-md-4">
								<small>13 Month Basis</small>
								<div class="panel-default panel">
									<div class="panel-body">
										<label>Compute 13 Month</label>
										@if($payroll_13th_month_basis != null)
										<div class="radio">
											<label><input type="radio" name="payroll_group_13month_basis" value="Gross Basic Pay" {{$payroll_13th_month_basis->payroll_group_13month_basis == 'Gross Basic Pay' ? 'checked':''}}>Gross Basic Pay</label>
										</div>
										<div class="radio">
											<label><input type="radio" name="payroll_group_13month_basis" value="Net Basic Pay" {{$payroll_13th_month_basis->payroll_group_13month_basis == 'Net Basic Pay' ? 'checked':''}}>Net Basic Pay</label>
										</div>
										<label>additions (+)</label>
										<div class="checkbox">
											<label><input type="checkbox" value="payroll_group_13th_month_addition_allowance" name="payroll_group_13th_month_addition[]" {{$payroll_13th_month_basis->payroll_group_13th_month_addition_allowance == 1 ? 'checked' : ''}}>Allowance</label>
										</div>
										<div class="checkbox">
											<label><input type="checkbox" value="payroll_group_13th_month_addition_de_minimis_benefit" name="payroll_group_13th_month_addition[]" {{$payroll_13th_month_basis->payroll_group_13th_month_addition_de_minimis_benefit == 1 ? 'checked' : ''}}>De Minimis Benefit</label>
										</div>
										<div class="checkbox">
											<label><input type="checkbox" value="payroll_group_13th_month_addition_special_holiday" name="payroll_group_13th_month_addition[]" {{$payroll_13th_month_basis->payroll_group_13th_month_addition_special_holiday == 1 ? 'checked' : ''}}>Special Holiday</label>
										</div>
										<div class="checkbox">
											<label><input type="checkbox" value="payroll_group_13th_month_addition_regular_holiday" name="payroll_group_13th_month_addition[]" {{$payroll_13th_month_basis->payroll_group_13th_month_addition_regular_holiday == 1 ? 'checked' : ''}}>Regular Holiday</label>
										</div>
										<div class="checkbox">
											<label><input type="checkbox" value="payroll_group_13th_month_addition_cola" name="payroll_group_13th_month_addition[]" {{$payroll_13th_month_basis->payroll_group_13th_month_addition_cola == 1 ? 'checked' : ''}}>COLA</label>
										</div>
										<div class="checkbox">
											<label><input type="checkbox" value="payroll_group_13th_month_addition_late" name="payroll_group_13th_month_addition[]" {{$payroll_13th_month_basis->payroll_group_13th_month_addition_late == 1 ? 'checked' : ''}}>Late</label>
										</div>
										<div class="checkbox">
											<label><input type="checkbox" value="payroll_group_13th_month_addition_undertime" name="payroll_group_13th_month_addition[]" {{$payroll_13th_month_basis->payroll_group_13th_month_addition_undertime == 1 ? 'checked' : ''}}>Undertime</label>
										</div>
										<div class="checkbox">
											<label><input type="checkbox" value="payroll_group_13th_month_addition_absent" name="payroll_group_13th_month_addition[]" {{$payroll_13th_month_basis->payroll_group_13th_month_addition_absent == 1 ? 'checked' : ''}}>Absent</label>
										</div>
										@else
										<div class="radio">
											<label><input type="radio" name="payroll_group_13month_basis" value="Gross Basic Pay">Gross Basic Pay</label>
										</div>
										<div class="radio">
											<label><input type="radio" name="payroll_group_13month_basis" value="Net Basic Pay" checked>Net Basic Pay</label>
										</div>
										<label>additions (+)</label>
										<div class="checkbox">
											<label><input type="checkbox" value="payroll_group_13th_month_addition_allowance" name="payroll_group_13th_month_addition[]">Allowance</label>
										</div>
										<div class="checkbox">
											<label><input type="checkbox" value="payroll_group_13th_month_addition_de_minimis_benefit" name="payroll_group_13th_month_addition[]">De Minimis Benefit</label>
										</div>
										<div class="checkbox">
											<label><input type="checkbox" value="payroll_group_13th_month_addition_special_holiday" name="payroll_group_13th_month_addition[]">Special Holiday</label>
										</div>
										<div class="checkbox">
											<label><input type="checkbox" value="payroll_group_13th_month_addition_regular_holiday" name="payroll_group_13th_month_addition[]">Regular Holiday</label>
										</div>
										<div class="checkbox">
											<label><input type="checkbox" value="payroll_group_13th_month_addition_cola" name="payroll_group_13th_month_addition[]">COLA</label>
										</div>
										<div class="checkbox">
											<label><input type="checkbox" value="payroll_group_13th_month_addition_late" name="payroll_group_13th_month_addition[]">Late</label>
										</div>
										<div class="checkbox">
											<label><input type="checkbox" value="payroll_group_13th_month_addition_undertime" name="payroll_group_13th_month_addition[]">Undertime</label>
										</div>
										<div class="checkbox">
											<label><input type="checkbox" value="payroll_group_13th_month_addition_absent" name="payroll_group_13th_month_addition[]">Absent</label>
										</div>
										@endif
									</div>
								</div>
							</div>
						</div>
					</div>
					<div id="deduction-basis" class="tab-pane fade form-horizontal">
						<div class="form-group">
							<div class="col-md-8">
								<!-- <div class="panel panel-default">
									<div class="panel-body">
										<label>Periods of Basic Deduction</label>
										<div class="checkbox">
											<label><input type="checkbox" name="payroll_group_deduct_before_absences" value="1" {{$group->payroll_group_deduct_before_absences == '1' ? 'checked':''}}>Deduct before absences and lates is deducted.</label>
										</div>
									</div>
								</div> -->
								<div class="panel panel-default">
									<div class="panel-body">
										<div class="form-horizontal">
											<div class="form-group">
												<div class="col-md-12">
													<div class="checkbox">
														<label><input type="checkbox" name="payroll_group_before_tax" value="1" {{$group->payroll_group_before_tax == 1 ? 'checked="checked"':''}}>Deduct Tax after SSS, PhilHealth, & Pagibig</label>
													</div>
												</div>
											</div>
										</div>
										
									</div>
								</div>
								<div class="panel panel-default">
									<div class="panel-body form-horizontal">
										<div class="form-group">
											<label class="col-md-12">Withholding Tax</label>
										</div>
										<div class="form-group">
											<div class="col-md-4">
												<div class="radio">
													<label><input type="radio" name="payroll_group_tax" value="Every Period" {{$group->payroll_group_tax == 'Every Period' ? 'checked':''}}>Every Period</label>
												</div>
											</div>
											<div class="col-md-4">
												<div class="radio">
													<label><input type="radio" name="payroll_group_tax" value="Last Period" {{$group->payroll_group_tax == 'Last Period' ? 'checked':''}}>Last Period</label>
												</div>
											</div>
											<div class="col-md-4">
												<div class="radio">
													<label><input type="radio" name="payroll_group_tax" value="Not Deducted" {{$group->payroll_group_tax == 'Not Deducted' ? 'checked':''}}>Not Deducted</label>
												</div>
											</div>
										</div>
									</div>
								</div>
								<div class="form-group">
									<div class="col-md-4">
										<div class="panel panel-default">
											<div class="panel-body">
												<label>SSS</label>
												<div class="radio">
													<label><input type="radio" name="payroll_group_sss" value="1st Period" {{$group->payroll_group_sss == '1st Period' ? 'checked' : ''}}>1st Period</label>
												</div>
												<!--<div class="radio">-->
												<!--	<label><input type="radio" name="payroll_group_sss" value="2nd Period" {{$group->payroll_group_sss == '2nd Period' ? 'checked' : ''}}>2nd Period</label>-->
												<!--</div>-->
												
												<div class="radio">
													<label><input type="radio" name="payroll_group_sss" value="Last Period" {{$group->payroll_group_sss == 'Last Period' ? 'checked' : ''}}>Last Period</label>
												</div>
												<div class="radio">
													<label><input type="radio" name="payroll_group_sss" value="Every Period" {{$group->payroll_group_sss == 'Every Period' ? 'checked' : ''}}>Every Period</label>
												</div>
												<div class="radio">
													<label><input type="radio" name="payroll_group_sss" value="Not Deducted" {{$group->payroll_group_sss == 'Not Deducted' ? 'checked' : ''}}>Not Deducted</label>
												</div>
											</div>
										</div>
									</div>
									<div class="col-md-4">
										<div class="panel panel-default">
											<div class="panel-body">
												<label>PhilHealth</label>
												<div class="radio">
													<label><input type="radio" name="payroll_group_philhealth" value="1st Period" {{$group->payroll_group_philhealth == '1st Period' ? 'checked' : ''}}>1st Period</label>
												</div>
												<!--<div class="radio">-->
												<!--	<label><input type="radio" name="payroll_group_philhealth" value="2nd Period" {{$group->payroll_group_philhealth == '2nd Period' ? 'checked' : ''}}>2nd Period</label>-->
												<!--</div>-->
												
												<div class="radio">
													<label><input type="radio" name="payroll_group_philhealth" value="Last Period" {{$group->payroll_group_philhealth == 'Last Period' ? 'checked' : ''}}>Last Period</label>
												</div>
												<div class="radio">
													<label><input type="radio" name="payroll_group_philhealth" value="Every Period" {{$group->payroll_group_philhealth == 'Every Period' ? 'checked' : ''}}>Every Period</label>
												</div>
												<div class="radio">
													<label><input type="radio" name="payroll_group_philhealth" value="Not Deducted" {{$group->payroll_group_philhealth == 'Not Deducted' ? 'checked' : ''}}>Not Deducted</label>
												</div>
											</div>
										</div>
									</div>
									<div class="col-md-4">
										<div class="panel panel-default">
											<div class="panel-body">
												<label>PAGIBIG</label>
												<div class="radio">
													<label><input type="radio" name="payroll_group_pagibig" value="1st Period" {{$group->payroll_group_pagibig == '1st Period' ? 'checked' : ''}}>1st Period</label>
												</div>
												<!--<div class="radio">-->
												<!--	<label><input type="radio" name="payroll_group_pagibig" value="2nd Period" {{$group->payroll_group_pagibig == '2nd Period' ? 'checked' : ''}}>2nd Period</label>-->
												<!--</div>-->
												
												<div class="radio">
													<label><input type="radio" name="payroll_group_pagibig" value="Last Period" {{$group->payroll_group_pagibig == 'Last Period' ? 'checked' : ''}}>Last Period</label>
												</div>
												<div class="radio">
													<label><input type="radio" name="payroll_group_pagibig" value="Every Period" {{$group->payroll_group_pagibig == 'Every Period' ? 'checked' : ''}}>Every Period</label>
												</div>
												<div class="radio">
													<label><input type="radio" name="payroll_group_pagibig" value="Not Deducted" {{$group->payroll_group_pagibig == 'Not Deducted' ? 'checked' : ''}}>Not Deducted</label>
												</div>
											</div>
										</div>
									</div>
								</div>
								<div class="panel panel-default">
									<div class="panel-body form-horizontal">
										<div class="form-group">
											<label class="col-md-12">Contribution References</label>
										</div>
										<div class="form-group">
											<div class="col-md-12">
												<table class="table table-condensed table-bordered">
													<tr>
														<td>
															Tax Contibution
														</td>
														<td width="50%">
															<select class="form-control" name="tax_reference">
																<option value="declared" {{$group->tax_reference == 'declared' ? 'selected="selected"':''}}>Declared Tax Salary</option>
																<option value="gross_basic" {{$group->tax_reference == 'gross_basic' ? 'selected="selected"':''}}>Computed Taxable Salary</option>
																<option value="net_basic" {{$group->tax_reference == 'net_basic' ? 'selected="selected"':''}}>Net Basic</option>
															</select>
														</td>
													</tr>
													<tr>
														<td>
															SSS Contibution
														</td>
														<td width="50%">
															<select class="form-control" name="sss_reference">
																<option value="declared" {{$group->sss_reference == 'declared' ? 'selected="selected"':''}}>Declared SSS Salary</option>
																<option value="gross_basic" {{$group->sss_reference == 'gross_basic' ? 'selected="selected"':''}}>Gross Pay</option>
																<option value="net_basic" {{$group->sss_reference == 'net_basic' ? 'selected="selected"':''}}>Net Basic</option>
															</select>
														</td>
													</tr>
													<tr>
														<td>
															PhilHealth Contibution
														</td>
														<td width="50%">
															<select class="form-control" name="philhealth_reference">
																<option value="declared" {{$group->philhealth_reference == 'declared' ? 'selected="selected"':''}}>Declared PhilHealth Salary</option>
																<option value="gross_basic" {{$group->philhealth_reference == 'gross_basic' ? 'selected="selected"':''}}>Gross Pay</option>
																<option value="net_basic" {{$group->philhealth_reference == 'net_basic' ? 'selected="selected"':''}}>Net Basic</option>
															</select>
														</td>
													</tr>
													<tr class="hidden">
														<td>
															PAGIBIG Contibution
														</td>
														<td width="50%">
															<select class="form-control" name="pagibig_reference">
																<option value="declared" {{$group->pagibig_reference == 'declared' ? 'selected="selected"':''}}>Declared PAGIBIG Contribution</option>
																<option value="gross_basic" {{$group->pagibig_reference == 'gross_basic' ? 'selected="selected"':''}}>Gross Pay</option>
																<option value="net_basic" {{$group->pagibig_reference == 'net_basic' ? 'selected="selected"':''}}>Net Basic</option>
															</select>
														</td>
													</tr>
												</table>
											</div>
										</div>
									</div>
								</div>
								<div class="panel panel-default">
									<div class="panel-body form-horizontal">
										<div class="form-group">
											<label class="col-md-12">Late Deduction</label>
										</div>
										<div class="form-group">
											<div class="col-md-4">
												<div class="radio">
													<label><input class="late-category-change" type="radio" value="Base on Salary" name="payroll_late_category" {{$group->payroll_late_category == 'Base on Salary' ? 'checked':''}}>Base on Salary</label>
												</div>
											</div>
											<div class="col-md-4">
												<div class="radio">
													<label><input class="late-category-change" type="radio" value="Custom" name="payroll_late_category" {{$group->payroll_late_category == 'Custom' ? 'checked':''}}>Custom</label>
												</div>
											</div>
											<div class="col-md-4">
												<div class="radio">
													<label><input class="late-category-change" type="radio" name="payroll_late_category" value="Not Deducted" {{$group->payroll_late_category == 'Not Deducted' ? 'checked':''}}>Not Deducted</label>
												</div>
											</div>
										</div>
										<div class="form-group {{$group->payroll_late_category == 'Custom' ? '':'display-none'}} late-custom-form">
											<div class="col-md-6">
												<small>Late parameter</small>
												<div class="input-group">
													<input type="number" name="payroll_late_interval" class="form-control late-param-change late-param-number text-right" value="{{$group->payroll_late_interval}}">
													<span class="input-group-btn" style="width: 100px">
														<select class="form-control late-param-change late-param-select" name="payroll_late_parameter">
															<option value="Minute" {{$group->payroll_late_parameter == 'Minute' ? '' : ''}}>Minute</option>
															<option value="Hour" {{$group->payroll_late_parameter == 'Hour' ? '' : ''}}>Hour</option>
														</select>
													</span>
												</div>

											</div>
											<div class="col-md-6">
												<small>Deduction Late Percentage</small>
												<input type="number" name="payroll_late_deduction" class="form-control text-right" value="{{$group->payroll_late_deduction}}" step="any">
											</div>
										</div>
									</div>
								</div>
								<div class="panel panel-default">
									<div class="panel-body form-horizontal">
										<div class="form-group">
											<label class="col-md-12">Under Time Deduction</label>
										</div>
										<div class="form-group">
											<div class="col-md-4">
												<div class="radio">
													<label><input class="under-time-category-change" type="radio" value="Base on Salary" name="payroll_under_time_category" {{$group->payroll_under_time_category == 'Base on Salary' ? 'checked="checked"':''}}>Base on Salary</label>
												</div>
											</div>
											<div class="col-md-4">
												<div class="radio">
													<label><input class="under-time-category-change" type="radio" value="Custom" name="payroll_under_time_category" {{$group->payroll_under_time_category == 'Custom' ? 'checked="checked"':''}}>Custom</label>
												</div>
											</div>
											<div class="col-md-4">
												<div class="radio">
													<label><input class="under-time-category-change" type="radio" name="payroll_under_time_category" value="Not Deducted" {{$group->payroll_under_time_category == 'Not Deducted' ? 'checked="checked"':''}}>Not Deducted</label>
												</div>
											</div>
										</div>
										<div class="form-group {{$group->payroll_under_time_category == 'Custom' ? '':'display-none'}} under-time-custom-form">
											<div class="col-md-6">
												<small>Under Time parameter</small>
												<div class="input-group">
													<input type="number" name="payroll_under_time_interval" class="form-control late-param-change late-param-number text-right" value="{{$group->payroll_under_time_interval}}" step="any">
													<span class="input-group-btn" style="width: 100px">
														<select class="form-control late-param-change late-param-select" name="payroll_under_time_parameter">
															<option value="Minute" {{$group->payroll_under_time_parameter == 'Minute' ? 'selected="selected"':''}}>Minute</option>
															<option value="Hour" {{$group->payroll_under_time_parameter == 'Hour' ? 'selected="selected"':''}}>Hour</option>
														</select>
													</span>
												</div>

											</div>
											<div class="col-md-6">
												<small>Deduction Undertime Percentage</small>
												<input type="number" name="payroll_under_time_deduction" step="any" value="{{$group->payroll_under_time_deduction}}" class="form-control text-right" step="any">
											</div>
										</div>
									</div>
								</div>
								<div class="panel panel-default">
									<div class="panel-body form-horizontal">
										<div class="form-group">
											<label class="col-md-12">Break Deduction</label>
										</div>
										<div class="form-group">
											<div class="col-md-4">
												<div class="radio">
													<label><input class="late-category-change" type="radio" value="Base on Salary" name="payroll_break_category" {{$group->payroll_break_category == 'Base on Salary' ? 'checked="checked"' : ''}}>Base on Salary</label>
												</div>
											</div>
											
											<div class="col-md-4">
												<div class="radio">
													<label><input class="late-category-change" type="radio" name="payroll_break_category" value="Not Deducted" {{$group->payroll_break_category == 'Not Deducted' ? 'checked="checked"' : ''}}>Not Deducted</label>
												</div>
											</div>
										</div>
										
									</div>
								</div>
							</div>
							<div class="col-md-4">
								<div class="panel panel-default">
									<div class="panel-body form-horizontal">
										<label>Agency Fee Deduction</label>
										<div class="radio">
											<label><input type="radio" name="payroll_group_agency" value="1st Period" {{$group->payroll_group_agency == '1st Period' ? 'checked':''}}>1st Period</label>
										</div>
										<div class="radio">
											<label><input type="radio" name="payroll_group_agency" value="2nd Period" {{$group->payroll_group_agency == '2nd Period' ? 'checked':''}}>2nd Period</label>
										</div>
										<div class="radio">
											<label><input type="radio" name="payroll_group_agency" value="Last Period" {{$group->payroll_group_agency == 'Last Period' ? 'checked':''}}>Last Period</label>
										</div>
										<div class="radio">
											<label><input type="radio" name="payroll_group_agency" value="Every Period" {{$group->payroll_group_agency == 'Every Period' ? 'checked':''}}>Every Period</label>
										</div>
										<div class="radio">
											<label><input type="radio" name="payroll_group_agency" value="Not Deducted" {{$group->payroll_group_agency == 'Not Deducted' ? 'checked':''}}>Not Deducted</label>
										</div>
										
									</div>
								</div>
								<div class="panel panel-default">
									<div class="panel-body form-horizontal">
										<div class="form-group">
											<div class="col-md-12">
												<small>Agency Fee</small>
												<input type="number" name="payroll_group_agency_fee" class="form-control text-right" step="any" {{$group->payroll_group_agency_fee}}>
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>
						
					</div>
					<div id="over-time-rates" class="tab-pane fade">
						<table class="table table-condensed table-bordered">
							<tr>
								<td colspan="2">Overtime Rate Factors</td>
								<td colspan="2"></td>
								<td colspan="3" class="text-center">Rest Day</td>
							</tr>
							<tr>
								<td colspan="2"></td>
								<td class="text-center">Over Time</td>
								<td class="text-center">Night Diff</td>
								<td></td>
								<td class="text-center">Over Time</td>
								<td class="text-center">Night Diff</td>
							</tr>
							@foreach($_overtime_rate as $rate)
							<tr>
								<td width="14.28571428571429%">
									<input type="text" name="payroll_overtime_name[]" value="{{$rate->payroll_overtime_name}}" class="width-100 border-none" readonly>
								</td>
								<td width="14.28571428571429%">
									<input type="number" name="payroll_overtime_regular[]" value="{{$rate->payroll_overtime_regular}}" step="any" class="width-100 border-none text-right" {{$rate->payroll_overtime_name == 'Regular' ? 'readonly':''}}>
								</td>
								<td width="14.28571428571429%">
									<input type="number" step="any" name="payroll_overtime_overtime[]" class="width-100 border-none text-right" value="{{$rate->payroll_overtime_overtime}}">
								</td>
								<td width="14.28571428571429%">
									<input type="number" step="any" name="payroll_overtime_nigth_diff[]" class="width-100 border-none text-right" value="{{$rate->payroll_overtime_nigth_diff}}">
								</td>
								<td width="14.28571428571429%">
									<input type="number" step="any" name="payroll_overtime_rest_day[]" class="width-100 border-none text-right" value="{{$rate->payroll_overtime_rest_day}}">
								</td>
								<td width="14.28571428571429%">
									<input type="number" step="any" name="payroll_overtime_rest_overtime[]" class="width-100 border-none text-right" value="{{$rate->payroll_overtime_rest_overtime}}">
								</td>
								<td width="14.28571428571429%">
									<input type="number" step="any" name="payroll_overtime_rest_night[]" class="width-100 border-none text-right" value="{{$rate->payroll_overtime_rest_night}}">
								</td>
							</tr>
							@endforeach
						</table>
					</div>
					<div id="shifting" class="tab-pane fade">
						<div class="form-horizontal">
						
							<div class="form-group">
								<div class="col-md-6">
									<small>Working Days(per month)</small>
									<input type="number" name="payroll_group_working_day_month" class="form-control text-center" required value="{{$group->payroll_group_working_day_month}}" step="any">
								</div>
								<!--<div class="col-md-6">-->
								<!--	<small>Grace Time Period</small>-->
									
								<!--</div>-->
								<input type="hidden" name="payroll_group_grace_time" class="form-control text-center" value="{{$group->payroll_group_grace_time}}">
							</div>
							<div class="form-group">
								<div class="col-md-6">
									<small>Grace Time (LATE)</small>
									<div class="panel panel-default">
										<div class="panel-body">
											<div class="form-group">
												<div class="col-md-6">
													<small>Grace Time</small>
													<input type="text" class="form-control text-center time-entry" value="{{$group->late_grace_time == '00:00:00' ? '' : date('H:i a', strtotime($group->late_grace_time))}}" placeholder="00:00" name="late_grace_time">
												</div>
												<div class="col-md-6">
													<small>Grace Time Rule</small>
													<select class="form-control" name="grace_time_rule_late">
														<option value="per_shift" {{$group->grace_time_rule_late == 'per_shift' ? 'selected="selected"':''}}>Per Shift</option>
														<option value="accumulative" {{$group->grace_time_rule_late == 'accumulative' ? 'selected="selected"':''}}>Accumulative</option>
														<option value="first" {{$group->grace_time_rule_late == 'first' ? 'selected="selected"':''}}>First Shift Only</option>
														<option value="last" {{$group->grace_time_rule_late == 'last' ? 'selected="selected"':''}}>Last Shift Only</option>
														<option value="first-deducted" {{$group->grace_time_rule_late == 'first-deducted' ? 'selected="selected"':''}}>First Shift then Deduct Time</option>
													</select>
												</div>
											</div>
										</div>
									</div>
									
									
								</div>
								<div class="col-md-6">
									<small>Grace Time (OVERTIME)</small>
									<div class="panel panel-default">
										<div class="panel-body">
											<div class="form-group">
												<div class="col-md-6">
													<small>Grace Time</small>
													<input type="text" class="form-control  text-center time-entry" placeholder="00:00" name="overtime_grace_time" value="{{$group->overtime_grace_time == '00:00:00' ? '' : date('H:i', strtotime($group->overtime_grace_time))}}">
												</div>
												<div class="col-md-6">
													<small>Grace Time Rule</small>
													<select class="form-control" name="grace_time_rule_overtime">
														<option value="per_shift" {{$group->grace_time_rule_late == 'per_shift' ? 'selected="selected"':''}}>Per Shift</option>
														<option value="accumulative" {{$group->grace_time_rule_late == 'accumulative' ? 'selected="selected"':''}}>Accumulative</option>
													</select>
												</div>
											</div>
										</div>
									</div>
								</div>
							</div>
							<!--<div class="form-group">-->
							<!--	<div class="col-md-12">-->
							<!--		<small>Default Shift Template</small>-->
							<!--		<select class="form-control shift_code_id" name="shift_code_id">-->
							<!--			@foreach($_shift_code as $key => $value)-->
							<!--				<option value="{{ $value->shift_code_id }}" {{ ($group->shift_code_id == $value->shift_code_id) ? 'selected' : '' }}>{{ $value->shift_code_name }}</option>-->
							<!--			@endforeach-->
							<!--		</select>-->
							<!--	</div>-->
							<!--</div>-->
						</div>
					</div>
					<div id="employeetagging" class="tab-pane fade">
					 			
								<div class="form-group">
									<div class="col-md-12">
										<span><a href="#" class="btn btn-custom-primary pull-right popup" link="/member/payroll/payroll_group/modal_payroll_tag_edit">Tag Employee</a></span>
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
												@foreach($employee as $emp)
													<tr>
													<td>{{$emp->payroll_employee_display_name}}<input type="hidden" name="employee_tag[]" value="{{$emp->payroll_employee_id}}"></td>
													<td class="text-center">
														<a href="#" class="popup" size="sm" link="/member/payroll/leave/v2/modal_leave_action/{{$emp->payroll_employee_id}}/remove_payroll_tag/{{$group->payroll_group_id}}"><i class="fa fa-times"></i></a>
													</td>
													</tr>
												@endforeach
											</tbody>
										</table>
									</div>
								</div>
					</div>
				</div>
			</div>
		</div>
	</div>
	<div class="modal-footer">
		<button type="button" class="btn btn-def-white btn-custom-white" data-dismiss="modal">Close</button>
		<button class="btn btn-primary btn-custom-primary" type="Submit">Update</button>
	</div>
</form>
<script type="text/javascript" src="/assets/external/jquery.timeentry.package-2.0.1/jquery.plugin.min.js"></script>
<script type="text/javascript" src="/assets/external/jquery.timeentry.package-2.0.1/jquery.timeentry.min.js"></script>
<script type="text/javascript" src="/assets/member/js/payroll/payroll_group.js"></script>
<script type="text/javascript">
	$(".restday-check").unbind("change");
	$(".restday-check").bind("change", function () {
		var parent = $(this).parents('tr').find('.extraday-check');
		if($(this).is(":checked"))
		{
			parent.prop("checked", false);
		}
		// else
		// {
			// 	parent.prop("checked", true);
		// }
	});
	$(".extraday-check").unbind("change");
	$(".extraday-check").bind("change", function () {
		var parent = $(this).parents('tr').find('.restday-check');
		if($(this).is(":checked"))
		{
			parent.prop("checked", false);
		}
		// else
		// {
			// 	parent.prop("checked", true);
		// }
	});
	$(".check-flexi").unbind("change");
	$(".check-flexi").bind("change", function(){
		if($(this).is(":checked"))
		{
			$(".select-target-hours").removeAttr("disabled");
		}
		else
		{
			$(".select-target-hours").attr("disabled", true);
			$(".select-target-hours").val("Daily");
		}
	});
	$(".time-entry").timeEntry('destroy');
	$(".time-entry").timeEntry({show24Hours: true, defaultTime: new Date(0, 0, 0, 0, 0, 0)});
	late_categoy_change_event();
	late_param_change();
	under_time_category_change_event();
	function late_categoy_change_event()
	{
		$(".late-category-change").unbind("change");
		$(".late-category-change").bind("change", function()
		{
			if($(this).val() == "Custom")
			{
				if($(".late-custom-form").hasClass('display-none'))
				{
					$(".late-custom-form").removeClass("display-none");
				}
			}
			else
			{
				if(!$(".late-custom-form").hasClass('display-none'))
				{
					$(".late-custom-form").addClass("display-none");
				}
			}
		});

		$(".payroll_group_is_flexi_break").unbind("change");
		$(".payroll_group_is_flexi_break").bind("change", function(){

			if($(this).is(":checked"))
			{
				$(".tbl-schedule-break").addClass("display-none");
				$(".flexi-break-container").removeClass("display-none");
			}
			else
			{
				$(".tbl-schedule-break").removeClass("display-none");
				$(".flexi-break-container").addClass("display-none");
			}
		});

		$(".late-param-change").unbind("change");
		$(".late-param-change").bind("change", function()
		{
			late_param_change();
		});

	}

	function under_time_category_change_event()
	{
		$(".under-time-category-change").unbind("change");
		$(".under-time-category-change").bind("change", function()
		{
			if($(this).val() == "Custom")
			{
				if($(".under-time-custom-form").hasClass('display-none'))
				{
					$(".under-time-custom-form").removeClass("display-none");
				}
			}
			else
			{
				if(!$(".under-time-custom-form").hasClass('display-none'))
				{
					$(".under-time-custom-form").addClass("display-none");
				}
			}
		});
	}


	function late_param_change()
	{
		var number = $('.late-param-number').val();
		var select = $('.late-param-select').val();
		if(number == null || number == '')
		{
			number = 0
		}
		$('.late-label-param').html(number + ' ' + select);
	}
</script>