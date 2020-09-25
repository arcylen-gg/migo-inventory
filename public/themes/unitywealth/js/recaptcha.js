var recaptcha = new recaptcha();

function recaptcha()
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
		event_submit_form();
		select2();
		action_set_slot_no();
		event_change_slot();
	}
	function select2()
	{
		$('.slot-owner').select2();
	}
	function event_change_slot()
	{
		$('.slot-owner').on('change',function()
		{
			action_set_slot_no();
		});
	}
	function action_set_slot_no()
	{
		$('.hidden_slot_no').val($('.slot-owner').val());
	}
	function event_submit_form()
	{
		$('.submit-captcha').on('click',function(e)
		{
			if($('.g-recaptcha-response').val() == '')
			{
				e.preventDefault();
				toastr.error('Verify Captcha First');
			}
			else
			{
				console.log('submitted');
			}
		});
	}
	
}