<table class="table table-bordered table-striped table-condensed table-hover" >
      <thead style="text-transform: uppercase">
            <tr>
                  <th class="text-center" >Company Name</th>
                  <th class="text-center" >Net Pay Base On Actual Rotation</th>
                  <th class="text-center" >Net Pay Base On Govt Reports</th>
                  <th class="text-center" >Difference Due To Rotation</th>
            </tr>
      </thead>
      <tbody>
            @foreach($_branch_report as $key => $branch_report)
            <tr>
                  <td class="text-center">{{ $branch_report["company_name"] }}</td>
                  <td class="text-center">{{ number_format($branch_report["rotational_gross_pay"], 2) }}</td>
                  <td class="text-center">{{ number_format($branch_report["government_gross_pay"], 2) }}</td>
                  <td class="text-center">{{ $branch_report["difference"] >= 0 ? number_format($branch_report["difference"], 2) : '('. number_format(($branch_report["difference"] * -1),2) . ')' }}</td>
            </tr>
            @endforeach
            <tr>
                  <td class="text-center" style="font-weight: bold;">TOTAL</td>
                  <td class="text-center" style="font-weight: bold;">{{ number_format($total_rotational_report,2) }}</td>
                  <td class="text-center" style="font-weight: bold;">{{ number_format($total_government_report,2) }}</td>
                  <td class="text-center" style="font-weight: bold;">{{ number_format($total_difference,2) }}</td>
            </tr>
      </tbody>
</table>
<hr>

<div class="row">
      <div class="col-lg-8">
            <table class="table table-bordered table-striped table-condensed table-hover" >
                  <thead style="text-transform: uppercase">
                        <tr>
                              <th class="text-center" >Company Name</th>
                              <th class="text-center" >Based on payslips</th>
                              <th class="text-center" >Based on worksheets</th>
                              <th class="text-center" >Difference Due To<BR>Rotation of employees</th>
                        </tr>
                  </thead>
                  <tbody>
                        @foreach($_company_parent_report as $key => $company_parent_report)
                        <tr>
                              <td class="text-center">{{ $company_parent_report["company_name"] }}</td>
                              <td class="text-center">{{ number_format($company_parent_report["rotational_gross_pay"],2) }}</td>
                              <td class="text-center">{{ number_format($company_parent_report["government_gross_pay"],2) }}</td>
                              <td class="text-center">{{ $company_parent_report["difference"] >= 0 ?  number_format($company_parent_report["difference"],2) : '('. number_format(($company_parent_report["difference"] * -1),2).')' }}</td>
                        </tr>
                        @endforeach
                        <tr>
                              <td class="text-center" style="font-weight: bold;">TOTAL</td>
                              <td class="text-center" style="font-weight: bold;">{{ number_format($total_rotational_report,2) }}</td>
                              <td class="text-center" style="font-weight: bold;">{{ number_format($total_government_report,2) }}</td>
                              <td class="text-center" style="font-weight: bold;">{{ number_format($total_difference,2) }}</td>
                        </tr>
                  </tbody>
            </table>
      </div>
      <div class="col-lg-4">
            
      </div>
      
</div>
