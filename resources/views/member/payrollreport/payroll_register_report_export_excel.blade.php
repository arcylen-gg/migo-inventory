<!DOCTYPE html>
<html>
<head>
  <meta name="viewport" content="width=device-width, initial-scale=1">

<style type="text/css">

table, td {
    border: 6px solid #333333;
    width:30px;
    height:20px;
    text-align:center;
    background-color:#fff;
    vertical-align:middle;
}

table {
    border-collapse: collapse;
}
body{
  background-color:#ededed;
}
.padding
{
    padding:300px 50px 100px 50px;
}

#title
{
    border: 2px solid #E5E5E5;
}

</style>
</head>
<body>
<div class="padding">




<table>
  
  <tr>
      <th id="title">{{$company->payroll_company_name}}</th>
  </tr>
  <tr>
      <th>Payroll Register</th>
  </tr>
  <tr>
      <th>{{$show_period_start}}</th>
      <th>{{$show_period_end}}</th>
  </tr>
  <tr>
      <th>-</th>
  </tr>

  <tr>
    <td rowspan="2">NAME</td>
    <td rowspan="2">GROSS BASIC PAY</td>
    <td rowspan="2">ABSENT</td>
    <td rowspan="2">LATE</td>
    <td rowspan="2">UNDERTIME</td>
    <td rowspan="2">BASIC PAY</td>
    <td rowspan="2">RENDERED DAYS</td>
    <td rowspan="2">COLA</td>
    <td rowspan="2">OVER TIME PAY</td>
    <td rowspan="2">NIGHT DIFFERENTIAL PAY</td>
    <td rowspan="2">REGULAR HOLIDAY PAY</td>
    <td rowspan="2">SPECIAL HOLIDAY PAY</td>
    <td rowspan="2">RESTDAY PAY</td>
    <td rowspan="2">LEAVE PAY</td>
    <td colspan="8">ALLOWANCES</td>
    <td rowspan="2">GROSS PAY</td>
    <td colspan="4">DEDUCTION</td>

    <td colspan="2">SSS</td>
    <td colspan="2">PAG-IBIG</td>
    <td rowspan="1">PHIL-HEALTH</td>
    <td rowspan="2">WITH HOLDING TAX</td>
    <td rowspan="2">TOTAL DEDUCTIONS</td>       
    <td rowspan="2">NETHOME PAY</td>
    <td colspan="4">GOVERNMENT EMPLOYER SHARE</td>
   </tr>
  <tr>
    <td ></td>
    <td ></td>
    <td ></td>
    <td ></td>  
    <td ></td>
    <td ></td>
    <td ></td>
    <td ></td>
    <td ></td>
    <td ></td>
    <td ></td>
    <td ></td>
    <td ></td>
    <td></td>
    <td >ALLOWANCE</td>
    <td >BONUS</td>
    <td >COMMISSION</td>
    <td >INCENTIVES</td>
    <td >ADDITIONS</td>
    <td >13TH MONTH AND OTHER</td>
    <td >DE MINIMIS BENEFIT</td>
    <td >OTHER</td>
    <td ></td>
    <td >DEDUCTION</td>
    <td >CASH BOND</td>
    <td >CASH ADVANCE</td>
    <td >OTHER LOAN</td>
    <td >SSS LOAN</td>
    <td >SSS EE</td>
    <td >HDMF LOAN</td>
    <td >HDMF EE</td>
    <td >PHIC EE</td>
    <td ></td>
    <td ></td>
    <td ></td>
    <td >SSS ER</td>
    <td >SSS EC</td>
    <td >HDMF ER</td>
    <td >PHIC ER</td>
  </tr>

  @foreach($_employee as $lbl => $employee)
  <tr>  
    <td class="text-center" >{{ $employee->payroll_employee_first_name }} {{ $employee->payroll_employee_last_name }}</td>
    <td class="text-center" >{{ number_format($employee->gross_basic_pay,2) }}</td>
    <td class="text-center" >({{ number_format($employee->absent,2) }}) <br> ({{$employee->time_absent}} times)</td>
    <td class="text-center" >({{ number_format($employee->late,2) }}) <br> ({{$employee->time_late}} hours)</td>
    <td class="text-center" >({{ number_format($employee->undertime,2) }}) <br> ({{$employee->time_undertime}} hours)</td>
    
    <td class="text-center" >{{ number_format($employee->net_basic_pay,2) }} <br> ({{$employee->time_spent}} hours)</td>
        
    <td class="text-center" >{{ number_format($employee->rendered_days,2) }} <br> ({{$employee->time_spent}} hours)</td>

    <td class="text-center" >{{ number_format($employee->cola,2) }}</td>
    <td class="text-center" >{{ number_format($employee->overtime,2) }} <br> ({{$employee->time_overtime}} hours)</td>
    <td class="text-center" >{{ number_format($employee->nightdiff,2) }} <br> ({{$employee->time_night_differential}} hours)</td>
    <td class="text-center" >{{ number_format($employee->special_holiday,2) }} <br> ({{$employee->time_special_holiday}} times)</td>
    <td class="text-center" >{{ number_format($employee->regular_holiday,2) }} <br> ({{$employee->time_regular_holiday}} times)</td>
    <td class="text-center" >{{ number_format($employee->restday,2 )}}</td>
    <td class="text-center" >{{ number_format($employee->leave_pay,2) }} <br> ({{$employee->time_leave_hours}} hours)</td>
          
    <td class="text-center" >{{ number_format((number_format($employee->allowance,2) + number_format($employee->adjustment_allowance, 2)), 2) }}</td>
    <td class="text-center" >{{ number_format($employee->adjustment_bonus,2) }}</td>
    <td class="text-center" >{{ number_format($employee->adjustment_commission,2) }}</td>
    <td class="text-center" >{{ number_format($employee->adjustment_incentives,2) }}</td>
    <td class="text-center" >{{ number_format($employee->adjustment_additions,2) }}</td>
    <td class="text-center" >{{ number_format($employee->adjustment_13th_month_and_other,2) }}</td>
    <td class="text-center" >{{ number_format($employee->allowance_de_minimis,2) }}</td>
    <td class="text-center" >{{ number_format($employee->adjustment_others,2) }}</td>

    <td class="text-center" >{{ number_format($employee->gross_pay,2) }}</td>

    <!--<td class="text-center" >{{ number_format($employee->adjustment_allowance,2) }}</td>-->
    
    <td class="text-center" >({{ number_format($employee->adjustment_deductions,2) }})</td>
    <td class="text-center" >({{ number_format((number_format($employee->cash_bond,2) + number_format($employee->adjustment_cash_bond,2)), 2) }})</td>
    <td class="text-center" >({{ number_format((number_format($employee->cash_advance,2) + number_format($employee->adjustment_cash_advance,2)),2) }})</td>
    <td class="text-center" >({{ number_format($employee->other_loans,2) }})</td>
    <!-- <td class="text-center" >{{ number_format($employee->adjustment_deduction,2) }}</td> -->

    <td class="text-center" >({{ number_format($employee->sss_loan,2) }})</td>
    <td class="text-center" >({{ number_format($employee->sss_ee,2) }})</td>

    <td class="text-center" >({{ number_format($employee->hdmf_loan,2) }})</td>
    <td class="text-center" >({{ number_format($employee->pagibig_ee,2) }})</td>

    <td class="text-center" >({{ number_format($employee->philhealth_ee,2) }})</td>

    <td class="text-center" >({{ number_format($employee->tax_ee,2) }})</td>
    <td class="text-center" >({{ number_format($employee->total_deduction_employee,2) }})</td>

    <td class="text-center" >{{ number_format($employee->net_pay,2) }}</td>

    <td class="text-center" >{{ number_format($employee->sss_er,2) }}</td>
    <td class="text-center" >{{ number_format($employee->sss_ec,2) }}</td>
    <td class="text-center" >{{ number_format($employee->pagibig_er,2) }}</td>
    <td class="text-center" >{{ number_format($employee->philhealth_er,2) }}</td>
  </tr>
 @endforeach
 <tr>
    <td class="text-center" ><b>Total</b></td>
    <td class="text-center" ><b>{{ number_format($total_gross_basic, 2) }}</b></td>
    <td class="text-center" ><b>({{ number_format($absent_total, 2) }})</b></td>
    <td class="text-center" ><b>({{ number_format($late_total, 2) }})</b></td>
    <td class="text-center" ><b>({{ number_format($undertime_total, 2) }})</b></td>

    <td class="text-center" ><b>{{ number_format($total_basic, 2) }}</b></td>
    <td class="text-center" ><b>{{ number_format($rendered_days_total, 2) }}</b></td>
    <td class="text-center" ><b>{{ number_format($cola_total, 2) }}</b></td>
    <td class="text-center" ><b>{{ number_format($overtime_total, 2) }}</b></td>
    <td class="text-center" ><b>{{ number_format($nightdiff_total, 2) }}</b></td>
    <td class="text-center" ><b>{{ number_format($regular_holiday_total, 2) }}</b></td>
    <td class="text-center" ><b>{{ number_format($special_holiday_total, 2) }}</b></td>
    <td class="text-center" ><b>{{ number_format($restday_total, 2) }}</b></td>
    <td class="text-center" ><b>{{ number_format($leave_pay_total, 2) }}</b></td>

    <td class="text-center" ><b>{{ number_format(number_format($total_adjustment_allowance, 2) + number_format($allowance_total, 2), 2)}}</b></td>
    <td class="text-center" ><b>{{ number_format($total_adjustment_bonus, 2) }}</b></td>
    <td class="text-center" ><b>{{ number_format($total_adjustment_commission, 2) }}</b></td>
    <td class="text-center" ><b>{{ number_format($total_adjustment_incentives, 2) }}</b></td>
    <td class="text-center" ><b>{{ number_format($total_adjustment_additions, 2) }}</b></td>
    <td class="text-center" ><b>{{ number_format($total_adjustment_13th_month_and_other, 2) }}</b></td>
    <td class="text-center" ><b>{{ number_format($allowance_de_minimis_total, 2) }}</b></td>
    <td class="text-center" ><b>{{ number_format($total_adjustment_others, 2) }}</b></td>

    <td class="text-center" ><b>{{ number_format($total_gross, 2) }}</b></td>
    
    <td class="text-center" ><b>({{ number_format($total_adjustment_deductions, 2) }})</b></td>
    <td class="text-center" ><b>({{ number_format((number_format($cash_bond_total, 2)    + number_format($total_adjustment_cash_bond, 2)), 2)}})</b></td>
    <td class="text-center" ><b>({{ number_format((number_format($cash_advance_total, 2) + number_format($total_adjustment_additions, 2)), 2)}})</b></td>
    <td class="text-center" ><b>({{ number_format($other_loans_total, 2) }})</b></td>

    <td class="text-center" ><b>({{ number_format($sss_loan_total, 2) }})</b></td>
    <td class="text-center" ><b>({{ number_format($sss_ee_total, 2) }})</b></td>

    <td class="text-center" ><b>({{ number_format($hdmf_loan_total, 2) }})</b></td>
    <td class="text-center" ><b>({{ number_format($hdmf_ee_total, 2) }})</b></td>
    
    <td class="text-center" ><b>({{ number_format($philhealth_ee_total, 2) }})</b></td>
    
    <td class="text-center" ><b>({{ number_format($witholding_tax_total, 2) }})</b></td>
    <td class="text-center" ><b>({{ number_format($deduction_total, 2) }})</b></td>

    <td class="text-center" ><b>{{ number_format($total_net, 2) }}</b></td>

    <td class="text-center" ><b>{{ number_format($sss_er_total, 2) }}</b></td>
    <td class="text-center" ><b>{{ number_format($sss_ec_total, 2) }}</b></td>
    <td class="text-center" ><b>{{ number_format($hdmf_er_total, 2) }}</b></td>
    <td class="text-center" ><b>{{ number_format($philhealth_er_total, 2) }}</b></td>
</tr>
</table>
</div>
</body>
</html>
