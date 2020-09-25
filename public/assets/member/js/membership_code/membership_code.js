var membership_code = new membership_code();
var load_table_data = {};

function membership_code()
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
		$('.filter-item-kit').globalDropList({
			hasPopup : 'false',
			width: "100%",
			placeholder: "All Item Kit",
			onChangeValue: function()
			{
				action_filter_item_kit(this);
			}
		});
		$('.filter-membership').globalDropList({
			hasPopup : 'false',
			width: "100%",
			placeholder: "All Membership",
			onChangeValue: function()
			{
				action_filter_membership(this);
			}
		});
	}
	function event_load_search()
	{
		$('.search-keyword').unbind("change");
		$('.search-keyword').bind("change", function(e)
		{
			action_filter_membership_kit(e.currentTarget);
		});
	}
	function action_filter_membership_kit(self)
	{
		var search = $(self).val();

		load_table_data.search_keyword = search;
	    load_table_data.page = 1;
	    action_load_table();
	}
	function action_filter_membership(self)
	{
		var item_membership_id = $(self).val();

		load_table_data.item_membership_id = item_membership_id;
	    load_table_data.page = 1;
	    action_load_table();		
	}
	function action_filter_item_kit(self)
	{
		var item_kit_id = $(self).val();

		load_table_data.item_kit_id = item_kit_id;
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
			url:"/member/mlm/code2/table",
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
function click_status(status)
{
	load_table_data.status = status;
    load_table_data.page = 1;

    $('.change-tab').removeClass('active');
    $('.'+status+'-tab').addClass('active');

    membership_code.action_load_table();
}
function success_change_status(data)
{
	if(data.status)
	{
        toastr.success('Success');
        data.element.modal("hide");
        membership_code.action_load_table();		
	}	
}
function success_dissamble(data) 
{	
	if(data.status)
	{
        toastr.success('Success');
        data.element.modal("hide");
        membership_code.action_load_table();		
	}
}