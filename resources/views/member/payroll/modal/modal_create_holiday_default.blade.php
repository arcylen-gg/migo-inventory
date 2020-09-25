<form class="global-submit" role="form" action="/member/payroll/holiday_default/modal_save_holiday_default" method="POST">
	<div class="modal-header">
		<button type="button" class="close" data-dismiss="modal">&times;</button>
		<h4 class="modal-title">Create Default Holiday</h4>
		<input type="hidden" name="_token" value="{{csrf_token()}}">
	</div>
	<div class="modal-body form-horizontal">
		<div class="form-group">
			<div class="col-md-12">
				<small>Default Holiday Name</small>
				<input type="text" name="payroll_holiday_name" class="form-control">
			</div>
		</div>
		<div class="form-group">
			<div class="col-md-6">
				<small>Date</small>
				<input type="text" name="payroll_holiday_date" class="form-control datepicker">
			</div>
			<div class="col-md-6">
				<small>Category</small>
				<select class="form-control" required name="payroll_holiday_category">
					<option value="">Select Category</option>
					<option value="Regular">Regular</option>
					<option value="Special">Special</option>
				</select>
			</div>
		</div>		
	</div>
	<div class="modal-footer">
		<button type="button" class="btn btn-custom-white" data-dismiss="modal">Close</button>
		<button class="btn btn-custom-primary btn-submit" type="submit">Save</button>
		
	</div>
</form>
<script type="text/javascript">
	$(".datepicker").datepicker();
	$(".check-all").unbind("change");
	$(".check-all").bind("change", function()
	{
		if($(this).is(':checked'))
		{
			$(".company-check").prop("checked", true);
		}	
		else
		{
			$(".company-check").prop("checked", false);
		}
	});


	$(".datepicker").unbind("change");
	$(".datepicker").bind("change", function()
	{
		if($(this).is(':checked'))
		{
			$(".company-check").prop("checked", true);
		}	
		else
		{
			$(".company-check").prop("checked", false);
		}
	});



</script>