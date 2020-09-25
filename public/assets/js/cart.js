var cart = new cart();

function cart()
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
		add_event_show_cart();
	}

	function add_event_show_cart()
	{
		$(".show-cart").click(function()
		{
			$(".shopping-cart").modal("show");
		});
	}
}