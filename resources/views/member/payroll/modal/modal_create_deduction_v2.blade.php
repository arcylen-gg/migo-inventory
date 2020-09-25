<form class="global-submit" role="form" action="/member/payroll/deduction/v2/modal_save_deduction" method="POST">
	<div class="modal-header">
		<button type="button" class="close" data-dismiss="modal">&times;</button>
		<h4 class="modal-title">Create Deduction</h4>
		<input type="hidden" name="_token" value="{{csrf_token()}}">
	</div>
	<div class="modal-body form-horizontal">

		<div class="form-group">
			<div class="col-md-12">
				<small>Deduction Type</small>
				<select class="form-control deduction-category-change" name="payroll_deduction_type" required>
					<option value="">Select Category</option>
					<option value="SSS Loan">SSS Loan</option>
					<option value="HDMF Loan">HDMF Loan</option>
					<option value="Cash Bond">Cash Bond</option>
					<option value="Cash Advance">Cash Advance</option>
					<option value="Others">Others...</option>
				</select>
			</div>
		</div>

		<div class="form-group">
			<div class="col-md-12">
				<small>Deduction Name</small>
				<input type="text" name="payroll_deduction_name" class="form-control" required>
			</div>
		</div>
		<div class="form-group">
			<div class="col-md-12">
				<small>Deduction Amount</small>
				<input type="number" name="payroll_deduction_amount" class="form-control text-right" step="any" required>
			</div>
			
		</div>
		<div class="form-group">
			<div class="col-md-6">
				<small>Monthly Amortization</small>
				<input type="number" name="payroll_monthly_amortization" class="form-control text-right" step="any" required>
			</div>
			<div class="col-md-6">
				<small>Periodical Deduction</small>
				<input type="number" name="payroll_periodal_deduction" class="form-control text-right" step="any" required>
			</div>
		</div>
		<div class="form-group">
			<div class="col-md-6">
				<small>Date filed</small>
				<input type="text" name="payroll_deduction_date_filed" class="form-control datepicker" required>
			</div>
			<div class="col-md-6">
				<small>Date start</small>
				<input type="text" name="payroll_deduction_date_start" class="form-control datepicker" required>
			</div>
		</div>
		{{-- <div class="form-group">
			<div class="col-md-6">
				<small>Date End</small>
				<input type="text" name="payroll_deduction_date_end" class="form-control datepicker" required>
			</div>
		</div> --}}
		<div class="form-group">
			<div class="col-md-12">
				<small>Deduct Every</small>
				<div class="panel panel-default">
					<div class="panel-body">
						<div class="row">
							<div class="col-md-6">
								<div class="radio">
									<label><input type="radio" name="payroll_deduction_period" value="First Period">First Period</label>
								</div>
							</div>
							<div class="col-md-6">
								<div class="radio">
									<label><input type="radio" name="payroll_deduction_period" value="Middle Period">Middle Period</label>
								</div>
							</div>
							<div class="col-md-6">
								<div class="radio">
									<label><input type="radio" name="payroll_deduction_period" value="Last Period">Last Period</label>
								</div>
							</div>
							<div class="col-md-6">
								<div class="radio">
									<label><input type="radio" name="payroll_deduction_period" checked value="Every Period">Every Period</label>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
		
		<div class="form-group">
			<div class="col-md-6">
				<small>Terms</small>
				<input type="number" name="payroll_deduction_terms" class="form-control" required>
			</div>
			<div class="col-md-6">
				<small># of payments</small>
				<input type="number" name="payroll_deduction_number_of_payments" class="form-control" required>
			</div>
		</div>

		<div class="form-group">
			<div class="col-md-6">
				<small>Category</small>
				<select class="form-control deduction-category-change" name="payroll_deduction_category" required>					
					<option value="">Select Category</option>
					<option value="Taxable">Taxable</option>
					<option value="Non-Taxable">Non-Taxable</option>
					<option value="Hidden">Hidden</option>
				</select>
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

		<hr>
		<div class="form-group">
			<div class="col-md-12">
				<span><b>Affected Employee</b><button class="btn btn-custom-primary pull-right popup" type="button" link="/member/payroll/deduction/v2/modal_deduction_tag_employee/0">Tag Employee</button></span>
			</div>
		</div>
		<div class="form-group">
			<div class="col-md-12">
				<table class="table table-condensed table-bordered">
					<thead>
						<tr>
							<th>Employee Name</th>
							<th width="5%"></th>
						</tr>
					</thead>
					<tbody class="table-employee-tag">
						
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
<script type="text/javascript" src="/assets/member/js/payroll/modal_create_deduction.js"></script>
<script type="text/javascript" src="/assets/member/js/textExpand.js"></script>