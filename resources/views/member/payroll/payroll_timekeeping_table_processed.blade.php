<table class="table table-bordered table-striped table-condensed">
    <thead style="text-transform: uppercase">
        <tr>
            <th class="text-center">Date Covered</th>
            <th class="text-center">Period</th>
            <th class="text-center">Company</th>
            <th class="text-center">Total Net Salary</th>
            <th class="text-center">Grand Total</th>
            <th class="text-center"></th>
        </tr>
    </thead>
    <tbody>
        @if(count($_period) > 0)
        	@foreach($_period as $period)
    		<tr>
    			<td class="text-center">
                    {{ date('M d, Y',strtotime($period->payroll_period_start)).' - '.date('M d, Y',strtotime($period->payroll_period_end)) }}
                </td>
                <td class="text-center">
                    {{ $period->payroll_period_category }} ({{ code_to_word($period->period_count) }} of {{ $period->month_contribution }}  {{ $period->year_contribution }})
                </td>
    			<td class="text-center">{{ $period->payroll_company_name }}</td>

                <td class="text-center">{{ payroll_currency($period->payroll_period_total_net) }}</td>
                <td class="text-center"><a href="javascript:">{{ payroll_currency($period->payroll_period_total_grand) }}</a></td>
                <td class="text-center">
                    <div class="btn-group">
                      <button type="button" class="btn btn-sm btn-custom-white dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        Action <span class="caret"></span>
                      </button>
                      <ul class="dropdown-menu dropdown-menu-custom">
                        <li><a onclick="return confirm('Are you sure you want to UNPROCESS this record ({{ $period->payroll_period_category }} - {{ code_to_word($period->period_count) }} of {{ $period->month_contribution }}  {{ $period->year_contribution }})? ')" href="/member/payroll/unprocess_payroll/{{ $period->payroll_period_company_id }}">Un-process</a> </li>
                        <li><a href="javascript:" class="popup" link="/member/payroll/process_payroll/modal_view_summary/{{ $period->payroll_period_company_id }}" size="lg">View Summary</a> </li>
                        <li><a href="/member/payroll/payroll_approved_view/generate_payslip_v2/{{ $period->payroll_period_company_id }}" target="_blank">View Payslip</a></li>
                        <li><a href="/member/payroll/process_payroll/{{ $period->payroll_period_company_id }}?step=register">Payroll Register</a></li>
                      </ul>
                    </div>
                </td>

    		</tr>
    		@endforeach
        @else
            <tr>
                <td colspan="6" style="padding: 80px; text-align: center;">No Data</td>
            </tr>
        @endif
    </tbody>
</table>