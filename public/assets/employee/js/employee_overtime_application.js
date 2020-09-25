var employee_overtime_application = new employee_overtime_application();
var data = {};
function employee_overtime_application()
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
			action_select_group_approver();
			action_select_group_approver_leave()
		});
	}

	function action_select_group_approver()
	{
	
		var target = $('.approver_group_list');

		$('.approver_group').unbind('change');
		$('.approver_group').bind('change', function()
		{
			data.approver_group_id = $('.approver_group').val();
			data._token = $('#_token').val();
			data.approver_group_type = 'overtime';
			
			$.ajax(
			{
				url: '/get_group_approver_list',
				type: 'post',
				data: data,
				success : function(data)
				{
					data = JSON.parse(data);
					var html = list_employee_group_by_level(data);
					target.html(html);
				}
			});
		});
	}

	function action_select_group_approver_leave()
	{
	
		var target = $('.approver_group_list_leave');

		$('.approver_group_leave').unbind('change');
		$('.approver_group_leave').bind('change', function()
		{
			data.approver_group_id = $('.approver_group_leave').val();
			data._token = $('#_token').val();
			data.approver_group_type = 'leave';
			
			$.ajax(
			{
				url: '/get_group_approver_list',
				type: 'post',
				data: data,
				success : function(data)
				{
					data = JSON.parse(data);
					var html = list_employee_group_by_level(data);
					target.html(html);
				}
			});
		});
	}


	function list_employee_group_by_level(data)
	{
		var html = "<ul class='list-group'>";

		$.each(data, function(level, group_approver) 
		{
			html += '<li> Level '+ level +' Approver/s <ul>'
				$.each(group_approver, function(index, employee_approver) 
				{
					html += '<li>'+ employee_approver['payroll_employee_first_name'] +'</li>';
				});
			html += '</ul></li>'
		});
		
		html += '</ul>';

		return html;
	}
}

function reload_overtime_management(data)
{
	data.element.modal("hide");
    window.location.href = '/employee_overtime_management';
}