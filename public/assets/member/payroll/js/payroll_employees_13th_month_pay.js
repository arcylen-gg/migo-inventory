var payroll_employees_13th_month_pay = new payroll_employees_13th_month_pay();
var basis = "";
var company_id = 0;
var token = "";
var year = "";
function payroll_employees_13th_month_pay()
{
	init();
	function init()
	{
		$(document).ready(function()
		{

			document_ready();
			action_load_modal_13th_month_basis();
			action_change_filter_company();
		});
	}

	function document_ready()
	{

	}

	function action_load_modal_13th_month_basis()
	{
		$('.modal-13th-pay-basis-button').on('click',function()
		{
			// var employee_id = $(this).data('id');
			// alert(employee_id);
			action_load_link_to_modal("/member/payroll/reports/modal_employee_13_month_pay_report", 'lg');
		});
	}
	function action_change_filter_company()
	{
		$('.filter-change-company').on('change',function()
		{
			if (basis != "") 
			{
				company_id = $('.filter-change-company').val();
				load_13th_month_pay_table(basis, token, company_id);
			}
		});
	}
}
function submit_done(data)
{
	if (data.function == 'load_13th_month_pay_table') 
	{
		token = data._token;
		basis = data.employee_13_month_basis;
		company_id = $('.filter-change-company').val();

		load_13th_month_pay_table(data.employee_13_month_basis, data._token, company_id, data.payroll_13th_month_pay_year);
		data.element.modal("hide");
	}
}

function load_13th_month_pay_table(employee_13_month_basis, _token, company_id)
{
	console.log(year);
	$('.modal-loader').removeClass('hidden');
	$.ajax({
		url		: '/member/payroll/reports/employees_13th_month_pay_table',
		data	: {employee_13_month_basis : employee_13_month_basis, _token : _token, company_id : company_id},
		type	: 'POST',
		success : function(result)
		{
			$('.employees-13th-month-pay').html(result);
			$('.modal-loader').addClass('hidden');
		}
	});

}