@if(count($_employee) > 0)
    <table class="table table-bordered table-striped table-condensed">
        <thead style="text-transform: uppercase">
            <tr>
                <th class="text-center"><input type="checkbox" name=""></th>
                <th class="text-center">NO.</th>
                <th class="text-center">Biometric No.</th>
                <th class="text-center">Employee Name</th>
                <th class="text-center">Current<br>Payroll Group</th>
                <th class="text-center">Current<br>Shift</th>
                <th class="text-center" width="120px">NET BASIC PAY</th>
                <th class="text-center" width="120px">GROSS PAY</th>
                <th class="text-center" width="120px">NET PAY<br>(TAKE HOME)</th>
                <th class="text-center" width="100px"></th>
                <th class="text-center" width="100px"></th>
            </tr>
        </thead>
        <tbody>
            @foreach($_employee as $employee)
                <!-- EMPLOYEE READY -->
                @if($employee->payroll_group_code != "" && $employee->shift_code_name != "")
                <tr>
                    <th class="text-center"><input type="checkbox" name=""></th>
                    <td class="text-center">{!! $employee->payroll_employee_number == "" ? "<span style='color: red;'>00</span>" : $employee->payroll_employee_number !!}</td>
                    <td class="text-center">{!! $employee->payroll_employee_biometric_number == "" ? "<span style='color: red;'>00</span>" : $employee->payroll_employee_biometric_number !!}</td>
                    <!-- EMPLOYEE NAME -->
                    <td class="text-center"><a href="javascript: action_load_link_to_modal('/member/payroll/employee_list/modal_employee_view/{{ $employee->payroll_employee_id }}?source_page=time_keeping','lg')">{{ $employee->payroll_employee_last_name }}, {{ $employee->payroll_employee_first_name }} {{ substr($employee->payroll_employee_middle_name, 0, -(strlen($employee->payroll_employee_middle_name))+1) }}.</a></td>
                    <!-- PAYROLL GROUP -->
                    @if($employee->payroll_group_code == "")
                        <td class="text-center">Unset</td>
                    @else
                        <td class="text-center"><a href="javascript: action_load_link_to_modal('/member/payroll/payroll_group/modal_edit_payroll_group/{{ $employee->payroll_group_id }}','lg')">{{ ($employee->payroll_group_code == "" ? "" : $employee->payroll_group_code) }}</a></td>
                    @endif
                    <!-- SHIFT -->
                    @if($employee->shift_code_name == "")
                        <td class="text-center">Unset</td>
                    @else
                        <td class="text-center"><a href="javascript: {!! $employee->shift_code_link !!}">{{ $employee->shift_code_name }}</a></td>
                    @endif
                    @if($employee->net_pay != "")
                        <td class="text-center">{{ payroll_currency($employee->net_basic_pay) }}</td>
                        <td class="text-center">{{ payroll_currency($employee->gross_pay) }}</td>
                        <td class="text-center">{{ payroll_currency($employee->net_pay) }}</td>
                        <td class="text-center"><a href="javascript: timesheet_employee_list.action_set_to_unapprove('{{ $company->payroll_period_company_id }}','{{ $employee->payroll_employee_id }}')">UN-READY</a></td>
                        <td class="text-center"><a href="javascript: action_load_link_to_modal('/member/payroll/company_timesheet2/income_summary/{{ $company->payroll_period_company_id }}/{{ $employee->payroll_employee_id }}', 'lg')">SUMMARY</a></td>
                    @else
                        <td class="text-center">{{ payroll_currency(0) }}</td>
                        <td class="text-center">{{ payroll_currency(0) }}</td>
                        <td class="text-center">{{ payroll_currency(0) }}</td>
                        <td class="text-center"><a href="javascript: action_load_link_to_modal('/member/payroll/company_timesheet2/{{ $company->payroll_period_company_id }}/{{ $employee->payroll_employee_id }}', 'lg')" >TIMESHEET</a></td>
                        <td class="text-center"><a href="javascript: action_load_link_to_modal('/member/payroll/company_timesheet2/income_summary/{{ $company->payroll_period_company_id }}/{{ $employee->payroll_employee_id }}', 'lg')">SUMMARY</a></td>
                     @endif
                </tr>
                @endif
            @endforeach

            @foreach($_employee as $employee)
                <!-- EMPLOYEE PENDING -->
                @if($employee->payroll_group_code == "" || $employee->shift_code_name == "")
                <tr>
                    <th class="text-center"><input type="checkbox" name=""></th>
                    <td class="text-center">{!! $employee->payroll_employee_number == "" ? "<span style='color: red;'>00</span>" : $employee->payroll_employee_number !!}</td>
                    <td class="text-center">{!! $employee->payroll_employee_biometric_number == "" ? "<span style='color: red;'>00</span>" : $employee->payroll_employee_biometric_number !!}</td>

                    <!-- EMPLOYEE NAME -->
                    <td class="text-center"><a href="javascript: action_load_link_to_modal('/member/payroll/employee_list/modal_employee_view/{{ $employee->payroll_employee_id }}?source_page=time_keeping','lg')">{{$employee->payroll_employee_first_name}} {{$employee->payroll_employee_last_name}}</a></td>
                    <!-- PAYROLL GROUP -->
                    @if($employee->payroll_group_code == "")
                        <td class="text-center">Unset</td>
                    @else
                        <td class="text-center"><a href="javascript: action_load_link_to_modal('/member/payroll/payroll_group/modal_edit_payroll_group/{{ $employee->payroll_group_id }}','lg')">{{ ($employee->payroll_group_code == "" ? "" : $employee->payroll_group_code) }}</a></td>
                    @endif
                    <!-- SHIFT -->
                    @if($employee->shift_code_name == "")
                        <td class="text-center">Unset</td>
                    @else
                        <td class="text-center"><a href="javascript: {!! $employee->shift_code_link !!}">{{ $employee->shift_code_name }}</a></td>
                    @endif
                    <td class="text-center">{{ payroll_currency(0) }}</td>
                    <td class="text-center">{{ payroll_currency(0) }}</td>
                    <td class="text-center">{{ payroll_currency(0) }}</td>
                    <td class="text-center"></td>
                    <td class="text-center"></td>
                </tr>
                @endif
            @endforeach
        </tbody>
    </table>
@else
    <div class="text-center" style="padding: 150px 0">NO DATA</div>
@endif
