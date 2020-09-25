<style type="text/css">
	.border
	{
		border: 1px solid #000000;
	}
	.padding-5
	{
		padding:5px;
	}
	.padding-3
	{
		padding:3px;
	}
	.text-center
	{
		text-align: center;
	}
	.text-left
	{
		text-align: left;
	}
	.text-right
	{
		text-align: right;
	}
</style>
<div class="main-container">
	@foreach($_record as $brk)
	<div class="div-payslip">
	<table cellpadding="5" cellspacing="0" class="border padding-5" width="{{$payslip->payslip_width}}%">
		@if($payslip->include_comany_logo == 1)
		<tr>
			<td colspan="2" class="{{$logo_position}} border padding-3">
				@if($logo)
				<img src="{{$brk['company_logo']}}" style="width:50px;height:50px;object-fit: contain"><br>
				@endif
				<b>{{$brk['company_name']}}</b><br>
				<small>{{$brk['company_address']}}</small>
			</td>
		</tr>
		@endif
		<tr>
			<td colspan="2"></td>
		</tr>
		<tr>
			<td colspan="2" class="border">
				<table cellpadding="2" cellspacing="0">
					<tr>
						<td>Employee Name</td>
						<td>
							{{$brk['display_name']}}
						</td>
					</tr>
					@if($payslip->include_department == 1)
					<tr>
						<td>Department</td>
						<td>
							{{$brk['emp']->payroll_department_name}}
						</td>
					</tr>
					@endif
					@if($payslip->include_job_title == 1)
					<tr>
						<td>Job Title</td>
						<td>
							{{$brk['emp']->payroll_jobtitle_name}}
						</td>
					</tr>
					@endif
				</table>
			</td>
			
		</tr>
		
		<tr>
			<td valign="top" {{$payslip->include_time_summary == 0 ? 'colspan=2' : ''}}>
				<table cellspacing="0" cellpadding="2" width="100%">
					<tr>
						<td colspan="2" class="border"><b>Salary Computation</b></td>
					</tr>
					@foreach($brk['break']['computation'] as $compute)
						@foreach($compute as $value)
						<tr>
							<td class="border">
								{!!$value['name']!!}
							</td>
							<td class="text-right border">
								{!!$value['amount']!!}
							</td>
						</tr>
							@foreach($value['sub'] as $sub)
							<tr>
								<td class="indent-15 border">
									{!!$sub['name']!!}
								</td>
								<td class="text-right border">
									{!!$sub['amount']!!}
								</td>
							</tr>
							@endforeach
						@endforeach
					@endforeach
				</table>
			</td>
			@if($payslip->include_time_summary == 1)
			<td valign="top">
				<table cellspacing="0" cellpadding="2" width="100%">
					<tr>
						<td colspan="2" class="border">
							<b>Time Sheet summary</b>
						</td>
					</tr>
					@foreach($brk['break']['time'] as $time)
					<tr>
						<td class="border">
							{!!$time['name']!!}
						</td>
						<td class="text-right border">
							{!!$time['time']!!}
						</td>
					</tr>
					@endforeach
				</table>
			</td>
			@endif
		</tr>
		<tr>
			<td style="text-align:center" width="50%">
				<span>____________________</span>
				<p>Date Received</p>
			</td>
			<td style="text-align:center">
				<span>____________________</span>
				<p>Employee Signature</p>
			</td>
		</tr>
	</table>
	</div>
	@endforeach
</div>
