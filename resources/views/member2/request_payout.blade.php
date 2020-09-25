<form method="post" class="request-payout-form">
	{{ csrf_field() }}
	<input type="hidden" name="settings_tax" value="10">
	<input type="hidden" name="settings_charge" value="100">

	<div class="modal-header">
		<button type="button" class="close" data-dismiss="modal">×</button>
		<h4 class="modal-title"><i class="fa fa-money"></i> REQUEST PAYOUT</h4>
	</div>
	<div class="modal-body clearfix">
		<div class="error-message-here text-center" style="color: red; padding: 5px;"></div>
	    <table class="table table-bordered table-striped table-condensed">
	        <thead style="text-transform: uppercase">
	            <tr>
	                <th class="text-center">SLOT CODE</th>
	                <th class="text-center" width="180px;">CURRENT WALLET</th>
	                <th class="text-center" width="180px">REQUEST AMOUNT</th>
	            </tr>
	        </thead>
	        <tbody>
	        	@foreach($_slot as $slot)

	            <tr current_wallet="{{ $slot->current_wallet }}">
	                <td style="vertical-align: middle;" class="text-center">{{ $slot->slot_no }}</td>
	                <td style="vertical-align: middle;" class="text-center">{{ $slot->display_current_wallet }}</td>
	                <td style="vertical-align: middle;" class="text-center"><input name="request_wallet[]" class="form-control text-center" placeholder="ENTER AMOUNT" type="text" value="{{ floor($slot->current_wallet) }}"></td>
	            </tr>
	            @endforeach
	        </tbody>
	    </table>
	</div>
	<div class="modal-footer">
		<button type="button" class="btn btn-def-white btn-custom-white btn-custom-close" data-dismiss="modal"><i class="fa fa-close"></i> Close</button>
		<button type="submit" class="btn btn-primary btn-custom-primary" type="button"><i class="fa fa-angle-double-right"></i> Request Payout</button>
	</div>
</form>

<script type="text/javascript">
	var request_payout = new request_payout();

	function request_payout()
	{
		init();

		function init()
		{
			event_request_payout_submit();
		}

		function event_request_payout_submit()
		{
			$(".request-payout-form").submit(function(e)
			{
				$.ajax(
				{
					url:"/members/request-payout",
					data: $(e.currentTarget).serialize(),
					type:"post",
					success: function(data)
					{
						if(data == "")
						{
							$("#global_modal").modal("hide");
							setTimeout(function()
							{
								action_load_link_to_modal("/members/verify-payout", "lg");
							}, 350);
						}
						else
						{
							$(".error-message-here").html(data);
						}	
					}
				});

				return false;
			});
		}
	}
</script>