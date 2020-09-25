<div class="modal-header">
    <button type="button" class="close" data-dismiss="modal">×</button>
    <h4 class="modal-title"><b>{{ucfirst($employee->payroll_employee_title_name." ".$employee->payroll_employee_first_name." ".$employee->payroll_employee_middle_name." ".$employee->payroll_employee_last_name." ".$employee->payroll_employee_suffix_name)}} - TIMESHEET</b></h4>
</div>

<div class="modal-body clearfix employee-timesheet-modal">
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
                    <td class="text-center">{{$timesheet["actual_in"]}}</td>
                    <td class="text-center">{{$timesheet["actual_out"]}}</td>
                    <td class="text-center">{{$timesheet["approved_in"]}}</td>
                    <td class="text-center">{{$timesheet["approved_out"]}}</td>
                    <td class="text-center">{{$timesheet["remarks"] or ''}}</td>
                    <td class="text-center">{{$timesheet["branch"]}}</td>
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