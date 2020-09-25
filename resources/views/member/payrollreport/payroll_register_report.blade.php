@extends('member.layout')
@section('content')
<div class="panel panel-default panel-block panel-title-block" id="top">
    <div class="panel-heading">
        <div>
            <i class="fa fa-tags"></i>
            <h1>
                <span class="page-title">Payroll Reports &raquo; Register Report</span>
                <small>
                select payroll period.
                </small>
            </h1>
        </div>
    </div>
</div>



<div class="panel panel-default panel-block panel-title-block">
    <div class="panel-body form-horizontal">
        <div class="form-group tab-content panel-body employee-container">
            <div id="all" class="tab-pane fade in active">
                <div class="form-group order-tags"></div>
                <div class="table-responsive">
                    <table class="table table-bordered table-striped table-condensed">
					    <thead style="text-transform: uppercase">
					        <tr>
					            <th class="text-center">Date Covered</th>
					            <th class="text-center">Company</th>
					            <th class="text-center">Period Type</th>
					            <th class="text-center">Covered Month</th>
					            <th class="text-center">Period Order</th>
					            <th class="text-center"></th>
					        </tr>
					    </thead>
					    <tbody>
					     
				        	@foreach($_period as $period)
				    		<tr>
				    			<td class="text-center">{{ date('M d, Y',strtotime($period->payroll_period_start)).' - '.date('M d, Y',strtotime($period->payroll_period_end)) }}</td>
				    			<td class="text-center">{{ $period->payroll_company_name }}</td>
				    			<td class="text-center">{{ $period->payroll_period_category }}</td>
				    			<td class="text-center">{{ $period->month_contribution }} {{ $period->year_contribution }}</td>
				    			<td class="text-center">{{ code_to_word($period->period_count) }}</td>
				    			<td class="text-center"><a href="/member/payroll/reports/payroll_register_report_period/{{$period->payroll_period_company_id}}">View Employee</a></td>
				    		</tr>
				    		@endforeach
					    </tbody>
					</table>
                </div>
            </div>
        </div> 
    </div>
</div>
@endsection
