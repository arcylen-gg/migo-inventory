var timesheet_employee_list = new timesheet_employee_list();

function timesheet_employee_list()
{
	init();

	this.action_load_table = function()
	{
		action_load_table();
	}
	this.action_set_to_unapprove = function(period_company_id, employee_id)
	{
		action_set_to_unapprove(period_company_id, employee_id);
	}


	function init()
	{
		$(document).ready(function()
		{
			document_ready();
		})
	}
	function document_ready()
	{
		event_change_tab();
		event_search_employee_name();
		event_focus_edit();
		action_load_table();
		event_change_company();
	}
	function event_change_company()
	{
		$(".company-change-event").change(function(e)
		{
			action_load_table();
		});
	}
	function action_reload_different_company($payroll_period_company_id)
	{
		window.location.href = '/member/payroll/company_timesheet2/' + $payroll_period_company_id;
	}
	function event_change_tab()
	{
		$(".change-tab").click(function(e)
		{
			$(".change-tab").removeClass("active");
			$(e.currentTarget).addClass("active");
			action_load_table();
		});
	}
	function event_search_employee_name()
	{
		$(".search-employee-name").keypress(function(e)
		{
			if(e.which == 13)
			{
				action_load_table()
			}
		});
	}
	function event_focus_edit()
	{
		$("body").on("focusin", ".text-table", function(e)
		{
			$(e.currentTarget).closest("tr").addClass("focus");
		});
		$("body").on("focusout", ".text-table", function(e)
		{
			$(e.currentTarget).closest("tr").removeClass("focus");
		});
		$("body").on("focusout", ".edit-data", function(e)
		{
			$(e.currentTarget).closest("tr").find(".time-in").focus();
		});
	}
	function action_set_to_unapprove(period_company_id, employee_id)
	{
		if(confirm("Are you sure you want to set this employee to unapprove?"))
		{
			$.ajax(
			{
				url:"/member/payroll/company_timesheet2/unapprove/" + period_company_id + "/" + employee_id,
				dataType:"json",
				type:"get",
				success: function(data)
				{
					action_load_table();
				}
			});
		}
	}
	function action_load_table()
	{
		var payroll_period_id = $(".payroll-period-id").val();
		$(".load-table-employee-list").html('<div style="padding: 150px 80px; padding-bottom: 500px; text-align: center; font-size: 30px; color: #1682ba"><i class="fa fa-spinner fa-pulse fa-fw"></i></div>');
		
		$mode = $(".change-tab.active").attr("mode");
		$search = $(".search-employee-name").val();
		$branch = $(".company-change-event").val();

		$url = "/member/payroll/company_timesheet2/table/" + payroll_period_id + "?search=" + $search + "&mode=" + $mode + "&branch=" + $branch;
		
		$(".load-table-employee-list").load($url, function()
		{
			
		})
	}
}	


function submit_done(data)
{
	if(data.function_name == "custom_shift_success")
	{
		$(".multiple_global_modal").modal("hide");
		$(".timesheet-of-employee").find(".tr-parent[timesheet_id='" + data.payroll_time_sheet_id + "']").find(".custom-shift-checkbox").text("CUSTOM");
		timesheet.external_reload_rate_for_date(data.sheet_date);
	}
}