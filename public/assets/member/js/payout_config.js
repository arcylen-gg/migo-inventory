var payout_config = new payout_config();

function payout_config()
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
		event_schedule_type_select();
		event_change_service_type();
	}

	function event_schedule_type_select()
	{
		action_schedule_type_select(".schedule-type-container .schedule-type:checked");

		$(".schedule-type-container .schedule-type").off("change");
		$(".schedule-type-container .schedule-type").on("change", function(e)
		{
			action_schedule_type_select(e.currentTarget);
		});
	}
	function event_change_service_type()
	{
		$('.change-service-type').unbind('keyup');
		$('.change-service-type').bind('keyup',function()
		{
			$('.service_fee_type').val(0);
			var service_charge_string = $(this).val();
			if(service_charge_string.indexOf('%') > 0)
			{
				$('.service_fee_type').val(1);
			}
		});
	}
	function action_schedule_type_select(self)
	{
		var schedule_type = $(self).val();

		hide_all_type();

		if (schedule_type == "weekly") 
		{
			$('.schedule-type-weekly').removeClass("hide");
			$('.schedule-type-weekly input').removeProp("disabled");
			$('.schedule-type-weekly input').removeAttr("disabled");
		}
		else if (schedule_type == "monthly") 
		{
			$('.schedule-type-monthly').removeClass("hide");
			$('.schedule-type-monthly input').removeProp("disabled");
			$('.schedule-type-monthly input').removeAttr("disabled");
		}
		else
		{
			hide_all_type();
		}
	}

	function hide_all_type()
	{
		$('.schedule-type-weekly').addClass("hide");
		$('.schedule-type-monthly').addClass("hide");

		$('.schedule-type-weekly input').prop("disabled", true);
		$('.schedule-type-monthly input').prop("disabled", true);
		$('.schedule-type-weekly input').attr("disabled", "disabled");
		$('.schedule-type-monthly input').attr("disabled", "disabled");
	}
}