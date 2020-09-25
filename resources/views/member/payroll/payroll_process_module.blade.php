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
		</div>
	</div>
</div>


<div class="panel panel-default panel-block panel-title-block panel-gray ">
    <ul class="nav nav-tabs">
        @if($access_processed == 1)
        <li class="{{$access_processed == 1 ? 'active' : ''}} cursor-pointer change-tab approve-tab" mode="processed"><a class="cursor-pointer"><i class="text-bold"> Processed </i></a></li>
        @endif
        @if($access_registered == 1)
        <li class="{{$access_processed == 0 ? 'active' : ''}} cursor-pointer change-tab approve-tab" mode="registered"><a class="cursor-pointer"><i class="text-bold"> Registered </i></a></li>
        @endif
        @if($access_posted == 1)
        <li class="{{($access_processed == 0 && $access_registered == 0) ? 'active' : ''}} cursor-pointer change-tab approve-tab" mode="posted"><a class="cursor-pointer"><i class="text-bold"> Posted </i></a></li>
        @endif
        @if($access_approved == 1)
        <li class="{{($access_processed == 0 && $access_registered == 0 && $access_posted == 0) ? 'active' : ''}} cursor-pointer change-tab approve-tab" mode="approved"><a class="cursor-pointer"><i class="text-bold"> Approved </i></a></li>
        @endif
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