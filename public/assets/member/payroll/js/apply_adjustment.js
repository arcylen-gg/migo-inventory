var apply_adjustment = new apply_adjustment();

function apply_adjustment_submit_done(data)
{
	$(".multiple_global_modal").modal("hide");

	setTimeout(function()
	{
		action_load_link_to_modal("/member/payroll/company_timesheet2/income_summary/" + data.period_id + "/" + data.employee_id, "lg");
	}, 300);
}
function apply_adjustment()
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
		event_change_adjustment_type();
		$("#global_modal").modal("hide");
	}
	function event_change_adjustment_type()
	{
		$(".adjustment-type").change(function(e)
		{
			$val = $(e.currentTarget).val();

			
			if($val == "addition")
			{
				$(".addition-setting").removeClass("hidden");
			}
			else
			{
				$(".addition-setting").addClass("hidden");
			}
		});
	}
}