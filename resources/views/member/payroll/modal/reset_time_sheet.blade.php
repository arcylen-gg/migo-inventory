<form class="reset-submit form-horizontal" role="form" action="/member/payroll/reset_payroll/reset_time_sheet/reset_time_sheet_action" method="post">
	<div class="modal-header">
		<button type="button" class="close" data-dismiss="modal">×</button>
		<h4 class="modal-title color-red">RESET TIMESHEET</h4>
		<input type="hidden" name="_token" value="{{csrf_token()}}">
	</div>
	<div class="modal-body clearfix">
		<div class="form-group">
			<div class="col-md-6">
				<small>Select Period</small>
				<select class="form-control reset-select-period" required>
					<option value="">Select Period</option>
					@foreach($_period as $period)
					<option value="{{$period->payroll_period_id}}">{{date('M d, Y', strtotime($period->payroll_period_start)).' - '.date('M d, Y', strtotime($period->payroll_period_end))}}</option>
					@endforeach
				</select>
			</div>
			<div class="col-md-6">
				<small>Password</small>
				<input type="password" name="password" class="form-control" required>
			</div>
		</div>
		<div class="form-group">
			<div class="col-md-12 reset-input">
				
			</div>
		</div>
	</div>
	<div class="modal-footer">
		<button type="button" class="btn btn-def-white btn-custom-white" data-dismiss="modal">Cancel</button>
		<button class="btn btn-custom-red btn-submit-reset" type="submit"">Reset</button>
	</div>
</form>

<script type="text/javascript">
	$(".reset-submit").unbind("submit");
	$(".reset-submit").bind("submit", function(e)
	{
		e.preventDefault();
		$(".btn-submit-reset").html(misc('spinner'));
		$.ajax({
			url 	: $(this).attr('action'),
			type 	: $(this).attr('method'),
			data 	: $(this).serialize(),
			success : function(result)
			{
				try
				{
					result = JSON.parse(result);
				}
				catch(err){}

				if(result.status == 'wrong password')
				{
					toastr.error(result.status);
				}
				else if(result.status == 'success')
				{
					toastr.success(result.affected);
					result.element.modal("toggle");
				}
				$(".btn-submit-reset").html('Reset');
			},
			error 	: 	function(err)
			{
				toastr.error('Error, something went wrong');
				$(".btn-submit-reset").html('Reset');
			}
		});
	});

	$(".reset-select-period").unbind("change");
	$(".reset-select-period").bind("change", function()
	{
		var period = $(this).val();
		$(".reset-input").html(misc('loader'));

		$.ajax({
			url 	: 	'/member/payroll/reset_payroll/reset_time_sheet/reset_time_sheet_select',
			type 	: 	'POST',
			data 	: 	{
				_token:misc('_token'),
				period:period
			},
			success : 	function(result)
			{
				var html = '';
				try
				{
					result = JSON.parse(result);
				}
				catch(err){}

				html += '<div class="checkbox"><label><input type="checkbox" class="parent-period-select">Check All</label></div>';

				$(result).each(function(index, data){
					// try
					html += '<div class="checkbox">';
					html += '<label>';
					html += '<input type="checkbox" class="child-period-select" value="'+data.payroll_period_company_id+'" name="period_company[]">';
					html += data.payroll_company_name;
					html += '</label>';
					html += '</div>';
				});
				$(".reset-input").html(html);
				parent_period_change();
			},
			error 	: 	function(err)
			{
				toastr.error('Error, something went wrong.');
			}
		});
	});

	function parent_period_change()
	{
		$(".parent-period-select").unbind("change");
		$(".parent-period-select").bind("change", function()
		{
			if($(this).is(":checked"))
			{
				$(".child-period-select").prop("checked", true);
			}
			else
			{
				$(".child-period-select").prop("checked", false);
			}
		});
	}

	function misc(str){
		var spinner = '<i class="fa fa-spinner fa-pulse fa-fw"></i><span class="sr-only">Loading...</span>';
		var plus = '<i class="fa fa-plus" aria-hidden="true"></i>';
		var times = '<i class="fa fa-times" aria-hidden="true"></i>';
		var pencil = '<i class="fa fa-pencil" aria-hidden="true"></i>';
		var loader = '<div class="loader-16-gray"></div>'
		var _token = $("#_token").val();

		switch(str){
			case "spinner":
				return spinner
				break;

			case "plus":
				return plus
				break;

			case "loader":
				return loader
				break;

			case "_token":
				return _token
				break;
			case "times":
				return times
				break;
			case "pencil":
				return pencil
				break;
		}
	}  
</script>