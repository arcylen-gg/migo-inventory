@extends('member.layout')
@section('content')
<form method="post">
    <input type="hidden" class="payroll-process-id" value="{{ $period_company_id }}">
    <div class="panel panel-default panel-block panel-title-block">
        <input type="hidden" name="_token" id="_token" value="{{csrf_token()}}"/>
        <div class="panel-heading">
            <div>
                <i class="fa fa-calendar"></i>
                <h1>
                <span class="page-title">Payroll {{ ucfirst($step) }} / {{$company->payroll_company_name}} ({{$company->payroll_period_category}})</span>
                <small style="font-size: 14px; color: gray;">
                     {{ payroll_date_format($company->payroll_period_start) }} to  {{ payroll_date_format($company->payroll_period_end) }} ({{ $company->month_contribution }} - {{ code_to_word($company->period_count) }})
                </small>
                </h1>

                <div class="dropdown pull-right">
                    @if($step == "process")
                        <button type="button" onclick="location.href='/member/payroll/company_timesheet2/{{ $period_company_id }}'" class="btn btn-default">&laquo; Back</button>
                    @else
                        <button type="button" onclick="location.href='/member/payroll/time_keeping'" class="btn btn-default">&laquo; Back</button>
                    @endif
                </div>
                
                <input type="hidden" name="" value="{{$company->payroll_period_id}}" id="payroll_period_id">
              
            </div>
        </div>
    </div>

    <div class="panel panel-default panel-block panel-title-block">
        <div class="panel-heading">
            <div class="text-center" style="font-size: 20px;">
                Please check carefully before proceeding. <br>
                <button type="submit" class="btn btn-primary"><i class="fa fa-star"></i> Click <b>HERE</b> to <b>CONFIRM PAYROLL {{ strtoupper($step) }}</b>!</button>
            </div>
        </div>
    </div>



    <div class="panel panel-default panel-block panel-title-block panel-gray ">
        <div class="tab-content codes_container">
            <div id="all" class="tab-pane fade in active">
                <div class="form-group order-tags"></div>
                    <div class="clearfix">
                        <div class="col-md-12">
                            <!-- LOAD TABLE EMPLOYEE SUMMARY -->
                            <div class="table-responsive load-table-employee-summary"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</form>
@endsection

@section('script')
<script type="text/javascript" src="/assets/member/payroll/js/payroll_process.js"></script>
@endsection