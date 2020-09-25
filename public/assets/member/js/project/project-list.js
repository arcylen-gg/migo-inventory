var project_list = new project_list();
var table_data = {};
var x = null;

function project_list()
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
		event_change_tab();
		event_archive();
		event_modify();
		event_search();
	}
	function event_search()
	{
		$(".search-project").keyup(function(e)
		{
			action_table_loader();
			clearTimeout(x);

			x = setTimeout(function()
			{
				action_load_table();
			}, 1000)
		});
	}
	function event_archive()
	{
		$("body").on("click", ".action-archive",function(e)
		{
			var project_id = $(e.currentTarget).closest("tr").attr("project_id");

			var action = "";
			if($('.action-archive').text()[0]=='A')
			{
				action="archive";
			}
			else
			{
				action="restore";
			}

			if(confirm("Are you sure you want to "+action))
			{
				action_table_loader();

				var url = "";
				if(action=='archive')
				{
					url = "/member/project/project_list/archive";
				}
				else
				{
					url = "/member/project/project_list/restore";
				}

				$.ajax(
				{
					url: url,
					data:{ 'project_id':project_id },
					type:"get",
					success: function(data)
					{
						action_load_table();
					}
				});
			}
		});
	}
	function event_change_tab()
	{
		$(".change-tab").click(function(e)
		{
			$(".change-tab").removeClass("active");
			$(e.currentTarget).addClass("active");
			action_load_table();
		});
	}
	function action_table_loader()
	{
		$(".load-table-here").html('<div style="padding: 100px; text-align: center; font-size: 20px;"><i class="fa fa-spinner fa-pulse fa-fw"></i></div>');
	}
	function action_load_table()
	{
		table_data.activetab = $(".change-tab.active").attr("mode");
		table_data.search = $(".search-project").val();

		action_table_loader();

		$.ajax(
		{
			url:"/member/project/project_list/table",
			data: table_data,
			type:"get",
			success: function(data)
			{
				$(".load-table-here").html(data);
			}

		});
	}
	function event_modify()
	{
		$("body").on("click",".action-modify",function(e)
		{
			var id = $(e.currentTarget).closest("tr").attr("project_id");
			action_load_link_to_modal('/member/project/project_list/modify?id='+id, 'md');
		});
	}
}