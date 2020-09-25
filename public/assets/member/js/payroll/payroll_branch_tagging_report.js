var payroll_branch_tagging_report = new payroll_branch_tagging_report();


function payroll_branch_tagging_report()
{
	init();

	function init()
	{
		ready_document();
	}

	function ready_document()
	{
		$(document).ready(function()
		{
			// biometric_load_table();
			action_load_table();
			// biometric_import_data();
		});
	}

	function action_load_table()
	{
		$('.btn-show').click(function()
		{
			biometric_load_table();
		});
	}

	function biometric_load_table()
	{
		var period_date_start = $('.period_date_start').val()
		var period_date_end = $('.period_date_end').val();
		
		$(".branch-tagging-table-load").html('<div style="padding: 150px 80px; padding-bottom: 500px; text-align: center; font-size: 30px; color: #1682ba"><i class="fa fa-spinner fa-pulse fa-fw"></i></div>');
		

		$.ajax({
			url		:  '/member/payroll/reports/branch_tagging_report_period_table',
			data	: {period_date_start : period_date_start, period_date_end : period_date_end},
			type	: 'GET',
			success : function(result)
			{
				$('.branch-tagging-table-load').html(result);
			}
		});
	}
}