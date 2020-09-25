<div class="modal-header">
    <button type="button" class="close" data-dismiss="modal">×</button>
    <h4 class="modal-title">
        <span class="page-title"> Payroll 13th Month Pay Report &raquo; {{ $employee->payroll_employee_first_name }} {{ $employee->payroll_employee_middle_name }} {{ $employee->payroll_employee_last_name }}</span>
    </h4>
</div>
<div class="modal-body clearfix">
    <div class="table-responsive tbl-13th-pay-table">
        <table class="table table-bordered table-striped table-condensed" >
            <thead style="text-transform: uppercase">
                <tr>
                    <th class="text-center">PERIOD</th>
                    <th class="text-center">VIEW SUMMARY</th>
                    <th class="text-center">13TH MONTH PAY BASIS</th>
                    <th class="text-center">13TH MONTH PAY COMPUTATION</th>
                </tr>
            </thead>
            <tbody>
                @foreach($_period as $period)
                <tr>
                    <td class="text-center">{{$period->month_contribution}} ( {{$period->period_count}} )</td>
                    <td class="text-center"><a href="javascript: action_load_link_to_modal('/member/payroll/company_timesheet2/income_summary/{{ $period->payroll_period_company_id }}/{{ $period->payroll_employee_id }}', 'lg')">SUMMARY</a></td>
                    <td class="text-center">{{ payroll_currency($period->payroll_13th_month_basis,2) }}</td>
                    <td class="text-center">{{ payroll_currency($period->payroll_13th_month_contribution,2) }}</td>
                </tr>
                @endforeach
                <tr>
                    <td class="text-center"><b>TOTAL 13TH MONTH PAY</b></td>
                    <td class="text-center"></td>
                    <td class="text-center"></td>
                    <td class="text-center"><b>{{ payroll_currency($grand_total_13th_month_pay) }}</b></td>
                </tr>
            </tbody>
        </table>
    </div>
</div>
<div class="modal-footer">
    <button type="button" class="btn btn-def-white btn-custom-white" data-dismiss="modal">Close</button>
</div>

