var brown_rank = new brown_rank();
var load_table_data = {};

function brown_rank()
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
	}
	function action_load_table()
	{
		if($(".load-rank-list").text() == "")
		{
			$(".load-rank-list").html(get_loader_html(100));
		}
		else
		{
			$(".load-rank-list").css("opacity", 0.3);
		}
		$.ajax(
		{
			url:"/member/mlm/plan/brown_rank/table",
			data: load_table_data,
			type: "get",
			success: function(data)
			{
				$(".load-rank-list").html(data);
				$(".load-rank-list").css("opacity", 1);
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
function success_created_rank(data)
{
    if(data.status)
    {
        toastr.success('Success');
        data.element.modal("hide");
        brown_rank.action_load_table();       
    }   
}