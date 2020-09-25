<div class="panel panel-default panel-block panel-title-block" id="top">
  <div class="panel-heading">
    <div>
      <style type="text/css">
        
        th, td {
          padding: 5px;
        }

        div.page
        {
            page-break-after: always;
            page-break-inside: avoid;
        }

      </style>
        <?php $counter = 1 ?>
        @foreach($_employee as $employee)

          @if($counter==1)  
            <div class="page">
          @endif  
          <div style="" >                
            <table class="" style="width: 50%; padding: 2px" border="1">            
              <tr>
                <tr>
                  <td colspan="3" class="text-center">{{ strtoupper($company->payroll_company_name) }}</td>
                </tr>
                <tr>
                  <td colspan="3">
                    Name: 
                      {{ $employee->payroll_employee_last_name }}, {{ $employee->payroll_employee_first_name }} {{ $employee->payroll_employee_middle_name }}
                  </td>                  
                </tr>
                <tr>
                  <td colspan="3">
                    {{ $show_period_start }} - {{ $show_period_end }}
                  </td>
                </tr>
              </tr> 
                <tbody>
                  <tr style="font-weight: bold;" width="100%">
                      <td>BASIC PAY</td>
                      <td class="text-right">{{ payroll_currency($employee->net_basic_pay) }}</td>
                      <td></td>
                  </tr>
                  <tr>
                    <td colspan="3"></td>
                  </tr>
                @foreach($employee->cutoff_breakdown->_gross_pay_breakdown as $breakdown)
                  <tr>
                      <td>{{ strtoupper($breakdown["label"]) }}</td>
                      <td class="text-right">{{ payroll_currency($breakdown["amount"]) }}</td>
                      <td></td>
                  </tr>
                @endforeach

                <tr style="font-weight: bold;">
                  <td>GROSS SALARY</td>
                  <td></td>
                  <td class="text-right">{{ payroll_currency($employee->gross_pay) }}</td>
                </tr>

                @foreach($employee->cutoff_breakdown->_taxable_salary_breakdown as $breakdown)
                  <tr>
                      <td>{{ strtoupper($breakdown["label"]) }}</td>
                      <td class="text-right">{{ payroll_currency($breakdown["amount"]) }}</td>
                      <td></td>
                  </tr>
                @endforeach



                @foreach($employee->cutoff_breakdown->_net_pay_breakdown as $breakdown)
                  <tr>
                    <td>{{ strtoupper($breakdown["label"]) }}</td>
                    <td class="text-right">{{ payroll_currency($breakdown["amount"]) }}</td>
                    <td></td>
                  </tr>
                @endforeach


                <tr>
                    <td>TOTAL DEDUCTION</td>
                    <td></td>
                    <td class="text-right">{{ payroll_currency($employee->total_deduction) }}</td>
                </tr>

                <tr style="font-weight: bold;">
                    <td>TAKE HOME PAY</td>
                    <td></td>
                    <td class="text-right">{{ payroll_currency($employee->net_pay) }}</td>
                </tr>
                </tbody>
            </table>
          </div>
          <br />
          @if($counter==2)  
            </div>
            <tr></tr></table><table cellpadding="5" cellspacing="0" class="border padding-5" width="50%" height="">
          @endif
          <?php ($counter>=2) ? $counter=1 : $counter++ ?>
        @endforeach
    </div>
  </div>
</div> 
    
