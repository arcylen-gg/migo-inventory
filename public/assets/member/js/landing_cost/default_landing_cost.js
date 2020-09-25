var default_landing_cost = new default_landing_cost();
var global_tr_html = $(".div-script tbody").html();
var item_selected = ''; 

function default_landing_cost()
{
	init();

	function init()
	{
		event_click_last_row();
		event_remove_tr();
		action_reassign_number();
	}
	function event_click_last_row()
	{
		$(document).on("click", "tbody.draggable tr:last td:not(.remove-tr)", function(){
			event_click_last_row_op();
		});
	}
	function event_click_last_row_op()
	{
		$("tbody.draggable").append(global_tr_html);
		action_reassign_number();
	}	
	function action_reassign_number()
	{
		var num = 1;
		$(".number-td").each(function(){
			$(this).html(num);
			num++;
		});
	}	
	function event_remove_tr()
	{
		$(document).on("click", ".remove-tr", function(e){
			var len = $(".tbody-item .remove-tr").length;
			if($(".tbody-item .remove-tr").length > 1)
			{
				$(this).parent().remove();
				action_reassign_number();
			}
			else
			{
				console.log("success");
			}
		});
	}
}
function success_create(data)
{
	if(data.status == 'success')
	{
		toastr.success(data.status_message);
		location.reload();
	}
}