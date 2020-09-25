{{--     <h4 class="modal-title"><b>Monthly Leave - : {{$date_start}} to {{$date_end}}</b> <i><br> Used - Leave Records<br>SL/VL / Summary of all Leave Types<br></i></h4>
 --}}
    <div class="modal-body clearfix">
                <div class="table-responsive">
                    <table class="table table-bordered table-condensed">
                        <thead>
                            <tr>
                                <th class="text-center wa">Company Name</th> 
                                <th class="text-center">{{ $payroll_period->payroll_company_name }}</th>
                            </tr>
                            <tr>
                                <th class="text-center wa">Company Code</th> 
                                <th class="text-center">{{ $payroll_period->payroll_company_code}}</th>
                            </tr>
                            <tr>
                                <th class="text-center wa">Company'S Depository Branch Code</th> 
                                <th class="text-center"></th>
                            </tr>
                             <tr>
                                <th class="text-center wa">Effectivity Date</th> 
                                <th class="text-center"></th>
                            </tr>
                             <tr>
                                <th class="text-center wa">Employee Code</th> 
                                <th class="text-center wa">Employee Name</th>
                                <th class="text-center wa">Branch Code</th>
                                <th class="text-center wa">Payroll Acct. No</th>
                                <th class="text-center wa">Amount</th>

                            </tr>
                        </thead>
                        <tbody>
                            @php $total = 0; @endphp
                            <?php $total = 0; ?>
                            @foreach($_employee as $employee)
                            <tr>   
                                @php $total += number_format((float)$employee->net_pay, 2, '.', ''); @endphp
                                <td>{{$employee->payroll_employee_number}}</td>
                                <td>{{$employee->payroll_employee_display_name}}</td>
                                <td>{{substr($employee->payroll_employee_atm_number, 0,3)}}</td>
                                <td>{{substr($employee->payroll_employee_atm_number,3)}}</td>
                                <td>{{ number_format($employee->net_pay, 2) }}</td>

                            </tr>
                            @endforeach
                            <br>
                            <tr>
                                <td>Total:</td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td>{{ number_format($total, 2) }}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
    </div>
<style>
.wa{
    background-color: #ffff00 !important;
     border: 5px solid #2C2C2C;
}


</style>