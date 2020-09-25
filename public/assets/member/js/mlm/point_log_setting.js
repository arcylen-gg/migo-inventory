var point_log_setting = new point_log_setting();

function point_log_setting()
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
		event_modify();
		event_append();
	}
	function action_table_loader()
	{
		$(".load-table-here").html('<div style="padding: 100px; text-align: center; font-size: 20px;"><i class="fa fa-spinner fa-pulse fa-fw"></i></div>');
	}
	function action_load_table()
	{
		action_table_loader();
		$.ajax(
		{
			url: '/member/mlm/point_log_complan/table',
			type: "get",
			success: function(data)
			{
				$('.load-table-here').html(data);
			}
		});
	}
	function event_modify()
	{
        $("body").unbind('click');
		$('body').on('click','.action-modify',function(e)
		{
			var id = $(e.currentTarget).closest('tr').attr('id');
			action_load_link_to_modal('/member/mlm/point_log_complan/modify?id='+id, 'lg');
		});
	}
	function event_append()
	{
		$('body').on('click','.append-variable',function(e)
        {
            var string = $(e.currentTarget).attr("string");
            var value="";
            var text="";
            if(string == 'slot_no')
            {
                value=" /_slot_no/ ";
                text=" <slot no> ";
            }
            else if(string == 'sponsor_slot_no')
            {
                value=" /_sponsor_slot_no/ ";
                text=" <sponsor slot no> ";
            }
            else if(string == 'amount')
            {
                value=" /_amount/ ";
                text=" <amount> ";
            }
            var sentence = $('.sentence').val();
            $('.sentence').val(sentence+value);
            setSentence();

        });
	}
}