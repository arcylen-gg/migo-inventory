<form class="global-submit " role="form" action="/member/payroll/payroll_process/create_payroll_adjustment" method="post">
	<div class="modal-header">
		<button type="button" class="close" data-dismiss="modal">×</button>
		<h4 class="modal-title">Payroll Adjustment</h4>
		<input type="hidden" value="{{$payroll_employee_id}}" name="payroll_employee_id">
		<input type="hidden" value="{{$company_period}}" name="company_period">
		<input type="hidden" name="_token" value="{{csrf_token()}}">
	</div>
	<div class="modal-body form-horizontal">
		<div class="form-group">
			<div class="col-md-12">
				<small>Adjustment Name</small>
				<input type="text" name="payroll_adjustment_name" class="form-control" placeholder="Adjustment Name" required>
			</div>
		</div>
		<div class="form-group">
			<div class="col-md-12">
				<small>Amount</small>
				<input type="number" name="payroll_adjustment_amount" class="form-control text-right" step="any" required placeholder="0.00">
			</div>
		</div>
		<div class="form-group">
			<div class="col-md-12">
				<small>Category</small>
				<select class="form-control" name="payroll_adjustment_category" required>
					<option value="">Select Category</option>
					<option value="Allowance">Allowance</option>
					<option value="Bonus">Bonus</option>
					<option value="Commissions">Commissions</option>
					<option value="Incentives">Incentives</option>
					<option value="13 month pay">13 month pay</option>
					<option value="Deductions">Deductions</option>
				</select>
			</div>
		</div>
	</div>
	<div class="modal-footer">
		<button type="button" class="btn btn-def-white btn-custom-white" data-dismiss="modal">Close</button>
		<button class="btn btn-primary btn-custom-primary" type="submit"">Submit</button>
	</div>
</form>