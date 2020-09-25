@extends('member.layout')
@section('content')
<div class="panel panel-default panel-block panel-title-block" id="top">
    <div class="panel-heading">
        <div>
            <i class="fa fa-tags"></i>
            <h1>
                <span class="page-title">Admin Dashboard &raquo; Employee Approver</span>
                <small>
                select employee.
                </small>
            </h1>
           <a href="/member/payroll/payroll_admin_dashboard/create_approver"><button class="btn btn-primary pull-right">Create Approver</button></a>
        </div>
    </div>
</div>

<div class="panel panel-default panel-block panel-title-block load-data">
   
      <ul class="nav nav-tabs">
        <li  class="active"><a data-toggle="tab" href="#ot-approver"><i class="fa fa-clock-o" aria-hidden="true" style="font-size: 23px; vertical-align: middle;"></i>    Over Time Approver</a></li>
        <li><a data-toggle="tab" href="#leave-approver"><i class="fa fa-calendar" aria-hidden="true" style="font-size: 19px; vertical-align: middle;"></i>    Leave Approver</a></li>
        
        <li><a data-toggle="tab" href="#rfp-approver"><i class="fa fa-money" aria-hidden="true" style="font-size: 23px; vertical-align: middle;"></i>    RFP Approver</a></li>
      </ul>

      <div class="tab-content tab-pane-div padding-top-10">

         <div id="leave-approver" class="tab-pane fade in active">
           <table class="table table-bordered table-condensed table-hover">
             <thead>
                <th class="text-center">Employee Name</th>
                <th class="text-center">Approver Level</th>
                <th class="text-center">Action</th>
             </thead>
             <tbody>
                @foreach($overtime_approver as $approver)
                <tr>
                  <td class="text-center">{{  $approver->payroll_employee_display_name  }}</td>
                  <td class="text-center">{{  $approver->payroll_approver_employee_level  }}</td>
                  <td class="text-center">
                    <div class="dropdown">
                      <button class="btn btn-custom-white dropdown-toggle btn-xs" type="button" data-toggle="dropdown">Action
                      <span class="caret"></span></button>
                      <ul class="dropdown-menu dropdown-menu-custom">
                        <li>
                          <a href="#" class="popup" link="/member/payroll/payroll_admin_dashboard/edit_approver/{{$approver->payroll_approver_employee_id}}?approver_type=overtime" ><i class="fa fa-pencil"></i>&nbsp;Edit</a>
                        </li>
                        <li>
                          <a href="#" class="popup" link="/member/payroll/payroll_admin_dashboard/delete_approver/{{$approver->payroll_approver_employee_id}}?approver_type=overtime" size="sm"><i class="fa fa-trash-o"></i>&nbsp;Delete</a>
                        </li>
                      </ul>
                    </div>
                  </td>
                </tr>
                @endforeach
             </tbody>
           </table>
         </div>

         <div id="ot-approver" class="tab-pane fade">
           <table class="table table-bordered table-condensed table-hover">
             <thead>
                <th class="text-center">Employee Name</th>
                <th class="text-center">Approver Level</th>
                <th class="text-center">Action</th>
             </thead>
             <tbody>
                @foreach($leave_approver as $approver)
                <tr>
                  <td class="text-center">{{  $approver->payroll_employee_display_name  }}</td>
                  <td class="text-center">{{  $approver->payroll_approver_employee_level  }}</td>
                  <td class="text-center">
                    <div class="dropdown">
                      <button class="btn btn-custom-white dropdown-toggle btn-xs" type="button" data-toggle="dropdown">Action
                      <span class="caret"></span></button>
                      <ul class="dropdown-menu dropdown-menu-custom">
                        <li>
                          <a href="#" class="popup" link="/member/payroll/payroll_admin_dashboard/edit_approver/{{$approver->payroll_approver_employee_id}}?approver_type=leave" ><i class="fa fa-pencil"></i>&nbsp;Edit</a>
                        </li>
                        <li>
                          <a href="#" class="popup" link="/member/payroll/payroll_admin_dashboard/delete_approver/{{$approver->payroll_approver_employee_id}}?approver_type=leave" size="sm"><i class="fa fa-trash-o"></i>&nbsp;Delete</a>
                        </li>
                      </ul>
                    </div>
                  </td>
                </tr>
                @endforeach
             </tbody>
           </table>
         </div>

         <div id="rfp-approver" class="tab-pane fade">
           <table class="table table-bordered table-condensed table-hover">
             <thead>
                <th class="text-center">Employee Name</th>
                <th class="text-center">Approver Level</th>
                <th class="text-center">Action</th>
             </thead>
             <tbody>
                @foreach($rfp_approver as $approver)
                <tr>
                  <td class="text-center">{{  $approver->payroll_employee_display_name  }}</td>
                  <td class="text-center">{{  $approver->payroll_approver_employee_level  }}</td>
                  <td class="text-center">
                    <div class="dropdown">
                      <button class="btn btn-custom-white dropdown-toggle btn-xs" type="button" data-toggle="dropdown">Action
                      <span class="caret"></span></button>
                      <ul class="dropdown-menu dropdown-menu-custom">
                        <li>
                          <a href="#" class="popup" link="/member/payroll/payroll_admin_dashboard/edit_approver/{{$approver->payroll_approver_employee_id}}?approver_type=rfp" ><i class="fa fa-pencil"></i>&nbsp;Edit</a>
                        </li>
                        <li>
                          <a href="#" class="popup" link="/member/payroll/payroll_admin_dashboard/delete_approver/{{$approver->payroll_approver_employee_id}}?approver_type=rfp" size="sm"><i class="fa fa-trash-o"></i>&nbsp;Delete</a>
                        </li>
                      </ul>
                    </div>
                  </td>
                </tr>
                @endforeach
             </tbody>
           </table>
         </div>
      </div>
</div>
@endsection('content')
@section('script')
<script type="text/javascript"></script>
<script type="text/javascript" src="/assets/js/payroll_employee_approver.js"></script>
@endsection('script')