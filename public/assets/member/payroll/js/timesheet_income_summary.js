var timesheet_income_summary = new timesheet_income_summary();

function timesheet_income_summary()
{
	init();
	function init()
	{
		$(document).ready(function()
		{
			document_ready();
		})
	}
	function document_ready()
	{
		action_click_approve_timesheet();
		event_make_adjustment();
		event_delete_adjustment();
	}
	function event_delete_adjustment()
	{
		$(".delete-adjustment").click(function(e)
		{
			var period_id = $(".employee-income-summary .period-id").val();
			var employee_id = $(".employee-income-summary .x-employee-id").val();
			var adjustment_id = $(e.currentTarget).attr("adjustment_id");
			action_load_link_to_modal("/member/payroll/company_timesheet2/delete_adjustment/" + period_id + "/" + employee_id + "/" + adjustment_id, "md");
		});
	}
	function event_make_adjustment()
	{
		$(".make-adjustment").click(function()
		{
			var period_id = $(".employee-income-summary .period-id").val();
			var employee_id = $(".employee-income-summary .x-employee-id").val();
			action_load_link_to_modal("/member/payroll/company_timesheet2/make_adjustment/" + period_id + "/" + employee_id, "md");
		});
	}
	function action_click_approve_timesheet()
	{
		$(".approve-timesheet-btn").unbind("click");
		$(".approve-timesheet-btn").bind("click", function()
		{
			$(".approve-timesheet-btn").html('<i class="fa fa-spinner fa-pulse fa-fw"></i>');

			var period_id = $(".employee-income-summary .period-id").val();
			var payroll_period_id = $(".employee-income-summary .payroll-period-id").val();
			var employee_id = $(".employee-income-summary .x-employee-id").val();

		
			$.ajax({
				url : "/member/payroll/company_timesheet_approve/approve_timesheet",
				type : "get",
				dataType:"json",
				data : {period_id : period_id, employee_id : employee_id, payroll_period_id : payroll_period_id,},
				success : function(data)
				{
					$("#global_modal").modal("hide");
					timesheet_employee_list.action_load_table();
				}
			})
		});
	}
}