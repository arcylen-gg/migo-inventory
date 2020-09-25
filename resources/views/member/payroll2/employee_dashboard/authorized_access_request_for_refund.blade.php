@extends('member.payroll2.employee_dashboard.layout')
@section('content')
<ol class="breadcrumb">
    <li class="breadcrumb-item">
        <a href="/employee">Dashboard</a>
    </li>
    <li class="breadcrumb-item active">Request Payment Application</li>
</ol>
<div class="card mb-3">
    <div class="card-header">
        <i class="fa fa-table"></i> Request Payment Application
    </div>
    <div class="card-body">
        <ul class="nav nav-tabs">
            <li class="tabs active" data-type='pending'><a data-toggle="tab" href="#pending" >Pending</a></li>
            <li class="tabs" data-type='approved'><a data-toggle="tab" href="#approved" >Approved</a></li>
            <li class="tabs" data-type='rejected'><a data-toggle="tab" href="#rejected" >Rejected</a></li>
            <li class="tabs" data-type='canceled'><a data-toggle="tab" href="#canceled" >Canceled</a></li>
          </ul>

          <div class="tab-content">
            
          </div>
    </div>
    <div class="card-footer small text-muted"></div>
</div>
@endsection
@section('script')
<script type="text/javascript" src="/assets/employee/js/authorized_access_request_for_refund.js"></script>
@endsection