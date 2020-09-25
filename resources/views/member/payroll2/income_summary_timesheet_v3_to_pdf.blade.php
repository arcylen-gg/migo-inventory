<style type="text/css">
	body
	{
		color: #333;
	}
	.labels
	{
		color: #555;
		font-size: 9px;
		padding: 1px;
		padding-left: 5px;
	}
	.answers
	{
		color: #000;
		padding-left: 5px;
	}
	.sub-group
	{
		float: left;
	}
	.divider
	{
		border-bottom: 1px solid #aaa;
	}
	.table-main thead tr td 
	{
		text-align: center;
		font-size: 9px;
		padding: 3px;
		color: #555;
		border-right: 1px solid #aaa;
		border-bottom: 1px solid #aaa;
	}
	.text
	{
		text-align: center;
		font-size: 9px;
		padding: 3px;
		color: #555;
		border-right: 1px solid #aaa;
		border-bottom: 1px solid #aaa;
	}
	.table-main tbody tr td
	{
		text-align: center;
		font-size: 9px;
		padding: 3px;
		color: #000;
		border-right: 1px solid #aaa;
		border-bottom: 1px solid #aaa;
	}
</style>

<div>
    <div style="float: left; width: 320px; font-size: 22px; font-weight: bold; text-align: center;"><b>{{ucfirst($employee->payroll_employee_title_name." ".$employee->payroll_employee_first_name." ".$employee->payroll_employee_middle_name." ".$employee->payroll_employee_last_name." ".$employee->payroll_employee_suffix_name)}}</b></div>
</div>
<div>
		<div class="labels">TIMESHEET</div>
</div>

<div class="modal-body clearfix employee-timesheet-modal">

    @if($employee)
    	<div style="border: 2px solid #000;">
        <table class="table-main" cellspacing="0" cellpadding="0" width="100%">
            <thead style="text-transform: uppercase">
                <tr>
                    <th class="text" rowspan="2" width="120px">Date</th>
                    <th class="text" colspan="2">Actual</th>
                    <th class="text" colspan="2">Approved</th>
                    <th class="text" rowspan="2" width="100px">Remarks</th>
                    <th class="text" rowspan="2" width="100px">Branch</th>
                </tr>
                <tr>
                    <th class="text">Time In</th>
                    <th class="text">Time Out</th>
                    <th class="text">Time In</th>
                    <th class="text">Time Out</th>
                   
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
        </div>
        <div class="modal-body clearfix">
        	 <div class="divider"></div>
            <div class="row">
                <div class="col-md-12">
                    <div class="col-md-3"></div>
                    <div class="col-md-3"></div>
                    <div style="text-transform: uppercase;" class="col-md-3 text-bold text-right title_summary">Number of Days Worked</div>
                    <div class="col-md-3 text-center title_description">{{$days_worked or 0}}</div>
                </div>
            </div>
             <div class="divider"></div>
            <div class="row">
                <div class="col-md-12">
                    <div class="col-md-3"></div>
                    <div class="col-md-3"></div>
                    <div style="text-transform: uppercase;" class="col-md-3 text-bold text-right title_summary">Total Absent(s)</div>
                    <div class="col-md-3 text-center title_description">{{$days_absent or 0}}</div>
                </div>
            </div>
             <div class="divider"></div>
            <div class="row">
                <div class="col-md-12">
                    <div class="col-md-3"></div>
                    <div class="col-md-3"></div>
                    <div style="text-transform: uppercase;" class="col-md-3 text-bold text-right title_summary">Total Late(s)</div>
                    <div class="col-md-3 text-center title_description">{{$total_late or 0}}</div>
                </div>
            </div>
             <div class="divider"></div>
            <div class="row">
                <div class="col-md-12">
                    <div class="col-md-3"></div>
                    <div class="col-md-3"></div>
                    <div style="text-transform: uppercase;" class="col-md-3 text-bold text-right title_summary">Overtime</div>
                    <div class="col-md-3 text-center title_description">{{$total_undertime or 0}}</div>
                </div>
            </div>
             <div class="divider"></div>
            <div class="row">
                <div class="col-md-12">
                    <div class="col-md-3"></div>
                    <div class="col-md-3"></div>
                    <div style="text-transform: uppercase;" class="col-md-3 text-bold text-right title_summary">Undertime</div>
                    <div class="col-md-3 text-center title_description">{{$total_undertime or 0}}</div>
                </div>
            </div>
             <div class="divider"></div>
       </div>
    @else
        <div class="text-center" style="padding: 150px 0">NO DATA</div>
    @endif
</div>


