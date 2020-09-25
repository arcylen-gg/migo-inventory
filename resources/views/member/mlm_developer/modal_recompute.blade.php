<div class="modal-header clearfix">
	<input type="hidden" class="recompute-token" value="{{ csrf_token() }}" name="_token">
	<div class="pull-right">
		<button type="button" class="btn btn-def-white btn-custom-white close-recompute" data-dismiss="modal">Close</button>
		<button class="btn btn-primary btn-custom-primary start-recompute" type="button">Start Recompute</button>
	</div>
	<h4 class="modal-title">RECOMPUTE REWARDS</h4>
</div>
<div class="modal-body clearfix">
	<div class="text-center recompute-status">Click START RECOMPUTE to initiate recompute.</div>
	<div style="text-align: center; padding-bottom: 10px;"><b><span class="count">0</span> out of <span class="total-count">{{ $count }}</span></b></div>
	<div class="progress">
		<div class="progress-bar" role="progressbar" aria-valuenow="0"
			aria-valuemin="0" aria-valuemax="100">
			<span class="percentage">0</span>%
		</div>
	</div>
	<div class="form-group order-tags"></div>
	<div class="clearfix">
		<div class="col-md-12">
			<div class="table-responsive">
				<table class="table table-bordered table-striped table-condensed">
					<thead style="text-transform: uppercase">
						<tr>
							<th class="text-center">SLOT NO.</th>
							<th class="text-center">DATE COMPUTED</th>
							<th class="text-center"></th>
						</tr>
					</thead>
					<tbody>
						@foreach($_slot as $key => $slot)
						<tr class="lslot" c="{{ $key }}" slot_id="{{ $slot->slot_id }}">
							<td class="text-center slot-no">{{ $slot->slot_no }}</td>
							<td class="text-center">{{ $slot->slot_created_date }}</td>
							<td class="text-center check-col"><i class="fa fa-circle-o"></i></td>
						</tr>
						@endforeach
					</tbody>
				</table>
			</div>
		</div>
	</div>
</div>

<script type="text/javascript">
	var compute_data = {};

	event_start_recompute();

	function event_start_recompute()
	{
		$(".start-recompute").click(function()
		{
			if(prompt("WARNING! This will zeroed out all reward and re-compute from the beginning. It is not advisable to use this if the system is live. Type RECOMPUTE if you are sure.") == "RECOMPUTE")
			{
				$(".start-recompute").remove();
				$(".close-recompute").remove();

				compute_data._token = $(".recompute-token").val();
				$(".recompute-status").html("Deleting Wallet Log Data");

				$.ajax(
				{
					url: "/member/mlm/developer/recompute_reset",
					data: compute_data,
					type: "post",
					success: function(data)
					{
						recompute(0);
					}
				});
			}
		});
	}

	function recompute(index)
	{
		$target = $(".lslot[c=" + index + "]");

		if($target.length > 0)
		{
			var slot_no = $target.find(".slot-no").text();

			$(".recompute-status").html("Computing Slot No. <b>" + slot_no + "<b>");

			compute_data.slot_id = $target.attr("slot_id");
			compute_data._token = $(".recompute-token").val();

			$.ajax(
			{
				url: "/member/mlm/developer/recompute",
				data: compute_data,
				type: "post",
				success: function(data)
				{
					var count = (index + 1);
					var count_total = $(".total-count").text();
					var percentage = (count / count_total) * 100;

					$(".progress-bar").css("width", percentage + "%");
					$(".percentage").text(parseInt(percentage));



					$target.find(".check-col").html('<i style="color: green;" class="fa fa-check-circle-o"></i>');

					$(".count").text(count);

					recompute(index+1);
				},
		        error: function()
		        {
		            setTimeout(function()
		            {
		                // alert(123);
		               recompute(index);
		            }, 2000);
		        }
			});
		}
		else
		{
			alert("Computation Done");
		}

	}
</script>