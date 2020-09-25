<form class="global-submit" role="form" action="/member/payroll/employee_list/modal_update_salary" method="POST">
	<div class="modal-header">
		<button type="button" class="close" data-dismiss="modal">&times;</button>
		<h4 class="modal-title">Salary Details</h4>
		<input type="hidden" name="_token" value="{{csrf_token()}}">
		<input type="hidden" name="payroll_employee_salary_id" value="{{$salary->payroll_employee_salary_id}}">

	</div>
	<div class="modal-body form-horizontal">
		<div class="form-group">
			<div class="col-md-6">
				<small>Effective date</small>
				<input type="text" name="payroll_employee_salary_effective_date" class="form-control datepicker" value="{{$salary->payroll_employee_salary_effective_date != '0000-00-00' ?  date('m/d/Y', strtotime($salary->payroll_employee_salary_effective_date)):''}}">
			</div>
		</div>
		<div class="form-group">
			
			<div class="col-md-12">
				<div class="checkbox">
					<label><input type="checkbox" name="payroll_employee_salary_minimum_wage" value="1" {{$salary->payroll_employee_salary_minimum_wage == 1 ? 'checked':''}}>Minimum wage earner</label>
				</div>
				<div class="checkbox">
					<label><input type="checkbox" name="tbl_payroll_employee_custom_compute" class="custom-compute-pop-chck" value="1" {{$salary->tbl_payroll_employee_custom_compute == 1 ? 'checked':''}}>Declare Salary for SSS, Philhealth and Tax?</label>
				</div>
			</div>
		</div>
		<div class="form-group">
			<div class="col-md-4">
				<small>Monthly Rate</small>
				<input type="number" name="payroll_employee_salary_monthly" step="any" class="form-control text-right" required placeholder="0.00" value="{{$salary->payroll_employee_salary_monthly}}">
			</div>
			<div class="col-md-4">
				<small>Daily Rate</small>
				<input type="number" step="any" name="payroll_employee_salary_daily" class="form-control text-right" placeholder="0.00" value="{{$salary->payroll_employee_salary_daily}}">
			</div>
			<div class="col-md-4">
				<small>Hourly Rate</small>
				<input type="number" step="any" name="payroll_employee_salary_hourly" class="form-control text-right" placeholder="0.00" value="{{$salary->payroll_employee_salary_hourly}}">
			</div>
		</div>
		<div class="form-group">
			<div class="col-md-4">
				<small>COLA (Daily)</small>
				<input type="number" step="any" name="payroll_employee_salary_cola" class="form-control text-right" placeholder="0.00" value="{{$salary->payroll_employee_salary_cola}}">
			</div>
			<div class="col-md-4">
				<small>COLA (Monthly)</small>
				<input type="number" step="any" name="payroll_employee_salary_monthly_cola" class="form-control text-right" placeholder="0.00" value="{{$salary->monthly_cola}}">
			</div>
		</div>
		<div class="custom-compute-pop-obj">
			<div class="form-group">
				<div class="col-md-4">
					<small>Taxable Salary</small>
					<input type="number" name="payroll_employee_salary_taxable" class="form-control text-right" step="any" placeholder="0.00" value="{{$salary->payroll_employee_salary_taxable}}">
				</div>
				<div class="col-md-4">
					<small>Pagibig Contribution</small>
					<input type="number" name="payroll_employee_salary_pagibig" class="form-control text-right" step="any" placeholder="0.00" value="{{$salary->payroll_employee_salary_pagibig}}">
				</div>
				<div class="col-md-4">
					<small>Philhealth Salary</small>
					<input type="number" name="payroll_employee_salary_philhealth" class="form-control text-right" step="any" placeholder="0.00" value="{{$salary->payroll_employee_salary_philhealth}}">
				</div>
			</div>
			<div class="form-group">
				<div class="col-md-4">
					<small>SSS Salary</small>
					<input type="number" name="payroll_employee_salary_sss" class="form-control text-right" step="any" placeholder="0.00" value="{{$salary->payroll_employee_salary_sss}}">
				</div>
			</div>
			<div class="form-group hidden">
				<div class="col-md-6">
					<small>COLA (Monthly)</small>
					<input type="number" step="any" name="monthly_cola" class="form-control text-right" placeholder="0.00">
				</div>

			</div>
			
			<hr>
	    <div class="form-group hidden">
	      <div class="col-md-12">
	        <label>Mode of Deduction</label>
	      </div>
	    </div>
	    <div class="form-group hidden">
	      <div class="col-md-6">
	        <div class="checkbox">
	          <label><input type="checkbox" name="is_deduct_sss_default" class="deduction-check-period-new" data-target="#sss-deduction-period-new" value="1" {{$salary->is_deduct_sss_default == 1 ? 'checked="checked"':''}}>Compute SSS base on default</label>
	        </div>
	      </div>
	      <div class="col-md-6">
	        <small>SSS contribution per period</small>
	        <input type="number" name="deduct_sss_custom" class="form-control text-right" placeholder="0.00" step="any" id="sss-deduction-period-new" value="{{$salary->deduct_sss_custom}}">
	      </div>
	    </div>
	    <div class="form-group hidden">
	      <div class="col-md-6">
	        <div class="checkbox">
	          <label><input type="checkbox" name="is_deduct_philhealth_default" class="deduction-check-period-new" data-target="#philhealth-deduction-period-new" value="1" {{$salary->is_deduct_philhealth_default == 1 ? 'checked="checked"':''}}>Compute PHILHEALTH base on default</label>
	        </div>
	      </div>
	      <div class="col-md-6">
	        <small>PHILHEALTH contribution per period</small>
	        <input type="number" name="deduct_philhealth_custom" class="form-control text-right" placeholder="0.00" step="any" id="philhealth-deduction-period-new" value="{{$salary->deduct_philhealth_custom}}">
	      </div>
	    </div>
	    <div class="form-group hidden">
	        <div class="col-md-6">
	          <div class="checkbox">
	            <label><input type="checkbox" name="is_deduct_pagibig_default" class="deduction-check-period-new" data-target="#pagibig-deduction-period-new" value="1" {{$salary->is_deduct_pagibig_default == 1 ? 'checked="checked"':''}}>Compute PAGIBIG base on default</label>
	          </div>
	        </div>
	        <div class="col-md-6">
	          <small>PAGIBIG contribution per period</small>
	          <input type="number" name="deduct_pagibig_custom" class="form-control text-right" placeholder="0.00" step="any" id="pagibig-deduction-period-new" value="{{$salary->deduct_pagibig_custom}}">
	        </div>
	      </div>
		</div>
	</div>
	<div class="modal-footer">
		<button type="button" class="btn btn-custom-white" data-dismiss="modal">Close</button>
		<button class="btn btn-custom-primary btn-submit" type="submit">Update</button>
	</div>
</form>
<script type="text/javascript">
	check_deduction_contribution_new_action();
	check_custom_compute_pop_action();
	function submit_done(data)
	{
		data.element.modal("toggle");
		// executeFunctionByName(data.function_name, window);
	}

	$(".deduction-check-period-new").unbind("change");
	$(".deduction-check-period-new").bind("change", function()
	{
		check_deduction_contribution_new_action();
	});
	
	$(".custom-compute-pop-chck").unbind('change');
	$(".custom-compute-pop-chck").bind("change", function()
	{
		check_custom_compute_pop_action();
	});
	
	function check_custom_compute_pop_action()
	{
		if($(".custom-compute-pop-chck").is(':checked'))
		{
			$(".custom-compute-pop-obj").fadeIn();
		}
		else
		{
			$(".custom-compute-pop-obj").css('display','none');
		}
	}
	
	function check_deduction_contribution_new_action()
	{
		$(".deduction-check-period-new").each(function()
		{

			var target = $(this).data("target");
			if($(this).is(":checked"))
			{	
				$(target).attr("readonly",true);
				$(target).removeAttr("required");
			}
			else
			{
				$(target).removeAttr("readonly");
				$(target).attr("required",true);
			}
		});
	}

	// function salary_update_success(data)
	// {
	// 	employeelist.reload_salary_list();
	// 	data.element.modal("hide");
	// }
</script>