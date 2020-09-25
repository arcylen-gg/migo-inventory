@extends('member.layout')
@section('css')
@endsection

@section('content')
<div class="panel panel-default panel-block panel-title-block" id="top">
  <div class="panel-heading">
    <div>
      <i class="fa fa-calculator"></i>
      <h1>
      <span class="page-title">Payroll Approved ({{$period->payroll_company_name}})</span>
      <small>
      {{$period->payroll_period_category}}&nbsp;({{date('F d, Y', strtotime($period->payroll_period_start))}} to {{date('F d, Y', strtotime($period->payroll_period_end))}})
      </small>
      </h1>
      <input type="hidden" name="_token" value="{{csrf_token()}}" id="_token">
    </div>
  </div>
</div>
<div class="panel panel-default panel-block">
  <div class="panel-body">
  <table class="table table-bordered table-condensed">
    <thead>
      <tr>
        <th class="text-left">Employee name</th>
        <th class="text-center">Gross Salary</th>
        <th class="text-center">Deductions</th>
        <th class="text-center">Net Salary</th>
        <th class="text-center">Action</th>
      </tr>
    </thead>
    <tbody>
      @foreach($_record as $record)
      <tr>
        <td>
          {{$record['payroll_employee_display_name']}}
        </td>
        <td class="text-right">
          {{number_format($record['total_gross'] , 2)}}
        </td>
        <td class="text-right">
          {{number_format($record['total_deduction'] , 2)}}
        </td>
        <td class="text-right">
          {{number_format($record['total_net'] , 2)}}
        </td>
        <td class="text-center">
           <button class="btn btn-custom-white btn-xs popup" link="/member/payroll/payroll_approved_view/payroll_record_by_id/{{$record['payroll_record_id']}}" size="lg" type="button"><i class="fa fa-search"></i>&nbsp;View</button>
        </td>
      </tr>
      @endforeach
    </tbody>
    <tbody>
      <tr>
        <td class="text-right">
          <b>Total</b>
        </td>
        <td class="text-right">
          <b>{{number_format($total_gross,2)}}</b>
        </td>
        <td class="text-right">
          <b>{{number_format($total_deduction,2)}}</b>
        </td>
        <td class="text-right">
          <b>{{number_format($total_net,2)}}</b>
        </td>
        <td class="text-right">
          <b></b>
        </td>
      </tr>
    </tbody>
  </table>
  </div>
</div>
@endsection

@section('script')
@endsection