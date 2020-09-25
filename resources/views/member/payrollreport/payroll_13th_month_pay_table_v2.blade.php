<table class="table table-bordered table-striped table-condensed">
        <thead style="text-transform: uppercase">
            <tr>
                <th class="text-center" width="50px"><input type="checkbox" name=""></th>
                <th class="text-center" width="100px">NO.</th>
                <th class="text-center">Employee Name</th>
                <th class="text-center" width="150px">13th Month Pay</th>
                <th class="text-center"></th>
            </tr>
        </thead>
        <tbody>
           	@foreach($_employee as $employee)
           		<tr>
           			<td class="text-center"><input type="checkbox" name=""></td>
           			<td class="text-center">{{ $employee->payroll_employee_number }}</td>
           			<td class="text-center">{{$employee->payroll_employee_first_name}} {{$employee->payroll_employee_last_name}}</td>
           			<td class="text-center">{{ payroll_currency($employee->grand_total_13th_month_pay) }}</td>
           			<td class="text-center"><a href="javascript: action_load_link_to_modal('/member/payroll/reports/employee_13_month_pay_report/{{$employee->payroll_employee_id}}?basis={{serialize($basis)}}', 'lg')" >View Details</a></td>
           		</tr>
           	@endforeach
        </tbody>
</table>