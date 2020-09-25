@extends('member.layout')
@section('content')
<div class="panel panel-default panel-block panel-title-block" id="top">
	<div class="panel-heading">
		<div>
			<i class="fa fa-tags"></i>
			<h1>
			<span class="page-title">Payroll Reports &raquo; Register Report</span>
			<small>
			{{ $company->payroll_company_name }}
			</small>
			</h1>
			<input type="number" name="period_company_id" value="{{$period_company_id}}" class="period_company_id hidden">
		</div>
	</div>
</div>
<div class=" panel panel-default panel-block panel-title-block" >
	<div class="panel-body form-horizontal">
		<div class="col-md-2 padding-lr-1">
			<small>Filter by Company</small>
			<select class="form-control" id="filter_report" data-id="{{$filtering_company}}">
				<option value="0">All Company</option>
				@foreach($_company as $company)
				<option value="{{$company['company']->payroll_company_id}}">{{$company['company']->payroll_company_name}}</option> 
				  @foreach($company['branch'] as $branch)
				  <option value="{{$branch->payroll_company_id}}">&nbsp;&nbsp;• {{$branch->payroll_company_name}}</option>
				  @endforeach
				@endforeach
			</select>
		</div>
		<div class="col-md-2 padding-lr-1" style="margin-left:25px;">
			<small>Filter by Department</small>
			<select class="form-control" id="filter_department" data-id="{{$filtering_company}}">
				<option value="0">All Department</option>
				@foreach($_department as $department)
				<option value="{{$department->payroll_department_id}}">{{$department->payroll_department_name}}</option> 
				@endforeach
			</select>
		</div>

		<div class="form-group tab-content panel-body employee-container">
			<div id="all" class="tab-pane fade in active">
				<div class="form-group order-tags"></div>
				<div class="labas_mo_dito table-responsive " id="show_me_something">
					<div>
						<button style="margin-bottom: 20px;" type="button" class="btn btn-success pull-right btn-export-excel"><i class="fa fa-file-excel-o" style="font-size:25px;color:white"></i> &nbsp;EXPORT TO EXCEL</button>
						<button style="margin-right:  20px;" type="button" onclick="action_load_link_to_modal('/member/payroll/reports/modal_filter_register_columns/{{$period_company_id}}', 'sm')" class="btn btn-def-white btn-custom-white pull-right"><i class="fa fa-cog" aria-hidden="true"></i> &nbsp;COLUMNS</button>
					</div>
					<div class="payroll_register_report_table">
						
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.0/jquery.min.js"></script>
{{-- <script type="text/javascript" src="/assets/js/ajax_offline.js"></script> --}}
<script type="text/javascript" src="/assets/js/payroll_register_report.js"></script>
@endsection