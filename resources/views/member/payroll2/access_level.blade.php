@extends('member.layout')
@section('content')
<div class="panel panel-default panel-block panel-title-block" id="top">
    <div class="panel-heading">
        <div>
            <i class="fa fa-tags"></i>
            <h1>
                <span class="page-title">Admin Dashboard &raquo; Access Level</span>
                <small>
                select employee.
                </small>
            </h1>
           <a href="/member/payroll/payroll_admin_dashboard/add_access_group"><button class="btn btn-primary pull-right">Create Access Group</button></a>
        </div>
    </div>
</div>

<div class="panel panel-default panel-block panel-title-block load-data">
   
      <ul class="nav nav-tabs">
        <li  class="active"><a data-toggle="tab" href="#ot-approver"><i class="fa fa-clock-o" aria-hidden="true" style="font-size: 23px; vertical-align: middle;"></i>    User Access</a></li>
        <li><a data-toggle="tab" href="#leave-approver"><i class="fa fa-calendar" aria-hidden="true" style="font-size: 19px; vertical-align: middle;"></i>    Permissions</a></li>
      </ul>

      <div class="tab-content tab-pane-div padding-top-10">

         <div id="leave-approver" class="tab-pane fade in active">
           <table class="table table-bordered table-condensed table-hover">
             <thead>
                <th class="text-center">Email Address</th>
                <th class="text-center">TIN Number</th>
                <th class="text-center">Permissions</th>
                <th class="text-center">Action</th>
             </thead>
             <tbody>
                @foreach($employee_info as $employee)
                <tr>
                  <td class="text-center">{{  $employee->payroll_employee_email  }}</td>
                  <td class="text-center">{{  $employee->payroll_employee_tin  }}</td>
                  <td class="text-center"></td>
                  <td class="text-center">
                    <div class="dropdown">
                      <button class="btn btn-custom-white dropdown-toggle btn-xs" type="button" data-toggle="dropdown">Action
                      <span class="caret"></span></button>
                      <ul class="dropdown-menu dropdown-menu-custom">
                        <li>
                          <a href="#" class="popup" {{-- link="/member/payroll/payroll_admin_dashboard/add_access_group" --}}"><i class="fa fa-pencil"></i>&nbsp;Edit</a
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