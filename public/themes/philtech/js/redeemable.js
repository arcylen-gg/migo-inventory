var redeemable = new redeemable();

function redeemable()
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
		select2();
		action_point_loader();
		event_slot_change();
	}
	function select2()
	{
		$('.slot-owner').select2();
	}
	function event_slot_change()
	{
		$('.slot-owner').on('change',function()
		{
			action_point_loader();
		});
	}
	function action_point_loader()
	{
		$(".points-here").html('<i class="fa fa-spinner fa-pulse fa-fw"></i>');
		action_load_points();
	}
	function action_load_points()
	{
		var slot_no = $('.slot-owner').val();
		$('.hidden-slot-no').val($('.slot-owner').val());
		console.log(slot_no);
		$.ajax(
		{
			url: '/members/slot-points',
			type: 'get',
			data: 'slot_no='+slot_no,
			success: function(data)
			{
				$('.points-here').html('<h4><b>Remaining Points: <span style="font-weight: 300;">'+data+' POINT(S)</span></b></h4>');
			}
		});
	}
}