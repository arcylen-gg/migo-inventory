var item_list = new item_list();
var load_table_data = {};
load_table_data.archived = 0;
load_table_data.item_type_id = '';
load_table_data.item_category_id = '';
load_table_data.search = '';
function item_list()
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
		event_archive();
		event_item_archive();
		event_filter_item_type();
		event_filter_item_category();
		action_click_bulk_item();
		event_filter_item_search();
		action_select_all_bulk_item();
	}
	function action_select_all_bulk_item()
	{
		$("body").on("change", ".check-all-checkbox", function(event)
		{
			// $(".check-all-checkbox").change(function()
		 //    {  
		    	//"select all" change 
		        $(".check-bulk-item").prop('checked', $(this).prop("checked")); //change all ".check-bulk-item" checked status
		    // });
		    //".check-bulk-item" change 
		    $('.check-bulk-item').change(function(){ 
		        //uncheck "select all", if one of the listed checkbox item is unchecked
		        if(false == $(this).prop("checked"))
		        { //if this item is unchecked
		            $(".check-all-checkbox").prop('checked', false); //change "select all" checked status to false
		        }
		        //check "select all" if all checkbox items are checked
		        if ($('.check-bulk-item:checked').length == $('.check-bulk-item').length )
		        {
		            $(".check-all-checkbox").prop('checked', true);
		        }
		    });
		    var ids = [];
			$(".check-bulk-item").each(function()
		    {
		        if($(this).is(":checked"))
		            ids.push($(this).val());
		    });
		    console.log(ids);
		     $('.bulk-edit-button').attr("link","/member/item/v2/submit_checked_to_edit?ids="+ids);
	    });
	}

	function action_click_bulk_item()
	{
		$("body").on("click", ".check-bulk-item", function(event)
		{
			var ids = [];
			$(".check-bulk-item").each(function()
		    {
		        if($(this).is(":checked"))
		            ids.push($(this).val());
		    });
		    $('.bulk-edit-button').attr("link","/member/item/v2/submit_checked_to_edit?ids="+ids);
		});
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
			url:"/member/item/v2/table",
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
	function event_archive()
	{
		$('.go-default').unbind("click");
	    $('.go-default').bind("click", function(e)
	    {
	        action_archive(0, e.currentTarget);
	    });

	    $('.go-archive').unbind("click");
	    $('.go-archive').bind("click", function(e)
	    {
	        action_archive(1, e.currentTarget);
	    });

	    $('.go-all').unbind("click");
	    $('.go-all').bind("click", function(e)
	    {
	        action_archive('all', e.currentTarget);
	    });
	}
	function event_item_archive()
	{
		$("body").on('click', '.item-archive', function(event) 
	    {
	        event.preventDefault();
	        var item_id = $(event.currentTarget).attr("item-id");
	        action_item_archive(item_id, "archive");
	    });

	    $("body").on('click', '.item-restore', function(event) 
	    {
	        event.preventDefault();
	        var item_id = $(event.currentTarget).attr("item-id");
	        action_item_archive(item_id, "restore");
	    });
	}
	function action_archive(archive, x)
	{
	    load_table_data.archived = archive;
	    load_table_data.page = 1;
	    action_load_table(); 
	    $('.nav-tabs li').removeClass('active'); 
	    $(x).parent().addClass('active');
	}
	function action_item_archive(item_id, action)
	{
	    $.ajax({
	        url: '/member/item/v2/'+action,
	        type: 'GET',
	        dataType: 'json',
	        data: {
	            item_id: item_id
	        },
	        success : function(data)
	        {
	        	if(data == 'success')
	        	{
		        	action_load_table(); 
	        	}
	        	else
	        	{
	        		toastr.warning(data.status_message);
	        	}
	        }
	    })
	}
	function event_filter_item_type()
	{
		$('.filter-item-type').globalDropList(
		{
			hasPopup: "false",
			width: "100%",
			placeholder: "All Item Type",
			onChangeValue: function()
			{
				action_filter_item_type(this);
			}
		});
	}
	function action_filter_item_type(self)
	{
		var item_type_id = $(self).val();

		load_table_data.item_type_id = item_type_id;
	    load_table_data.page = 1;
	    action_load_table();
	}
	function event_filter_item_category()
	{
		$('.category-select').globalDropList(
		{
			hasPopup: "false",
			width: "100%",
			placeholder: "All Category",
			onChangeValue: function()
			{
				action_filter_item_category(this);
			}
		});
	}
	function action_filter_item_category(self)
	{
		var item_category_id = $(self).val();

		load_table_data.item_category_id = item_category_id;
	    load_table_data.page = 1;
	    action_load_table();
	}
	function event_filter_item_search()
	{
		$('.search-item-list').unbind("change");
		$('.search-item-list').bind("change", function(e)
		{

			action_filter_item_search(e.currentTarget);
		});
	}
	function action_filter_item_search(self)
	{
		var search = $(self).val();

		load_table_data.search = search;
	    load_table_data.page = 1;
	    action_load_table();
	}
}
function success_refill(data)
{
	if(data.status == 'success')
	{
        toastr.success("Success refilling item");
        data.element.modal("hide");
        item_list.action_load_table();
	}
}

function success_item(data)
{
	if(data.status == 'success')
	{
        toastr.success(data.message);
        data.element.modal("hide");
        item_list.action_load_table();
	}
}
function sortTable(header_name)
{
	var order_by = $(".order-by").val();

	if(order_by == 'desc')
	{
		order = 'asc';
	}
	else if(order_by == 'asc')
	{
		order = 'desc';
	}
	
   	$(".order-by").val(order).change();

    load_table_data.order_by = order_by;
    load_table_data.header_name = header_name;
    item_list.action_load_table();
}

function success_checked_item_to_edit(data)
{
	if(data.status == 'success')
	{
		toastr.success('Checked');
		$('.edit-bulk-item').modal('show');
		$('.edit-bulk-item').load('/member/item/v2/edit/bulk_item', function()
		{
			console.log("success");

		});
	}
	else if(data.status == 'warning')
	{
		toastr.warning('Please checked item to edit');
		
	}
}
function print_barcode()
{
	window.open('/member/item/v2/print_barcode?archived='+load_table_data.archived+
											  '&item_type_id='+load_table_data.item_type_id+
											  '&item_category_id='+load_table_data.item_category_id+
											  '&search='+load_table_data.search
											  ,'_blank');
}