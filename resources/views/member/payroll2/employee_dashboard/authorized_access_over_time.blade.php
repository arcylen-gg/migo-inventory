@extends('member.payroll2.employee_dashboard.layout')
@section('content')
<ol class="breadcrumb">
    <li class="breadcrumb-item">
        <a href="/employee">Dashboard</a>
    </li>
    <li class="breadcrumb-item active">{{ $page }}</li>
</ol>
<!-- Example DataTables Card-->
<div class="card mb-3">
    <div class="card-header">
        <i class="fa fa-table"></i> Overtime Application
    </div>
    <div class="card-body">

        <ul class="nav nav-tabs">
          <li class="active"><a data-toggle="tab" href="#pending">Pending</a></li>
          <li><a data-toggle="tab" href="#approved">Approved</a></li>
          <li><a data-toggle="tab" href="#rejected">Rejected</a></li>
          <li><a data-toggle="tab" href="#canceled">Canceled</a></li>
        </ul>

        <div class="tab-content">
          <div id="pending" class="tab-pane fade in active show">
            @if($employee_approver_info != null && count($_request_pending) > 0)
            <div class="table-responsive">
                <table class="table table-bordered" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th class="text-center">Name</th>
                            <th class="text-center">Date of Application</th>
                            <th class="text-center">Overtime Type</th>
                            <th class="text-center">Overtime From</th>
                            <th class="text-center">Overtime To</th>
                            <th class="text-center">No. of hours</th>
                            <th class="text-center">Status</th>
                            <th class="text-center">Status Level</th>
                            <th class="text-center">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($_request_pending as $request)
                        <tr>
                            <td class="text-center">{{ $request->payroll_employee_first_name }} {{ $request->payroll_employee_last_name }}</td>
                            <td class="text-center">{{ date('F d, Y',strtotime($request->payroll_request_overtime_date)) }}</td>
                            <td class="text-center">{{ $request->payroll_request_overtime_type }}</td>
                            <td class="text-center">{{ date('h:i A',strtotime($request->payroll_request_overtime_time_in) )}}</td>
                            <td class="text-center">{{ date('h:i A',strtotime($request->payroll_request_overtime_time_out)) }}</td>
                            <td class="text-center">{{ $request->payroll_request_overtime_total_hours }}</td>
                            <td class="text-center">{{ $request->payroll_request_overtime_status }}</td>
                            <td class="text-center">{{ $request->payroll_request_overtime_status_level }}</td>
                            <td class="text-center">
                                <button class="btn btn-link dropdown-toggle" type="button" id="menu-dropdown" data-toggle="dropdown">Action
                                  <span class="caret"></span></button>
                                  <ul class="dropdown-menu" role="menu" aria-labelledby="menu-dropdown">
                                    <li style="padding-left: 10px;" role="presentation"><a role="menuitem" class="popup" link="/authorized_access_over_time/view_overtime_request/{{$request->payroll_request_overtime_id}}" size="lg" href="#"><i class="fa fa-search" aria-hidden="true"></i> &nbsp; View</a></li>
                                    <li style="padding-left: 10px;" role="presentation"><a role="menuitem" class="popup" link="/authorized_access_over_time/approve_overtime_request/{{$request->payroll_request_overtime_id}}" size="sm" href="#"><i class="fa fa-check" aria-hidden="true"></i> &nbsp; Approve</a></li>
                                    <li style="padding-left: 10px;" role="presentation"><a role="menuitem" class="popup" link="/authorized_access_over_time/reject_overtime_request/{{$request->payroll_request_overtime_id}}" size="sm" href="#"><i class="fa fa-ban" aria-hidden="true"></i> &nbsp; Reject</a></li>
                                  </ul>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
                <div class="container">
                    {!! $_request_pending->render() !!}
                </div>
            </div>
            @else
            <div style="margin: 50px; text-align: center;">
                <h2>No Data</h2>
            </div>
            @endif
          </div>

          <div id="approved" class="tab-pane fade">
            @if($employee_approver_info != null && count($_request_approved) > 0)
            <div class="table-responsive">
                <table class="table table-bordered" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th class="text-center">Name</th>
                            <th class="text-center">Date of Application</th>
                            <th class="text-center">Overtime Type</th>
                            <th class="text-center">Overtime From</th>
                            <th class="text-center">Overtime To</th>
                            <th class="text-center">No. of hours</th>
                            <th class="text-center">Status</th>
                            <th class="text-center">Status Level</th>
                            <th class="text-center">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($_request_approved as $request)
                        <tr>
                            <td class="text-center">{{ $request->payroll_employee_first_name }} {{ $request->payroll_employee_last_name }}</td>
                            <td class="text-center">{{ date('F d, Y',strtotime($request->payroll_request_overtime_date)) }}</td>
                            <td class="text-center">{{ $request->payroll_request_overtime_type }}</td>
                            <td class="text-center">{{ date('h:i A',strtotime($request->payroll_request_overtime_time_in) )}}</td>
                            <td class="text-center">{{ date('h:i A',strtotime($request->payroll_request_overtime_time_out)) }}</td>
                            <td class="text-center">{{ $request->payroll_request_overtime_total_hours }}</td>
                            <td class="text-center">{{ $request->payroll_request_overtime_status }}</td>
                            <td class="text-center">{{ $request->payroll_request_overtime_status_level }}</td>
                            <td class="text-center">
                                <button class="btn btn-link dropdown-toggle" type="button" id="menu-dropdown" data-toggle="dropdown">Action
                                  <span class="caret"></span></button>
                                  <ul class="dropdown-menu" role="menu" aria-labelledby="menu-dropdown">
                                    <li style="padding-left: 10px;" role="presentation"><a role="menuitem" class="popup" link="/authorized_access_over_time/view_overtime_request/{{$request->payroll_request_overtime_id}}" size="lg" href="#"><i class="fa fa-search" aria-hidden="true"></i> &nbsp; View</a></li>
                                    <li style="padding-left: 10px;" role="presentation"><a role="menuitem" class="popup" link="/authorized_access_over_time/approve_overtime_request/{{$request->payroll_request_overtime_id}}" size="sm" href="#"><i class="fa fa-check" aria-hidden="true"></i> &nbsp; Approve</a></li>
                                    <li style="padding-left: 10px;" role="presentation"><a role="menuitem" class="popup" link="/authorized_access_over_time/reject_overtime_request/{{$request->payroll_request_overtime_id}}" size="sm" href="#"><i class="fa fa-ban" aria-hidden="true"></i> &nbsp; Reject</a></li>
                                  </ul>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
                <div class="container">
                    {!! $_request_approved->render() !!}
                </div>
            </div>
            @else
            <div style="margin: 50px; text-align: center;">
                <h2>No Data</h2>
            </div>
            @endif
          </div>
          <div id="rejected" class="tab-pane fade">
            @if($employee_approver_info != null && count($_request_rejected) > 0)
            <div class="table-responsive">
                <table class="table table-bordered" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th class="text-center">Name</th>
                            <th class="text-center">Date of Application</th>
                            <th class="text-center">Overtime Type</th>
                            <th class="text-center">Overtime From</th>
                            <th class="text-center">Overtime To</th>
                            <th class="text-center">No. of hours</th>
                            <th class="text-center">Status</th>
                            <th class="text-center">Status Level</th>
                            <th class="text-center">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($_request_rejected as $request)
                        <tr>
                            <td class="text-center">{{ $request->payroll_employee_first_name }} {{ $request->payroll_employee_last_name }}</td>
                            <td class="text-center">{{ date('F d, Y',strtotime($request->payroll_request_overtime_date)) }}</td>
                            <td class="text-center">{{ $request->payroll_request_overtime_type }}</td>
                            <td class="text-center">{{ date('h:i A',strtotime($request->payroll_request_overtime_time_in) )}}</td>
                            <td class="text-center">{{ date('h:i A',strtotime($request->payroll_request_overtime_time_out)) }}</td>
                            <td class="text-center">{{ $request->payroll_request_overtime_total_hours }}</td>
                            <td class="text-center">{{ $request->payroll_request_overtime_status }}</td>
                            <td class="text-center">{{ $request->payroll_request_overtime_status_level }}</td>
                            <td class="text-center">
                                <button class="btn btn-link dropdown-toggle" type="button" id="menu-dropdown" data-toggle="dropdown">Action
                                  <span class="caret"></span></button>
                                  <ul class="dropdown-menu" role="menu" aria-labelledby="menu-dropdown">
                                    <li style="padding-left: 10px;" role="presentation"><a role="menuitem" class="popup" link="/authorized_access_over_time/view_overtime_request/{{$request->payroll_request_overtime_id}}" size="lg" href="#"><i class="fa fa-search" aria-hidden="true"></i> &nbsp; View</a></li>
                                    <li style="padding-left: 10px;" role="presentation"><a role="menuitem" class="popup" link="/authorized_access_over_time/approve_overtime_request/{{$request->payroll_request_overtime_id}}" size="sm" href="#"><i class="fa fa-check" aria-hidden="true"></i> &nbsp; Approve</a></li>
                                    <li style="padding-left: 10px;" role="presentation"><a role="menuitem" class="popup" link="/authorized_access_over_time/reject_overtime_request/{{$request->payroll_request_overtime_id}}" size="sm" href="#"><i class="fa fa-ban" aria-hidden="true"></i> &nbsp; Reject</a></li>
                                  </ul>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
                <div class="container">
                    {!! $_request_rejected->render() !!}
                </div>
            </div>
            @else
            <div style="margin: 50px; text-align: center;">
                <h2>No Data</h2>
            </div>
            @endif
          </div>
          <div id="canceled" class="tab-pane fade">
            @if($employee_approver_info != null && count($_request_canceled) > 0)
            <div class="table-responsive">
                <table class="table table-bordered" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th class="text-center">Name</th>
                            <th class="text-center">Date of Application</th>
                            <th class="text-center">Overtime Type</th>
                            <th class="text-center">Overtime From</th>
                            <th class="text-center">Overtime To</th>
                            <th class="text-center">No. of hours</th>
                            <th class="text-center">Status</th>
                            <th class="text-center">Status Level</th>
                            <th class="text-center">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($_request_canceled as $request)
                        <tr>
                            <td class="text-center">{{ $request->payroll_employee_first_name }} {{ $request->payroll_employee_last_name }}</td>
                            <td class="text-center">{{ date('F d, Y',strtotime($request->payroll_request_overtime_date)) }}</td>
                            <td class="text-center">{{ $request->payroll_request_overtime_type }}</td>
                            <td class="text-center">{{ date('h:i A',strtotime($request->payroll_request_overtime_time_in) )}}</td>
                            <td class="text-center">{{ date('h:i A',strtotime($request->payroll_request_overtime_time_out)) }}</td>
                            <td class="text-center">{{ $request->payroll_request_overtime_total_hours }}</td>
                            <td class="text-center">{{ $request->payroll_request_overtime_status }}</td>
                            <td class="text-center">{{ $request->payroll_request_overtime_status_level }}</td>
                            <td class="text-center">
                                <button class="btn btn-link dropdown-toggle" type="button" id="menu-dropdown" data-toggle="dropdown">Action
                                  <span class="caret"></span></button>
                                  <ul class="dropdown-menu" role="menu" aria-labelledby="menu-dropdown">
                                    <li style="padding-left: 10px;" role="presentation"><a role="menuitem" class="popup" link="/authorized_access_over_time/view_overtime_request/{{$request->payroll_request_overtime_id}}" size="lg" href="#"><i class="fa fa-search" aria-hidden="true"></i> &nbsp; View</a></li>
                                    <li style="padding-left: 10px;" role="presentation"><a role="menuitem" class="popup" link="/authorized_access_over_time/approve_overtime_request/{{$request->payroll_request_overtime_id}}" size="sm" href="#"><i class="fa fa-check" aria-hidden="true"></i> &nbsp; Approve</a></li>
                                    <li style="padding-left: 10px;" role="presentation"><a role="menuitem" class="popup" link="/authorized_access_over_time/reject_overtime_request/{{$request->payroll_request_overtime_id}}" size="sm" href="#"><i class="fa fa-ban" aria-hidden="true"></i> &nbsp; Reject</a></li>
                                  </ul>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
                <div class="container">
                    {!! $_request_canceled->render() !!}
                </div>
            </div>
            @else
            <div style="margin: 50px; text-align: center;">
                <h2>No Data</h2>
            </div>
            @endif
          </div>
        </div>


    </div>
    <div class="card-footer small text-muted"></div>
</div>
</div>
<!-- /.container-fluid-->
<!-- /.content-wrapper-->
@endsection;