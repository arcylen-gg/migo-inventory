@foreach($company as $comid)
    <br>
    <table class="table table-bordered table-condensed">
      <thead>
        <tr>
          <th colspan="7" class="text-center">{{strtoupper($comid->payroll_company_name)}}</th>
        </tr>
          <tr>
              <th class="text-center">EMPLOYEE NAME</th>
              <th class="text-center">DESCRIPTION</th>
              <th class="text-center"># of Payments</th>
              <th class="text-center">LOAN AMOUNT</th>
              <th class="text-center">TOTAL PAYMENT</th>
              <th class="text-center">BALANCE</th>
              <th></th>
          </tr>
      </thead>
      <tbody class="table-warehouse">
        <input type="hidden" value="{{ $comid->payroll_company_id }}">
          @foreach($_loan_data as $key => $loan_data)
            @if($loan_data->payroll_employee_company_id == $comid->payroll_company_id)
              <tr id="blank">
                  <td class="text-center">{{ $loan_data->payroll_employee_display_name }}</td>
                  <td class="text-center">{{ $loan_data->payroll_deduction_name }}</td>
                  <td class="text-center">{{ $loan_data->number_of_payment .' out of '. $loan_data->payroll_deduction_number_of_payments}}</td>
                  <td class="text-center" id="loan-{{$comid->payroll_company_id}}">{{ $loan_data->payroll_deduction_amount }}</td>
                  <td class="text-center" id="payment-{{$comid->payroll_company_id}}">{{ $loan_data->total_payment }}</td>
                  <td class="text-center" id="balance-{{$comid->payroll_company_id}}">{{ $loan_data->payroll_deduction_amount - $loan_data->total_payment }}</td>
                  <td class="text-center"><a href="javascript: action_load_link_to_modal('/member/payroll/reports/modal_loan_summary_report/{{ $loan_data->payroll_employee_id }}/{{ $loan_data->payroll_deduction_id }}','lg')">SUMMARY</a></td>
              </tr>
          @endif
          @endforeach

          @foreach($totals['totals'] as $key => $total)
                @if($total['payroll_employee_company_id'] == $comid->payroll_company_id)
                  <tr class="total">
                    <td class="text-center"><strong>TOTAL</strong></td>
                    <td class="text-center"></td>
                    <td class="text-center"></td>
                    <td class="text-center">{{$total['loan_total']}}</td>
                    <td class="text-center">{{$total['total_total_payment']}}</td>
                    <td class="text-center">{{$total['total_remaining_balance']}}</td>
                    <td class="text-center"></td>
                  </tr>
              @endif
          @endforeach
      </tbody>
    </table>
@endforeach



<script>
$(document).ready(function() {
  $('.table-condensed tbody').each(function(){
      var company_id = $(this).find('input').val();

      if($(this).children().length == 0)
      {
          $(this).css("display", "none");
          $(this).parent().css("display", "none");
      }
      // else
      // {
      //  var totall = 0;
      //  var totalp = 0;
      //  var totalb = 0;
      //  $(this).find('td').each(function(){
      //    var loan = $(this).attr('id');
      //    var companyloanid = 'loan-'+company_id;
      //    var totalloan = 'total-'+loan;

      //    var payment = $(this).attr('id');
      //    var companypaymentid = 'payment-'+company_id;
      //    var totalpayment = 'total-'+payment;

      //    var balance = $(this).attr('id');
      //    var companybalanceid = 'balance-'+company_id;
      //    var totalbalance = 'total-'+balance;
      //    if(loan == companyloanid)
      //    {
      //      totall += parseInt($(this).text());
      //      $("#"+totalloan).text(totall);
            
      //    }
      //    if(payment == companypaymentid)
      //    {
      //      totalp += parseInt($(this).text());
      //      $("#"+totalpayment).text(totalp);
      //    }
      //    if(balance == companybalanceid)
      //    {
      //      totalb += parseInt($(this).text());
      //      $("#"+totalbalance).text(totalb);
      //    }
          
      //  });
      // }
});



});
</script>