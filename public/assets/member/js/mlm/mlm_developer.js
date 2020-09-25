var mlm_developer = new mlm_developer();
var table_key = "slot_list_test_menu";
var slot_table_data = {};

function mlm_developer()
{
	this.action_load_data = function()
	{
		action_load_table();
	}

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
		add_event_search();
		add_event_filter();
		add_event_allow_slot();
		event_tag_as_ambassador();
	}
	function add_event_filter()
	{
		$(".change-filter-membership").change(function()
		{
			action_load_table();
		});

		$(".change-filter-type").change(function()
		{
			action_load_table();
		});
	}
	function add_event_allow_slot()
	{
		$("body").on("click", ".allow-slot-change", function(e)
		{
			var customer_id = $(e.currentTarget).attr('customer-id');
			$.ajax({
				url : '/member/mlm/developer/allow_multiple_slot',
				type : 'get',
				data : {customer_id : customer_id},
				success : function()
				{
					console.log('success');	
				}
			});
		});
	}
	function add_event_search()
	{
		$(".search-employee-name").keypress(function(e)
		{
			if(e.which == 13)
			{
				slot_table_data.page = 1;
				action_load_table();
			}
		});
	}
	function add_event_pagination()
	{
		$("body").on("click", ".pagination a", function(e)
		{
			$url = $(e.currentTarget).attr("href");
			var url = new URL($url);
			$page = url.searchParams.get("page");
			slot_table_data.page = $page;
			action_load_table();
			return false;
		});
	}
	function action_load_table()
	{
		$html_test_slots = '<div class="text-center" style="padding: 180px 30px; font-size: 26px;"><i class="fa fa-spinner fa-pulse fa-fw"></i></div>';
	
		slot_table_data.search = $(".search-employee-name").val();
		slot_table_data.membership = $(".change-filter-membership").val();
		slot_table_data.type = $(".change-filter-type").val();

		$(".load-test-slots").html($html_test_slots);

		$.ajax(
		{
			url:"/member/mlm/developer/table",
			data:slot_table_data,
			success: function(data)
			{
				$(".load-test-slots").html(data);
			}
		});
	}
	function event_tag_as_ambassador()
	{
		$("body").on("click",".tag_as_ambassador", function(e)
		{
			var customer_id = $(e.currentTarget).attr("customer_id");

			$.ajax(
			{
				url: "/member/mlm/developer/tag_as_ambassador",
				type: "get",
				data: {customer_id:customer_id},
				success: function(data)
				{
					console.log(data);
				}
			});


		});
	}
}
