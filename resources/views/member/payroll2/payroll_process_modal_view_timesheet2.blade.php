<div class="modal-header">
    <button type="button" class="close" data-dismiss="modal">×</button>
    <h4 class="modal-title"><b>{{ucfirst($employee->payroll_employee_title_name." ".$employee->payroll_employee_first_name." ".$employee->payroll_employee_middle_name." ".$employee->payroll_employee_last_name." ".$employee->payroll_employee_suffix_name)}} - TIMESHEET</b></h4>
</div>

<div class="modal-body clearfix employee-timesheet-modal">
    <div class="col-md-2 padding-lr-1 pull-right">
            <a role="form" target="_blank" href="/member/payroll/process_payroll/income_summary/timesheet_view_pdf/{{$period_company_id_pdf}}/{{$employee_id_pdf}}"><button type="button" class="btn btn-success" style="margin:10px 10px 10px 0px"><i class="fa fa-file-pdf-o" aria-hidden="true"></i> View PDF</button></a>
    </div>
    @if($employee)
        <table class="table table-bordered table-striped table-condensed">
            <thead style="text-transform: uppercase">
                <tr>
                    <th class="text-center" rowspan="2" width="120px">Date</th>
                    <th class="text-center" colspan="2">Actual</th>
                    <th class="text-center" colspan="2">Approved</th>
                    <th class="text-center" rowspan="2" width="100px">Remarks</th>
                    <th class="text-center" rowspan="2" width="100px">Branch</th>
                </tr>
                <tr>
                    <th class="text-center">Time In</th>
                    <th class="text-center">Time Out</th>
                    <th class="text-center">Time In</th>
                    <th class="text-center">Time Out</th>
                   
                </tr>
            </thead>
            <tbody>
                @foreach($_timesheet as $timesheet)
                <tr>
                    <td class="text-center">{{$timesheet["covered_date"]}}</td>

                    <td class="text-center">
                        @foreach($timesheet["actual_time"] as $time)
                            <div>{{  date('h:i:s A', strtotime($time->payroll_time_sheet_in)) }}</div>
                        @endforeach
                    </td>
                    <td class="text-center">
                        @foreach($timesheet["actual_time"] as $time)
                            <div>{{  date('h:i:s A', strtotime($time->payroll_time_sheet_out)) }}</div>
                        @endforeach
                    </td>

                    <td class="text-center">
                        @foreach($timesheet["approve_time"] as $time)
                            <div>{{  date('h:i:s A', strtotime($time->payroll_time_sheet_in)) }}</div>
                        @endforeach
                    </td>
                    <td class="text-center">
                        @foreach($timesheet["approve_time"] as $time)
                            <div>{{  date('h:i:s A', strtotime($time->payroll_time_sheet_out)) }}</div>
                        @endforeach
                    </td>

                    <td class="text-center">
                        @foreach($timesheet["actual_time"] as $time)
                            <div>{{  $time->payroll_time_shee_activity }}</div>
                        @endforeach
                    </td>
                    <td class="text-center">
                        @foreach($timesheet["actual_time"] as $time)
                            <div>{{  $time->payroll_company_name }}</div>
                        @endforeach
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
        <div class="modal-body clearfix">
            <div class="row">
                <div class="col-md-12">
                    <div class="col-md-3"></div>
                    <div class="col-md-3"></div>
                    <div style="text-transform: uppercase;" class="col-md-3 text-bold text-right title_summary">Number of Days Worked</div>
                    <div class="col-md-3 text-center title_description">{{$days_worked or 0}}</div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <div class="col-md-3"></div>
                    <div class="col-md-3"></div>
                    <div style="text-transform: uppercase;" class="col-md-3 text-bold text-right title_summary">Total Absent(s)</div>
                    <div class="col-md-3 text-center title_description">{{$days_absent or 0}}</div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <div class="col-md-3"></div>
                    <div class="col-md-3"></div>
                    <div style="text-transform: uppercase;" class="col-md-3 text-bold text-right title_summary">Total Late(s)</div>
                    <div class="col-md-3 text-center title_description">{{$total_late or 0}}</div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <div class="col-md-3"></div>
                    <div class="col-md-3"></div>
                    <div style="text-transform: uppercase;" class="col-md-3 text-bold text-right title_summary">Overtime</div>
                    <div class="col-md-3 text-center title_description">{{$total_undertime or 0}}</div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <div class="col-md-3"></div>
                    <div class="col-md-3"></div>
                    <div style="text-transform: uppercase;" class="col-md-3 text-bold text-right title_summary">Undertime</div>
                    <div class="col-md-3 text-center title_description">{{$total_undertime or 0}}</div>
                </div>
            </div>
        </div>
    @else
        <div class="text-center" style="padding: 150px 0">NO DATA</div>
    @endif
</div>