var redeemable = new redeemable();
var table_data = {};
var modify_data = {};
var x = null;

function redeemable()
{
	init();

	this.action_load_table = function()
	{
		action_load_table();
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
		action_load_table();
		event_archive();
		event_modify();
		event_change_tab();
		event_search();
	}
	function action_table_loader()
	{
		$(".load-table-here").html('<div style="padding: 100px; text-align: center; font-size: 20px;"><i class="fa fa-spinner fa-pulse fa-fw"></i></div>');
	}
	function action_load_table()
	{
		table_data.activetab = $('.change-tab.active').attr("mode");
		table_data.search = $('.search').val();


		console.log($('.change-tab.active').attr("mode"));

		action_table_loader();
		$.ajax(
		{
			url: '/member/item/redeemable/redeemable_table',
			data: table_data,
			type: "get",
			success: function(data)
			{
				$('.load-table-here').html(data);
			}
		});
	}
	function event_change_tab(e)
	{
		$(".change-tab").click(function(e)
		{
			$('.change-tab').removeClass('active');
			$(e.currentTarget).addClass('active');
			action_load_table();
		});
	}
	function event_archive()
	{
		$('body').on('click','.action-archive',function(e)
		{
			var id = $(e.currentTarget).closest("tr").attr("id");
			var action = "";

			if($('.action-archive').text()[0]== "A")
			{
				action = 'archive';
			}
			else
			{
				action = 'restore';
			}

			if(confirm("Are you sure you want to "+action+"?"))
			{

				$.ajax(
				{
					url : "/member/item/redeemable/archive",
					type: "get",
					data: {"item_redeemable_id":id,"action":action},
					success: function(data)
					{
						action_load_table();
					}
				});
			}
		});
	}
	function event_modify()
	{
		$("body").on('click','.action-modify',function(e)
		{
			var id = $(e.currentTarget).closest("tr").attr("id");

			action_load_link_to_modal('/member/item/redeemable/modify?item_redeemable_id='+id, 'lg');

		});
	}
	function event_search()
	{
		$('.search').keyup(function()
		{
			action_table_loader();
			clearTimeout(x);

			x = setTimeout(function()
			{
				action_load_table();
			}, 1000);

		});
	}

	

}

