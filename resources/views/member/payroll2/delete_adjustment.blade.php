<div class="modal-header">
    <button type="button" class="close" data-dismiss="modal">×</button>
    <h4 class="modal-title"><b>DELETE ADJUSTMENT</b> &raquo; {{ $employee_info->payroll_employee_first_name }} {{ $employee_info->payroll_employee_last_name }} (Employee No. {{ $employee_info->payroll_employee_number == "" ? "00" : $employee_info->payroll_employee_number }})</h4>
    <div>{{ $show_period_start }} to {{ $show_period_end }}</div>
</div>
<form method="post" class="submit-adjustment global-submit" action="/member/payroll/company_timesheet2/delete_adjustment/{{ $period_id }}/{{ $employee_id }}/{{ $adjustment_id }}">
    <div class="modal-body clearfix make-adjustment-modal form-horizontal">
        {{ csrf_field() }}
        <input type="hidden" class="period-id" value="{{ $period_id }}" />
        <input type="hidden" class="x-employee-id" value="{{ $employee_id }}" /> 
        <input type="hidden" class="adjustment-id" value="{{ $adjustment_id }}" /> 
        <div class="text-center" style="font-size: 20px;"> Are you sure you want to delete <b>{{ $adjustment->payroll_adjustment_name }}</b>?</div>
    </div>
    <div class="modal-footer text-right">
        <button type="button" class="btn btn-def-white btn-custom-white" data-dismiss="modal">CLOSE</button>
        <button type="submit" class="btn btn-primary load-summary">DELETE ADJUSTMENT</button>
    </div>
</form>

<script type="text/javascript" src="/assets/member/payroll/js/apply_adjustment.js"></script>