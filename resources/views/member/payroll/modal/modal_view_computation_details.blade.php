<form class="global-submit " role="form" action="{link_submit_here}" method="post">
	<div class="modal-header">
		<button type="button" class="close" data-dismiss="modal">×</button>
		<h4 class="modal-title">{{$emp->payroll_employee_display_name}}</h4>
	</div>
	<div class="modal-body form-horizontal">
		<div class="break-down-div">
			<div class="form-group">
				<div class="col-md-12 over-flow">
					<table class="table table-condensed table-bordered column-fit">
						<thead>
							<tr>
								<th>Date</th>
								<th>REG</th>
								<th>BRK</th>
								<th>LATE</th>
								<th>UT</th>
								<th>AB</th>
								<th>E-OT</th>
								<th>REG-OT</th>
								<th>RD</th>
								<th>ED</th>
								<th>ND</th>
								<th>SH</th>
								<th>RH</th>
								<th>COLA</th>
								<th>LV</th>
							</tr>
						</thead>
						<tbody>
							@foreach($_details as $details)
							<tr>
								<td>
									{{date('(d) D', strtotime($details['date']))}}
								</td>
								<td class="text-right {{$details['regular_salary'] == 0 ? 'color-gray':''}}">
									{{number_format($details['regular_salary'], 2)}}
								</td>
								<td class="text-right {{$details['break'] == 0 ? 'color-gray':''}}">
									{{number_format($details['break'], 2)}}
								</td> 
								<td class="text-right {{$details['late_deduction'] == 0 ? 'color-gray':''}}">
									{{number_format($details['late_deduction'], 2)}}
								</td> 
								<td class="text-right {{$details['under_time'] == 0 ? 'color-gray':''}}">
									{{number_format($details['under_time'], 2)}}
								</td>
								<td class="text-right {{$details['absent_deduction'] == 0 ? 'color-gray':''}}">
									{{number_format($details['absent_deduction'], 2)}}
								</td>
								<td class="text-right {{$details['total_early_ot'] == 0 ? 'color-gray':''}}">
									{{number_format($details['total_early_ot'], 2)}}
								</td>
								<td class="text-right {{$details['total_reg_ot'] == 0 ? 'color-gray':''}}">
									{{number_format($details['total_reg_ot'], 2)}}
								</td>
								<td class="text-right {{$details['total_rest_days'] == 0 ? 'color-gray':''}}">
									{{number_format($details['total_rest_days'], 2)}}
								</td>
								<td class="text-right {{$details['extra_salary'] == 0 ? 'color-gray':''}}">
									{{number_format($details['extra_salary'], 2)}}
								</td>
								<td class="text-right {{$details['total_night_differential'] == 0 ? 'color-gray':''}}">
									{{number_format($details['total_night_differential'], 2)}}
								</td>
								<td class="text-right {{$details['sh_salary'] == 0 ? 'color-gray':''}}">
									{{number_format($details['sh_salary'], 2)}}
								</td>
								<td class="text-right {{$details['rh_salary'] == 0 ? 'color-gray':''}}">
									{{number_format($details['rh_salary'], 2)}}
								</td>
								<td class="text-right {{$details['cola'] == 0 ? 'color-gray':''}}">
									{{number_format($details['cola'], 2)}}
								</td>
								<td class="text-right {{$details['leave'] == 0 ? 'color-gray':''}}">
									{{number_format($details['leave'], 2)}}
								</td>
							</tr>
							@endforeach
							<tr>
								<td>
									<b>Total</b>
								</td>
								<td class="text-right">
									<b>{{number_format($total_regular_salary, 2)}}</b>
								</td>
								<td class="text-right">
									<b>{{number_format($total_break, 2)}}</b>
								</td>
								<td class="text-right">
									<b>{{number_format($total_late_deduction, 2)}}</b>
								</td>
								<td class="text-right">
									<b>{{number_format($total_under_time, 2)}}</b>
								</td>
								<td class="text-right">
									<b>{{number_format($total_absent_deduction, 2)}}</b>
								</td>
								<td class="text-right">
									<b>{{number_format($total_early_ot, 2)}}</b>
								</td>
								<td class="text-right">
									<b>{{number_format($total_reg_ot, 2)}}</b>
								</td>
								<td class="text-right">
									<b>{{number_format($total_rest_days, 2)}}</b>
								</td>
								<td class="text-right">
									<b>{{number_format($total_extra_salary, 2)}}</b>
								</td>
								<td class="text-right">
									<b>{{number_format($total_night_differential, 2)}}</b>
								</td>
								<td class="text-right">
									<b>{{number_format($total_sh_salary, 2)}}</b>
								</td>
								<td class="text-right">
									<b>{{number_format($total_rh_salary, 2)}}</b>
								</td>
								<td class="text-right">
									<b>{{number_format($total_cola, 2)}}</b>
								</td>
								<td class="text-right">
									<b>{{number_format($total_leave, 2)}}</b>
								</td>
							</tr>
						</tbody>
					</table>
				</div>
				
			</div>
		</div>
	</div>
	<div class="modal-footer">
		<button type="button" class="btn btn-def-white btn-custom-white" data-dismiss="modal">Close</button>
	</div>
</form>