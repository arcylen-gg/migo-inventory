
var payroll_report_excel = new payroll_report_excel();

function payroll_report_excel()
{
	init();
	function init()
	{
		document_ready();// KIM BRIEL FIRST TIME AJAX YEY
	}

	function document_ready()
	{
		$(document).ready(function()
			{
				export_excel();
			});
	}


	function export_excel()
	{

		$('.export-to-excel').unbind('click');
		$('.export-to-excel').bind('click',function()
			{
				var payroll_deduction_id	= $('.payroll-deduction-id').val();
				var employee_id		= $('.employee-id').val();

				
				// $.ajax({
				// 	url: '/member/payroll/reports/export_loan_summary_report_to_excel',
				// 	type: 'get',
				// 	dataTypte: 'json',
				// 	data : {employee_id : employee_id, payroll_deduction_id : payroll_deduction_id},
				// 	success : function($data)
				// 	{
				// 		console.log('exporting excel success');
				// 	}
				// });
			});
	}
}
