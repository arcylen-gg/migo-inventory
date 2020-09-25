@extends('member.layout')
@section('css')
@endsection
@section('content')
<div class="panel panel-default panel-block panel-title-block" id="top">
	<div class="panel-heading">
		<div>
			<i class="fa fa-bar-chart" aria-hidden="true"></i>
			<h1>
			<span class="page-title">Payroll Reports</span>
			<small>
			Manage Payroll Reports
			</small>
			</h1>
			<a href="#" class="btn btn-custom-primary pull-right popup" link="/member/payroll/payroll_reports/modal_create_reports">New Custom Reports</a>
			<input type="hidden" name="_token" value="{{csrf_token()}}" id="_token">
		</div>
	</div>
</div>
<div class="panel panel-default panel-block panel-title-block">
	<div class="panel panel-body">
		<div class="data-list">
			<ul class="nav nav-tabs">
				<li class="active"><a data-toggle="tab" href="#report-list"><i class="fa fa-star" aria-hidden="true"></i>&nbsp;Report List</a></li>
				<li><a data-toggle="tab" href="#archived"><i class="fa fa-trash" aria-hidden="true"></i>&nbsp;Archived</a></li>
			</ul>
			<div class="tab-content">
				<div id="report-list" class="tab-pane fade in active">
					<ul class="list-group">
						<li class="list-group-item">
							<span>Payroll Journal Entry</span>
							<a href="/member/payroll/journal_entry" class="btn btn-xs btn-custom-white pull-right">View Journal</a>
						</li>
						<li class="list-group-item">
							<span>13th Month Pay Report</span>
							<a href="/member/payroll/report_13th_month_pay" class="btn btn-xs btn-custom-white pull-right">View 13th Month Pay</a>
						</li>
						@foreach($_active as $active)
						<li class="list-group-item">
							{{$active->payroll_reports_name}}
							<div class="dropdown pull-right">
								<button class="btn btn-custom-white dropdown-toggle btn-xs" type="button" data-toggle="dropdown">Action
								<span class="caret"></span></button>
								<ul class="dropdown-menu dropdown-menu-custom">
									<li>
										<a href="/member/payroll/payroll_reports/view_report/{{$active->payroll_reports_id}}"><i class="fa fa-search"></i>&nbsp;View</a>
									</li>
									<li>
										<a href="#" class="popup" link="/member/payroll/payroll_reports/modal_edit_reports/{{$active->payroll_reports_id}}" ><i class="fa fa-pencil"></i>&nbsp;Edit</a>
									</li>
									<li>
										<a href="#" class="popup" link="/member/payroll/payroll_reports/modal_archive_reports/1/{{$active->payroll_reports_id}}" size="sm"><i class="fa fa-trash-o"></i>&nbsp;Archived</a>
									</li>
								</ul>
							</div>
						</li>
						@endforeach
					</ul>
				</div>
				<div id="archived" class="tab-pane fade">
					<ul class="list-group">
						@foreach($_archived as $archived)
						<li class="list-group-item">
							{{$archived->payroll_reports_name}}
							<div class="dropdown pull-right">
								<button class="btn btn-custom-white dropdown-toggle btn-xs" type="button" data-toggle="dropdown">Action
								<span class="caret"></span></button>
								<ul class="dropdown-menu dropdown-menu-custom">
									<li>
										<a href="#" class="popup" link="/member/payroll/payroll_reports/modal_edit_reports/{{$archived->payroll_reports_id}}" ><i class="fa fa-pencil"></i>&nbsp;Edit</a>
									</li>
									<li>
										<a href="#" class="popup" link="/member/payroll/payroll_reports/modal_archive_reports/0/{{$archived->payroll_reports_id}}" size="sm"><i class="fa fa-trash-o"></i>&nbsp;Restore</a>
									</li>
								</ul>
							</div>
						</li>
						@endforeach
					</ul>
				</div>
			</div>
		</div>
	</div>
</div>
@endsection
@section('script')
<script type="text/javascript" src="/assets/member/js/payroll/payroll_reports.js"></script>
@endsection