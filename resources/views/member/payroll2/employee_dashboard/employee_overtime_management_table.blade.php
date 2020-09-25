<div class="table-responsive">
    @if(count($_overtime_request) > 0)
    <table class="table table-bordered" width="100%" cellspacing="0">
        <thead>
            <tr>
                <th class="text-center" >Date Request</th>
                <th class="text-center" >Overtime Type</th>
                <th class="text-center" >Overtime Time In</th>
                <th class="text-center" >Overtime Time Out</th>
                <th class="text-center" >Overtime Total Hours</th>
                <th class="text-center" >Status</th>
                <th class="text-center" >Status level</th>
                <th class="text-center" >Action</th>
            </tr>
        </thead>
        <tbody>
            @foreach($_overtime_request as $overtime_request)
            <tr>
                <td class="text-center">{{ date('F d, Y',strtotime($overtime_request->payroll_request_overtime_date)) }}</td>
                <td class="text-center">{{ $overtime_request->payroll_request_overtime_type }}</td>
                <td class="text-center">{{ date('h:i A',strtotime($overtime_request->payroll_request_overtime_time_in)) }}</td>
                <td class="text-center">{{ date('h:i A',strtotime($overtime_request->payroll_request_overtime_time_out)) }}</td>
                <td class="text-center">{{ date('H:i',strtotime($overtime_request->payroll_request_overtime_total_hours)) }}</td>
                <td class="text-center">{{ $overtime_request->payroll_request_overtime_status }}</td>
                <td class="text-center">{{ $overtime_request->payroll_request_overtime_status_level }}</td>
                <td class="text-center">
                    <div class="dropdown">
                      <button class="btn btn-link dropdown-toggle" type="button" id="menu-drop-down" data-toggle="dropdown">Action
                      <span class="caret"></span></button>
                      <ul class="dropdown-menu" role="menu" aria-labelledby="menu-drop-down">
                        <li style="padding-left: 10px;" role="presentation" class="popup" link='/employee_request_overtime_view/{{$overtime_request->payroll_request_overtime_id}}' size='lg'><a role="menuitem" tabindex="-1" href="#"><i class="fa fa-search" aria-hidden="true"></i> &nbsp; View</a></li>
                        <li style="padding-left: 10px;" role="presentation" class="popup" link='/employee_request_overtime_cancel/{{$overtime_request->payroll_request_overtime_id}}' size='sm'><a role="menuitem" tabindex="-1" href="#"><i class="fa fa-ban" aria-hidden="true"></i> &nbsp; Cancel</a></li>
                        <li>
                            &nbsp;&nbsp;<a role="form" target="_blank" tabindex="-1" href="/employee_request_overtime_export_pdf/{{$overtime_request->payroll_request_overtime_id}}"><i class="fa fa-file-pdf-o" aria-hidden="true"></i>&nbsp;View PDF Form</a>
                        </li>
                      </ul>
                    </div>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
    @else
    <h2 style="margin: 50px; text-align: center;">No Data</h2>
    @endif
  </div>