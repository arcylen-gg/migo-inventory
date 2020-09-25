@extends('member.layout')
@section('css')
@endsection
@section('content')
<div class="panel panel-default panel-block panel-title-block" id="top">
  <div class="panel-heading">
    <div>
      <i class="fa fa-calculator"></i>
      <h1>
      <span class="page-title">Payroll Approved</span>
      <small>
      Payroll Result
      </small>
      </h1>
      <input type="hidden" name="_token" value="{{csrf_token()}}" id="_token">
    </div>
  </div>
</div>
<div class="panel panel-default panel-block">
  <div class="panel-body">
    @foreach($_period as $period)
    <div class="custom-panel">
      <div class="custom-panel-header cursor-pointer">
        {{$period['period']->payroll_period_category}}&nbsp;({{date('F d, Y', strtotime($period['period']->payroll_period_start))}} to {{date('F d, Y', strtotime($period['period']->payroll_period_end))}})
      </div>
      <div class="width-100 display-table">
        <div class="triangle-top-right"></div>
        <div class="custom-panel-body">
          <div class="custom-panel-child display-none">
            <ul class="list-group">
              @foreach($period['_company'] as $company)
              <li class="list-group-item">
                  {{$company->payroll_company_name}}
                  <a href="/member/payroll/payroll_approved_view/payroll_approved_company/{{$company->payroll_period_company_id}}" class="btn btn-custom-primary btn-sm pull-right"><i class="fa fa-search"></i>&nbsp;View</a>
                  <a href="/member/payroll/modal_generate_bank/{{$company->payroll_period_company_id}}" class="btn btn-custom-green-white btn-sm pull-right margin-right-10" link=""><i class="fa fa-university""></i>&nbsp;Banking</a>
                  <a class="btn btn-custom-red-white btn-sm pull-right margin-right-10" href="/member/payroll/payroll_approved_view/genereate_payslip/{{$company->payroll_period_company_id}}"><i class="fa fa-file-pdf-o""></i>&nbsp;Payslip</a>
              </li>
              @endforeach
            </ul>
          </div>
        </div>
      </div>
    </div>
    @endforeach
  </div>
</div>
@endsection
@section('script')
<script type="text/javascript" src="/assets/member/js/payroll/payroll_process.js"></script>
@endsection