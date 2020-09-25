var employee_leave_management = new employee_leave_management();

function employee_leave_management()
{
	init();

	function init()
	{
		ajax_load_approved_leave()
		ajax_load_rejected_leave()
		ajax_load_canceled_leave()

		$(document).ready(function()
		{
			document_ready();

		})
	}

	function document_ready()
	{
		ajax_load_pending_leave();
	}


	function ajax_load_pending_leave()
	{
			$(".tbl-tag").html('<tr><td colspan="8" class="text-center">'+misc('spinner') + '</td></tr>');
			$.ajax({
				url 	: 	"/leave/ajax_load_pending_leave",
				type 	: 	"POST",
				data 	: 	{
					_token:misc('_token')
				},
				success : 	function(result)
				{
					result = JSON.parse(result);
					var html = "";
					
					if(result.length > 0)
					{
						$(result).each(function(index, emp)
						{			
							 html += tbl_tag(emp);
						});
						$(".tbl-tag").html(html);
					}
					else
					{
					 	html += tbl_tag_2();
						$(".tbl-tag").html(html);
					}
				},
				error 	: 	function(err)
				{
					error_function();
				}
			});
	}

	function ajax_load_approved_leave()
	{
		$(".approved").on("click", function(e)
		{
			$(".tbl-tag").html('<tr><td colspan="8" class="text-center">'+misc('spinner') + '</td></tr>');
			$.ajax({
				url 	: 	"/leave/ajax_load_approved_leave",
				type 	: 	"POST",
				data 	: 	{
					_token:misc('_token')
				},
				success : 	function(result)
				{
					result = JSON.parse(result);
					var html = "";
					
					if(result.length > 0)
					{
						$(result).each(function(index, emp)
						{			
							 html += tbl_tag(emp);
						});
						$(".tbl-tag").html(html);
					}
					else
					{
					 	html += tbl_tag_2();
						$(".tbl-tag").html(html);
					}

				},
				error 	: 	function(err)
				{
					error_function();
				}
			});
		});
	}

	function ajax_load_rejected_leave()
	{
		$(".rejected").on("click", function(e)
		{
			$(".tbl-tag").html('<tr><td colspan="8" class="text-center">'+misc('spinner') + '</td></tr>');
			$.ajax({
				url 	: 	"/leave/ajax_load_rejected_leave",
				type 	: 	"POST",
				data 	: 	{
					_token:misc('_token')
				},
				success : 	function(result)
				{
					result = JSON.parse(result);
					var html = "";

					if(result.length > 0)
					{
						$(result).each(function(index, emp)
						{			
							 html += tbl_tag(emp);
						});
						$(".tbl-tag").html(html);
					}
					else
					{
					 	html += tbl_tag_2();
						$(".tbl-tag").html(html);
					}
		
			
				},
				error 	: 	function(err)
				{
					error_function();
				}
			});
		});
	}

	function ajax_load_canceled_leave()
	{
		$(".canceled").on("click", function(e)
		{
			$(".tbl-tag").html('<tr><td colspan="8" class="text-center">'+misc('spinner') + '</td></tr>');
			$.ajax({
				url 	: 	"/leave/ajax_load_canceled_leave",
				type 	: 	"POST",
				data 	: 	{
					_token:misc('_token')
				},
				success : 	function(result)
				{
					result = JSON.parse(result);
					var html = "";

					if(result.length > 0)
					{
						$(result).each(function(index, emp)
						{			
							 html += tbl_tag(emp);
						});
						$(".tbl-tag").html(html);
					}
					else
					{
					 	html += tbl_tag_2();
						$(".tbl-tag").html(html);
					}
		
			
				},
				error 	: 	function(err)
				{
					error_function();
				}
			});
		});
	}

	$(".pending").on("click", function(e)
	{
		ajax_load_pending_leave();
	});

	function error_function()
	{
		toastr.error("Error, something went wrong.");
	}

	function tbl_tag(data)
	{

		var html = '<tr class="text-center">';
		html += '<td>'+data.payroll_request_leave_date_filed+'</td>';
		html += '<td>'+data.payroll_request_leave_type+'</td>';
		html += '<td>'+data.payroll_request_leave_date+'</td>';
		html += '<td>'+data.payroll_request_leave_total_hours+'</td>';
		html += '<td>'+data.payroll_employee_display_name+'</td>';
		html += '<td>'+data.payroll_request_leave_status+'</td>';
		html += '<td>'+data.payroll_request_leave_status_level+'</td>';
		html += '<td><div class="dropdown">';
		html += '<button class="btn btn-link dropdown-toggle" type="button" id="menu-drop-down" data-toggle="dropdown" style="font-size:12px;">Action';
		html += '<span class="caret"></span></button>';
		html += '<ul class="dropdown-menu" role="menu" aria-labelledby="menu-drop-down">';
		html += '<li style="padding-left: 10px;" role="presentation" class="popup" link="/employee_request_leave_view/'+data.payroll_request_leave_id+'" size="lg"><a role="menuitem" tabindex="-1" href="#"><i class="fa fa-search" aria-hidden="true"></i> &nbsp; View</a></li>';
		html += '<li style="padding-left: 10px;" role="presentation" class="popup" link="/employee_request_leave_cancel/'+data.payroll_request_leave_id+'" size="sm"><a role="menuitem" tabindex="-1" href="#"><i class="fa fa-ban" aria-hidden="true"></i> &nbsp; Cancel</a></li>';
			html += '<li style="padding-left: 10px;" link= size="sm"><a role="form" target="_blank" tabindex="-1" href="/employee_request_leave_export_pdf/'+data.payroll_request_leave_id+'"><i class="fa fa-file-pdf-o" aria-hidden="true"></i>&nbsp;View PDF Form</a></li>';
		html += '</ul></div></td>';
		html += '</tr>';
		return html;
	}

	function tbl_tag_2()
	{
			var html = '<tr class="text-center">';
			html += '<td colspan="8"><h2 style="margin: 50px; text-align: center;">No Data</h2></td>';
			html += '</tr>';

			return html;
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
}