<table class="table table-bordered table-striped table-condensed">
    <thead style="text-transform: uppercase">
        <tr>
            <th class="text-center" >Employee Number</th>
            <th class="text-center" >Employee Name</th>
            <th class="text-center">Time In</th>
             <th class="text-center">Time Out</th>
            <th class="text-center">Date</th>
            <th class="text-center">Company Name</th>
        </tr>
    </thead>
    <tbody>
     	@foreach($_biometric_record as $biometric_record)
     		<tr>
     			<td class="text-center">{{$biometric_record->payroll_employee_number}}</td>
     			<td class="text-center">{{$biometric_record->payroll_employee_first_name}} {{$biometric_record->payroll_employee_last_name}}</td>
     			<td class="text-center">{{ date('h:i a', strtotime($biometric_record->payroll_time_in)) }}</td>
     			<td class="text-center">{{ date('h:i a', strtotime($biometric_record->payroll_time_out)) }}</td>
     			<td class="text-center">{{$biometric_record->payroll_time_date}}</td>
     			<td class="text-center">{{$biometric_record->payroll_company_name}}</td>
     		</tr>
     	@endforeach
    </tbody>
</table>