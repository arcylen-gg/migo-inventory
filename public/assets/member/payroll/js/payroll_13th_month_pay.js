var payroll_13th_month_pay = new payroll_13th_month_pay();

function payroll_13th_month_pay()
{
	init();
	function init()
	{
		$(document).ready(function()
		{

			document_ready();
			action_load_modal_13th_month_basis();

		});
	}

	function document_ready()
	{

	}

	
	function action_load_modal_13th_month_basis()
	{
		$('.modal-13th-pay-basis-button').on('click',function()
		{
			var employee_id = $(this).data('id');
			// alert(employee_id);
			action_load_link_to_modal("/member/payroll/reports/modal_employee_13_month_pay_report/"+employee_id, 'lg');
		});
	
	}

	
}

function submit_done(data)
{
	if (data.function == 'load_13th_month_pay_table') 
	{
		load_13th_month_pay_table(data.employee_13_month_basis,data._token);
		data.element.modal("hide");
	}

}

function load_13th_month_pay_table(employee_13_month_basis,_token)
{

	$.ajax({
		url		: '/member/payroll/reports/employee_13_month_pay_report_table',
		data	: {employee_13_month_basis : employee_13_month_basis, _token : _token},
		type	: 'POST',
		success : function(result)
		{
			$('.tbl-13th-pay-table').html(result);
		}
	});
}