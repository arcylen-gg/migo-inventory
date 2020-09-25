<style type="text/css">
.modal-scroll
{
    height: 350px;
    overflow: auto;
}
</style>

<div class="modal-header employee-income-summary">
    <input type="hidden" class="period-id" value="{{ $period_info->payroll_period_company_id }}">
    <input type="hidden" class="payroll-period-id" value="{{ $period_info->payroll_period_id }}">
    <input type="hidden" class="x-employee-id" value="{{ $employee_id }}">
    <button type="button" class="close" data-dismiss="modal">×</button>
    <h4 class="modal-title">
        <b>Summary for {{ $employee_info->payroll_employee_first_name }} {{ $employee_info->payroll_employee_last_name }}</b>
    </h4>
    <div>FLAT RATE COMPUTATION ({{ $period_info->month_contribution }} - {{ code_to_word($period_info->period_count) }})</div>
</div>


@if($time_keeping_approved == false)
<div class="modal-header text-right">
    <button class="btn btn-def-white btn-custom-white" data-dismiss="modal">CLOSE</button>
    <button class="btn btn-def-white btn-custom-white make-adjustment">MAKE ADJUSTMENT</button>
    <button class="btn btn-primary approve-timesheet-btn">MARK READY</button>
</div>
@endif

<div class="modal-scroll">
    @if($access_salary_rate == 1)
    <div class="modal-body clearfix">
        <div class="text-center text-bold" style="font-size: 20px; color: #1682ba">SALARY COMPUTATION</div>
        <div class="col-md-12" style="text-align: left; font-weight: normal; margin-bottom: 10px; font-size: 16px;"></div>
        <div class="clearfix">
            <div class="col-md-12">
                <div class="table-responsive">

                    <table width="100%" class="table table-condensed">
                        <tbody>
                            <tr>
                                <td colspan="7"></td>
                                <td width="150px"></td>
                            </tr>
                            <!-- NET BASIC PAY -->
                            <tr class="text-right" style="border-top: 2px solid #000" style="opacity: 0.7">
                                <td colspan="7" style="font-weight: bold;">NET BASIC PAY</td>
                                <td style="font-weight: bold;">{{ payroll_currency($cutoff_breakdown->basic_pay_total) }}</td>
                            </tr>

                            <!-- GROSS BREAKDOWN  -->
                            @if($cutoff_breakdown->_gross_pay_breakdown)
                                @foreach($cutoff_breakdown->_gross_pay_breakdown as $breakdown)
                                    {!! $breakdown["tr"] !!}
                                @endforeach
                            @endif

                            <!-- GROSS PAY -->
                            <tr style="font-weight: bold;">
                                <td colspan="7" class="text-right" style="border-top: 2px solid #000">
                                    GROSS PAY
                                </td>
                                <td class="text-right" style="border-top: 2px solid #000">
                                    {{ payroll_currency($cutoff_breakdown->gross_pay_total) }}
                                </td>
                            </tr>
                            
                            <!-- TAXABLE BREAKDOWN -->
                            @foreach($cutoff_breakdown->_taxable_salary_breakdown as $breakdown)
                                {!! $breakdown["tr"] !!}
                            @endforeach
                           
                            <!-- TAXABLE SALARY -->
                            <tr style="font-weight: bold;">
                                <td colspan="7" class="text-right" style="border-top: 2px solid #000">
                                    TAXABLE SALARY
                                </td>
                                <td class="text-right" style="border-top: 2px solid #000">
                                    {{ payroll_currency($cutoff_breakdown->taxable_salary_total) }}
                                </td>
                            </tr>

                            <!-- NET PAY BREAKDOWN -->
                            @foreach($cutoff_breakdown->_net_pay_breakdown as $breakdown)
                                {!! $breakdown["tr"] !!}
                            @endforeach
                            
                            <!-- NET SALARY -->
                            <tr style="font-weight: bold;">
                                <td colspan="7" class="text-right" style="border-top: 2px solid #000">
                                    NET PAY / TAKE HOME PAY
                                </td>
                                <td class="text-right" style="border-top: 2px solid #000; color: #1682ba">
                                    {{ payroll_currency($cutoff_breakdown->net_pay_total) }}
                                </td>
                            </tr> 
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <div class="modal-body clearfix">
        <div class="text-center text-bold" style="font-size: 20px; color: #1682ba">GOVERNMENT CONTRIBUTIONS</div>
        <div class="col-md-12" style="text-align: left; font-weight: normal; margin-bottom: 10px; font-size: 16px;"></div>
        <div class="clearfix">
            <div class="col-md-12">
                <div class="table-responsive">
                    <table class="table table-condensed timesheet table-timesheet">
                        <thead style="text-transform: uppercase">
                            <tr>
                                <th class="text-left"></th>
                                <th class="text-center" width="200px">REFERENCE SALARY</th>
                                <th class="text-center" width="150px">EE SHARE</th>
                                <th class="text-center" width="150px">ER SHARE</th>
                                <th class="text-center" width="150px">EC SHARE</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td class="text-left text-bold">SSS</td>
                                <td class="text-center">{{ payroll_currency($cutoff_breakdown->sss_contribution["salary"]) }}</td>
                                <td class="text-center">{{ payroll_currency($cutoff_breakdown->sss_contribution["ee"]) }}</td>
                                <td class="text-center">{{ payroll_currency($cutoff_breakdown->sss_contribution["er"]) }}</td>
                                <td class="text-center">{{ payroll_currency($cutoff_breakdown->sss_contribution["ec"]) }}</td>
                            </tr>
                            <tr>
                                <td class="text-left text-bold">PHILHEALTH</td>
                                <td class="text-center">{{ payroll_currency($cutoff_breakdown->philhealth_contribution["salary"]) }}</td>
                                <td class="text-center">{{ payroll_currency($cutoff_breakdown->philhealth_contribution["ee"]) }}</td>
                                <td class="text-center">{{ payroll_currency($cutoff_breakdown->philhealth_contribution["er"]) }}</td>
                                <td class="text-center">-</td>
                            </tr>
                            <tr>
                                <td class="text-left text-bold">PAG-IBIG</td>
                                <td class="text-center">-</td>
                                <td class="text-center">{{ payroll_currency($cutoff_breakdown->pagibig_contribution["ee"]) }}</td>
                                <td class="text-center">{{ payroll_currency($cutoff_breakdown->pagibig_contribution["er"]) }}</td>
                                <td class="text-center">-</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    @endif
</div>


@if($time_keeping_approved == false)
<div class="modal-footer text-right">
    <button class="btn btn-def-white btn-custom-white" data-dismiss="modal">CLOSE</button>
    <button class="btn btn-def-white btn-custom-white make-adjustment">MAKE ADJUSTMENT</button>
    <button class="btn btn-primary approve-timesheet-btn">MARK READY</button>
</div>
@endif

<script type="text/javascript" src="/assets/member/payroll/js/timesheet_income_summary.js"></script>

<div class="view-debug-mode modal-footer" style="opacity: 0.5">
    <div onclick='$(".debug-view").removeClass("hidden")' style="text-align: center; cursor: pointer; color: #005fbf">DEBUG MODE (DEVELOPER ONLY) &nbsp; <i class="fa fa-caret-down"></i></div>
    <div class="debug-view hidden text-left" style="padding-top: 10px;">
        {{ dd($cutoff_breakdown) }}
    </div>
</div>