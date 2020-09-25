var dashboard = new dashboard();
var $timer;
function dashboard()
{
	init();
	function init()
	{
		$(document).ready(function()
		{
			document_ready();
			action_change_to_insights();
			action_change_to_home();
			action_load_count_transaction();
			action_load_reorder_point();
		});
	}
	function document_ready()
	{
		event_connected_arrow();
	}
	function event_connected_arrow()
	{
			
	}
	
	function action_connected_arrow()
	{
		
	}

	function action_change_to_insights()
	{
		$(".dashboard-insights").click(function () {
			
			$(".home-content").hide('slide',{direction:'left'}, 1000, function()
			{
				$(".insights-content").show('slide',{direction:'right'}, 1000);
				$(".dashboard-home").fadeIn();
			});
			$(this).fadeOut();
	    });
	}

	function action_change_to_home()
	{
		$(".dashboard-home").click(function () {
			$(".insights-content").hide('slide',{direction:'right'}, 1000, function()
			{
				$(".home-content").show('slide',{direction:'left'}, 1000);
				$(".dashboard-insights").fadeIn();
			});
        	$(this).fadeOut();
	    });
	}

	function action_load_count_transaction()
	{
		if($(".auto_load_dashboard").val())
		{
			clearInterval($timer);
			$timer = setInterval(function()
			{
				$(".load-count-transaction").load("/member/load_count_transaction", function()
				{
					console.log("success");
				})

			},30000);			
		}
	}

	function action_load_reorder_point()
	{
		if($(".auto_load_reorder_print").val() && $(".position-name").val() == 'developer' || $(".position-name").val() == 'Cerilo Admin')
		{
			clearInterval($timer);
			$timer = setInterval(function()
			{
				var today = new Date();
				console.log(today.getHours());
				console.log(12345);
				// FOR REORDER
				// Hours should be 20 for 8PM
				if(today.getHours() == $(".set-hour").val() && today.getMinutes() <= $('.set-min').val())
				{
					console.log(today.getMinutes());
					console.log(123);
					window.open(window.location.href+'/transaction/notification/check-reorder','_blank');
				}

				// FOR CHECKING DELIVERY
				$.ajax({
					url : '/member/transaction/notification/check-notification-message',
					type : 'get',
					dataType : 'json',
					success : function(data)
					{
						if((data.print).length > 0)
						{
							for (var i = 0; i <= (data.print).length; i++)
							{
								if((data.print)[i] != null)
								{
									window.open('/member/transaction/'+(data.print)[i], '_blank');
								}
							}			
						}
					}
				});
			},300000);
		}
	}
}