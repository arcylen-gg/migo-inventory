var bulk_archive = new bulk_archive();

function bulk_archive()
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
		initialize_select();
	}

	function initialize_select()
	{
		$('.customer-droplist').globalDropList(
        {
            hasPopup: "false",
            width : "100%",
            placeholder : "Customer Name",
            onChangeValue: function()
            {
                action_customer_droplist($(this));
            }

        });
	}

	function action_customer_droplist()
	{
		$(".digima-table tbody").append(append);
		initialize_select();
	}
}