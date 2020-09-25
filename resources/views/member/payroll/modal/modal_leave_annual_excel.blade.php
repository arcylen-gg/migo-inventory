	    <h4 class="modal-title"><b>Annual Leave Report </b> <i><br> Monthly Leave Summary </i></h4>

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
