@extends('member.layout')
@section('content')
<input type="hidden" class="payroll-period-id" value="{{ $payroll_period_id }}"/>
<div class="panel panel-default panel-block panel-title-block">
    <input type="hidden" name="_token" id="_token" value="{{csrf_token()}}"/>
    <div class="panel-heading">
        <div>
            <i class="fa fa-calendar"></i>
            <h1>
            <span class="page-title">Timesheet / {{$company->payroll_company_name}} ({{$company->payroll_period_category}})</span>
            <small style="font-size: 14px; color: gray;">
                 {{ payroll_date_format($company->payroll_period_start) }} to  {{ payroll_date_format($company->payroll_period_end) }} ({{ $company->month_contribution }} - {{ code_to_word($company->period_count) }})
            </small>
            </h1>

            <div class="dropdown pull-right">
                <button onclick="location.href='/member/payroll/time_keeping'" class="btn btn-default">&laquo; Back</button>
                <button onclick="location.href='/member/payroll/process_payroll/{{$company->payroll_period_company_id}}?step=process'" class="btn btn-primary"><i class="fa fa-star"></i> Payroll Process</button>
                <button class="btn btn-custom-danger dropdown-toggle " type="button" data-toggle="dropdown"><i class="fa fa-file-pdf-o"></i>&nbsp;Summary
                <span class="caret"></span></button>
                <ul class="dropdown-menu dropdown-menu-custom">
                    <li><a href="#" class="popup" link="/member/payroll/timesheet/show_summary/late/{{$company->payroll_period_company_id}}" size="lg">Late Summary</a></li>
                    <li><a href="#" class="popup" link="/member/payroll/timesheet/show_summary/under_time/{{$company->payroll_period_company_id}}" size="lg">Under Time Summary</a></li>
                    <li><a href="#" class="popup" link="/member/payroll/timesheet/show_summary/over_time/{{$company->payroll_period_company_id}}" size="lg">Over Time Summary</a></li>
                </ul>
            </div>
            <input type="hidden" name="" value="{{$company->payroll_period_id}}" id="payroll_period_id">
        </div>
    </div>
</div>

<div class="panel panel-default panel-block panel-title-block panel-gray ">
    <ul class="nav nav-tabs">
        <li class="active change-tab pending-tab cursor-pointer" mode="pending"><a class="cursor-pointer"><i class="fa fa-check"></i> Pending</a></li>
        <li class="cursor-pointer change-tab approve-tab" mode="approved"><a class="cursor-pointer"><i class="fa fa-star"></i> Ready</a></li>
    </ul>
    <div class="search-filter-box">
        <div class="col-md-4" style="padding: 10px">
            <!-- BRANCH LIST -->
            <select class="form-control item_type company-change-event">
                <option value="0">All Branches / Sub-Company</option>
                @foreach($_company as $company_info)
                <option {{ $company_info->payroll_company_id == $company->payroll_company_id ? 'selected' : '' }} value="{{ $company_info->payroll_company_id }}">{{ $company_info->payroll_company_name }}</option>
                @endforeach
            </select>
        </div>
        <div class="col-md-4 col-md-offset-4" style="padding: 10px">
            <div class="input-group">
                <span style="background-color: #fff; cursor: pointer;" class="input-group-addon" id="basic-addon1"><i class="fa fa-search"></i></span>
                <input type="text" class="form-control search-employee-name" placeholder="Search by employee name / number" aria-describedby="basic-addon1">
            </div>
        </div>  
    </div>
    <div class="tab-content codes_container">
        <div id="all" class="tab-pane fade in active">
            <div class="form-group order-tags"></div>
                <div class="clearfix">
                    <div class="col-md-12">
                        <div class="table-responsive load-table-employee-list">
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div id="approved" class="tab-pane fade in">
            <div class="form-group order-tags"></div>
                <div class="clearfix">
                    <div class="col-md-12">
                        <div class="table-responsive">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection

@section('css')
<link rel="stylesheet" type="text/css" href="/assets/member/payroll/css/timesheet.css">
<link rel="stylesheet" type="text/css" href="/assets/external/jquery.timeentry.package-2.0.1/jquery.timeentry.css">
@endsection

@section('script')
<script type="text/javascript" src="/assets/member/payroll/js/timesheet_employee_list.js?v=2.0"></script>
<script type="text/javascript" src="/assets/external/jquery.timeentry.package-2.0.1/jquery.plugin.min.js"></script>
<script type="text/javascript" src="/assets/external/jquery.timeentry.package-2.0.1/jquery.timeentry.min.js"></script>
@endsection