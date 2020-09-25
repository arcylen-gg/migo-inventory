@extends('member.layout')
@section('css')
@endsection
@section('content')
<div class="panel panel-default panel-block panel-title-block" id="top">
	<div class="panel-heading">
		<div>
			<i class="fa fa-calendar"></i>
			<h1>
			<span class="page-title">Select a Payroll Period</span>
			<small>You can view timesheet inside a specific payroll period.</small>
			</h1>
			<div class="dropdown pull-right">
				<button class="btn btn-custom-primary dropdown-toggle " type="button" data-toggle="dropdown"><i class="fa fa-gears"></i>&nbsp;Operation
				<span class="caret"></span></button>
				<ul class="dropdown-menu dropdown-menu-custom">
					<li><a href="#" class="popup" link="/member/payroll/payroll_period_list/modal_create_payroll_period"><i class="fa fa-plus"></i>&nbsp;Generate Period</a></li>
					<li><a href="#" class="popup" size="lg" link="/member/payroll/import_bio/modal_biometrics"><i class="fa fa-upload"></i>&nbsp;Import Time Sheet</a></li>
				</ul>
                
			</div>
		</div>
	</div>
</div>


<div class="panel panel-default panel-block panel-title-block panel-gray ">
    <ul class="nav nav-tabs">
        <li class="active change-tab pending-tab cursor-pointer" mode="generated"><a class="cursor-pointer"><i class="text-bold">Payroll Periods</i></a></li>
    </ul>
    
    <div class="search-filter-box">
        <div class="col-md-4" style="padding: 10px">
            <select class="form-control item_type company-change-event">
            	<option value="0">All Company</option>
            	@foreach($_company as $company)
            		<option value="{{ $company->payroll_company_id }}">{{ $company->payroll_company_name }}</option>
            	@endforeach
            </select>
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
    </div>
</div>

@endsection
@section('script')
<script type="text/javascript" src="/assets/member/js/payroll/payroll_timekeeping.js?version=10"></script>
@endsection