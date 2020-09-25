<form method="post" class="request-payout-form">
	{{ csrf_field() }}
	<input type="hidden" name="settings_tax" value="10">
	<input type="hidden" name="settings_charge" value="100">

	<div class="modal-header">
		<button type="button" class="close" data-dismiss="modal">×</button>
		<h4 class="modal-title"><i class="fa fa-money"></i> VERIFY REQUEST PAYOUT</h4>
	</div>
	<div class="modal-body clearfix">
		<div class="error-message-here text-center" style="color: red; padding: 5px;"></div>
	    <table class="table table-bordered table-striped table-condensed">
	        <thead style="text-transform: uppercase">
	            <tr>
	                <th class="text-center">SLOT CODE</th>
	                <th class="text-center" width="180px">REQUEST AMOUNT</th>
	                <th class="text-center" width="120px;">TAX</th>
	                <th class="text-center" width="120px;">SERVICE</th>
	                <th class="text-center" width="120px;">OTHER</th>
	                <th class="text-right" width="180px;">SUBTOTAL</th>
	            </tr>
	        </thead>
	        <tbody>
	        	@foreach($_slot as $slot)
	        	<tr>
	        		<td class="text-center">{{ $slot->slot_no }}</td>
	        		<td class="text-center">{{ $slot->display_request_amount }}</td>
	        		<td class="text-center">{{ $slot->display_tax_amount }}</td>
	        		<td class="text-center">{{ $slot->display_service_charge }}</td>
	        		<td class="text-center">{{ $slot->display_other_charge }}</td>
	        		<td class="text-right">{{ $slot->display_take_home }}</td>
	        	</tr>
	        	@endforeach
	        </tbody>
	        <tfoot>
	        	<tr>
	        		<td colspan="5" class="text-right">TAKE HOME PAY</td>
	        		<td class="text-right" style="font-weight: bold;">{{ $display_total_payout }}</td>
	        	</tr>
	        </tfoot>
	    </table>
	</div>
	<div class="modal-footer">
		<button type="button" class="btn btn-def-white btn-custom-white btn-custom-cancel" data-dismiss="modal"><i class="fa fa-close"></i> Cancel</button>
		<button type="submit" class="btn btn-primary btn-custom-primary" type="button"><i class="fa fa-check"></i> Confirm Request Payout</button>
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
					url:"/members/verify-payout",
					data: $(e.currentTarget).serialize(),
					type:"post",
					success: function(data)
					{
						if(data == "")
						{
							window.location.reload();
						}
						else
						{
							alert("A problem occurred");
						}
					}
				});

				return false;
			});
		}
	}

</script>