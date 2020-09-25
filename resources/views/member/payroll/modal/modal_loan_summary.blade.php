
<input type="text" name="payroll_employee_id" value="{{ $employee_id }}" class="hidden employee-id">
<input type="text" name="payroll_deduction_id" value="{{ $payroll_deduction_id }}" class="hidden payroll-deduction-id">


<input type="number" name="count" value="{{ $count=0 }}" class="hidden">
<div class="modal-header">
    <button type="button" class="close" data-dismiss="modal">×</button>
    <h4 class="modal-title"><b>LOAN SUMMARY</b> &raquo; {{ $employee_info->payroll_employee_first_name }} {{ $employee_info->payroll_employee_last_name }} (Employee No. {{ $employee_info->payroll_employee_number == "" ? "00" : $employee_info->payroll_employee_number }}) </h4>
</div>


       
 

<div class="modal-body clearfix employee-timesheet-modal">
<div>
    <a href="/member/payroll/reports/export_loan_summary_report_to_excel/ {{ $employee_id }} / {{ $payroll_deduction_id }}"> <button type="button" class="btn btn-success pull-right" >EXPORT TO EXCEL</button></a>
</div>
    <div class="col-md-12" style="text-align: left; font-weight: normal; margin-bottom: 10px; font-size: 16px;"></div>
    <div class="clearfix">
        <div class="col-md-12">
            <div class="table-responsive">
                <table class="table table-bordered table-condensed timesheet table-timesheet timesheet-of-employee">
                    <thead style="text-transform: uppercase">
                        <tr>
                            <th class="text-center"># OF PAYMENTS</th>
                            <th class="text-center">PAYMENT PERIOD</th>
                            <th class="text-center">BEGINNING BALANCE</th>
                            <th class="text-center">TOTAL PAYMENT</th>
                            <th class="text-center">REMAINING BALANCE </th>
                        </tr>
                    </thead>
                    <tbody>
                   		 	
                        	@foreach($_loan_data as $key => $loan_data)
                            <tr>
                                <td class="text-center">{{ $count+=1 }}</td>
                                <td class="text-center">{{$loan_data->payroll_payment_period}}</td>
                                <td class="text-center">{{$loan_data->payroll_beginning_balance}}</td>
                                <td class="text-center">{{$loan_data->payroll_total_payment_amount}}</td>
                                <td class="text-center">{{$loan_data->payroll_remaining_balance}}</td>
                            </tr>
                        	@endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<style type="text/css">
    #global_modal .modal-dialog
    {
        width: 85% !important;
    }
</style>




