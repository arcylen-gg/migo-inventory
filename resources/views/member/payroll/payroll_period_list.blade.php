@extends('member.layout')
@section('css')
@endsection

@section('content')
<div class="body-period">
  <div class="panel panel-default panel-block panel-title-block" id="top">
    <div class="panel-heading">
      <div>
        <i class="fa fa-calendar"></i>
        <h1>
        <span class="page-title">Payroll Period</span>
        <small>
        	Manage your paryoll period
        </small>
        </h1>
        <button class="btn btn-custom-primary panel-buttons pull-right popup" link="/member/payroll/payroll_period_list/modal_create_payroll_period">Create Period</button>
        <input type="hidden" name="_token" value="{{csrf_token()}}" id="_token">
      </div>
    </div>
  </div>
  <ul class="nav nav-tabs">
    <li class="active"><a data-toggle="tab" href="#active-period"><i class="fa fa-star" aria-hidden="true"></i>&nbsp;Active</a></li>
    <li><a data-toggle="tab" href="#archived-period"><i class="fa fa-trash-o" aria-hidden="true"></i>&nbsp;Archived</a></li>
  </ul>
  <div class="period-list">
    <div class="tab-content tab-pane-div padding-10">
      <div id="active-period" class="tab-pane fade in active">
        <div class="load-data" target="value-id-1">
          <div id="value-id-1">
            <table class="table table-bordered table-condensed">
              <thead>
                <tr>
                  <th>Period Category</th>
                  <th>Period Start</th>
                  <th>Period End</th>
                  <th class="text-center">Action</th>
                </tr>
              </thead>
              <tbody>
                @foreach($_active as $active)
                <tr>
                  <td>
                    {{$active->payroll_period_category}}
                  </td>
                   <td>
                    {{date('F d, Y',strtotime($active->payroll_period_start))}}
                  </td>
                   <td>
                    {{date('F d, Y', strtotime($active->payroll_period_end))}}
                  </td>
                  <td class="text-center">
                    <div class="dropdown">
                      <button class="btn btn-custom-white dropdown-toggle btn-xs" type="button" data-toggle="dropdown">Action
                      <span class="caret"></span></button>
                      <ul class="dropdown-menu dropdown-menu-custom">
                        <li>
                          <a href="#" class="popup" link="/member/payroll/payroll_period_list/modal_edit_period/{{$active->payroll_period_id}}" ><i class="fa fa-pencil"></i>&nbsp;Edit Period</a>
                        </li>
                        <li>
                          <a href="#" class="popup" link="/member/payroll/payroll_period_list/modal_schedule_employee_shift?id={{$active->payroll_period_id}}" size="lg"><i class="fa fa-calendar"></i>&nbsp;Schedule</a>
                        </li>
                        <li>
                          <a href="#" class="popup" link="/member/payroll/payroll_period_list/modal_archive_period/1/{{$active->payroll_period_id}}" size="sm"><i class="fa fa-trash-o"></i>&nbsp;Archive</a>
                        </li>
                      </ul>
                    </div>
                  </td>
                </tr>
                @endforeach
              </tbody>
            </table>
            <div class="pagination"> {!! $_active->render() !!} </div>
          </div>
        </div>
      </div>
      <div id="archived-period" class="tab-pane fade">
        <div class="load-data" target="value-id-2">
          <div id="value-id-2">
            <table class="table table-bordered table-condensed">
              <thead>
                <tr>
                  <th>Period Category</th>
                  <th>Period Start</th>
                  <th>Period End</th>
                  <th class="text-center">Action</th>
                </tr>
              </thead>
              <tbody>
                @foreach($_archived as $archived)
                <tr>
                  <td>
                    {{$archived->payroll_period_category}}
                  </td>
                   <td>
                    {{date('F d, Y',strtotime($archived->payroll_period_start))}}
                  </td>
                   <td>
                    {{date('F d, Y', strtotime($archived->payroll_period_end))}}
                  </td>
                  <td class="text-center">
                    <div class="dropdown">
                      <button class="btn btn-custom-white dropdown-toggle btn-xs" type="button" data-toggle="dropdown">Action
                      <span class="caret"></span></button>
                      <ul class="dropdown-menu dropdown-menu-custom">
                        <li>
                          <a href="#" class="popup" link="/member/payroll/payroll_period_list/modal_edit_period/{{$archived->payroll_period_id}}" ><i class="fa fa-pencil"></i>&nbsp;Edit Period</a>
                        </li>
                        <a href="#" class="popup" link="/member/payroll/payroll_period_list/modal_schedule_employee_shift?id={{$archived->payroll_period_id}}" size="lg"><i class="fa fa-calendar" ></i>&nbsp;Schedule</a>
                        <li>
                          <a href="#" class="popup" link="/member/payroll/payroll_period_list/modal_archive_period/0/{{$archived->payroll_period_id}}" size="sm"><i class="fa fa-refresh"></i>&nbsp;Restore</a>
                        </li>
                      </ul>
                    </div>
                  </td>
                </tr>
                @endforeach
              </tbody>
            </table>
            <div class="pagination"> {!! $_archived->render() !!} </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection
@section('script')
<script type="text/javascript" src="/assets/member/js/payroll/payroll_period_list.js"></script>
<script type="text/javascript">
  function loading_done_paginate (data)
  {
    console.log(data);
  }
</script>

<script type="text/javascript" src="/assets/member/js/paginate_ajax_multiple.js"></script>
@endsection