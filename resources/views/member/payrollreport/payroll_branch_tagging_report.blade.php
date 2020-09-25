@extends('member.layout')
@section('content')
<div class="panel panel-default panel-block panel-title-block" id="top">
    <div class="panel-heading">
        <div>
            <i class="fa fa-tags"></i>
            <h1>
                <span class="page-title">Payroll Branch Tagging Report &raquo; Branch Report</span>
                <small>
                select payroll period.
                </small>
            </h1>
        </div>
    </div>
</div>



<div class="panel panel-default panel-block panel-title-block">
    <div class="panel-body form-horizontal">
    	<div class="row">
    	    <div class="col-md-2">
    	        <small>Period Date Start</small>
    	        <input type="text" name="period_date_start" class="form-control datepicker period_date_start">
    	    </div>
    	   
    	    <div class="col-md-2">
    	        <small>Period Date End</small>
    	        <input type="text" name="period_date_end" class="form-control datepicker period_date_end">
    	    </div>

    	    <div class="col-md-2">
    	        <button class="btn btn-primary btn-show" style="margin-top: 20px">SHOW</button>
    	    </div>
    	</div>
        <div class="form-group tab-content panel-body employee-container">
            <div id="all" class="tab-pane fade in active">
                <div class="form-group order-tags"></div>
                <div class="table-responsive branch-tagging-table-load">
                 
                </div>
            </div>
        </div> 
    </div>
</div>
@endsection
@section('script')
<script type="text/javascript" src="/assets/member/js/payroll/payroll_branch_tagging_report.js"></script>
@endsection
