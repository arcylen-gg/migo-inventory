var product_content = new product_content();

function product_content()
{
	init();

	function init()
	{
		document_ready();
	}

	function document_ready()
	{
    	$(document).ready(function()
    	{
    		action_product_zoom();
    		event_select_variation();
    	});
	}

	function action_product_zoom()
	{
		$('.img-holder .img').elevateZoom();
	}

	function event_select_variation()
	{
		var select_variation = '.select-variation';

		$("body").on('change', select_variation, function(event) 
		{
			event.preventDefault();
			action_select_variation(event);
		});
	}

	function action_select_variation(e)
	{
		var content_holder = '.product-content';

		var selected = $(e.currentTarget).closest(content_holder).attr("variant-id");
		var product_id = $(e.currentTarget).attr("product-id");
		var variant_label = $(e.currentTarget).attr("variant-label");
		var variation = [];
		toload = true;

		var select_variation = '.select-variation[variant-id="'+selected+'"]';
		var select_variation_label = '.select-variation[variant-label="'+variant_label+'"]';

		$(select_variation).each(function(index, el) 
		{
			variation.push($(el).val());

			if ($(el).val() == 0) 
			{
				toload = false;
			}
		});

		$('.product-add-cart').prop("disabled", true);
		$('.product-add-cart').addClass("disabled");

		jqxhr.abort();

		jqxhr = $.ajax({
			url: '/product/variant/',
			type: 'GET',
			dataType: 'json',
			data: 
			{
				variation: variation,
				product_id: product_id,
			},
		})
		.done(function(data) 
		{
			if(data.result == "success")
			{
				var variant_id = data.variation.evariant_id;
				var content_holder_specific = '.product-content[variant-id="'+variant_id+'"]';
			
				$(content_holder).addClass("hide");
				$(content_holder_specific).removeClass("hide");

				$('.4-3-ratio').keepRatio({ ratio: 4/3, calculate: 'height' });

				$(select_variation_label).val($(e.currentTarget).val());

				if (data.variation.inventory_status == "out of stock") 
				{
					$('.product-add-cart').prop("disabled", true);
					$('.product-add-cart').addClass("disabled");	
				}
				else
				{
					$('.product-add-cart').prop("disabled", false);
					$('.product-add-cart').removeClass("disabled");
				}
			}
			else
			{
				$(select_variation_label).val($(e.currentTarget).val());
			}
		})
		.fail(function() 
		{
			$(select_variation_label).val($(e.currentTarget).val());
		});
	}
}