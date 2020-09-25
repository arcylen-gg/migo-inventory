<form class="global-submit form-horizontal" role="form" action="{link_submit_here}" method="post">
	<div class="modal-header">
		<button type="button" class="close" data-dismiss="modal">×</button>
		<h4 class="modal-title">EXPORT TO BANK</h4>
	</div>
	<div class="modal-body clearfix">
	<table class="table table-bordered table-striped table-condensed">
		<thead>
			<tr>
				<th class="text-center"><!-- <input onChange="action_check_all_checkbox();" class="all-checkbox-check" type="checkbox" > --></th>
				<th>ACCOUNT #</th>
				<th>AMOUNT</th>
				<th>NAME</th>
				<th>REMARKS</th>
			</tr>
		</thead>
		<tbody>
			@foreach($_employee as $employee)
			<tr>
				<td class="text-center"><input onChange="action_employee_checkbox();" class="employee-checkbox" value="{{ $employee->employee_id }}" type="checkbox" name="employee[]"></td>
				<td>{{ $employee->payroll_employee_atm_number }}</td>
				<td>{{ number_format($employee->net_pay, 2) }}</td>
				<td>{{ $employee->payroll_employee_first_name }} {{ $employee->payroll_employee_middle_name }} {{ $employee->payroll_employee_last_name }}</td>
				<td>OK</td>
			</tr>
			@endforeach
	    </tbody>
	</table>
	</div>
	<div class="modal-footer">
		<button type="button" class="btn btn-def-white btn-custom-white" data-dismiss="modal">Close</button>
		<a class="btn btn-default btn-custom-default export-xls-button" href="/member/payroll/banking/{{ $payroll_period_company_id }}/download?xls=1">Export Excel</a>
		<a class="btn btn-primary btn-custom-primary export-txt-button" href="/member/payroll/banking/{{ $payroll_period_company_id }}/download">Export Bank Template</a>
	</div>
</form>
<script type="text/javascript">
var default_xls = $(".export-xls-button").attr("href");
var default_txt = $(".export-txt-button").attr("href");

function action_employee_checkbox()
{
	var param = [];

	$('.employee-checkbox:checked').each(function(index, el) 
	{
		param.push($(el).val());
	});

	var employee = JSON.stringify(param);

	$(".export-xls-button").attr("href", default_xls + '&employee=' + employee);
	$(".export-txt-button").attr("href", default_txt + '?employee=' + employee);
}
function action_check_all_checkbox()
{

	$(document).on('change', '.all-checkbox-check', function() {
	    if(this.checked) {
	       $('.employee-checkbox').attr('checked', true);
	    }
	    else{
	    	$('.employee-checkbox').attr('checked', false);
	    }
	});
}
</script>