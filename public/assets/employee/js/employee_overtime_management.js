var employee_overtime_management = new employee_overtime_management();
var data = {};
var status = '';
function employee_overtime_management()
{
	init();

	function init()
	{
		document_ready();
	}

	function document_ready()
	{
		$(document).ready(function() {
			action_select_tab();
			event_change_tab_content_data('pending');
			action_select_paginate_page();
		});
	}

	function action_select_paginate_page()
	{

	}

	function action_select_tab()
	{
		
		$('.tabs').on('click',function(e)
		{
			$('.tabs').removeClass('active');
			$(e.currentTarget).addClass('active');
			status = $(e.currentTarget).attr('data-type');
			event_change_tab_content_data(status);
		});
	}

	function event_change_tab_content_data(status)
	{
		$target = $('.tab-content');
		$target.html(misc('loader'));

		$.ajax({
			url: '/employee_overtime_management_table',
			type: 'get',
			data: {status: status},
			success: function(data)
			{
				$target.html(data);
			}
		});
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