<form class="global-submit" role="form" action="/member/payroll/allowance/v2/modal_save_allowances" method="POST">
	<div class="modal-header">
		<button type="button" class="close" data-dismiss="modal">&times;</button>
		<h4 class="modal-title">Create Allowance</h4>
		<input type="hidden" name="_token" value="{{csrf_token()}}">
	</div>
	<div class="modal-body form-horizontal">
		<div class="form-group">
			<div class="col-md-12">
				<small>Allowance Name</small>
				<input type="text" name="payroll_allowance_name" class="form-control" required>
			</div>
			
		</div>
		<!-- <div class="form-group">
			<div class="col-md-6">
				<small>Amount</small>
				<input type="number" name="payroll_allowance_amount" class="form-control text-right" required step="any">
			</div>
		</div> -->
		<div class="form-group">
			<div class="col-md-6">
				<small>Type</small>
				<select class="form-control payroll-allowance-type" name="payroll_allowance_type" required="">
					<option value="">Select Type</option>
					<option value="fixed">fixed</option>
					<option value="daily">daily</option>
					<option value="pro_rated">pro rated</option>
				</select>
			</div>
			<div class="col-md-6">
				<small>Category</small>
				<select class="form-control" name="payroll_allowance_category" required="">
					<option value="">Select Category</option>
					<option value="Taxable">Taxable</option>
					<option value="Non-Taxable">Non Taxable</option>
					<option value="Hidden">Hidden</option>
				</select>
			</div>
		</div>

		<div class="form-group actual-gross-pay hidden">
			<div class="col-md-12">
				<small>Actual Gross Pay</small>
				<div class="panel panel-default">
					<div class="panel-body">
						<div class="row">

							<div class="col-md-4">
								<div class="checkbox">
									<label><input type="checkbox" name="actual_gross_pay[]" value="basic_pay">Basic Pay</label>
								</div>
							</div>

							<div class="col-md-4">
								<div class="checkbox">
									<label><input type="checkbox" name="actual_gross_pay[]" value="cola">COLA</label>
								</div>
							</div>

							<div class="col-md-4">
								<div class="checkbox">
									<label><input type="checkbox" name="actual_gross_pay[]" value="over_time_pay">Over Time Pay</label>
								</div>
							</div>

							<div class="col-md-4">
								<div class="checkbox">
									<label><input type="checkbox" name="actual_gross_pay[]" value="regular_holiday_pay">Regular Holiday Pay</label>
								</div>
							</div>

							<div class="col-md-4">
								<div class="checkbox">
									<label><input type="checkbox" name="actual_gross_pay[]" value="special_holiday_pay">Special Holiday Pay</label>
								</div>
							</div>

							<div class="col-md-4">
								<div class="checkbox">
									<label><input type="checkbox" name="actual_gross_pay[]" value="leave_pay">Leave Pay</label>
								</div>
							</div>

						</div>				
					</div>
				</div>
			</div>
		</div>

		<div class="form-group">
			<div class="col-md-12">
				<small>Add Every</small>
				<div class="panel panel-default">
					<div class="panel-body">
						<div class="row">
							<div class="col-md-6">
								<div class="radio">
									<label><input type="radio" name="payroll_allowance_add_period" value="First Period">First Period</label>
								</div>
							</div>
							<div class="col-md-6">
								<div class="radio">
									<label><input type="radio" name="payroll_allowance_add_period" value="Second Period">Second Period</label>
								</div>
							</div>
							<div class="col-md-6">
								<div class="radio">
									<label><input type="radio" name="payroll_allowance_add_period" value="Last Period">Last Period</label>
								</div>
							</div>
							<div class="col-md-6">
								<div class="radio">
									<label><input type="radio" name="payroll_allowance_add_period" checked value="Every Period">Every Period</label>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>

		<div class="form-group">
			<div class="col-md-6">
	            <label>Expense Account *</label>
	            <select name="expense_account_id" class="drop-down-coa form-control expense_account_id" id="expense_account_id" required>                
	            	@include("member.load_ajax_data.load_chart_account", ['add_search' => "", '_account' => $_expense, 'account_id' => $default_expense])
	            </select>
	        </div>
		</div>
		
		<div class="form-group">
			<div class="col-md-12">
				<span><b>Affected Employee<a href="#" class="btn btn-custom-primary pull-right popup" link="/member/payroll/allowance/v2/modal_allowance_tag_employee/0'">Tag Employee</a></b></span>
			</div>
		</div>
		<div class="form-group">
			<div class="col-md-12">
				<table class="table table-bordered table-condensed">
					<thead>
						<tr>
							<th>Employee Name</th>
							<th>Amount</th>
							<th width="5%"></th>
						</tr>
					</thead>
					<tbody class="tbl-tag">
						
					</tbody>
				</table>
			</div>
		</div>

	</div>
	<div class="modal-footer">
		<button type="button" class="btn btn-custom-white" data-dismiss="modal">Close</button>
		<button class="btn btn-custom-primary btn-submit" type="submit">Save</button>
	</div>
</form>
<script type="text/javascript" src="/assets/member/js/payroll/create_allowance.js"></script>