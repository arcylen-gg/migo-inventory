<form class="global-submit form-horizontal" role="form" action="{link_submit_here}" method="post">
    <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">×</button>
        <h4 class="modal-title text-bold">EMPLOYEE SUMMARY</h4>
        <div>{{ $show_period_start }} to {{ $show_period_end }}</div>
    </div>
    <div class="modal-body clearfix">
        @if(count($_employee) > 0)
            <table class="table table-bordered table-striped table-condensed" style="margin-bottom: 30px">
                <thead style="text-transform: uppercase">
                    <tr>
                        <th class="text-center">NO.</th>
                        <th class="text-center">Employee Name</th>

                        <th class="text-center" width="120px">TOTAL GROSS PAY</th>
                        <th class="text-center" width="120px">TOTAL DEDUCTIONS</th>
                        <th class="text-center" width="120px">TOTAL NET PAY</th>
                    
                        <th class="text-center" width="100px"></th>
                        <th class="text-center" width="100px"></th>
                    </tr>
                </thead>
                <tbody>

                    @foreach($_employee as $employee)
                    <tr>
                        <td class="text-center">{!! $employee->payroll_employee_number == "" ? "<span style='color: red;'>00</span>" : $employee->payroll_employee_number !!}</td>
                        <td class="text-center">{{ $employee->payroll_employee_first_name }} {{ $employee->payroll_employee_last_name }}</td>
                        <td class="text-center">{{ payroll_currency($employee->gross_pay) }}</td>
                        <td class="text-center">{{ payroll_currency($employee->total_deduction_employee) }}</td>
                        <td class="text-center">{{ payroll_currency($employee->net_pay) }}</td>
                        <td class="text-center"><a href="javascript: action_load_link_to_modal('/member/payroll/company_timesheet2/income_summary/{{ $company->payroll_period_company_id }}/{{ $employee->payroll_employee_id }}', 'lg')">SUMMARY</a></td>
                        <td class="text-center"><a href="javascript: action_load_link_to_modal('/member/payroll/process_payroll/income_summary/timesheet/{{ $company->payroll_period_company_id }}/{{ $employee->payroll_employee_id }}', 'lg')">TIMESHEET</a></td>
                    </tr>
                    @endforeach
                    
                    <tr style="font-weight: bold; border-top: 2px solid #000">
                        <td class="text-center"></td>
                        <td class="text-center"></td>
                        <td class="text-center">{{ payroll_currency($total_gross) }}</td>
                        <td class="text-center">{{ payroll_currency($total_deduction_of_all_employee) }}</td>
                        <td class="text-center">{{ payroll_currency($total_net) }}</td>
                  
                        
                        <td></td>
                        <td></td>
                    </tr>
                </tbody>
            </table>
        @else
            <div class="text-center" style="padding: 150px 0">NO DATA</div>
        @endif
    </div>
    <div class="modal-footer">
        <button type="button" class="btn btn-def-white btn-custom-white" data-dismiss="modal">Close</button>
    </div>
</form>
<style type="text/css">
    #global_modal .modal-dialog
    {
        width: 85% !important;
    }
</style>