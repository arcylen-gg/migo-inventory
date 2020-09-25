var cart_modal = new cart_modal();
var jqxhr = {abort: function () {}};

function cart_modal()
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
		event_quantity_price();
		event_remove_item();
	}

	function event_quantity_price()
	{
		$(".item-qty").unbind("change");
		$(".item-qty").bind("change", function(e)
		{
			action_quantity_price(e.currentTarget);
		});
	}

	function action_quantity_price(self)
	{
		var item_id = $(self).attr("item-id");
		var quantity = $(self).val();

		jqxhr.abort();

		jqxhr = $.ajax({
			url: '/cartv2/update',
			type: 'GET',
			dataType: 'json',
			data: {
				item_id: item_id,
				quantity: quantity
			},
		})
		.done(function() 
		{
			reload_cart(quantity);
		})
		.fail(function() 
		{
			console.log("error");
		})
		.always(function() 
		{
			console.log("complete");
		});
	}

	function event_remove_item()
	{
		$(".remove-item-cart").unbind("click");
		$(".remove-item-cart").bind("click", function(e)
		{
			action_remove_item(e.currentTarget);
		});
	}

	function action_remove_item(self)
	{
		cart_loader_show();

		var item_id = $(self).attr("item-id");

		$.ajax({
			url: '/cartv2/remove',
			type: 'GET',
			dataType: 'json',
			data: {item_id: item_id},
		})
		.done(function() 
		{
			reload_cart();
		})
		.fail(function() 
		{
			console.log("error");
		})
		.always(function() 
		{
			console.log("complete");
		});
		
	}

	function reload_cart(quantity = null)
	{
		$(".popup-buy-a-kit").load('/cartv2 .modal-content', function()
		{
			cart_loader_hide();
			document_ready();

			if (quantity) 
			{
				$(".item-qty").val(quantity);
			}
		});
	}

	function cart_loader_show()
	{
		$(".not-loader").addClass("hide");
		$(".cart-loader").removeClass("hide");
	}

	function cart_loader_hide()
	{
		$(".not-loader").removeClass("hide");
		$(".cart-loader").addClass("hide");
	}
}