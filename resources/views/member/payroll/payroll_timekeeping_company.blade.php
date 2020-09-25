@extends('member.layout')
@section('css')
@endsection
@section('content')
<div class="panel panel-default panel-block panel-title-block">
	<div class="panel-heading">
		<div>
			<i class="fa fa-calendar"></i>
			<h1>
			<span class="page-title">Time Keeping ({{$period->payroll_period_category}})</span>
			<small>
			Manage your Time Keeping
			</small>
			</h1>
		</div>
	</div>
</div>
<div class="panel panel-default panel-block">
	<div class="panel-body well-white">
		<span class="f-18">{{date('F d, Y', strtotime($period->payroll_period_start))}} to {{date('F d, Y', strtotime($period->payroll_period_end))}}</span>
		<hr>
		<ul class="list-group">
		  @foreach($_company as $company)
		  	<li class="list-group-item">
		  		{{$company['payroll_company_name']}}
		  		<a href="/member/payroll/company_timesheet2/{{$company['payroll_period_company_id']}}" class="btn btn-custom-primary pull-right margin-nt-4">Timesheet V2</a> 
		  		<a style="margin-right: 5px;" href="/member/payroll/company_timesheet/{{$company['payroll_period_company_id']}}" class="btn btn-custom-primary pull-right margin-nt-4">View</a>
		  		<span class="custom-badge {{$company['badge']}} pull-right margin-right-80 margin-top-6px">{{$company['payroll_period_status']}}</span>
		  	</li>
		  @endforeach
		</ul>
	</div>
</div>
@endsection
@section('script')
@endsection