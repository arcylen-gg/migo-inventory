    <h2 style="margin-bottom: 30px;margin-left: 100px;">{{$company_name}}</h2>

    <h5 style="margin-bottom: 30px;margin-left: 100px;">MONTHLY REMITTANCE RETURN<br>OF INCOME TAXED WITHHELD<br> ON COMPENSATION</h5>

    <h5 style="margin-left: 100px;>{{$month_name}}&nbsp;{{$year}}</h5>

    <h5 class="pull-right">Run date : &nbsp;&nbsp;&nbsp;{{$date_today}}</h5>


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