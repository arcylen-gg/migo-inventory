var timesheet = new timesheet();
var timesheet_request = null;
var adjust_form_request = null;
var new_sub_ctr = 1000;
var timechangerequest = null;
var timechangerequestdelay = new Array();
function timesheet()
{
	init();

	this.external_event_time_entry = function()
	{
		event_time_entry();
	}
	this.external_reload_rate_for_date = function(tr_date)
	{
		action_reload_rate_for_date_start(tr_date);
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
		event_time_entry();
		event_create_new_time();
		event_click_custom_shift_checkbox();
		event_time_focus_out_recompute();
		event_load_summary();
		action_click_approve_timesheet();
	}
	function event_load_summary()
	{
		$(".load-summary").click(function()
		{
			$("#global_modal").modal("hide");
			var employee_id = $(".employee-timesheet-modal .x-employee-id").val();
			var period_id = $(".employee-timesheet-modal .period-id").val();

			setTimeout(function()
			{

				action_load_link_to_modal("/member/payroll/company_timesheet2/income_summary/" + period_id + "/" + employee_id, "lg");
			}, 500);
		});
	}
	function event_click_custom_shift_checkbox()
	{
		$(".custom-shift-checkbox").unbind("click")
		$(".custom-shift-checkbox").bind("click", function(e)
		{
			var timesheet_date = $(e.currentTarget).closest(".tr-parent").attr("date");
			var employee_id = $(".employee-timesheet-modal .x-employee-id").val();
			var period_id = $(".employee-timesheet-modal .period-id").val();
			var timesheet_id = $(e.currentTarget).closest(".tr-parent").attr("timesheet_id");
			action_load_link_to_modal('/member/payroll/company_timesheet_custom_shift?date=' + timesheet_date + '&employee_id=' + employee_id + '&period_id=' + period_id + '&timesheet_id=' + timesheet_id, 'lg');
		});
	}
	function action_click_approve_timesheet()
	{
		$(".approve-timesheet-btn").unbind("click");
		$(".approve-timesheet-btn").bind("click", function()
		{
			$(".approve-timesheet-btn").html('<i class="fa fa-spinner fa-pulse fa-fw"></i>');

			var period_id = $(".employee-timesheet-modal .period-id").val();
			var employee_id = $(".employee-timesheet-modal .x-employee-id").val();
			
			$.ajax({
				url : "/member/payroll/company_timesheet_approve/approve_timesheet",
				type : "get",
				dataType:"json",
				data : {period_id : period_id, employee_id : employee_id},
				success : function(data)
				{
					$("#global_modal").modal("hide");
					timesheet_employee_list.action_load_table();
				}
			})
		});
	}
	/* EVENTS */
	function event_create_new_time()
	{
		$(".table-timesheet").on("keypress",".new-time-event", function(e)
		{
			if(e.which == 13)
			{
				action_create_new_line($(e.currentTarget).closest(".tr-parent"));
			}
		});
	}
	function event_time_entry()
	{
		$(".time-entry").timeEntry('destroy');
		$(".time-entry-24").timeEntry('destroy');
		$(".time-entry.time-in").timeEntry({ampmPrefix: ' ', defaultTime: new Date(0, 0, 0, 0, 0, 0)});
		$(".time-entry.time-out").timeEntry({ampmPrefix: ' ', defaultTime: new Date(0, 0, 0, 12, 0, 0)});
		$(".time-entry-24").timeEntry({show24Hours: true, defaultTime: new Date(0, 0, 0, 0, 0, 0)});

		$(".time-entry.prevent_edit").each(function(key, val)
		{
			$(this).timeEntry('disable');
			console.log(key);
		});

		//alert(123);
	}

	function event_time_focus_out_recompute()
	{
		/* STORE INITITAL VALUE */
		$(".table-timesheet").on("focusin", ".time-entry", function(e)
		{
			$focus_value = $(e.currentTarget).val();
			$(e.currentTarget).attr("focus_value", $focus_value);
		});


		$(".table-timesheet").on("keyup", ".time-entry", function(e)
		{
			var tr_date = $(e.currentTarget).closest(".tr-parent").attr("date");
			var td_class = $(e.currentTarget).closest("td").attr("class");
			var time_in_val = $(e.currentTarget).closest(".tr-parent").find('.time-out-td').find('.time-in').first().val();
			
			if (td_class == "time-comment-td") 
			{
				action_time_sheet_save_remark(tr_date);
			}
			else
			{
				action_reload_rate_for_date(tr_date);
			}
		});

		/* CHECK IF INITIAL VALUE CHANGED AFTER FOCUS OUT */
		$(".table-timesheet").on("focusout", ".time-entry", function(e)
		{
			$focus_value = $(e.currentTarget).attr("focus_value");
			$focus_unq = $(e.currentTarget).attr("unq");

			/* CHECK IF TIME IN AND TIME OUT IS BLANK */
			$time_in = $(".time-in[unq=" + $focus_unq + "]").val(); 
			$time_out = $(".time-out[unq=" + $focus_unq + "]").val();

			/* IF BOTH BLANK THEN JUST REMOVE */
			if($time_in == "" && $time_out == "" && $(e.currentTarget).closest(".tr-parent").find(".time-in").length > 1)
			{
				$(".time-in[unq=" + $focus_unq + "]").remove(); 
				$(".time-out[unq=" + $focus_unq + "]").remove();
				$(".comment[unq=" + $focus_unq + "]").remove();
			}
		});
	}

	function action_reload_rate_for_date(tr_date)
	{
		clearTimeout(timechangerequestdelay[tr_date]);
		$target = $(".tr-parent[date='" + tr_date + "']");
		$target.find(".rate-output").css("opacity", "0.5");

	    timechangerequestdelay[tr_date] = setTimeout(function()
	    {
	    	action_reload_rate_for_date_start(tr_date)
	    }, 1000);
	}

	/* ACTIONS */
	function action_reload_rate_for_date_start(tr_date)
	{
		$target = $(".tr-parent[date='" + tr_date + "']");
		$target.find(".rate-output").css("opacity", "0.5");
		console.log("RELOADING RATE FOR DATE");
		$input = $(".timesheet-of-employee").find(".tr-parent[date='" + tr_date + "'] :input").serialize();
		
		$period_id = $(".employee-timesheet-modal .period-id").val();
		$employee_id = $(".employee-timesheet-modal .x-employee-id").val();
				
		$url = "/member/payroll/company_timesheet2/change/" + $period_id + "/" + $employee_id;

		if(timechangerequest)
		{
			timechangerequest.abort();
		}

		timechangerequest = $.ajax(
		{
			url: $url,
			dataType: "json",
			data: $input,
			type:"post",
			success: function(data)
			{
				console.log("TIME CHANGED SUCCESS");
				$target = $(".tr-parent[date='" + tr_date + "']");
				$target.find(".rate-output").html(data.string_income).css("opacity", "1");
			}
		})
	}

	function action_time_sheet_save_remark(tr_date)
	{
		$input = $(".timesheet-of-employee").find(".tr-parent[date='" + tr_date + "'] :input").serialize();

		$period_id = $(".employee-timesheet-modal .period-id").val();
		$employee_id = $(".employee-timesheet-modal .x-employee-id").val();

		$.ajax({
			url: '/member/payroll/company_timesheet2/remarks_change/'+ $period_id + "/" + $employee_id,
			type: 'get',
			data: $input,
			success : function (data)
			{

			}
		})
		.done(function() {
			console.log("success");
		})
		.fail(function() {
			console.log("error");
		})
		.always(function() {
			console.log("complete");
		});
		
	}


	function action_create_new_line($target_tr)
	{
		$time_in_blank = false;
		$time_out_blank = false;

		/* CHECK IF ONE OF TIME-IN IS BLANK */
		$target_tr.find(".time-in").each(function(key)
		{
			if($(this).val() == "")
			{
				$time_in_blank = true;
			}
		});

		/* CHECK IF ONE OF TIME-OUT IS BLANK */
		$target_tr.find(".time-out").each(function(key)
		{
			if($(this).val() == "")
			{
				$time_out_blank = true;
			}
		});

		if($time_in_blank == false && $time_out_blank == false)
		{
			$unq = getRandomIntInclusive(10000000, 999999999);
			$target_tr.find(".time-in-td").append('<input name="time-in[]" unq="' + $unq + '" value="" type="text" placeholder="NO TIME" class="new-time-event new-time-focus text-table text-center time-entry time-in is-timeEntry" >');
			$target_tr.find(".time-out-td").append('<input name="time-out[]" unq="' + $unq + '" value="" type="text" placeholder="NO TIME" class="new-time-event text-table text-center time-entry time-out is-timeEntry">');
			$target_tr.find(".time-comment-td").append('<input name="remarks[]" unq="' + $unq + '" value="" type="text" class="comment new-time-event text-table time-entry is-timeEntry" name="">');
			$target_tr.find(".source-td").append('<input unq="' + $unq + '" type="text" disabled class="comment new-time-event text-table" value="Manually Encoded">');
			
			$target_tr.find(".new-time-focus").focus().removeClass("new-time-focus");
			event_time_entry();
		}
	}

	function j_number_format(number)
	{
	    number = number.toFixed(2) + '';
	    x = number.split('.');
	    x1 = x[0];
	    x2 = x.length > 1 ? '.' + x[1] : '';
	    var rgx = /(\d+)(\d{3})/;
	    while (rgx.test(x1)) {
	        x1 = x1.replace(rgx, '$1' + ',' + '$2');
	    }
	    return x1 + x2;
	}

	function getRandomIntInclusive(min, max)
	{
	  min = Math.ceil(min);
	  max = Math.floor(max);
	  return Math.floor(Math.random() * (max - min + 1)) + min; //The maximum is inclusive and the minimum is inclusive 
	}
}	