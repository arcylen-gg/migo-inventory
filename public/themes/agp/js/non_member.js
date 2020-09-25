var non_member = new non_member();

function non_member()
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
		add_event_click_enter_a_code();
		add_event_submit_verify_sponsor();
		add_event_submit_verify_code();
		add_event_process_slot_creation();
		add_event_submit_verify_placement();
		add_event_process_slot_placement();
		add_event_place_slot();
		add_event_congratulation_proceed();
		auto_popup_if_slot_creation_success();
	}
	function add_event_click_enter_a_code()
	{
		$(".btn-enter-a-code").click(function()
		{
			if($(".mobile-mode").length > 0)
			{
				mainView.router.loadPage('/members/enter-code');
			}
			else
			{
				action_load_link_to_modal("/members/enter-code");
			}
		});
	}
	function add_event_congratulation_proceed()
	{
		$(".btn-congratulation").bind("click", function()
		{
			if($(".mobile-mode").length > 0)
			{
				mainView.router.loadPage('/members/enter-sponsor');
			}
			else
			{
				$("#popup-notification-modal").modal("hide");
			
				setTimeout(function()
				{
					action_load_link_to_modal("/members/enter-sponsor");
				}, 350);	
			}

		});
	}
	function add_event_place_slot()
	{
		$(".place_slot_btn").click(function()
		{
			$(".message-return-slot-placement-verify").empty();
			$(".chosen_slot_id").val($(this).attr("place_slot_id"));
			$("#slot-placement-modal").modal("show");
		});
	}
	function auto_popup_if_slot_creation_success()
	{
		if($(".not_placed_yet").val() == 1)
		{
			setTimeout(function()
			{
				var enter_placement_link = $(".not_placed_yet").attr("link");
				
				if($(".mobile-mode").length > 0)
				{
					myApp.confirm('Brown App detected that you are not yet placed. Would you like to place yourself now?', 'Brown Tree Notification',  function ()
					{
						mainView.router.loadPage(enter_placement_link);
					});
				}
				else
				{
					action_load_link_to_modal(enter_placement_link);
				}
			}, 350);
		}
		else
		{
			if($("._mode").val() == "success")
			{
				$("#success-modal").modal("show");
			}
		}
	}
	function add_event_process_slot_creation()
	{
		$("body").on("click", ".process-slot-creation", function()
		{
			var form_data = {};
			form_data._token = $("._token").val();

			$(".process-slot-creation").html('<i class="fa fa-spinner fa-pulse fa-fw"></i> Processing');

			$.ajax(
			{
				url:"/members/final-verify",
				dataType:"json",
				type:"post",
				data: form_data,
				success: function(data)
				{
					if(data == "success")
					{
						if($(".mobile-mode").length > 0)
						{
						}
						else
						{
							$("#proceed-modal-1").modal('hide');
						}

						window.location.reload();
					}
					else
					{
						alert(data);
						window.location.reload();
					}
				}
			});
		});
	}
	function add_event_submit_verify_code()
	{
		$("body").on("submit", ".code-verification-form", function()
		{
			action_verify_code();
			return false;
		});
	}
	function add_event_submit_verify_sponsor()
	{
		$("body").on("submit", ".submit-verify-sponsor", function(e)
		{
			if($(e.currentTarget).find(".btn-verify-sponsor").hasClass("use"))
			{
				if($(".mobile-mode").length > 0)
				{
					mainView.router.loadPage('/members/final-verify');
				}
				else
				{
					$("#global_modal").modal("hide");
	
					setTimeout(function()
					{
						action_load_link_to_modal("/members/final-verify");
					}, 350);
				}
			}
			else
			{
				action_verify_sponsor();
			}
			
			return false;
		});
	}	
	function add_event_submit_verify_placement()
	{
		$("body").on("submit", ".slot-placement-form", function(e)
		{
			action_verify_placement();
			return false;
		});
	}
	function action_verify_code()
	{
		var form_data = {};
		form_data._token = $("._token").val();
		form_data.pin = $(".input-pin").val();
		form_data.activation = $(".input-activation").val();

		/* START LOADING AND DISABLE FORM */
		$(".code-verification-form").find(".btn-proceed-2").html('<i class="fa fa-spinner fa-pulse fa-fw"></i> VERIFYING').attr("disabled", "disabled");
		$(".code-verification-form").find("select").attr("disabled", "disabled");

		$.ajax(
		{
			url:"/members/verify-code",
			data:form_data,
			type:"post",
			success: function(data)
			{
				$(".message-return-code-verify").html(data);
				$(".code-verification-form").find(".btn-proceed-2").html('<i class="fa fa-angle-double-right"></i> PROCEED').removeAttr("disabled");

				if(data == "")
				{
					$(".code-verification-form").find("input").val("");
					
					if($(".mobile-mode").length > 0)
					{
						mainView.router.loadPage('/members/enter-sponsor');
					}
					else
					{
						$("#global_modal").modal('hide');
						
						setTimeout(function()
						{
							action_load_link_to_modal('/members/enter-sponsor');
						}, 350);	
					}
						

					
				}
			},
			error: function(data)
			{
				alert("An ERROR occurred. Please contact administrator.");
				$(".code-verification-form").find(".btn-proceed-2").html('<i class="fa fa-angle-double-right"></i> PROCEED').removeAttr("disabled");
			}
		});

	}
	function action_verify_sponsor()
	{
		$(".submit-verify-sponsor").find(".btn-verify-sponsor").html('<i class="fa fa-spinner fa-pulse fa-fw"></i> VERIFYING SPONSOR').attr("disabled", "disabled");
		$(".submit-verify-sponsor").find("input").attr("disabled", "disabled");

		var form_data = {};
		form_data._token = $("._token").val();
		form_data.verify_sponsor = $(".input-verify-sponsor").val();

		$.ajax(
		{
			url:"/members/verify-sponsor",
			data:form_data,
			type:"post",
			success: function(data)
			{
				$(".submit-verify-sponsor").find(".output-container").html(data);
				$(".card").hide().slideDown('fast');

				if($(".card").length > 0)
				{
					$(".submit-verify-sponsor").find(".btn-verify-sponsor").html('<i class="fa fa-check"></i> USE THIS SPONSOR').removeAttr("disabled").addClass("use");
				}	
				else
				{
					$(".submit-verify-sponsor").find("input").removeAttr("disabled");
					$(".submit-verify-sponsor").find(".btn-verify-sponsor").html('<i class="fa fa-check"></i> VERIFY SPONSOR').removeAttr("disabled").removeClass("use");
				}
			},
			error: function()
			{
				alert("An ERROR occurred. Please contact administrator.");
				$(".submit-verify-sponsor").find("input").removeAttr("disabled");
				$(".submit-verify-sponsor").find(".btn-verify-sponsor").html('<i class="fa fa-check"></i> VERIFY SPONSOR').removeAttr("disabled").removeClass("use");
			}
		});
	}
	function action_verify_placement()
	{
		var form_data 			 = {};
		form_data._token 		 = $("._token").val();
		form_data.slot_placement = $(".input-slot-placement").val();
		form_data.slot_position  = $(".input-slot-position").val();
		form_data.slot_id 		 = $(".chosen_slot_id").val();
		/* START LOADING AND DISABLE FORM */
		$(".slot-placement-form").find(".btn-verify-placement").html('<i class="fa fa-spinner fa-pulse fa-fw"></i> VERIFYING').attr("disabled", "disabled");
		// $(".slot-placement-form").find("select").attr("disabled", "disabled");

		$.ajax(
		{
			url:"/members/verify-slot-placement",
			data:form_data,
			type:"post",
			success: function(data)
			{
				$(".message-return-slot-placement-verify").html(data);
				$(".slot-placement-form").find(".btn-verify-placement").html('<i class="fa fa-angle-double-right"></i> PROCEED').removeAttr("disabled");

				if(data === '"success"')
				{
					if($(".mobile-mode").length > 0)
					{
						mainView.router.loadPage("/members/final-verify-placement?slot_id="+form_data.slot_id+"&slot_position="+form_data.slot_position+"&slot_placement="+form_data.slot_placement);
					}
					else
					{
						$("#global_modal").hide();
						
						setTimeout(function()
						{
							action_load_link_to_modal("/members/final-verify-placement?slot_id="+form_data.slot_id+"&slot_position="+form_data.slot_position+"&slot_placement="+form_data.slot_placement);
						});	
					}
				

				}
				else
				{
					
				}
			},
			error: function(data)
			{
				alert("An ERROR occurred. Please contact administrator.");
				$(".slot-placement-form").find(".btn-verify-placement").html('<i class="fa fa-angle-double-right"></i> PROCEED').removeAttr("disabled");
			}
		});

	}
	function add_event_process_slot_placement()
	{
		$("body").on("click", ".process-slot-placement", function()
		{
			var form_data 			 = {};
			form_data._token 		 = $("._token").val();
			form_data.slot_placement = $(".final_verification_slot_placement").val();
			form_data.slot_position  = $(".final_verification_slot_position").val();
			form_data.slot_id 		 = $(".final_verification_slot_id").val();
			$(".process-slot-placement").html('<i class="fa fa-spinner fa-pulse fa-fw"></i> Processing');

			$.ajax(
			{
				url:"/members/final-verify-placement",
				dataType:"json",
				type:"post",
				data: form_data,
				success: function(data)
				{
					if(data == "success")
					{
						if($(".mobile-mode").length > 0)
						{
						}
						else
						{
							$("#proceed-modal-1").modal('hide');
						}
						
						window.location.reload();
					}
					else
					{
						console.log(data);
						window.location.reload();
					}
				}
			});
		});
	}
}