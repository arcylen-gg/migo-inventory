	    <h4 class="modal-title"><b>Remaining - Leave Records : {{$date_start}} to {{$date_end}}</b> <i><br> Remaining - Leave Records</i></h4>

    <div class="modal-body clearfix">
                <div class="table-responsive">
                    <table class="table table-bordered table-condensed">
                        <thead>
                            <tr>
                                <th class="text-center wa" id="border"></th>
                                <th class="text-center wa" id="border"></th>
                                <th class="text-center empname" id="border"></th>
                                <th class="text-center wa" id="border"></th>
                                <th colspan="3" class="text-center wa">Used Leave</th>
                                <th class="text-center wa" id="border"></th>
                            </tr>
                            <tr>
                                <th class="text-center wa" id="bordertop">Leave Name</th>
                                <th class="text-center wa" id="bordertop">Employee Number</th>
                                <th class="text-center empname" id="bordertop">Employee Name</th>
                                <th class="text-center wa" id="bordertop">Leave Credits</th>
                                <th class="text-center wa">With Pay</th>
                                <th class="text-center wa">Without Pay</th>
                                <th class="text-center wa">Total Used Leave</th>
                                <th class="text-center wa" id="bordertop">Remaining Leave</th>
                            </tr>
                        </thead>
                         <tbody>
                            @if(isset($leave_report))
                            @foreach($leave_report as $leave_data)
                                @foreach($leave_data as $leave)
                            <tr>
                                <td class="text-center">{{ $leave->payroll_leave_temp_name }}</td>
                                <td class="text-center">{{ $leave->payroll_employee_id }}</td>
                                <td class="text-center">{{ $leave->payroll_employee_display_name }}</td>
                                <td class="text-center">{{ $leave->payroll_leave_temp_hours }}</td>
                                    
                                @php $paycheck = NULL; @endphp
                                @foreach($remwithpay as $remwith)
                                    @foreach($remwith as $pay)
                                             @if($pay->payroll_employee_id == $leave->payroll_employee_id)
                                                     @php
                                                     $paycheck = $pay;
                                                     break;
                                                     @endphp
                                             @endif
                                    @endforeach
                                @endforeach

                                @if(!is_null($paycheck))
                                @foreach($remwithpay as $remwith)
                                    @foreach($remwith as $pay)
                                             @if($pay->payroll_employee_id == $leave->payroll_employee_id)
                                                 <td class="text-center">{{$pay->total_leave_consume}}</td>
                                             @endif
                                    @endforeach
                                @endforeach
                                 @else
                                    <td class="text-center">0.00</td>
                                @endif

                                @php $withoutpaycheck = NULL; @endphp
                                @foreach($remwithoutpay as $remwiths)
                                    @foreach($remwiths as $withoutpay)
                                             @if($withoutpay->payroll_employee_id == $leave->payroll_employee_id)
                                                     @php
                                                     $withoutpaycheck = $withoutpay;
                                                     break;
                                                     @endphp
                                             @endif
                                    @endforeach
                                @endforeach

                                @if(!is_null($withoutpaycheck))
                                    @foreach($remwithoutpay as $remwiths)
                                            @foreach($remwiths as $withoutpay)
                                                     @if($withoutpay->payroll_employee_id == $leave->payroll_employee_id)
                                                        <td class="text-center">{{$withoutpay->total_leave_consume}}</td>
                                                     @endif
                                            @endforeach
                                     @endforeach
                                @else
                                    <td class="text-center">0.00</td>
                                @endif
                                

                                <td class="text-center">{{ $leave->total_leave_consume }}</td>
                                <td class="text-center">{{ $leave->remaining_leave }}</td>
                            </tr>
                                 @endforeach
                            @endforeach
                            @endif
                        </tbody>
                    </table>
                </div>
	</div>
<style>
.wa{
    background-color: #ffff99 !important;
     border: 5px solid #2C2C2C;
}
.empname{
    background-color: #ccffff !important;
        border: 5px solid #2C2C2C;
}

</style>