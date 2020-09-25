 <input type="hidden" class="_token" value="{{ csrf_token() }}" />
    
    <div class="modal-header">
		<button type="button" class="close" data-dismiss="modal">×</button>
		<h4 class="modal-title">BIR REPORT FOR THE MONTH OF {{ strtoupper($month_name) }} {{ $year }} </h4>
	</div>
    <div class="panel panel-default panel-block panel-title-block">
        <div class="panel-body form-horizontal">
            <div class="form-group">
            </div>
        </div>
    </div>
    <div class="text-center" id="spinningLoader" style="display:none;">
                <img src="/assets/images/loader.gif">
    </div>
    <div class="load-filter-data">
	<div class="modal-body clearfix">
                <h4 style="text-align: center;"><strong>{{$company_name}}</strong></h4>
                                    <br>
        @if(isset($bir_report))
        <div class="filter-result table-responsive ">
            <table class="table table-bordered table-condensed">
                <thead>
                    <tr>
                    	<th class="text-center"></th>
                        <th class="text-center">1ST PERIOD</th>
                        <th class="text-center">LAST PERIOD</th>
                        <th class="text-center">TOTAL FOR 1601C</th>
                    </tr>
                </thead>
                <tbody>
                	<tr>
                        <td>Total Amount of Compensation</td>
                        <td class="text-center"><strong>{{number_format($bir_report[0]['first_period_total_compensation'],2)}}</strong></td>
                        <td class="text-center"><strong>{{number_format($bir_report[0]['last_period_total_compensation'],2)}}</strong></td>
                        <td class="text-center"><strong>{{number_format($bir_report[0]['total_for_1601c'],2)}}</strong></td>
                	</tr>
                	<tr>
                		<td colspan="4">&nbsp;&nbsp; Less: Non-Taxable Compensation</td>
                	</tr>
                    <tr>
                        <td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; <strong>16A</strong> Statutory Minimum Wage (MWEs)</td>
                        <td class="text-center">{{number_format($bir_report[0]['first_period_total_minimum'],2)}}</td>
                        <td class="text-center">{{number_format($bir_report[0]['last_period_total_minimum'],2)}}</td>
                        <td class="text-center">{{number_format($bir_report[0]['total_for_1601c_minimum'],2)}}</td>
                    </tr>
                    <tr>
                        <td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; <strong>16B</strong> Holiday Pay, Overtime Pay, Night Shift</td>
                        <td class="text-center">{{number_format($bir_report[0]['first_minimum_16_b'],2)}}</td>
                        <td class="text-center">{{number_format($bir_report[0]['last_minimum_16_b'],2)}}</td>
                        <td class="text-center">{{number_format($bir_report[0]['total_minimum_1601c'],2)}}</td>
                    </tr>
                    <tr>
                        <td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; <strong>16C</strong> Other Non-Taxable Compensation</td>
                        <td class="text-center">{{number_format($bir_report[0]['non_tax_compensation_first'],2)}}</td>
                        <td class="text-center">{{number_format($bir_report[0]['non_tax_compensation_last'],2)}}</td>
                        <td class="text-center">{{number_format($bir_report[0]['total_non_tax_compensation'],2)}}</td>
                    </tr>
                    <tr>
                        <td colspan="4">&nbsp;</td>
                    </tr>
                    <tr>
                        <td class="text-center">Total Taxable Comensation</td>
                        <td class="text-center"><strong>{{number_format($bir_report[0]['total_taxable_first'],2)}}</strong></td>
                        <td class="text-center"><strong>{{number_format($bir_report[0]['total_taxable_last'],2)}}</strong></td>
                        <td class="text-center"><strong>{{number_format($bir_report[0]['total_taxable'],2)}}</strong></td>
                    </tr>
                      <tr>
                        <td colspan="4">&nbsp;</td>
                    </tr>
                    <tr>
                        <td class="text-center">Tax Required to be Withheld</td>
                        <td class="text-center"><strong>{{number_format($bir_report[0]['tax_with_held_first'],2)}}</strong></td>
                        <td class="text-center"><strong>{{number_format($bir_report[0]['tax_with_held_last'],2)}}</strong></td>
                        <td class="text-center"><strong>{{number_format($bir_report[0]['tax_total'],2)}}</strong></td>
                    </tr>
                </tbody>
            </table>
        </div>
        @else
            <h3 style="text-align: center;">NO RECORDS</h3>
        @endif
    </div>
    <div class="modal-footer">
        <button type="button" class="btn btn-def-white btn-custom-white" data-dismiss="modal">Close</button>
        &nbsp;<a href="/member/payroll/reports/bir_export_excel/{{$year}}/{{$month}}/{{$company}}"><button type="button" class="btn btn-success pull-right"><i class="fa fa-file-excel-o" ></i> &nbsp;EXPORT TO EXCEL</button></a>
    </div>

{{--     <div class="modal-footer">
        <button type="button" class="btn btn-def-white btn-custom-white" data-dismiss="modal">Close</button>
        <a href="/member/payroll/reports/government_forms_hdmf_export_excel/{{$month}}/0/{{ $year }}"><button type="button" class="btn btn-success pull-right"><i class="fa fa-file-excel-o" ></i> &nbsp;EXPORT TO EXCEL</button></a>
        <a role="form" target="_blank" href="/member/payroll/reports/government_forms_hdmf_iframe/{{ $month }}/0/{{ $year }}"><button class="btn btn-primary btn-custom-primary" type="submit">View PDF Form</button></a>&nbsp;&nbsp;
    </div> --}}
    </div>