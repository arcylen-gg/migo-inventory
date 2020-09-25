var payroll_process = new payroll_process();

function payroll_process()
{
	init();
	function init()
	{
		$(document).ready(function()
		{
			document_ready();
		});
	}
	function document_ready()
	{
		action_load_table();
	}
	function action_load_table()
	{
		$(".load-table-employee-summary").load("/member/payroll/process_payroll/table/" + $(".payroll-process-id").val())
	}
}