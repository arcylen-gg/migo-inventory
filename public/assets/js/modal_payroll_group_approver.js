var modal_payroll_group_approver = new modal_payroll_group_approver();
var approver_level_count;
var approver_type; 
var approver_group_id;

function modal_payroll_group_approver()
{
	init();


	this.reload_group_approver = function()
	{
		reload_group_approver();
	}

	function init()
	{
		ready_document();
	}

	function ready_document()
	{
		$(document).ready(function() 
		{
			approver_level_count = $('#approver_level_count').val();
			approver_type = $('#approver_type').val();
			approver_group_id = $('#approver_group_id').val();
			
			action_change_level_and_type();
			action_edit_group_modal();
		});
	}

	function action_change_level_and_type()
	{
		$('#approver_level_count').unbind('change');
		$('#approver_level_count').bind('change',function(event) 
		{
			
			approver_level_count = $(this).val();

			if (approver_type != '' && approver_level_count != 0) 
			{
				append_selector(approver_level_count ,approver_type);
			}
		});

		$('#approver_type').unbind('change');
		$('#approver_type').bind('change',function(event) 
		{
			approver_type = $(this).val();

			if (approver_type != '' && approver_level_count != 0) 
			{
				append_selector(approver_level_count ,approver_type);
			}
		});

		
	}


	function append_selector(level, type)
	{
		var target = $('.approver-container');
		target.html(misc('loader'))
		$.ajax({
			url: '/member/payroll/payroll_admin_dashboard/get_employee_approver_by_level',
			type: 'get',
			data: {level : level, type : type},
			success: function(data)
			{
				target.html('<hr>'+data);
			}
		});
		
	}

	function action_edit_group_modal()
	{
		/*check if dom exist*/
		if ($('#approver_group_id').length != 0) 
		{
			approver_level_count = $('#approver_level_count').val();
			approver_type = $('#approver_type').val();
			approver_group_id = $('#approver_group_id').val();

			if (approver_type != '' && approver_level_count != 0) 
			{
				append_selector_edit_modal(approver_level_count ,approver_type, approver_group_id);
			}
		}
	}

	function append_selector_edit_modal(level, type, approver_group_id)
	{
		var target = $('.approver-container');
		target.html(misc('loader'))
		$.ajax({
			url: '/member/payroll/payroll_admin_dashboard/get_employee_approver_by_level',
			type: 'get',
			data: {level : level, type : type, approver_group_id : approver_group_id},
			success: function(data)
			{
				target.html('<hr>'+data);
			}
		});
		
	}


	function reload_group_approver()
	{
		window.location.href = '/member/payroll/payroll_admin_dashboard/group_approver';
	}



	function misc(str){
		var spinner = '<i class="fa fa-spinner fa-pulse fa-fw"></i><span class="sr-only">Loading...</span>';
		var plus = '<i class="fa fa-plus" aria-hidden="true"></i>';
		var times = '<i class="fa fa-times" aria-hidden="true"></i>';
		var pencil = '<i class="fa fa-pencil" aria-hidden="true"></i>';
		var loader = '<div class="loader-16-gray"></div>'
		var _token = $("#_token").val();

		switch(str){
			case "spinner":
				return spinner
				break;

			case "plus":
				return plus
				break;

			case "loader":
				return loader
				break;

			case "_token":
				return _token
				break;
			case "times":
				return times
				break;
			case "pencil":
				return pencil
				break;
		}
	}
}


/* CALL A FUNCTION BY NAME */
function executeFunctionByName(functionName, context /*, args */) 
{
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
	console.log(data.function_name);
	try
	{
		data = JSON.parse(data);
	}
	catch(err)
	{

	}
	conole.log(data);


	if(typeof data.type  !== 'undefined')
	{
		
	}
	else
	{
		console.log(data.function_name);
		executeFunctionByName(data.function_name, window);
	}
}