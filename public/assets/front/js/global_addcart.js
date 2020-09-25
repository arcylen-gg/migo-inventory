var global_addcart = new global_addcart();
var jqxhr = {abort: function () {}};

function global_addcart()
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
		action_zoom_image();
		event_select_variation();
		event_slick();
		event_change_image();
		event_add_to_cart();
	}

	function action_zoom_image()
	{
		$(''+product_image+'').removeClass("first-img");

		$(''+product_image+'').removeData('elevateZoom');
		$('.zoomContainer').remove();

		$(''+product_image+'.key-0').addClass("first-img");
		$('.first-img').elevateZoom({});
	}

	function event_change_image()
	{
		$(document).on('click', '.single-product-holder .thumb .holder', function(event) 
		{
			event.preventDefault();
			
			var variant_id = $(event.currentTarget).attr("variant-id");
			var key = $(event.currentTarget).attr("key");

			$(''+product_container+'[variant-id="'+variant_id+'"] '+product_image+'').addClass("hide");
			$(''+product_container+'[variant-id="'+variant_id+'"] '+product_image+'[key="'+key+'"]').removeClass("hide");
			$(''+product_image+'').removeClass("first-img");
			$(''+product_container+'[variant-id="'+variant_id+'"] '+product_image+'[key="'+key+'"]').addClass("first-img");
			image_crop(".1-1-ratio", 1, 1);
			$(''+product_image+'').removeData('elevateZoom');
			$('.zoomContainer').remove();
			$(".first-img").elevateZoom({});
		});
	}

	function event_slick()
	{
		if ($('.thumb').hasClass("slick-initialized")) 
		{
			$('.thumb').slick('unslick');
		}

		$('.thumb').slick({
			infinite: true,
			slidesToShow: 4,
			slidesToScroll: 1,
			arrows: false
		});

		$('.1-1-ratio').keepRatio({ ratio: 4/3, calculate: 'height' });
	}

	function event_select_variation()
	{
		$("body").on('change', '#select-variation .attribute-variation', function(event) 
		{
			event.preventDefault();
			action_select_variation(event);
		});
	}

	function action_select_variation(e)
	{
		var selected = $(e.currentTarget).closest("'+product_container+'").attr("variant-id");
		var product_id = $(e.currentTarget).attr("product-id");
		var variant_label = $(e.currentTarget).attr("variant-label");
		var variation = [];
		toload = true;

		$(''+product_container+'[variant-id="'+selected+'"] .attribute-variation[variant-id="'+selected+'"]').each(function(index, el) 
		{
			variation.push($(el).val());

			if ($(el).val() == 0) 
			{
				toload = false;
			}
		});

		if (toload == true) 
		{
			$(''+button_cart+'').addClass("disabled");
			$(''+button_cart+'').prop("disabled", true);

			$('.loader-variation').fadeIn(400, function()
			{
				$(''+button_cart+'').prop("disabled", false);
				$(''+button_cart+'').removeClass("disabled");
			});
		}
		else
		{
			$(''+button_cart+'').prop("disabled", true);
			$(''+button_cart+'').addClass("disabled");
		}

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
				
				$(''+product_container+'').addClass("hide");
				$(''+product_container+'[variant-id="'+variant_id+'"]').removeClass("hide");

				$(''+product_image+'').removeClass("first-img");

				$(''+product_image+'[variant-id="'+variant_id+'"]').addClass("first-img");

				$(''+product_image+'').removeData('elevateZoom');
				$('.zoomContainer').remove();

				$(".first-img").elevateZoom({
				});
				event_slick();

				$('.1-1-ratio').keepRatio({ ratio: 1/1, calculate: 'height' });
				$('.attribute-variation[variant-label="'+variant_label+'"]').val($(e.currentTarget).val());

				if (toload == true) 
				{
					if (data.variation.inventory_status == "out of stock") 
					{
						$(''+button_cart+'').prop("disabled", true);
						$(''+button_cart+'').addClass("disabled");	
					}
					else
					{
						$('.single-order-availability').html('In Stock');
						$(''+button_cart+'').prop("disabled", false);
						$(''+button_cart+'').removeClass("disabled");
					}
				}

				$(".loader-variation").fadeOut();
			
			}
			else if (data.result == 'fail')
			{
				if (toload == true) 
				{
					if (data.no_stock  == 'nostock') 
					{
						$('.single-order-availability').html('Out of Stock');
						$(''+button_cart+'').addClass("disabled");
						$(''+button_cart+'').prop("disabled", true);
					}
					else
					{
						$('.single-order-availability').html('In Stock')
					}
					
					$(".loader-variation").fadeOut();	
				}
			}
			else
			{
				$('.attribute-variation[variant-label="'+variant_label+'"]').val($(e.currentTarget).val());
				if (toload == true) 
				{
					$(".loader-variation").fadeOut();
				}
			}
		})
		.fail(function() 
		{
			console.log("error");
		});
	}

	function event_add_to_cart()
	{
		$(document).on('click', ''+button_cart+'', function(event) 
		{
			event.preventDefault();
			
			$(event.currentTarget).prop("disabled", true);
			$(event.currentTarget).addClass("disabled");

			var variant_id = $(event.currentTarget).attr("variant-id");
			var quantity = $(""+product_quantity+"[variant-id='"+variant_id+"']").val();

			$.ajax({
				url: '/cart/add',
				type: 'GET',
				dataType: 'json',
				data: {
					variant_id: variant_id,
					quantity: quantity,
				},
			})
			.done(function(data) 
			{
				if (data.status == "error") 
				{
					alert("An error occurred. Please try again later.");

					$(button_cart).removeProp("disabled");
					$(button_cart).removeClass("disabled");
				}
				else
				{
					// location.href='/product';	
					load_cart();
					$(cart_holder).css("display", "block").css("opacity", "1");

					$(button_cart).removeProp("disabled");
					$(button_cart).removeClass("disabled");
				}
			})
			.fail(function() 
			{
				console.log("error");
			})
			.always(function() 
			{
				console.log("complete");
			});
			
		});
	}
}
