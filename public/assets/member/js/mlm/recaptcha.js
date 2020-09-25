var recaptcha = new recaptcha();

function recaptcha()
{
	init();

	this.action_load_table = function()
	{
		action_load_table();
	}
	this.action_load_pool = function()
	{
		action_load_pool();
	}
	this.action_load_points = function()
	{
		action_load_points();
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
		action_load_pool();
		action_load_points();
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
			url: '/member/mlm/recaptcha/recaptcha_table',
			type: "get",
			success: function(data)
			{
				$('.load-table-here').html(data);
			}
		});
	}
	function action_load_pool()
	{
		$(".pool-amount").html('<label>Total Pool Amount <b>: </b> </label><font color="red"><i class="fa fa-spinner fa-pulse fa-fw"></i>');
		$.ajax(
		{
			url: '/member/mlm/recaptcha/load_pool',
			type: "get",
			success: function(data)
			{
				$(".pool-amount").html('<label>Total Pool Amount <b>:</b> </label><font color="red"> '+data+'</font>');
			}
		});
	}
	function action_load_points()
	{
		$(".points-per-submit").html('<label>Acquired points per submit <b>: </b> </label><font color="red"><i class="fa fa-spinner fa-pulse fa-fw"></i>');
		$.ajax(
		{
			url: '/member/mlm/recaptcha/load_points',
			type: "get",
			success: function(data)
			{
				$(".points-per-submit").html('<label>Acquired points per submit <b>:</b> </label><font color="red"> '+data+'</font>');
			}
		});
	}
}