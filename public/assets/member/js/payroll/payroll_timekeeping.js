var payroll_timekeeping = new payroll_timekeeping();

function payroll_timekeeping()
{
	init();

	function init()
	{
		$( document ).ready(function()
		{
			document_ready();
		});
	}
	function document_ready()
	{	
		event_change_company();
		action_load_payroll_time_keeping_table(0);
		event_change_tab();
	}

	function event_change_tab()
	{
		$(".change-tab").click(function(e)
		{
			$(".change-tab").removeClass("active");
			$(e.currentTarget).addClass("active");
			action_load_payroll_time_keeping_table();
		});
	}



	function event_change_company()
	{
		$('.company-change-event').on('change', function()
		{
  			action_load_payroll_time_keeping_table();
		});
	}


	function action_load_payroll_time_keeping_table()
	{
		var payroll_company_id = $('.company-change-event').val();
		var mode = $(".change-tab.active").attr("mode");
		$(".load-table-employee-list").html('<div style="padding: 150px 80px; padding-bottom: 500px; text-align: center; font-size: 30px; color: #1682ba"><i class="fa fa-spinner fa-pulse fa-fw"></i></div>');
		$(".load-table-employee-list").load( "/member/payroll/time_keeping/table/" + payroll_company_id + "?mode=" + mode, function()
		{
		});
	}

	this.reload_timekeeping = function()
	{
		$(".panel-timekeeping-list").load("/member/payroll/time_keeping .panel-timekeeping-list", function()
		{
			toastr.success("Time keeping list updated.");
		});
	}

	this.reload_period = function()
	{
		$(".period-list").load("/member/payroll/time_keeping/modal_generate_period .period-list", function()
		{
			toastr.success("New period has been created");
		});
	}
}

/* CALL A FUNCTION BY NAME */
function executeFunctionByName(functionName, context /*, args */) {
  var args = [].slice.call(arguments).splice(2);
  var namespaces = functionName.split(".");
  var func = namespaces.pop();
  for(var i = 0; i < namespaces.length; i++) {
    context = context[namespaces[i]];
  }
  return context[func].apply(context, args);
}


function submit_done(data)
{
	try
	{
		data = JSON.parse(data);
	}
	catch(err)
	{

	}
	
	data.element.modal("toggle");
	if(data.function_name == "payroll_period_list.reload_list")
	{
		window.location.reload();
	}
	
}

