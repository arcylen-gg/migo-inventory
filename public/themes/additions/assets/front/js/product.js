	var product = new product();

function product()
{
	init();

	function init()
	{
		document_ready();
	}

	function document_ready()
	{
		// event_carousel_product();
		// event_set_variant_options();
		// event_change_variant_options();
		// event_counter_click();
		// event_adjust_change();
		// event_click_add_cart();
		
		// event_thumb_carousel();
		// event_click_picture();
	}

	function event_carousel_product()
	{
		$('.carousel-product').slick({
		  dots: false,
		  infinite: true,
		  speed: 300,
		  slidesToShow: 4,
		  slidesToScroll: 4,
		  autoplay: true,
		  autoplaySpeed: 4000,
		  prevArrow:"<img class='a-left control-c prev slick-prev' src='/assets/front/img/arrow-left.png'>",
  		  nextArrow:"<img class='a-right control-c next slick-next' src='/assets/front/img/arrow-right.png'>",
		  responsive: [
		    {
		      breakpoint: 1024,
		      settings: {
		        slidesToShow: 3,
		        slidesToScroll: 3,
		        infinite: true,
		        dots: true
		      }
		    },
		    {
		      breakpoint: 600,
		      settings: {
		        slidesToShow: 2,
		        slidesToScroll: 2
		      }
		    },
		    {
		      breakpoint: 480,
		      settings: {
		        slidesToShow: 1,
		        slidesToScroll: 1
		      }
		    }
		    // You can unslick at a given breakpoint now by adding:
		    // settings: "unslick"
		    // instead of a settings object
		  ]
		});
	}

	function event_change_variant_options()
	{
		$(".option_value").change(function()
		{
			trigger_ajax_loading();

			$.ajax(
			{
				url:"/product/check_variant",
				dataType:"json",
				type:"post",
				data:$(".form-to-submit").serialize(),

				success: function(data)
				{

					if(data.mode == "success")
					{
						event_product_image_change(data.variants['variant_main_image']);
						$(".variant-id").val(data.variants['variant_id']);
						$(".variant-price").text(parseFloat(data.variants['variant_price']).toFixed(2));
						$(".variant-inventory-count").text(data.variants['variant_inventory_count']);
						$(".variant-inventory-count").attr("value", data.variants['variant_inventory_count']);
						$(".variant-note").addClass("hidden");
						remove_ajax_loading();
						check_add_to_cart();
					}
					else
					{
						remove_ajax_loading();
						$(".variant-id").text(0);
						$(".variant-price").text('PHP 0.00');
						$(".variant-inventory-count").text('No Stocks Available');
						$(".variant-inventory-count").attr("value", 0);
						check_add_to_cart();
					}
				},
				error: function(data)
				{
					console.log(data.statusText);
				}
			});
		})
	}

	function event_counter_click()
	{
		$('.btn-add').click(function()
		{
			// $('.input-adjust').val(parseInt($counter_val)+1);
			if($(".input-adjust").val() < parseInt($(".variant-inventory-count").text()))
			{
				var num = +$(".input-adjust").val() + 1;
				$(".input-adjust").val(num);
				check_add_to_cart()
			}
		})

		$('.btn-minus').click(function()
		{
			// $('.input-adjust').val(parseInt($counter_val)+1);
			if($(".input-adjust").val() > 0)
			{
				var num = +$(".input-adjust").val() - 1;
				$(".input-adjust").val(num);
			}
			check_add_to_cart()
		})
	}

	function event_adjust_change()
	{
		$('.input-adjust').change(function()
		{
			check_add_to_cart();
		});
	}

	function check_add_to_cart()
	{
		if((parseInt($(".variant-inventory-count").text()) > 0) && (parseInt($(".input-adjust").val()) > 0))
		{
			$(".add-cart").removeClass("disabled");
		}
		else
		{
			$(".add-cart").addClass("disabled");
		}
	}

	function trigger_ajax_loading()
	{
		$(".variant-price").text("");
		$(".variant-inventory-count").text("");
		$(".ajax-loading").removeClass("hide");
	}

	function remove_ajax_loading()
	{
		$(".ajax-loading").addClass("hide");
	}

	function event_click_add_cart()
	{
		$(".add-cart").click(function()
		{
			$(this).addClass("disabled");
			$(".ajax-loading-cart").removeClass("hide");
		})
	}
	
	// function event_thumb_carousel()
 //   {
 //       $('.product-thumb').slick({
 //         infinite: true,
 //         centerMode: true,
 //         slidesToShow: 5,
 //         slidesToScroll: 1,
 //       });
 //   }
 
	function event_thumb_carousel()
    {
    	console.log($(".product-image").length);
        $('.slider-for').slick({
          slidesToShow: 1,
		  slidesToScroll: 1,
          arrows: false,
          fade: true,
          asNavFor: '.slider-nav'
        });
        
        if($(".product-image").length > 4)
        {
	        $('.slider-nav').slick({
			  slidesToShow: 3,
			  slidesToScroll: 1,
			  asNavFor: '.slider-for',
			  dots: false,
			  centerMode: true,
			  focusOnSelect: true
			});
        }
        else
        {
        	$('.slider-nav').slick({
			  slidesToShow: 4,
			  slidesToScroll: 1,
			  asNavFor: '.slider-for',
			  dots: false,
			  focusOnSelect: true
			});
        }
    }
    
    // function event_change_product_picture()
    // {
    //     $(".product-thumb .holder").unbind("click");
    //     $(".product-thumb .holder").bind("click", function(e)
    //     {
    //       action_change_product_picture(e.currentTarget); 
    //     });
    // }
    
    // function action_change_product_picture(current)
    // {
    //     var product = $(current).attr("product");
    //     $(".product-thumb .holder").removeClass("active");
    //     $(current).addClass("active");
    //     $(".variant-image").addClass("hide");
    //     $('.variant-image[product="'+product+'"]').removeClass("hide");
    // }
    
    function event_product_image_change(id)
    {
    	$('.product-image[product="' +id +'"]').trigger("click");
    }
    
    function event_click_picture()
    {
    	
    }
    
}
