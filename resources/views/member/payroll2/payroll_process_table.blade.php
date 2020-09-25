@if(count($_employee) > 0)
    <table class="table table-bordered table-striped table-condensed">
        <thead style="text-transform: uppercase">
            <tr>
                <th class="text-center">NO.</th>
                <th class="text-center">Employee Name</th>

                <th class="text-center" width="120px">TOTAL ER</th>
                <th class="text-center" width="120px">TOTAL EE</th>
                <th class="text-center" width="120px">TOTAL EC</th>
                <th class="text-center" width="120px">TOTAL TAX</th>
                <th class="text-center" width="120px">NET PAY<br>(TAKE HOME)</th>
                <th class="text-center" width="150px"></th>
            </tr>
        </thead>
        <tbody>
            @foreach($_employee as $employee)
            <tr>
                <td class="text-center">{!! $employee->payroll_employee_number == "" ? "<span style='color: red;'>00</span>" : $employee->payroll_employee_number !!}</td>
                <td class="text-center">{{ $employee->payroll_employee_first_name }} {{ $employee->payroll_employee_last_name }}</td>

                <td class="text-center">{{ payroll_currency($employee->total_er) }}</td>
                <td class="text-center">{{ payroll_currency($employee->total_ee) }}</td>
                <td class="text-center">{{ payroll_currency($employee->total_ec) }}</td>
                <td class="text-center">{{ payroll_currency($employee->tax_ee) }}</td>
                <td class="text-center">{{ payroll_currency($employee->net_pay) }}</td>
                <td class="text-center"><a href="javascript: action_load_link_to_modal('/member/payroll/company_timesheet2/income_summary/{{ $company->payroll_period_company_id }}/{{ $employee->payroll_employee_id }}', 'lg')">SUMMARY</a></td>
            </tr>
            @endforeach
            <tr style="font-weight: bold; border-top: 2px solid #000">
                <td class="text-center"></td>
                <td class="text-center"></td>
                <td class="text-center">{{ payroll_currency($total_er) }}</td>
                <td class="text-center">{{ payroll_currency($total_ee) }}</td>
                <td class="text-center">{{ payroll_currency($total_ec) }}</td>
                <td class="text-center">{{ payroll_currency($total_tax) }}</td>
                <td class="text-center">{{ payroll_currency($total_net) }}</td>
                <td class="text-center" style="color: #1682ba; font-size: 16px;">{{ payroll_currency($total_grand) }}</td>
            </tr>
        </tbody>
    </table>
@else
    <div class="text-center" style="padding: 150px 0">NO DATA</div>
@endif