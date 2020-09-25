@extends('member.layout')
@section('content')
<div class="panel panel-default panel-block panel-title-block">
 
    <input type="hidden" name="_token" id="_token" value="{{csrf_token()}}"/>
    <div class="panel-heading">
        <div>

            <i class="fa fa-calendar"></i>
            <h1>
            <span class="page-title">Timesheet / {{ $company->payroll_company_name }} ({{ $company->payroll_period_category }})</span>
            <small>
            Time Record for Employee (<span class="employee-name"></span>)
            </small>
            </h1>

            <div class="dropdown pull-right">
                <button class="btn btn-custom-danger dropdown-toggle " type="button" data-toggle="dropdown"><i class="fa fa-file-pdf-o"></i>&nbsp;Summary
                <span class="caret"></span></button>
                <ul class="dropdown-menu dropdown-menu-custom">
                    <li><a href="#" class="popup" link="/member/payroll/timesheet/show_summary/late/{{$company->payroll_period_company_id}}" size="lg">Late Summary</a></li>
                    <li><a href="#" class="popup" link="/member/payroll/timesheet/show_summary/under_time/{{$company->payroll_period_company_id}}" size="lg">Under Time Summary</a></li>
                    <li><a href="#" class="popup" link="/member/payroll/timesheet/show_summary/over_time/{{$company->payroll_period_company_id}}" size="lg">Over Time Summary</a></li>
                </ul>
            </div>
            <button class="btn btn-custom-primary pull-right margin-right-20 btn-mark-ready" data-content="{{$payroll_period_company_id}}" type="button" {{$company->payroll_period_status != 'pending' ? 'disabled' : ''}}>{{$company->payroll_period_status != 'pending' ? 'Ready' : 'Mark as Ready'}}</button>
            <input type="hidden" name="" value="{{$company->payroll_period_id}}" id="payroll_period_id">
        </div>
    </div>
</div>
<div class="panel panel-default panel-block panel-title-block">
    <div class="panel-body">
        <div class="search-filter-box">
            <div class="col-md-5" style="padding: 10px">
                <span style="font-size: 18px;">{{date('F d, Y', strtotime($company->payroll_period_start)).' to '.date('F d, Y', strtotime($company->payroll_period_end))}}</span>
            </div>
            <div class="col-md-4 col-md-offset-3" style="padding: 10px">
                <div class="input-group">
                    <span style="background-color: #fff; cursor: pointer;" class="input-group-addon" id="basic-addon1"><i class="fa fa-search"></i></span>
                    <select class="form-control choose-employee">
                        @foreach($_employee as $employee)
                        <option value="{{ $employee->payroll_employee_id }}" {{ $current_employee->payroll_employee_id == $employee->payroll_employee_id ? 'selected="selected"' : '' }}>{{ $employee->payroll_employee_display_name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
        </div>
        <div class=" panel-customer load-data">
            <form class="payroll-form" action="return false;">
                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                <div class="load-timesheet"></div>
            </form>
            <div class="padding-10 text-center"></div>
        </div>
    </div>
</div>
@endsection
@section('css')
<link rel="stylesheet" type="text/css" href="/assets/member/payroll/css/timesheet.css">
<link rel="stylesheet" type="text/css" href="/assets/external/jquery.timeentry.package-2.0.1/jquery.timeentry.css">
@endsection
@section('script')

<script type="text/javascript" src="/assets/member/payroll/js/timesheet.js"></script>
<script type="text/javascript" src="/assets/external/jquery.timeentry.package-2.0.1/jquery.plugin.min.js"></script>
<script type="text/javascript" src="/assets/external/jquery.timeentry.package-2.0.1/jquery.timeentry.min.js"></script>
@endsection