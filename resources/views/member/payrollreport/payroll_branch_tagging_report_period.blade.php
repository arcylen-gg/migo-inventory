@extends('member.layout')
@section('content')
<div class="panel panel-default panel-block panel-title-block" id="top">
	<div class="panel-heading">
		<div>
			<i class="fa fa-tags"></i>
			<h1>
			<span class="page-title">Payroll Reports &raquo; Branch Tagging Report</span>
			<small>
			{{ $company->payroll_company_name }}
			</small>
			</h1>
			<!-- <input type="number" name="period_company_id" value="{{$period_company_id}}" class="period_company_id hidden"> -->
		</div>
	</div>
</div>
<div class=" panel panel-default panel-block panel-title-block" >
	<div class="panel-body form-horizontal">
		
	</div>
</div>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.0/jquery.min.js"></script>
{{-- <script type="text/javascript" src="/assets/js/ajax_offline.js"></script> --}}
<script type="text/javascript" src="/assets/js/payroll_register_report.js"></script>
@endsection