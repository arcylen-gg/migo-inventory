<form class="global-submit form-horizontal" role="form" action="{link_submit_here}" method="post">
	<input type="hidden" class="_token" value="{{ csrf_token() }}" />
    <div class="modal-header">
		<button type="button" class="close" data-dismiss="modal">×</button>
		<h4 class="modal-title">SSS CONTRIBUTION FOR THE MONTH OF {{ strtoupper($month_name) }} {{ $year }} </h4>
	</div>
    <div class="panel panel-default panel-block panel-title-block">
        <div class="panel-body form-horizontal">
            <div class="form-group">
                <div class="col-md-3">
                    <select class="form-control filter-by-company-sss" data-id="{{$month}}">
                        <option value="0">ALL COMPANY</option>
                        @foreach($_company as $company)
                            <option value="{{$company->payroll_company_id}}">{{$company->payroll_company_name}}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <input type="hidden" class="year" value="{{$year}}">
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
                            	<th class="text-center">#</th>
                                <th class="text-center">SSS NO.</th>
                                <th class="text-center">LAST NAME</th>
                                <th class="text-center">FIRST NAME</th>
                                <th class="text-center">NAME EXT</th>
                                <th class="text-center">MIDDLE NAME</th>
                                <th class="text-center">COVERED</th>
                                <th class="text-center">EE SHARE</th>
                                <th class="text-center">ER SHARE</th>
                                <th class="text-center">EC SHARE</th>
                                <th class="text-center">TOTAL</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if(isset($contri_info["_employee_contribution"]))
                 			@foreach($contri_info["_employee_contribution"] as $key => $contribution)
                        	<tr>
                        		<td class="text-center">{{ $contribution->count }}</td>
                        		<td class="text-center">{{ $contribution->payroll_employee_sss }}</td>
                        		<td class="text-center">{{ $contribution->payroll_employee_last_name }}</td>
                        		<td class="text-center">{{ $contribution->payroll_employee_first_name }}</td>
                        		<td class="text-center">{{ $contribution->payroll_employee_suffix_name }}</td>
                        		<td class="text-center">{{ $contribution->payroll_employee_middle_name }}</td>
                        		<td class="text-center">{{ $contribution->period_covered }}</td>
                        		<td class="text-center">{{ payroll_currency($contribution->total_sss_ee) }}</td>
                        		<td class="text-center">{{ payroll_currency($contribution->total_sss_er) }}</td>
                                <td class="text-center">{{ payroll_currency($contribution->total_sss_ec) }}</td>
                        		<td class="text-center" style="color: #76B6EC; font-weight: bold;">{{ payroll_currency($contribution->total_sss_ee_er) }}</td>
                        	</tr>
                        	@endforeach
                            @endif
                        </tbody>
                        <tfoot>
                            <tr>
                                <td colspan="7" class="text-right" style="font-weight: bold;">GRAND TOTAL</td>
                                <td class="text-center" style="font-weight: bold;">{{ payroll_currency($contri_info["grand_total_sss_ee"]) }}</td>
                                <td class="text-center" style="font-weight: bold;">{{ payroll_currency($contri_info["grand_total_sss_er"]) }}</td>
                                <td class="text-center" style="font-weight: bold;">{{ payroll_currency($contri_info["grand_total_sss_ec"]) }}</td>
                                <td class="text-center" style="color: #76B6EC; font-weight: bold;">{{ payroll_currency($contri_info["grand_total_sss_ee_er"]) }}</td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
	</div>
    <div class="modal-footer">
        <button type="button" class="btn btn-def-white btn-custom-white" data-dismiss="modal">Close</button>
        &nbsp;<a href="/member/payroll/reports/government_forms_sss_export_excel/{{$month}}/0/{{ $year }}"><button type="button" class="btn btn-success pull-right"><i class="fa fa-file-excel-o" ></i> &nbsp;EXPORT TO EXCEL</button></a>
    </div>
    </div>
</form>

<style type="text/css">
    #global_modal .modal-dialog
    {
        width: 85% !important;
    }
</style>
<script type="text/javascript" src="/assets/js/government_forms.js"></script>