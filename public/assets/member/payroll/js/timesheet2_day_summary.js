var timesheet_day_summary = new timesheet_day_summary();
var timechangerequestday = null;

function timesheet_day_summary()
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
		event_time_entry();
		event_change_approve_check();
		event_mark_as_checked();
		action_check_each_approve_check();
		event_change_time_approve();
	}
	function event_mark_as_checked()
	{
		$(".mark-as-checked").click(function()
		{
			$(".mark-as-checked").html('<i class="fa fa-spinner fa-pulse fa-fw"></i>');
			action_re_compute_on_change(true);
		});
	}


	/* EVENTS */
	function event_time_entry()
	{
		$(".time-entry").timeEntry('destroy');
		$(".time-entry-24").timeEntry('destroy');
		$(".time-entry.time-in").timeEntry({ampmPrefix: ' ', defaultTime: new Date(0, 0, 0, 0, 0, 0)});
		$(".time-entry.time-out").timeEntry({ampmPrefix: ' ', defaultTime: new Date(0, 0, 0, 12, 0, 0)});
		$(".time-entry-24").timeEntry({show24Hours: true, defaultTime: new Date(0, 0, 0, 0, 0, 0)});
	}
	
	function event_change_approve_check()
	{
		$(".approve-checkbox").change(function(e)
		{
			$target = $(e.currentTarget);
			action_show_hide_overtime_depending_if_checked($target);
			action_re_compute_on_change();
		});
	}

	function event_change_time_approve()
	{
		$(".day-time-change").keyup(function()
		{
			action_re_compute_on_change();
		});
	}
	
	/* ACTIONS */
	
	function action_check_each_approve_check()
	{
		$(".approve-checkbox").each(function()
		{
			action_show_hide_overtime_depending_if_checked($(this));
		});
	}
	function action_show_hide_overtime_depending_if_checked($target)
	{
		if($target.is(":checked"))
		{
			$target.closest("tr").find(".overtime-checkbox").show();
		}
		else
		{
			$target.closest("tr").find(".overtime-checkbox").hide();
		}
	}
	function action_re_compute_on_change($approve = false)
	{
		$input = $(".day-summary-table :input").serialize();
		$url = "/member/payroll/company_timesheet_day_summary/change?approve=" + $approve;
		$payroll_time_sheet_id = $(".payroll-time-sheet-id").val();
		$period_company_id = $(".period-company-id").val();
		$day =  $(".day-summary-date").val();
		
		$(".load-detail-table").css("opacity", 0.3);

		if(timechangerequestday)
		{
			timechangerequestday.abort();
		}

		timechangerequestday = $.ajax(
		{
			url: $url,
			dataType: "json",
			data: $input,
			type:"post",
			success: function(data)
			{

				$(".load-detail-table").load("/member/payroll/company_timesheet_day_summary/info/" + $payroll_time_sheet_id + "?period_company_id=" + $period_company_id, function()
				{
					$(".load-detail-table").css("opacity", 1);
					$rate_output = $(".hidden-compute-for-timesheet").html();
					$(".table-timesheet").find(".tr-parent[date='" + $day + "']").find(".rate-output").html($rate_output);
					
					if($approve == true)
					{
						$(".multiple_global_modal").modal("hide");
					}
				});
			}
		});
	}
}	