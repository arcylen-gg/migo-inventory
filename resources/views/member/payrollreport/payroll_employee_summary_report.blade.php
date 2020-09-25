@extends('member.layout')
@section('css')

@endsection
@section('content')
<div class="panel panel-default panel-block panel-title-block" id="top">
  <div class="panel-heading">
    <div>
      <i class="fa fa-group"></i>
      <h1>
      <span class="page-title">Employee List</span>
      <small>
      Employee 201 files
      </small>
      </h1>
      
      <input type="hidden" name="_token" value="{{csrf_token()}}" id="_token">
    </div>
  </div>
</div>
<ul class="nav nav-tabs">
  <li class="active"><a data-toggle="tab" href="#active-employee-tab"><i class="fa fa-star"></i>&nbsp;Active Employee</a></li>
</ul>
<div class="tab-content tab-pane-div padding-top-10">
  <div id="active-employee-tab" class="tab-pane fade in active">
    <div class="form-horizontal">
      <div class="form-group">
        <div class="col-md-12 filter-div">
          <div class="col-md-2 padding-lr-1">
            <small>Filter by Company</small>
            <select class="form-control filter-change filter-change-company" data-target="#active-employee">
              <option value="0">Select Company</option>
              @foreach($_company as $company)
              <option value="{{$company['company']->payroll_company_id}}">{{$company['company']->payroll_company_name}}</option> 
                @foreach($company['branch'] as $branch)
                <option value="{{$branch->payroll_company_id}}">&nbsp;&nbsp;• {{$branch->payroll_company_name}}</option>
                @endforeach
              @endforeach
            </select>
          </div>
          <form class="col-md-4 pull-right padding-lr-1 search-form" data-target="#active-employee" method="POST" action="/member/payroll/employee_list/search_employee">
            <input type="hidden" name="_token" value="{{csrf_token()}}">
            <input type="hidden" name="trigger" value="active">
            <small>Search Employee</small>
            <div class="input-group">
              <input type="search" name="employee_search" data-trigger="active" class="form-control perdictive perdictive-active width-100" placeholder="Search employee here">
              <span class="input-group-btn">
                <button class="btn btn-custom-primary" type="submit"><i class="fa fa-search"></i></button>
              </span>
            </div>
            
           
          </form>
        </div>
      </div>
      <div class="form-group">
        <div class="col-md-12" id="active-employee">
          <div class="load-data" target="value-id-1">
            <div id="value-id-1">
              <table class="table table-condensed table-bordered">
                <thead>
                  <tr>
                    <th>Employee No</th>
                    <th>Employee Name</th>
                    <th>Monthly Rate</th>
                    <th>Daily Rate</th>
                    <th>Employee Company</th>
                    <th>Department</th>
                    <th>Position</th>
                  </tr>
                </thead>
                @foreach($_active as $active)
                <tr>
                  <td>
                    {{$active->payroll_employee_number}}
                  </td>
                  <td>
                    {{$active->payroll_employee_last_name}}, {{$active->payroll_employee_first_name}} {{ substr($active->payroll_employee_middle_name, 0, -(strlen($active->payroll_employee_middle_name))+1) }}.
                  </td>
                  <td>
                    {{payroll_currency($active->monthly_salary)}}
                  </td>
                  <td>
                    {{payroll_currency($active->daily_salary)}}
                  </td>
                  <td>
                    {{$active->payroll_company_name}}
                  </td>
                  <td>
                    {{$active->payroll_department_name}}
                  </td>
                  <td>
                    {{$active->payroll_jobtitle_name}}
                  </td>
                </tr>
                @endforeach
              </table>
              <div class="pagination"></div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection
@section('script')
<!-- <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.js"></script> -->
<script type="text/javascript" src="/assets/member/js/payroll/employeelist.js"></script>
<script type="text/javascript">
  function loading_done_paginate (data)
  {
    console.log(data);
  }
</script>
<script type="text/javascript" src="/assets/member/js/paginate_ajax_multiple.js"></script>
@endsection