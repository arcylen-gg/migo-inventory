var wallet_refill = new wallet_refill();
var table_data = {};
var x = null;
function wallet_refill()
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
		event_change_tab();
		event_change_slot_owner();
		event_change_amount();
		event_upload();
	}
	function action_table_loader()
	{
		$(".load-table-here").html('<div style="padding: 100px;text-align: center; font-size: 20px;"><i class="fa fa-spinner fa-pulse fa-fw"></i></div>');
	}
	function action_load_table()
	{
		action_table_loader();
		var activetab = $('.change-tab.active').attr("mode");
		var slotno = $('.slot-owner').val();
		$.ajax(
		{
			url: "/members/wallet-refill-table",
			type: "get",
			data: {"activetab":activetab,"slotno":slotno},
			success: function(data)
			{
				$(".load-table-here").html(data);
			}
		});
	}
	function event_change_tab()
	{
		$('.change-tab').click(function(e)
		{
			$('.change-tab').removeClass('active');
			$(e.currentTarget).addClass('active');
			action_load_table();
		});
	}
	function event_change_slot_owner()
	{
		$(".slot-owner").on("change",function()
		{
			action_load_table();
		});
	}
	function event_change_amount()
	{
		$('.requested-amount').keyup(function()
		{
			action_load_amount();
			var amount = $('.requested-amount').val();
			var fee = $('.processing-fee').text();
			clearTimeout(x);

			x = setTimeout(function()
			{
				if(amount!=0||amount!='')
				{
					$('.amount-container').html("<label>Amount to pay: </label><label class='amount-to-pay'>"+(parseInt(amount)+parseInt(fee))+"</label>");
				}
				else
				{
					$('.amount-container').html("");
				}
			},1000);
		});
	}
	function action_load_amount()
	{
		$(".amount-container").html('<div style="text-align: right; font-size: 20px;"><i class="fa fa-spinner fa-pulse fa-fw"></i></div>');
	}
	function event_upload()
	{
		$("body").on('click','.action-upload',function(e)
		{
			$('body').unbind("click");
			var id = $(e.currentTarget).closest('tr').attr("id");
			action_load_link_to_modal('/members/upload-attachment?id='+id, 'md');
		});
	}
}