var checkout_form = new checkout_form();
var ajax_load_location = null;
var ajax_load_bill_location = null;

var	input_quantity = ".input-quantity";
var rawprice = ".raw-price";
var subtotal = ".sub-total";
var maincontainer = ".cart-item-container";
var total = '.subtotal';

function checkout_form()
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
		action_load_location(1, 0);
		action_load_bill_location(1, 0)
		action_load_sidecart();
		action_load_location();
		action_load_bill_location();
		event_load_location_change();
		event_show_bill_address();
	}

	function action_load_sidecart()
	{
		$(".order-summary-container").load("/checkout/side");
	}

	function action_load_location(level, parent)
	{
		if(level < 4)
		{
			$(".load-location[level=" + level + "]").html("<option>LOADING LOCATION</option>");

			var deflt;
			var firstload = false;

			/* GET DEFAULT ON FIRST LOAD */
			if($(".load-location[level=" + level + "]").attr("firstload") == "true")
			{
				$(".load-location[level=" + level + "]").attr("firstload", "false");
				firstload = true;
				deflt = $(".load-location[level=" + level + "]").attr("default");
			}

			if(ajax_load_location)
			{
				ajax_load_location.abort();
			}

			ajax_load_location = 	$.ajax(
									{
						            	url: '/checkout/locale?parent=' + parent,
						            	success: function(data)
						            	{
						            		$(".load-location[level=" + level + "]").html(data);

						            		if(deflt != "" && firstload == true)
						            		{
						            			$(".load-location[level=" + level + "]").val(deflt);
						            		}

						            		if (level == 3) 
						            		{
						            			var append = 'NEXT <i class="fa fa-angle-double-right"></i>';

						            			$('.checkout-button-submit').html(append);
						            			$('.checkout-button-submit').removeAttr("disabled");
						            			$('.checkout-button-submit').removeProp("disabled");
						            		}
						            		
						              		action_load_location(level+1, $(".load-location[level=" + (level) + "]").val());
						            	}
						          	});
		}
	}

	function event_load_location_change()
	{
		$(".load-location").change(function(e)
		{
			parent = $(e.currentTarget).val();
			level = parseInt($(e.currentTarget).attr("level")) + 1;
			action_load_location(level, parent);

			if($(e.currentTarget).attr("level") == 3)
			{
				$(".checkout-summary .loader-here").removeClass("hidden");
				action_load_sidecart();
			}

		});

		$(".bill-load-location").change(function(e)
		{
			parent = $(e.currentTarget).val();
			level = parseInt($(e.currentTarget).attr("level")) + 1;
			action_load_bill_location(level, parent);

			if($(e.currentTarget).attr("level") == 3)
			{
				$(".checkout-summary .loader-here").removeClass("hidden");
				action_load_sidecart();
			}

		});
	}

	function action_load_bill_location(level, parent)
	{
		if(level < 4)
		{
			$(".bill-load-location[level=" + level + "]").html("<option>LOADING LOCATION</option>");

			var bill_deflt;
			var bill_firstload = false;

			/* GET DEFAULT ON FIRST LOAD */
			if($(".bill-load-location[level=" + level + "]").attr("bill_firstload") == "true")
			{
				$(".bill-load-location[level=" + level + "]").attr("bill_firstload", "false");
				bill_firstload = true;
				bill_deflt = $(".bill-load-location[level=" + level + "]").attr("default");
			}

			if(ajax_load_bill_location)
			{
				ajax_load_bill_location.abort();
			}

			ajax_load_bill_location = 	$.ajax(
									{
						            	url: '/checkout/locale?parent=' + parent,
						            	success: function(data)
						            	{
						            		$(".bill-load-location[level=" + level + "]").html(data);

						            		if(bill_deflt != "" && bill_firstload == true)
						            		{
						            			$(".bill-load-location[level=" + level + "]").val(bill_deflt);
						            		}


						            		
						              		action_load_bill_location(level+1, $(".bill-load-location[level=" + (level) + "]").val());
						            	}
						          	});
		}
	}

	function event_show_bill_address()
	{
		$('.checkbox-bill').prop("checked", false);
		$('.checkbox-bill').change(function(event) 
		{
			action_show_bill_address(event.currentTarget);
		});
	}

	function action_show_bill_address(x)
	{
		if ($(x).is(':checked')) 
		{
			$(".different-container").removeClass("hide");
			$(".disable-bill select").removeProp("disabled");
			$(".disable-bill input, .disable-bill textarea").removeProp("disabled");
		}
		else
		{
			$(".different-container").addClass("hide");
			$(".disable-bill select").prop("disabled", true);
			$(".disable-bill input, .disable-bill textarea").prop("disabled", true);
		}
	}
}