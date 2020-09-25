var shift_temp = new shift_temp();

function shift_temp()
{
	
	init();

	function init()
	{
		$(document).ready(function()
		{
			document_ready();
		})
	}

	function document_ready()
	{
		custom_drop_down($(".shift_temp"));	
	}

	function custom_drop_down(target)
	{
		$(target).globalDropList(
		{  
		  hasPopup                : "true",
		  link                    : "/member/customer/modalcreatecustomer",
		  link_size               : "lg",
		  width                   : "100px",
		  maxHeight				  : "129px",
		  placeholder             : "Search....",
		  no_result_message       : "No result found!",
		  onChangeValue           : function(){},
		  onCreateNew             : function(){},
		})
	}

	function event_click_btn()
	{
		$("body").on("click", ".btn", function(e)
		{
			action_show_quote_of_button($(e.currentTarget));
			action_highlight_button($(e.currentTarget));
			action_get_customer_data();
		});
	}
	function action_get_customer_data()
	{
		$.ajax(
		{
			url:"/home_ajax_customer",
			dataType:"json",
			type:"get",
			success: function(data)
			{
				$.each(data._customer, function(key, val)
				{
					$(".btn-container").append("<button class='btn btn-default' description='" + val.first_name + "'>" + val.last_name.toUpperCase() + "</button>")

				});
			}
		})
	}

}