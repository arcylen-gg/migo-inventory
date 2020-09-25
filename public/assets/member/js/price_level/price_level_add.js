var add_price_level = new add_price_level();

function add_price_level()
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
		event_price_level_checkbox_click();
		event_change_price_level_select();
		action_change_price_level_select();
		event_check_all_item_click();
		event_adjust_marked_items();
	}
	function event_change_price_level_select()
	{
		$(".select-type-of-price-level").change(function(e)
		{
			action_change_price_level_select()
		});
	}
	function action_change_price_level_select()
	{
		$price_level_type = $(".select-type-of-price-level").val();

		$(".price-level-container").hide();
		$(".price-level-container[leveltype=" + $price_level_type + "]").show();
	}
	function event_price_level_checkbox_click()
	{
		$(".price-level-check-event").click(function(e)
		{
			action_price_level_checkbox_click(e);
		});
	}
	function action_price_level_checkbox_click(e)
	{
		if(!$(e.target).is('.checkboxs'))
		{
			$(e.currentTarget).closest("tr").find(".checkboxs").trigger("click");
		}
	}

	function event_check_all_item_click()
	{
		$(".check-all-item").click(function(e)
		{
			action_check_all_item_click(e);
		});
	}
	function action_check_all_item_click(e)
	{
		if(!$(e.target).is('.checkbox-all'))
		{
			$(".checkbox-all").trigger("click");
		}

		if($(".checkbox-all").is(":checked"))
		{
			$(".checkboxs").each(function() 
			{
			    if(!$(this).is(":checked"))
			    {
			    	// $(this).trigger("click");
			    	$(this).prop("checked",true);
			    }
			});
		}
		else
		{
			$(".checkboxs").each(function() 
			{
			    if($(this).is(":checked"))
			    {
			    	// $(this).trigger("click");
			    	$(this).prop("checked",false);
			    }
			});
		}
	}
	function event_adjust_marked_items()
	{
		$(".adjust-marked-btn").click(function()
		{
			action_adjust_marked_items();
		});
	}
	function action_adjust_marked_items()
	{
		$(".checkboxs").each(function() 
		{
		    if($(this).is(":checked"))
		    {
		    	adjust_target_item(this);
		    }
		});
	}
	function adjust_target_item($row)
	{
		$adjust_price_type    = $(".adjust-price-type").val();
		$adjust_price_percent = $(".adjust-price-percent").val();
		$adjust_price_range   = $(".adjust-price-range").val();
		$adjust_price_roundup = $(".adjust-price-roundup").val();
		$target_item 		  = $($row).closest("tr");
		$value                = 0;
		$total                = 0;

		$adjust_price_percent = parseFloat($adjust_price_percent);

		if($adjust_price_type == "standard price")
		{
			$value = $target_item.find(".item-price").attr("item-value");
		}
		else if($adjust_price_type == "cost price")
		{
			$value = $target_item.find(".item-cost").attr("item-value");
		}

		$value = parseFloat($value);

		if($adjust_price_range == "lower")
		{
			$total = $value - ($value * ($adjust_price_percent/100));
		}
		else if($adjust_price_range == "upper")
		{
			$total = $value + ($value * ($adjust_price_percent/100));
		}

	
		$target_item.find(".custom-price-textbox").val($total);
	}
}