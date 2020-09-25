var add_to_cart = new add_to_cart;

function add_to_cart()
{
	init();

	function init()
	{
		document_ready();
	}

	function document_ready()
	{
		event_put_variant_id();
	}

	function event_put_variant_id()
	{
		$(document).on("click",".add-to-cart",function()
		{
			var prod_id = $(this).attr("variant-id");
			$(".variant_id").val(prod_id);
		})
	}
}