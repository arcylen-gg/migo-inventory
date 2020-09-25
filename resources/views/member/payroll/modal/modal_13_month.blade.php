<form class="global-submit" role="form" action="/member/payroll/payroll_process/modal_submit_13_month" method="post">
	<div class="modal-header">
		<button type="button" class="close" data-dismiss="modal">×</button>
		<h4 class="modal-title">Process 13 month</h4>
		<input type="hidden" name="_token" value="{{csrf_token()}}">
		<input type="hidden" name="payroll_employee_id" value="{{$payroll_employee_id}}"> 
		<input type="hidden" name="payroll_period_company_id" value="{{$payroll_period_company_id}}"> 
	</div>
	<div class="modal-body form-horizontal">
		<table class="table table-condensed table-bordered">
			<thead>
				<tr>
					<th class="text-center">
						<input type="checkbox" name="" class="chck-all">
					</th>
					<th>Payroll Period</th>
					<th>Basic Earnings</th>
					<th></th>
					<th>Sub Total</th>
				</tr>
			</thead>
			<tbody>

				@foreach($_13_month as $n13_month)
				<tr>
					<td class="text-center" valign="top">
						<input type="checkbox" class="m-13-chck" name="payroll_record_id[]" data-value="{{round(($n13_month['regular_salary'] / 12), 2)}}" value="{{$n13_month['payroll_record_id']}}" {!!$n13_month['status']!!}>
					</td>
					<td>
						{{date('F d, Y', strtotime($n13_month['payroll_period_start']))}} to  {{date('F d, Y', strtotime($n13_month['payroll_period_end']))}}
					</td>
					<td class="text-right">
						{{number_format($n13_month['regular_salary'])}}
					</td>
					<td class="text-center">
						divided by 12
					</td>
					<td class="text-right month-13-sub-total">
						{{number_format(($n13_month['regular_salary'] / 12), 2)}}
					</td>
				</tr>
				@endforeach
				<tr>
					<td class="text-center" valign="top">
						<input type="checkbox" class="m-13-chck" name="payroll_period_company_id_v_13" data-value="{{round(($v_regular_salary / 12), 2)}}" value="{{$payroll_period_company_id}}" {!!$v_status!!}>
					</td>
					<td>
						{{date('F d, Y', strtotime($v_13_month_period_start))}} to  {{date('F d, Y', strtotime($v_13_month_period_end))}}
					</td>
					<td class="text-right">
						{{number_format($v_regular_salary)}}
					</td>
					<td class="text-center">
						divided by 12
					</td>
					<td class="text-right month-13-sub-total">
						{{number_format(($v_regular_salary / 12), 2)}}
					</td>
				</tr>
				<tr>
					<td colspan="4" class="text-right">
						<b>Total 13 Month</b>
					</td>
					<td class="text-right month-13-total"></td>
				</tr>
			</tbody>
		</table>
	</div>
	<div class="modal-footer">
		<button type="button" class="btn btn-def-white btn-custom-white" data-dismiss="modal">Close</button>
		<button class="btn btn-primary btn-custom-primary" type="submit"">Submit</button>
	</div>
</form>

<script type="text/javascript">

	compute_13_month();

	$(".m-13-chck").unbind("change");
	$(".m-13-chck").bind("change", function()
	{
		compute_13_month();
	});

	$(".chck-all").unbind("change");
	$(".chck-all").bind("change", function()
	{
		if($(this).is(":checked"))
		{
			$(".m-13-chck").prop("checked", true);
			
		}
		else
		{
			$(".m-13-chck").prop("checked", false);
			
		}

		compute_13_month();
	});


	function compute_13_month()
	{
		var m_13_value = 0;
		$(".m-13-chck").each(function ()
		{
			var m_13 = $(this).data("value");
			if($(this).is(":checked"))
			{
				m_13_value += parseFloat(m_13);
			}
			
		});
		m_13_value = m_13_value.toFixed(2);

		$(".month-13-total").html("<b>"+numberWithCommas(m_13_value)+"</b>");
	}

	function numberWithCommas(x) {
	    return x.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
	}
</script>