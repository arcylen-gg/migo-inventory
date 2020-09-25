var new_token = new new_token();

function new_token()
{
	init();

	this.action_load_table = function()
	{
		action_load_table();
	}

	function init()
	{
		$(document).ready(function()
		{
			document_ready();
		});
	}
	function document_ready()
	{
		event_change_tab();
		action_load_table();
		event_click_action();
		event_click_modify();
	}
	function event_change_tab()
	{
		$('.change_tab').click(function(e)
		{
			$('.change_tab').removeClass('active');
			$(e.currentTarget).addClass('active');
			action_load_table();
		});
	}
	function action_table_loader()
	{
		$(".load-table-here").html('<div style="padding:10px; text-align: center; font-size: 20px;"><i class="fa fa-spinner fa-pulse fa-fw"></i></div>');
	}
	function action_load_table()
	{
		var activetab = $('.change_tab.active').attr('mode');

		action_table_loader();
		$.ajax(
		{
			url: '/member/item/token/token-list-table',
			type: 'get',
			data: 'activetab='+activetab,
			success: function(data)
			{
				$('.load-table-here').html(data);
			}
		});
	}
	function event_click_action()
	{
		$('body').on('click','.token-archive',function(e)
		{
			var archived = 0;
			var id = $(e.currentTarget).closest('tr').attr('id');
			if($(e.currentTarget).text()[0] == 'A')
			{
				console.log('archive');
				archived = 1;
			}
			else
			{
				console.log('restore');
				archived = 0;
			}

			$.ajax(
			{
				url: '/member/item/token/archived',
				type: 'get',
				data: 'archived='+archived+'&token_id='+id,
				success: function(data)
				{
					console.log('updated');
					action_load_table();
				}
			});
			action_table_loader();
		});
	}
	function event_click_modify()
	{
		$('body').on('click','.token-modify',function(e)
		{
			var token_id = $(e.currentTarget).closest('tr').attr('id');
			action_load_link_to_modal('/member/item/token/update-token?id='+token_id, 'md');
		});
	}

}