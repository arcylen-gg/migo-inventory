<table class="table table-bordered table-striped table-condensed">
    <thead style="text-transform: uppercase">
        <tr>
            <th class="text-center">Date Covered</th>
            <th class="text-center">Company</th>
            <th class="text-center">Period Type</th>
            <th class="text-center">Covered Month</th>
            <th class="text-center">Period Order</th>
            <th class="text-center"></th>
            <th class="text-center"></th>
        </tr>
    </thead>
    <tbody>
        @if(count($_period) > 0)
        	@foreach($_period as $period)
    		<tr>
    			<td class="text-center"><a href="javascript: action_load_link_to_modal('/member/payroll/payroll_period_list/modal_edit_period/{{$period->payroll_period_id }}', 'md')">{{ date('M d, Y',strtotime($period->payroll_period_start)).' - '.date('M d, Y',strtotime($period->payroll_period_end)) }}</a></td>
    			<td class="text-center">{{ $period->payroll_company_name }}</td>
    			<td class="text-center">{{ $period->payroll_period_category }}</td>
    			<td class="text-center">{{ $period->month_contribution }} {{ $period->year_contribution }}</td>
    			<td class="text-center">{{ code_to_word($period->period_count) }}</td>
    			<td class="text-center"><a onclick="return confirm('Are you sure you want to delete this payroll period? You will not be able to recover the timesheet if you delete a payroll period.')" href="/member/payroll/time_keeping/company_period/delete/{{ $period->payroll_period_company_id }}">Delete</a></td>
    			<td class="text-center"><a href="/member/payroll/company_timesheet2/{{$period->payroll_period_company_id}}">View Employee</a></td>
    		</tr>
    		@endforeach
        @else
            <tr>
                <td colspan="8" style="padding: 80px; text-align: center;"><button onclick="action_load_link_to_modal('/member/payroll/payroll_period_list/modal_create_payroll_period')" class="btn btn-primary">Click HERE to Create your first Payroll Period</button></td>
            </tr>
        @endif
    </tbody>
</table>