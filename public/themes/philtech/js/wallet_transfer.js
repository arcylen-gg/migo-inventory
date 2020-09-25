var wallet_transfer = new wallet_transfer();
var table_data = {};
var x = null;
function wallet_transfer()
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
		event_slot_change();
		action_load_wallet();
		event_search_recipient();
		event_choose_prediction();
		action_load_transfer_fee();
	}
	function action_load_wallet()
	{
		var slot = $(".slot-owner").val();
		$.ajax(
		{
			url: "/members/current-wallet",
			type: "get",
			data: {"slot_owner":slot},
			success: function(data)
			{
				$('.current_wallet').text("Current Wallet: "+data);
				$('.amount').attr("max",data-20);
				if(data<1)
				{
					$('.amount').attr("disabled",true);
				}
				else
				{
					$('.amount').attr("disabled",false);
				}
			}
		});

	}
	function action_load_transfer_fee()
	{
		$.ajax(
		{
			url: "/members/wallet-transfer-fee",
			type: "get",
			success: function(data)
			{
				$('.transfer-fee').text("Transfer fee: "+data);
			}
		});
	}
	function event_slot_change()
	{
		$(".slot-owner").on("change",function()
		{
			action_load_wallet();
		});
	}
	function event_search_recipient()
	{
		$('.search-recipient').keyup(function()
		{
			action_load_table();
			clearTimeout(x);

			x = setTimeout(function()
			{
				action_load_table();
			}, 1000);

		});
	}
	function action_table_loader()
	{
		$(".load-prediction-here").html('<div style="margin-top:5px;text-align: center; font-size: 20px;"><i class="fa fa-spinner fa-pulse fa-fw"></i></div>');
	}
	function action_load_table()
	{
		action_table_loader();
		table_data.keyword = $('.search-recipient').val();
		$.ajax(
		{
			url: '/members/wallet-transfer-prediction',
			data: table_data,
			type: "get",
			success: function(data)
			{
				$('.load-prediction-here').html(data);
			}
		});
	}
	function event_choose_prediction()
	{
		$("body").on("click",".prediction-choice",function(e)
		{
			$(".search-recipient").val($(e.currentTarget).attr("id"));
			$('.load-prediction-here').html("");
		});
	}

}