<form class="global-submit form-horizontal" role="form" action="{link_submit_here}" method="post">
    <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">×</button>
        <h4 class="modal-title text-bold">PAYROLL SUMMARY</h4>
        <div>{{ $show_period_start }} to {{ $show_period_end }}</div>
    </div>
    <div class="modal-body clearfix">
        <table class="table table-bordered table-condensed">
            <tbody>
                <!-- EMPLOYEE -->
                @foreach($_deduction as $label => $deduction) 
                <tr>
                    <td width="30%">{{ ucwords($label) }}</td>
                    <td class="text-right" width="20%">{{ payroll_currency($deduction) }}</td>
                    <td width="30%"></td>
                    <td class="text-right"  width="20%"></td>
                </tr> 
                @endforeach
                <tr class="text-bold">
                    <td width="30%"></td>
                    <td class="text-right" width="20%"></td>
                    <td width="30%">TOTAL NET BASIC</td>
                    <td class="text-right"  width="20%">{{ payroll_currency($total_basic) }}</td>
                </tr>
                <tr>
                    <td width="30%">Total SSS (EE)</td>
                    <td class="text-right" width="20%">{{ payroll_currency($total_sss_ee) }}</td>
                    <td width="30%"></td>
                    <td class="text-right"  width="20%"></td>
                </tr>
                <tr>
                    <td width="30%">Total PHILHEALTH (EE)</td>
                    <td class="text-right" width="20%">{{ payroll_currency($total_philhealth_ee) }}</td>
                    <td width="30%"></td>
                    <td class="text-right"  width="20%"></td>
                </tr>
                <tr>
                    <td width="30%">Total HDMF (EE)</td>
                    <td class="text-right" width="20%">{{ payroll_currency($total_pagibig_ee) }}</td>
                    <td width="30%"></td>
                    <td class="text-right"  width="20%"></td>
                </tr> 
                @foreach($_other_deduction as $label => $deduction) 
                <tr>
                    <td width="30%">{{ $label }}</td>
                    <td class="text-right" width="20%">{{ payroll_currency($deduction) }}</td>
                    <td width="30%"></td>
                    <td class="text-right"  width="20%"></td>
                </tr> 
                @endforeach
                @foreach($_addition as $label => $addition) 
                <tr>
                    <td width="30%"></td>
                    <td class="text-right"  width="20%"></td>
                    <td width="30%">{{ $label }}</td>
                    <td class="text-right" width="20%">{{ payroll_currency($addition) }}</td>
                </tr> 
                @endforeach
                <tr>
                    <td width="30%"></td>
                    <td class="text-right" width="20%"></td>
                    <td width="30%"></td>
                    <td class="text-right"  width="20%"></td>
                </tr>
                <tr style="font-weight: bold;">
                    <td width="30%">TOTAL DEDUCTIONS</td>
                    <td class="text-right" width="20%">{{ payroll_currency($total_deduction) }}</td>
                    <td width="30%">TOTAL GROSS</td>
                    <td class="text-right" width="20%">{{ payroll_currency($total_gross) }}</td>
                </tr> 
                <tr style="font-weight: bold;">
                    <td width="30%"></td>
                    <td class="text-right" width="20%"></td>
                    <td width="30%">TOTAL NET</td>
                    <td style="color: #00a9ff" class="text-right" width="20%">{{ payroll_currency($total_net) }}</td>
                </tr> 
                <tr>
                    <td colspan="4"></td>
                </tr>
                <tr>
                    <td colspan="4" class="text-center text-bold">EMPLOYER CONTRIBUTIONS</td>
                </tr>
                <tr>
                    <td width="30%">Total SSS (ER)</td>
                    <td class="text-right" width="20%">{{ payroll_currency($total_sss_er) }}</td>
                    <td width="30%">Total Philhealth (ER)</td>
                    <td class="text-right"  width="20%">{{ payroll_currency($total_philhealth_er) }}</td>
                </tr>
                <tr>
                    <td width="30%">Total SSS (EC)</td>
                    <td class="text-right"  width="20%">{{ payroll_currency($total_sss_ec) }}</td>
                    <td width="30%">Total HDMF (ER)</td>
                    <td class="text-right"  width="20%">{{ payroll_currency($total_pagibig_er) }}</td>
                </tr>

            </tbody>
        </table>
    </div>
    <div class="modal-footer">
        <button type="button" class="btn btn-def-white btn-custom-white" data-dismiss="modal">Close</button>
    </div>
</form>