var checkout = new checkout();

function checkout()
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
		event_qty_control();
	}

	function event_qty_control()
	{
		$(input_quantity).unbind("change");
		$(input_quantity).bind("change", function(e)
		{
			action_qty_control(e.currentTarget);
		});
	}

	function action_qty_control(x)
	{
		var variation_id = $(x).attr("vid");
	    var product_raw_price = parseFloat($(maincontainer + " " + rawprice + '[vid="'+variation_id+'"]').text().replace(',',''));
	    var product_quantity = parseInt($(x).val());
	    
	    var product_subtotal = number_format(product_raw_price * product_quantity);

	    $(maincontainer + " " + subtotal + '[vid="'+variation_id+'"]').html(product_subtotal);

	    action_total_price();

	    jqxhr.abort();

	    jqxhr = $.ajax({
	        url: '/cart/update',
	        type: 'GET',
	        dataType: 'json',
	        data: {
	            variation_id: variation_id,
	            quantity: product_quantity
	        },
	    })
	    .done(function() {
	        action_enable_checkout_button();
	    })
	    .fail(function() {
	        console.log("error");
	    })
	    .always(function() {
	        console.log("complete");
	    });
	}

	function action_total_price()
	{
	    var product_total = 0;

	    $(maincontainer + " " + subtotal).each(function(index, el) 
	    {
	        product_total += parseFloat($(el).text().replace(',',''));
	    });

	    $(total).html(number_format(product_total));
	}
}