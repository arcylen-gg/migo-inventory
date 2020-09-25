var usecode = new usecode();

function usecode()
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
		event_use_code();
	}
	function event_use_code()
	{
		$("body").on("click",".use-code",function(e)
		{
			var pin = $(e.currentTarget).closest("tr").attr("pin");
			var activation = $(e.currentTarget).closest("tr").attr("activation");

			action_load_link_to_modal('/members/usecode?pin='+pin+"&activation="+activation, 'md');

		});
	}
}