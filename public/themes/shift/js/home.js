var home = new home();

function home()
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
			event_click_scroll();
		});
	}

	function event_click_scroll()
	{
		$(".scroll-down").off("click");
		$(".scroll-down").on("click", function()
		{
			action_click_scroll();
		});
	}

	function action_click_scroll()
	{
		$('html, body').animate({
	        scrollTop: $(".brand-container").offset().top
	    }, 2000);
	}
}