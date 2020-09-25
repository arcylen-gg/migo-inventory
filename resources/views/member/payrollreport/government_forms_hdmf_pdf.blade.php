<style type="text/css">
	body
	{
		color: #333;
	}
	.labels
	{
		color: #555;
		font-size: 9px;
		padding: 1px;
		padding-left: 5px;
	}
	.answers
	{
		color: #000;
		padding-left: 5px;
	}
	.sub-group
	{
		float: left;
	}
	.divider
	{
		border-bottom: 1px solid #aaa;
	}
	.table-main thead tr td
	{
		text-align: center;
		font-size: 9px;
		padding: 3px;
		color: #555;
		border-right: 1px solid #aaa;
		border-bottom: 1px solid #aaa;
	}
	.table-main tbody tr td
	{
		text-align: center;
		font-size: 9px;
		padding: 3px;
		color: #000;
		border-right: 1px solid #aaa;
		border-bottom: 1px solid #aaa;
	}
</style>


<div style="text-align: right; font-size: 12px;">HQP-PFF-053</div>

<div style="overflow: auto; margin-top: 15px;">
	<div style="float: left; width: 80px; text-align: left;"><img width="50px;" src="/assets/government_forms/hdmf_logo.png"></div>
	<div style="float: left; width: 320px; font-size: 22px; font-weight: bold; text-align: center;">MEMBER'S CONTRIBUTION REMITTANCE FORM (MCRF)</div>
	<div style="float: right; width: 250px;">
		<table border="1" bordercolor="green" cellspacing="-1px" width="100%">
			<tr>
				<td style="font-size: 9px; padding: 3px; background-color: #eee">Pag-IBIG EMPLOYER'S ID NUMBER</td>
			</tr>
			<tr>
				<td style="font-size: 9px; height: 28px; font-size: 16px; padding: 5px;">
					110-1125-3333-4555
				</td>
			</tr>
		</table>
	</div>
</div>

<div style="font-style: italic; font-size: 11px; margin-top: 10px;">NOTE: PLEASE READ INSTRUCTIONS AT THE BACK</div>
<div style="border: 2px solid #000;">
	<div>
		<div class="labels">EMPLOYER/BUSINESS NAME</div>
		<div class="answers">DIGIMA WEB SOLUTIONS, INC.</div>
	</div>
	<div class="divider"></div>
	<div>
		<div class="labels" style="font-size: 9px; padding: 1px; padding-left: 5px;">EMPLOYER'S BUSINESS ADDRESS</div>
		<div class="sub-group" style="width: 150px;">
			<div class="labels">Unit/Room No. Floor</div>
			<div class="answers">1424</div>
		</div>
		<div class="sub-group" style="width: 150px;">
			<div class="labels">Building</div>
			<div class="answers">CHATTEAU</div>
		</div>
		<div class="sub-group" style="width: 180px;">
			<div class="labels">Lot No. Block No. Phase No. House No.</div>
			<div class="answers">1834</div>
		</div>
		<div class="sub-group" style="width: 150px; float: right;">
			<div class="labels">Street Name</div>
			<div class="answers">1834</div>
		</div>
	</div>
	<div class="divider"></div>
	<div>
		<div class="labels" style="font-size: 9px; padding: 1px; padding-left: 5px;">EMPLOYER'S BUSINESS ADDRESS</div>
		<div class="sub-group" style="width: 120px;">
			<div class="labels">Subdivision</div>
			<div class="answers">San Roque</div>
		</div>
		<div class="sub-group" style="width: 120px;">
			<div class="labels">Barangay</div>
			<div class="answers">Poblacion</div>
		</div>
		<div class="sub-group" style="width: 120px;">
			<div class="labels">Municipality/City</div>
			<div class="answers">Pandi</div>
		</div>
		<div class="sub-group" style="width: 150px;">
			<div class="labels">Province/State/Country (<i>if abroad</i>)</div>
			<div class="answers">Bulacan</div>
		</div>
		<div class="sub-group" style="width: 150px; float: right;">
			<div class="labels">Zip Code</div>
			<div class="answers">3014</div>
		</div>
	</div>
	<div class="divider"></div>
	<div>
		<table class="table-main" cellspacing="0" cellpadding="0" width="100%">
			<thead>
				<tr>
					<td>Pag-IBIG MID<br>No/RTN</td>
					<td>ACCOUNT NO.</td>
					<td>MEMBERSHIP<br>PROGRAM</td>
					<td>Last Name</td>
					<td>First Name</td>
					<td>Name Ext.</td>
					<td>Middle Name</td>
					<td>PERIOD<br>COVERED</td>
					<td>MONTHLY<br>COMPENSATION</td>
					<td>EE<br>SHARE</td>
					<td>ER<br>SHARE</td>
					<td>TOTAL</td>
					<td style="border-right: none;">REMARKS</td>
				</tr>
			</thead>
			<tbody>
				@foreach($contri_info["_employee_contribution"] as $key => $contribution)
				<tr>
					<td>{{ $contribution->payroll_employee_pagibig }}</td>
					<td>-</td>
					<td>-</td>
					<td>{{ $contribution->payroll_employee_last_name }}</td>
					<td>{{ $contribution->payroll_employee_first_name }}</td>
					<td>{{ $contribution->payroll_employee_suffix_name }}</td>
					<td>{{ $contribution->payroll_employee_middle_name }}</td>
					<td>{{ $contribution->period_covered }}</td>
					<td>-</td>
					<td>{{ number_format($contribution->total_pagibig_ee, 2) }}</td>
					<td>{{ number_format($contribution->total_pagibig_er, 2) }}</td>
					<td>{{ number_format($contribution->total_pagibig_ee_er, 2) }}</td>
					<td style="border-right: none;">-</td>
				</tr>
				@endforeach
			</tbody>
		</table>
	</div>
</div>
