<!DOCTYPE html>
<html>
<input type="number" name="count" value="{{ $count=0 }}" class="hidden">
<head>
	 <style type="text/css">
            td
            {
                text-align: left;
            }
     </style>
</head>
<body>

		<tr>
			<th>LOAN NAME</th>
			<td>{{$loan_deduction->payroll_deduction_name}}</td>
		</tr>
		<tr>
			<th>DATE FILLED</th>
			<td>{{$loan_deduction->payroll_deduction_date_filed}}</td>
		</tr>
		<tr>
			<th># Number of Payments</th>
			<td>{{$loan_deduction->payroll_deduction_number_of_payments}}</td>
		</tr>
		<tr>
			<th>TERM</th>
			<td>{{$loan_deduction->payroll_deduction_terms}}</td>
		</tr>
		<tr>
			<th>BEGINNING DATE</th>
			<td>{{$loan_deduction->payroll_deduction_date_start}}</td>
		</tr>
		<tr>
			<th>MODE OF PAYMENT</th>
			<td>{{$loan_deduction->payroll_deduction_period}}</td>
		</tr>
		<tr>
			<th>EMPLOYEE NAME</th>
			<td>{{$employee_info->payroll_employee_display_name}}</td>
		</tr>
		<tr>

		</tr>
		<tr>
			
		</tr>

		<tr>
		    <th class="text-center"># OF PAYMENTS</th>
	        <th class="text-center">PAYMENT PERIOD</th>
	        <th class="text-center">BEGINNING BALANCE</th>
	        <th class="text-center">TOTAL PAYMENT</th>
	        <th class="text-center">REMAINING BALANCE </th>
        </tr>
	
		
		@foreach($_loan_data as $key => $loan_data)
        <tr>
            <td class="text-center">{{ $count+=1 }}</td>
            <td class="text-center">{{$loan_data->payroll_payment_period}}</td>
            <td class="text-center">{{$loan_data->payroll_beginning_balance}}</td>
            <td class="text-center">{{$loan_data->payroll_total_payment_amount}}</td>
            <td class="text-center">{{$loan_data->payroll_remaining_balance}}</td>
        </tr>
    	@endforeach
		
			
		

	


</body>
</html>
