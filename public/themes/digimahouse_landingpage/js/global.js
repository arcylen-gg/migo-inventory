var global = new global()

function global()
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
			action_match_height();
			action_fit_text();
			action_slick();
			event_sticky_nav();
			event_mobile_sidenav();
		});
	}

	function event_show_cart()
	{
		$('html').click(function() 
		{
			action_hide_cart();
		});

		$("body").on("click", ".shopping-cart-container", function(e)
		{
			event.stopPropagation();
			action_show_cart();
		});
	}

	function action_match_height()
	{
		$(".match-height").matchHeight();

		$(window).resize(function()
		{
			$(".match-height").matchHeight();
		});
	}

	function action_fit_text()
	{
		jQuery(".item-price").fitText(0.8, {
			maxFontSize: '16px',
			minFontSize: '8px',
		});
	}

	function event_sticky_nav()
	{
		var element_position = $('.content').offset().top;
	    var y_scroll_pos = window.pageYOffset;
	    var scroll_pos_test = element_position;

	    var get_height = $('.navbar').height();

	    if(y_scroll_pos > scroll_pos_test) 
        {
            $('.header-nav').css("margin-bottom", get_height+"px");
	        $('.navbar').addClass("sticky");
        }
        else
        {
            $('.header-nav').css("margin-bottom", "0px");
		    $('.navbar').removeClass("sticky");
        }
	    
	    $(window).on('scroll', function() 
	    {
	        var y_scroll_pos = window.pageYOffset;
	        var scroll_pos_test = element_position;
	        
	        if(y_scroll_pos > scroll_pos_test) 
	        {
	            $('.header-nav').css("margin-bottom", get_height+"px");
	        	$('.navbar').addClass("sticky");
	        }
	        else
	        {
	            $('.header-nav').css("margin-bottom", "0px");
		    	$('.navbar').removeClass("sticky");
	        }
	    });
	}
}