var transaction_reset = new transaction_reset();
function transaction_reset()
{
	init();

	function init()
	{
		event_click_reset_btn();
		event_click_check_all();
		event_click_check_single();
	}
	function event_click_check_all()
	{
		$("body").on("click", ".check-all", function()
		{
			if($(this).is(':checked'))
			{
				$(this).parents(".check-list").find(".check-li").prop('checked', true);
			}
			else
			{
				$(this).parents(".check-list").find(".check-li").prop('checked', false);
			}
		});
	}
	function event_click_check_single()
	{
		$("body").on("click", ".items-check", function()
		{
			if($(this).is(':checked'))
			{
				$(".inventory-check").prop('checked', true);
				$(".transaction-check").prop('checked', true);
				$(".journal_entry-check").prop('checked', true);
			}
			else
			{
				$(".category-check").prop('checked', false);
				$(".inventory-check").prop('checked', false);
				$(".transaction-check").prop('checked', false);
				$(".journal_entry-check").prop('checked', false);
			}
		});

		$("body").on("click", ".category-check", function()
		{
			if($(this).is(':checked'))
			{
				$(".items-check").prop('checked', true);
				$(".inventory-check").prop('checked', true);
				$(".transaction-check").prop('checked', true);
				$(".journal_entry-check").prop('checked', true);
			}
			else
			{
				$(".items-check").prop('checked', false);
				$(".inventory-check").prop('checked', false);
				$(".transaction-check").prop('checked', false);
				$(".journal_entry-check").prop('checked', false);
			}
		});
		$("body").on("click", ".transaction-check", function()
		{
			if($(this).is(':checked'))
			{
				$(".inventory-check").prop('checked', true);
				$(".journal_entry-check").prop('checked', true);
			}
			else
			{
				$(".inventory-check").prop('checked', false);
				$(".journal_entry-check").prop('checked', false);
			}
		});

		$("body").on("click", ".customer-check", function()
		{
			if($(this).is(':checked'))
			{
				$(".inventory-check").prop('checked', true);
				$(".transaction-check").prop('checked', true);
				$(".journal_entry-check").prop('checked', true);
			}
			else
			{
				$(".inventory-check").prop('checked', false);
				$(".transaction-check").prop('checked', false);
				$(".journal_entry-check").prop('checked', false);
			}
		});
		$("body").on("click", ".warehouse-check", function()
		{
			if($(this).is(':checked'))
			{
				$(".inventory-check").prop('checked', true);
				$(".transaction-check").prop('checked', true);
			}
			else
			{
				$(".inventory-check").prop('checked', false);
				$(".transaction-check").prop('checked', false);
			}
		});

		$("body").on("click", ".vendor-check", function()
		{
			if($(this).is(':checked'))
			{
				$(".inventory-check").prop('checked', true);
				$(".transaction-check").prop('checked', true);
				$(".journal_entry-check").prop('checked', true);
			}
			else
			{
				$(".inventory-check").prop('checked', false);
				$(".transaction-check").prop('checked', false);
				$(".journal_entry-check").prop('checked', false);
			}
		});

		$("body").on("click", ".journal_entry-check", function()
		{
			if($(this).is(':checked'))
			{
				$(".inventory-check").prop('checked', true);
				$(".transaction-check").prop('checked', true);
			}
			else
			{
				$(".inventory-check").prop('checked', false);
				$(".transaction-check").prop('checked', false);
			}
		});
	}
	function event_click_reset_btn()
	{
		$("body").on("click", ".reset-btn", function()
		{
			var password = prompt("WARNING! All selected transaction will be deleted. Please write RESET if you are sure.");
			if(password)
			{
				$(".encrypt-pass").val(password);
				$(".form-submit").submit();
			}
		});
	}
}
function success_reset(data)
{
	if(data.status == 'success')
	{
		toastr.success(data.status_message);
		location.reload();
	}
}