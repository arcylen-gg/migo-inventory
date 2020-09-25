var test = test()
var ajaxdata = {};

function test()
{
	init();

	function init()
	{
		$(document).ready(function()
		{
			document_ready();
		})
		
	}
	function document_ready()
	{
		event_click_create_table();
		event_pagination_click();
		action_load_table();
		event_sort_click();
		event_filter_shop();
	}
	function event_filter_shop()
	{
		$("body").on("change", ".filter-by-shop", function(e)
		{
			$shop_id = $(e.currentTarget).val();
			ajaxdata.shop_id = $shop_id;
			ajaxdata.page = 1;
			action_load_table();
		});
	}
	function event_sort_click()
	{
		$("body").on("click", ".sorts", function(e)
		{
			$sort = $(e.currentTarget).attr("sortby");
			ajaxdata.sort = $sort;
			ajaxdata.page = 1;
			action_load_table();
		});
	}
	function event_pagination_click()
	{
		$("body").on("click", ".pagination a", function(e)
		{
			$url = $(e.currentTarget).attr("href"); //get URL (string)
			var url = new URL($url); //convert format URL
			$page = url.searchParams.get("page"); //get the page in the URL
			ajaxdata.page = $page;
			action_load_table();
			return false;
		});
	}
	function event_click_create_table()
	{
		$(".load-table").click(function()
		{
			ajaxdata.un = "developer";
			ajaxdata.pw = "digima1100";		

			action_load_table();
			$(".table-loader").html('<i class="fa fa-spinner fa-pulse fa-fw"></i>');
		});

	}
	function action_load_table()
	{
		ajaxdata._token = $(".token").val();

		$.ajax(
		{
			url:"/s",
			data: ajaxdata,
			type:"post",

			success: function(data)
			{
				$(".table-loader").html('<i class="fa fa-table"></i>');
				$(".table-container").html(data);
			}
		})
	}
}