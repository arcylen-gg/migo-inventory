<link rel="stylesheet" type="text/css" href="/assets/member/payroll/css/timesheet.css">
<link rel="stylesheet" type="text/css" href="/assets/external/jquery.timeentry.package-2.0.1/jquery.timeentry.css">
<form class="global-submit form-horizontal" role="form" action="{link_submit_here}" method="post">
	<input type="hidden" class="_token" value="{{ csrf_token() }}" />
    <div class="modal-header">
		<button type="button" class="close" data-dismiss="modal">×</button>
		<h4 class="modal-title">Remaining Leave Records</h4>
	</div>
            <input type="hidden" id="category" value="monthly_remaining">
    <div class="panel panel-default panel-block panel-title-block">
        <div class="panel-body form-horizontal">
                       <div class="form-group">
             
                            <div class="col-md-2">
                                <small>Date Start</small>
                                <input type="text" name="payroll_schedule_leave_start" id="start" class="date_picker form-control payroll_schedule_leave_start" value="{{date("m/d/Y")}}" required style="width: 150px">
                            </div>

                            <div class="col-md-2">
                                <small>Date End</small>
                                <input type="text" name="payroll_schedule_leave_end" id="end" class="date_picker form-control payroll_schedule_leave_end" value="{{date("m/d/Y")}}" required style="width: 150px">
                            </div>

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
    <div class="text-center" id="spinningLoader" style="display:none;">
                <img src="/assets/images/loader.gif">
    </div>
    <div class="load-filter-data">
	<div class="modal-body clearfix">
                <div class="table-responsive">
                    <table class="table table-bordered table-condensed">
                        <thead>
                            <tr>
                                <th class="text-center" id="border"></th>
                                <th class="text-center" id="border"></th>
                                <th class="text-center" id="border"></th>
                                <th class="text-center" id="border"></th>
                                <th colspan="3" class="text-center">Used Leave</th>
                                <th class="text-center" id="border"></th>
                            </tr>
                            <tr>
                                <th class="text-center" id="bordertop">Leave Name</th>
                                <th class="text-center" id="bordertop">Employee Number</th>
                                <th class="text-center" id="bordertop">Employee Name</th>
                                <th class="text-center" id="bordertop">Leave Credits</th>
                                <th class="text-center">With Pay</th>
                                <th>Without Pay</th>
                                <th>Total Used Leave</th>
                                <th class="text-center" id="bordertop">Remaining Leave</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if(isset($leave_report))
                            @foreach($leave_report as $leave_data)
                                @foreach($leave_data as $leave)
                            <tr>
                                <td class="text-center">{{ $leave->payroll_leave_temp_name }}</td>
                                <td class="text-center">{{ $leave->payroll_employee_id }}</td>
                                <td class="text-center">{{ $leave->payroll_employee_display_name }}</td>
                                <td class="text-center">{{ $leave->payroll_leave_temp_hours }}</td>


                                @php $paycheck = NULL; @endphp
                                @foreach($remwithpay as $remwith)
                                    @foreach($remwith as $pay)
                                             @if($pay->payroll_employee_id == $leave->payroll_employee_id)
                                                     @php
                                                     $paycheck = $pay;
                                                     break;
                                                     @endphp
                                             @endif
                                    @endforeach
                                @endforeach

                                @if(!is_null($paycheck))
                                @foreach($remwithpay as $remwith)
                                    @foreach($remwith as $pay)
                                             @if($pay->payroll_employee_id == $leave->payroll_employee_id)
                                                 <td class="text-center">{{$pay->total_leave_consume}}</td>
                                             @endif
                                    @endforeach
                                @endforeach
                                 @else
                                    <td class="text-center">0.00</td>
                                @endif

                                @php $withoutpaycheck = NULL; @endphp
                                @foreach($remwithoutpay as $remwiths)
                                    @foreach($remwiths as $withoutpay)
                                             @if($withoutpay->payroll_employee_id == $leave->payroll_employee_id)
                                                     @php
                                                     $withoutpaycheck = $withoutpay;
                                                     break;
                                                     @endphp
                                             @endif
                                    @endforeach
                                @endforeach

                                @if(!is_null($withoutpaycheck))
                                    @foreach($remwithoutpay as $remwiths)
                                            @foreach($remwiths as $withoutpay)
                                                     @if($withoutpay->payroll_employee_id == $leave->payroll_employee_id)
                                                        <td class="text-center">{{$withoutpay->total_leave_consume}}</td>
                                                     @endif
                                            @endforeach
                                     @endforeach
                                @else
                                    <td class="text-center">0.00</td>
                                @endif
                                <td class="text-center">{{ $leave->total_leave_consume }}</td>
                                <td class="text-center">{{ $leave->remaining_leave }}</td>
                            </tr>
                                 @endforeach
                            @endforeach
                            @endif
                        </tbody>
                    </table>
                </div>
	</div>
    <div class="modal-footer">
        <button type="button" class="btn btn-def-white btn-custom-white" data-dismiss="modal">Close</button>
        &nbsp;<a href="/member/payroll/leave/v2/remaining_leave_report_excel/{{date("Y-m-d")}}/{{date("Y-m-d")}}/0"><button type="button" class="btn btn-success pull-right"><i class="fa fa-file-excel-o" ></i> &nbsp;EXPORT TO EXCEL</button></a>
    </div>
    </div>
</form>

<style type="text/css">
    #global_modal .modal-dialog
    {
        width: 85% !important;
    }
    #border
    {
        border-bottom-color: white !important;
    }
    #bordertop
    {
        border-top-color: white !important;
    }
</style>
<script type="text/javascript" src="/assets/external/jquery.timeentry.package-2.0.1/jquery.plugin.min.js"></script>
<script type="text/javascript" src="/assets/external/jquery.timeentry.package-2.0.1/jquery.timeentry.min.js"></script>
<script type="text/javascript">
        $(".date_picker").datepicker();
</script>
<script type="text/javascript" src="/assets/member/js/payroll/modal_create_leave_tempv2.js"></script>
