<div class="modal-header">
	<button type="button" class="close" data-dismiss="modal">×</button>
	<h4 class="modal-title">{{$emp->payroll_employee_display_name}}</h4>
</div>
<div class="modal-body form-horizontal">
	<div class="break-down-div">
		<div class="form-group">
			<div class="col-md-6">
				<table class="table table-condensed table-bordered">
					<tbody>
						<tr>
							<td colspan="2"><b>Salary Computation</b></td>
						</tr>
					</tbody>
					@foreach($_breakdown['computation'] as $breakdown)
					<tbody>
						@foreach($breakdown as $value)
						<tr>
							<td>
								{!!$value['name']!!}
							</td>
							<td class="text-right">
								{!!$value['amount']!!}
							</td>
						</tr>
							@foreach($value['sub'] as $sub)
							<tr>
								<td class="indent-15">
									{!!$sub['name']!!}
								</td>
								<td class="text-right">
									{!!$sub['amount']!!}
								</td>
							</tr>
							@endforeach
						@endforeach
					</tbody>
					@endforeach
				</table>
			</div>
			<div class="col-md-6">
				<table class="table table-condensed table-bordered">
					<tbody>
						<tr>
							<td colspan="2">
								<b>Time Sheet summary</b>
							</td>
						</tr>
					</tbody>
					<tbody>
						@foreach($_breakdown['time'] as $time)
						<tr>
							<td>
								{!!$time['name']!!}
							</td>
							<td class="text-right">
								{!!$time['time']!!}
							</td>
						</tr>
						@endforeach
					</tbody>
				</table>
				<br>
				<table class="table table-condensed table-bordered">
					<tbody>
						<tr>
							<td colspan="2">
								<b>Working day summary</b>
							</td>
						</tr>
					</tbody>
					<tbody>
						@foreach($_breakdown['day'] as $day)
						<tr>
							<td>
								{!!$day['name']!!}
							</td>
							<td class="text-right">
								{!!$day['day']!!}
							</td>
						</tr>
						@endforeach
					</tbody>
				</table>
			</div>
		</div>
	</div>
</div>
<div class="modal-footer">
	<button class="btn btn-custom-primary popup" link="/member/payroll/payroll_process/payroll_explain_computation/{{$emp->payroll_employee_id}}/{{$payroll_period_company_id}}" size="lg" type="button">Computation Details</button>
	@if($status == 'process')
	<button class="btn btn-custom-primary popup" link="/member/payroll/payroll_process/modal_create_payroll_adjustment/{{$emp->payroll_employee_id}}/{{$payroll_period_company_id}}" size="sm" type="button">Make Adjustment</button>
	<button class="btn btn-custom-primary popup" type="button" link="/member/payroll/payroll_process/modal_unused_leave/{{$emp->payroll_employee_id}}/{{$payroll_period_company_id}}" >Process unused leave</button>
	<button class="btn btn-custom-primary popup" type="button" link="/member/payroll/payroll_process/modal_13_month/{{$emp->payroll_employee_id}}/{{$payroll_period_company_id}}" size="lg">Process 13 month</button>
	@endif
	<button type="button" class="btn btn-def-white btn-custom-white" data-dismiss="modal">Close</button>
</div>
