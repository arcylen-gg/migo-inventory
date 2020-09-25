<html>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<style type="text/css">
	*
	{
		font-family: Arial Narrow;
	}
	.strong
	{
		font-weight: 700;
	}
	td
	{
		padding: 2.5px;
	}
	</style>
	<tr>
		<td>&nbsp;</td>
	</tr>
	<tr>
		<td style="font-weight: 700;">{{ $payroll_period->payroll_company_name }}</td>
	</tr>
	<tr>
		<td>&nbsp;</td>
	</tr>
	<tr>
		<td>{{ $payroll_period->payroll_company_nature_of_business }}</td>
	</tr>
	<tr>
		<td>{{ $payroll_period->bank_name }}</td>
	</tr>
	<tr>
		<td>{{ $payroll_period->payroll_company_address }}</td>
	</tr>
	<tr>
		<td>&nbsp;</td>
	</tr>
	<tr>
		<td>Subject:</td>
		<td style="font-weight: 700; text-decoration: underline;">Payroll Deduction</td>
		<td style="text-decoration: underline; text-align: center;">{{ date("F d, Y", strtotime($payroll_period->payroll_release_date)) }}</td>
	</tr>
	<tr>
		<td>&nbsp;</td>
	</tr>
	<tr>
		<td>Dear Sir/Madam:</td>
	</tr>
	<tr>
		<td colspan="4">Please debit my savings account no. {{ $payroll_period->payroll_company_account_no }} the following personnels.</td>
	</tr>
	<tr style="text-align: center; font-weight: 700; text-align: center; font-size: 12px;">
		<td style="border: 1px solid #000;">Name</td>
		<td style="border: 1px solid #000;">Account No.</td>
		<td style="border: 1px solid #000;">Amount</td>
	</tr>
	<?php $total = 0; ?>
	
	@foreach($_employee as $employee)
	<?php $total += number_format((float)$employee->net_pay, 2, '.', ''); ?>
	<tr style="font-size: 12px;">	
		<td style="border: 1px solid #000;">{{ $employee->payroll_employee_first_name }} {{ $employee->payroll_employee_middle_name }} {{ $employee->payroll_employee_last_name }}</td>
		<td style="border: 1px solid #000; text-align: right;">{{ $employee->payroll_employee_atm_number }}</td>
		<td style="border: 1px solid #000; text-align: right;">{{ number_format($employee->net_pay, 2) }}</td>
	</tr>
	@endforeach
	<tr>
		<td>&nbsp;</td>
	</tr>
	<tr style="text-align: right;">
		<td>&nbsp;</td>
		<td style="font-weight: 700;">Total:</td>
		<td>{{ number_format($total, 2) }}</td>
	</tr>
	<tr>
		<td>&nbsp;</td>
	</tr>
	<tr>
		<td>APPROVED BY:</td>
	</tr>
	<tr>
		<td>&nbsp;</td>
	</tr>
	<tr>
		<td>&nbsp;</td>
		<td style="border-bottom: 1px solid #000;"></td>
	</tr>
	<!-- <tr>
		<td>&nbsp;</td>
		<td style="text-align: center;">ROMAN SEE JR.</td>
	</tr> -->
</html>