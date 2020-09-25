    @if($category == 'monthly_leave')
    <div class="modal-body clearfix">
                <div class="table-responsive">
                    <table class="table table-bordered table-condensed">
                        <thead>
                            <tr>
                                <th class="text-center">Leave Name</th>
                                <th class="text-center">Employee Number</th>
                                <th class="text-center">Employee Name</th>
                                <th class="text-center">Date of Leave</th>
                                <th class="text-center">Leave Credits</th>
                                <th class="text-center">Number of Hours</th>
                                <th class="text-center">Remaining Leave</th>
                                <th class="text-center">Payment Indicator</th>
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
                                <td class="text-center">{{ $leave->payroll_schedule_leave }}</td>
                                <td class="text-center">{{ $leave->payroll_leave_temp_hours }}</td>
                                <td class="text-center">{{ $leave->total_leave_consume}}</td>
                                @foreach($remainings as $remain)
                                    @foreach($remain as $rem)
                                          @if($rem->payroll_employee_id == $leave->payroll_employee_id)
                                          <td class="text-center">{{ $rem->remaining_leave }}</td>
                                          @endif
                                    @endforeach
                                @endforeach
                                <td class="text-center">{{ $leave->payroll_leave_temp_with_pay == '1' ? 'P' : 'NP'}}</td>
                            </tr>
                                 @endforeach
                            @endforeach
                            @endif
                        </tbody>
                    </table>
                </div>
    </div>
    <div class="modal-footer">
        <button type="button" class="btn btn-def-white btn-custom-white" data-dismiss="modal">Close</button>
        &nbsp;<a href="/member/payroll/leave/v2/monthly_leave_report_excel/{{$date_start}}/{{$date_end}}/{{$company}}"><button type="button" class="btn btn-success pull-right"><i class="fa fa-file-excel-o" ></i> &nbsp;EXPORT TO EXCEL</button></a>
    </div>
    @elseif($category == 'monthly_action')
        <div class="modal-body clearfix">
                    <div class="table-responsive">
                        <table class="table table-bordered table-condensed">
                            <thead>
                                <tr>
                                    <th class="text-center">Leave Name</th>
                                    <th class="text-center" width="30">Employee Number</th>
                                    <th class="text-center">Employee Name</th>
                                    <th class="text-center" width="10">Leave Credits</th>
                                    <th class="text-center" width="10">Leave Hours Remaining</th>
                                    <th class="text-center" width="10">Leave Hours Accumulated</th>
                                    <th class="text-center" width="10">Cash Converted</th>
                                    <th class="text-center">Date</th>
                                </tr>
                            </thead>
                            <tbody>
                                @if(isset($leave_report))
                                @foreach($leave_report as $leave)
                                <tr>
                                    <td class="text-center">{{ $leave->payroll_leave_temp_name }}</td>
                                    <td class="text-center">{{ $leave->payroll_employee_id }}</td>
                                    <td class="text-center">{{ $leave->payroll_employee_display_name }}</td>
                                    <td class="text-center">{{ $leave->payroll_leave_temp_hours }}</td>
                                    <td class="text-center">{{ $leave->payroll_leave_hours_remaining }}</td>
                                    <td class="text-center">{{ $leave->payroll_leave_hours_accumulated }}</td>
                                    <td class="text-center">{{ $leave->payroll_leave_cash_converted }}</td>
                                    <td class="text-center">{{ $leave->payroll_report_date_created }}</td>
                                </tr>
                                @endforeach
                                @endif
                            </tbody>
                        </table>
                    </div>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-def-white btn-custom-white" data-dismiss="modal">Close</button>
            &nbsp;<a href="/member/payroll/leave/v2/leave_action_report_excel/{{$date_start}}/{{$date_end}}/{{$company}}"><button type="button" class="btn btn-success pull-right"><i class="fa fa-file-excel-o" ></i> &nbsp;EXPORT TO EXCEL</button></a>
        </div>
    @elseif($category == 'monthly_without')
        <div class="modal-body clearfix">
                <div class="table-responsive">
                    <table class="table table-bordered table-condensed">
                        <thead>
                            <tr>
                                <th class="text-center">Leave Name</th>
                                <th class="text-center">Employee Number</th>
                                <th class="text-center">Employee Name</th>
                                <th class="text-center">Leave Credits</th>
                                <th class="text-center">Used Leave without Pay</th>
                                <th class="text-center">Remaining Leave</th>
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
                                        <td class="text-center">{{ $leave->total_leave_consume }}</td>
                                        @foreach($remainings as $remain)
                                         @foreach($remain as $rem)
                                          @if($rem->payroll_employee_id == $leave->payroll_employee_id)
                                          <td class="text-center">{{ $rem->remaining_leave }}</td>
                                          @endif
                                          @endforeach
                                        @endforeach
                                    </tr>
                                 @endforeach
                            @endforeach
                            @endif
                        </tbody>
                    </table>
                </div>
    </div>
    <div class="modal-footer">
        <button type="button" class="btn btn-def-white btn-custom-white" data-dismiss="modal">Close</button>
        &nbsp;<a href="/member/payroll/leave/v2/withoutpay_leave_report_excel/{{$date_start}}/{{$date_end}}/{{$company}}"><button type="button" class="btn btn-success pull-right"><i class="fa fa-file-excel-o" ></i> &nbsp;EXPORT TO EXCEL</button></a>
    </div>
    @elseif($category == 'monthly_with')
        <div class="modal-body clearfix">
                <div class="table-responsive">
                    <table class="table table-bordered table-condensed">
                        <thead>
                            <tr>
                                <th class="text-center">Leave Name</th>
                                <th class="text-center">Employee Number</th>
                                <th class="text-center">Employee Name</th>
                                <th class="text-center">Leave Credits</th>
                                <th class="text-center">Used Leave with Pay</th>
                                <th class="text-center">Remaining Leave</th>
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
                                        <td class="text-center">{{ $leave->total_leave_consume }}</td>
                                        @foreach($remainings as $remain)
                                         @foreach($remain as $rem)
                                          @if($rem->payroll_employee_id == $leave->payroll_employee_id)
                                          <td class="text-center">{{ $rem->remaining_leave }}</td>
                                          @endif
                                          @endforeach
                                        @endforeach
                                    </tr>
                                 @endforeach
                            @endforeach
                            @endif
                        </tbody>
                    </table>
                </div>
    </div>
    <div class="modal-footer">
        <button type="button" class="btn btn-def-white btn-custom-white" data-dismiss="modal">Close</button>
        &nbsp;<a href="/member/payroll/leave/v2/pay_leave_report_excel/{{$date_start}}/{{$date_end}}/{{$company}}"><button type="button" class="btn btn-success pull-right"><i class="fa fa-file-excel-o" ></i> &nbsp;EXPORT TO EXCEL</button></a>
    </div>
    @elseif($category == 'monthly_remaining')
        <div class="modal-body clearfix">
                <div class="table-responsive">
                    <table class="table table-bordered table-condensed">
                        <thead>
                            <tr>
                                <th class="text-center" id="border"></th>
                                <th class="text-center" id="border"></th>
                                <th class="text-center" id="border"></th>
                                <th class="text-center" id="border"></th>
                                <th colspan="3" class="text-center">Used Leave</th>
                                <th class="text-center" id="border"></th>
                            </tr>
                            <tr>
                                <th class="text-center" id="bordertop">Leave Name</th>
                                <th class="text-center" id="bordertop">Employee Number</th>
                                <th class="text-center" id="bordertop">Employee Name</th>
                                <th class="text-center" id="bordertop">Leave Credits</th>
                                <th class="text-center">With Pay</th>
                                <th>Without Pay</th>
                                <th>Total Used Leave</th>
                                <th class="text-center" id="bordertop">Remaining Leave</th>
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
    <div class="modal-footer">
        <button type="button" class="btn btn-def-white btn-custom-white" data-dismiss="modal">Close</button>
        &nbsp;<a href="/member/payroll/leave/v2/remaining_leave_report_excel/{{$date_start}}/{{$date_end}}/{{$company}}"><button type="button" class="btn btn-success pull-right"><i class="fa fa-file-excel-o" ></i> &nbsp;EXPORT TO EXCEL</button></a>
    </div>
    @elseif($category == 'annual_leave')
        <div class="modal-body clearfix">
                <div class="table-responsive">
                    <table class="table table-bordered table-condensed">
                        <thead>
                            <tr>
                                <th class="text-center">Employee Number</th>
                                <th class="text-center">Employee Name</th>
                                <th class="text-center">Leave Name</th>
                                <th class="text-center">Jan {{$lastyear}}</th>
                                <th class="text-center">Feb {{$lastyear}}</th>
                                <th class="text-center">Mar {{$lastyear}}</th>
                                <th class="text-center">Apr {{$lastyear}}</th>
                                <th class="text-center">May {{$lastyear}}</th>
                                <th class="text-center">Jun {{$lastyear}}</th>
                                <th class="text-center">July {{$lastyear}}</th>
                                <th class="text-center">Aug {{$lastyear}}</th>
                                <th class="text-center">Sep {{$lastyear}}</th>
                                <th class="text-center">Oct {{$lastyear}}</th>
                                <th class="text-center">Nov {{$lastyear}}</th>
                                <th class="text-center">Dec {{$lastyear}}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if(isset($leave_report))
                            @foreach($leave_report as $annual)
                            <tr>
                                <td class="text-center">{{ $annual->payroll_employee_number }}</td>
                                <td class="text-center">{{ $annual->payroll_employee_display_name }}</td>
                                <td class="text-center">{{ $annual->payroll_leave_name }}</td>
                                <td class="text-center">@if(isset($annual->January)){{ $annual->January }}@else 0 @endif</td>
                                <td class="text-center">@if(isset($annual->February)){{ $annual->February }}@else 0 @endif</td>
                                <td class="text-center">@if(isset($annual->March)){{ $annual->March }}@else 0 @endif</td>
                                <td class="text-center">@if(isset($annual->April)){{ $annual->April }}@else 0 @endif</td>
                                <td class="text-center">@if(isset($annual->May)){{ $annual->May }}@else 0 @endif</td>
                                <td class="text-center">@if(isset($annual->June)){{ $annual->June }}@else 0 @endif</td>
                                <td class="text-center">@if(isset($annual->July)){{ $annual->July }}@else 0 @endif</td>
                                <td class="text-center">@if(isset($annual->August)){{ $annual->August }}@else 0 @endif</td>
                                <td class="text-center">@if(isset($annual->September)){{ $annual->September }}@else 0 @endif</td>
                                <td class="text-center">@if(isset($annual->October)){{ $annual->October }}@else 0 @endif</td>
                                <td class="text-center">@if(isset($annual->November)){{ $annual->November }}@else 0 @endif</td>
                                <td class="text-center">@if(isset($annual->December)){{ $annual->December }}@else 0 @endif</td>
                            </tr>
                            @endforeach
                            @endif
                        </tbody>
                    </table>
                </div>
    </div>
    <div class="modal-footer">
        <button type="button" class="btn btn-def-white btn-custom-white" data-dismiss="modal">Close</button>
        &nbsp;<a href="/member/payroll/leave/v2/leave_annual_export/{{$company}}"><button type="button" class="btn btn-success pull-right"><i class="fa fa-file-excel-o" ></i> &nbsp;EXPORT TO EXCEL</button></a>
    </div>
    @endif