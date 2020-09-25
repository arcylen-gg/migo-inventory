var price_level = new price_level();
var load_table_data = {};

function price_level()
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
		action_load_table();
		add_event_pagination();
		event_initialize_select();
		event_load_search();
	}
	function event_initialize_select()
	{
		$('.filter-type').globalDropList({
			hasPopup : 'false',
			width: "100%",
			placeholder: "All Type",
			onChangeValue: function()
			{
				action_filter_type(this);
			}
		});
	}
	function event_load_search()
	{
		$('.search-keyword').unbind("change");
		$('.search-keyword').bind("change", function(e)
		{
			action_load_search(e.currentTarget);
		});
	}
	function action_load_search(self)
	{
		var search_keyword = $(self).val();

		load_table_data.search_keyword = search_keyword;
	    load_table_data.page = 1;
	    action_load_table();
	}
	function action_filter_type(self)
	{
		var type = $(self).val();

		load_table_data.type = type;
	    load_table_data.page = 1;
	    action_load_table();
	}
	function add_event_pagination()
	{
		$("body").on("click", ".pagination a", function(e)
		{
			$url = $(e.currentTarget).attr("href");
			var url = new URL($url);
			$page = url.searchParams.get("page");
			load_table_data.page = $page;
			action_load_table();
			return false;
		});
	}
	function action_load_table()
	{
		if($(".load-item-table").text() == "")
		{
			$(".load-item-table").html(get_loader_html(100));
		}
		else
		{
			$(".load-item-table").css("opacity", 0.3);
		}
		$.ajax(
		{
			url:"/member/item/price_level/table",
			data: load_table_data,
			type: "get",
			success: function(data)
			{
				$(".load-item-table").html(data);
				$(".load-item-table").css("opacity", 1);
			}
		});
	}
	function get_loader_html($padding = 50)
	{
		return '<div style="padding: ' + $padding + 'px; font-size: 20px;" class="text-center"><i class="fa fa-spinner fa-pulse fa-fw"></i></div>';
	}

	this.action_load_table = function()
	{
		action_load_table();
	}
}
function new_price_level_save_done(data) 
{	
	if(data.status)
	{
        toastr.success('Success');
        data.element.modal("hide");
        price_level.action_load_table();		
	}
}