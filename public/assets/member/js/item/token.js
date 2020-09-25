var token = new token();

function token()
{
	init();

	this.action_load_tokens = function()
	{
		action_load_tokens();
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
		event_new_token();
	}
	function event_new_token()
	{
		$('.token-type').on('change',function()
		{
			if($('.token-type').val() == 'add_new')
			{
				$('.token-type').val('');
				console.log('show modal');
				action_load_link_to_modal('/member/item/token/add-token', 'md');
			}
		});
	}
	function action_load_tokens()
	{
		console.log('loading options');
		$('.token-type').attr('disabled','disabled');
		$.ajax(
		{
			url: '/member/item/token/token-list',
			type: 'get',
			success: function(data)
			{
				$('.tokens-holder').html(data);
				$('.token-type').removeAttr('disabled');
				console.log(data);
				event_new_token();
			}
		});
	}
}