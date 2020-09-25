var commission_report = new commission_report();

function commission_report()
{
	init();

	this.action_load_table = function()
	{
		action_load_table();
	}
	this.action_change_warehouse = function()
	{
		action_change_warehouse();
	}

	function init()
	{
		$(document).ready(function()
		{
			document_ready();
		});
	}
	function document_ready()
	{
		event_change_warehouse();
		action_load_table();
	}
	function event_change_warehouse()
    {
    	action_change_warehouse();
    }
    function action_change_warehouse()
    {
    	var loc = window.location.href;
    	console.log('location: '+loc);

        var currentWarehouse = $('.select_current_warehouse').val();
        $('.commission-percentage').attr('disabled',true);
        $.ajax(
        {
            url: '/member/merchant/commission-report/getpercentage',
            type: 'get',
            data: 'warehouse_id='+currentWarehouse,
            success: function(data)
            {
                $('.commission-percentage').val(data);
                $('.commission-percentage').removeAttr('disabled');
                action_load_table();
            }
        });
    }
    function action_table_loader()
	{
		$(".load-commission-table-here").html('<div style="padding: 100px; text-align: center; font-size: 20px;"><i class="fa fa-spinner fa-pulse fa-fw"></i></div>');
	}
	function action_load_table()
	{
		var currentWarehouse = $('.select_current_warehouse').val(); 
		action_table_loader();
		$.ajax(
		{
			url: '/member/merchant/commission_report/table',
			data: 'warehouse_id='+currentWarehouse,
			type: "get",
			success: function(data)
			{
				$('.load-commission-table-here').html(data);
			}
		});
	}
}
