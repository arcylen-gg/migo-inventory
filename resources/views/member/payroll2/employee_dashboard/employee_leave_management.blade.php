@extends('member.payroll2.employee_dashboard.layout')
@section('content')
<ol class="breadcrumb">
    <li class="breadcrumb-item">
        <a href="/employee">Dashboard</a>
    </li>
    <li class="breadcrumb-item active">{{ $page }}</li>
     <a href="javascript:" onClick="action_load_link_to_modal('/employee_leave_application', 'lg')"><button class="btn btn-primary pull-right">Create Leave</button></a>
</ol>
<div class="panel panel-default panel-block panel-title-block panel-gray ">
           <input type="hidden" name="_token" id="_token" value="{{csrf_token()}}">
    
</div>

<script type="text/javascript" src="/assets/member/js/payroll/payroll_timekeeping.js?version=10"></script>


<div class="card mb-3">
    <div class="card-header">
        <i class="fa fa-table"></i> Leave Application
    </div>
    <div class="card-body">
        <ul class="nav nav-tabs">
            <li class="tabs active pending" data-type='pending'><a data-toggle="tab" href="#pending" >Pending</a></li>
            <li class="tabs approved" data-type='approved'><a data-toggle="tab" href="#approved" >Approved</a></li>
            <li class="tabs rejected" data-type='rejected'><a data-toggle="tab" href="#rejected" >Rejected</a></li>
            <li class="tabs canceled" data-type='canceled'><a data-toggle="tab" href="#canceled" >Canceled</a></li>
          </ul>
            <br>
                  <table class="table table-bordered" style="font-size:12px;">
            <thead>
              <tr>
                <th style="text-align: center;">Date Request</th>
                <th style="text-align: center;">Leave Type</th>
                <th style="text-align: center;">Leave Date</th>
                <th style="text-align: center;">Leave Hours</th>
                <th style="text-align: center;">Reliever</th>
                <th style="text-align: center;">Status</th>
                <th style="text-align: center;">Status Level</th>
                <th style="text-align: center;">Action</th>
              </tr>
            </thead>
            <tbody class="tbl-tag">
                                
            </tbody>
          </table>

    </div>
    <div class="card-footer small text-muted"></div>
</div>

</div>
<!-- /.container-fluid-->
<!-- /.content-wrapper-->
<script type="text/javascript" src="/assets/member/js/payroll/employee_leave_management.js"></script>
@endsection;