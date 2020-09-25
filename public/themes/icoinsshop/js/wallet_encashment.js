var encashment = new encashment();

function encashment()
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
		add_event_request_payout_clicked();
	}
	function add_event_request_payout_clicked()
	{
		$(".request-payout").click(function()
		{
			$("#wallet-encashmnet-modal").modal("show");
		});
	}
}