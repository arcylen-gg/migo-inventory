<link rel="stylesheet" type="text/css" href="/assets/member/payroll/css/timesheet.css">
<link rel="stylesheet" type="text/css" href="/assets/external/jquery.timeentry.package-2.0.1/jquery.timeentry.css">
<form class="global-submit form-horizontal" role="form" action="{link_submit_here}" method="post">
	<input type="hidden" class="_token" value="{{ csrf_token() }}" />
    <div class="modal-header">
		<button type="button" class="close" data-dismiss="modal">×</button>
		<h4 class="modal-title">Annual Leave Report</h4>
	</div>
        <input type="hidden" id="category" value="annual_leave">
    <div class="panel panel-default panel-block panel-title-block">
        <div class="panel-body form-horizontal">
                <div class="form-group">
             


            <div class="col-md-2">
                <small>Company</small>
                <select class="select-company-name form-control" style="width: 300px">    
                    <option value="0">All Company</option>
                      @foreach($_company as $company)
                      <option value="{{$company['company']->payroll_company_id}}">{{$company['company']->payroll_company_name}}</option> 
                        @foreach($company['branch'] as $branch)
                            <option value="{{$branch->payroll_company_id}}">&nbsp;&nbsp;• {{$branch->payroll_company_name}}</option>
                        @endforeach
                      @endforeach
                </select>
            </div>

            </div>
        </div>
    </div>
    <div class="text-c  enter" id="spinningLoader" style="display:none;">
                <img src="/assets/images/loader.gif">
    </div>
    <div class="load-filter-data">
	<div class="modal-body clearfix">
                <div class="table-responsive">
                    <table class="table table-bordered table-condensed">
                        <thead>
                            <tr>
                                <th class="text-center">Employee Number</th>
                                <th class="text-center">Employee Name</th>
                                <th class="text-center">Leave Name</th>
                                <th class="text-center">Jan {{$lastyear}}</th>
                                <th class="text-center">Feb {{$lastyear}}</th>
                                <th class="text-center">Mar {{$lastyear}}</th>
                                <th class="text-center">Apr {{$lastyear}}</th>
                                <th class="text-center">May {{$lastyear}}</th>
                                <th class="text-center">Jun {{$lastyear}}</th>
                                <th class="text-center">July {{$lastyear}}</th>
                                <th class="text-center">Aug {{$lastyear}}</th>
                                <th class="text-center">Sep {{$lastyear}}</th>
                                <th class="text-center">Oct {{$lastyear}}</th>
                                <th class="text-center">Nov {{$lastyear}}</th>
                                <th class="text-center">Dec {{$lastyear}}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if(isset($leave_report))
                 			@foreach($leave_report as $annual)
                        	<tr>
                                <td class="text-center">{{ $annual->payroll_employee_number }}</td>
                        		<td class="text-center">{{ $annual->payroll_employee_display_name }}</td>
                        		<td class="text-center">{{ $annual->payroll_leave_name }}</td>
                        		<td class="text-center">@if(isset($annual->January)){{ $annual->January }}@else 0 @endif</td>
                        		<td class="text-center">@if(isset($annual->February)){{ $annual->February }}@else 0 @endif</td>
                        		<td class="text-center">@if(isset($annual->March)){{ $annual->March }}@else 0 @endif</td>
                                <td class="text-center">@if(isset($annual->April)){{ $annual->April }}@else 0 @endif</td>
                                <td class="text-center">@if(isset($annual->May)){{ $annual->May }}@else 0 @endif</td>
                                <td class="text-center">@if(isset($annual->June)){{ $annual->June }}@else 0 @endif</td>
                                <td class="text-center">@if(isset($annual->July)){{ $annual->July }}@else 0 @endif</td>
                                <td class="text-center">@if(isset($annual->August)){{ $annual->August }}@else 0 @endif</td>
                                <td class="text-center">@if(isset($annual->September)){{ $annual->September }}@else 0 @endif</td>
                                <td class="text-center">@if(isset($annual->October)){{ $annual->October }}@else 0 @endif</td>
                                <td class="text-center">@if(isset($annual->November)){{ $annual->November }}@else 0 @endif</td>
                                <td class="text-center">@if(isset($annual->December)){{ $annual->December }}@else 0 @endif</td>
                        	</tr>
                        	@endforeach
                            @endif
                        </tbody>
                    </table>
                </div>
	</div>
    <div class="modal-footer">
        <button type="button" class="btn btn-def-white btn-custom-white" data-dismiss="modal">Close</button>
        &nbsp;<a href="/member/payroll/leave/v2/leave_annual_export/0"><button type="button" class="btn btn-success pull-right"><i class="fa fa-file-excel-o" ></i> &nbsp;EXPORT TO EXCEL</button></a>
    </div>
    </div>
</form>

<style type="text/css">
    #global_modal .modal-dialog
    {
        width: 85% !important;
    }
</style>
<script type="text/javascript" src="/assets/external/jquery.timeentry.package-2.0.1/jquery.plugin.min.js"></script>
<script type="text/javascript" src="/assets/external/jquery.timeentry.package-2.0.1/jquery.timeentry.min.js"></script>
<script type="text/javascript">
        $(".date_picker").datepicker();
</script>
<script type="text/javascript" src="/assets/member/js/payroll/modal_create_leave_tempv2.js"></script>
