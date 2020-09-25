<form class="global-submit form-horizontal" id="payroll_13th_month_pay_basis_form" role="form" action="/member/payroll/reports/employee_13_month_pay_basis_submit" method="post">
	<div class="modal-header">
		<input type="hidden" name="_token" value="{{csrf_token()}}">
		@if(isset($employee_id))
		<input type="hidden" name="payroll_employee_id" value="{{$employee_id}}">
		@endif
		<button type="button" class="close" data-dismiss="modal">×</button>
		<h4 class="modal-title">13TH MONTH PAY BASIS</h4>
	</div>
	<div class="modal-body clearfix">
		<div class="row">
				
			  	<div class="form-group col-md-6" >
				    <label>Basis</label>
				    <select class="form-control" name="payroll_13th_month_pay_basis" >
				    	<option value="gross_pay">Gross Basic Pay</option>
				    	<option value="net_pay">Net Basic Pay</option>
				    	<option value="monthly_rate">Monthly Rate</option>
				    </select>
			  	</div>
			  	<div class="form-group col-md-6" style="margin-left: 10px;">
				    <label>Year</label>
				    <select class="form-control" name="payroll_13th_month_pay_year" >
				    	<option value="2017">2017</option>
				    	<option value="2018">2018</option>
				    	<option value="2019">2019</option>
				    </select>
			  	</div>
		</div>
	  	<div class="form-group">
	  		<label>Additions (+)</label>
	  		<div class="row">
	  			<div class="col-md-2">
	  				<label><input type="checkbox" value="allowance" name="payroll_allowance">Allowance</label>
	  			</div>
	  			<div class="col-md-2">
	  				<label><input type="checkbox" value="special_holiday" name="special_holiday"> Special Holiday</label>
	  			</div>
	  			<div class="col-md-2">
	  				<label><input type="checkbox" value="undertime" name="undertime">Undertime</label>
	  			</div>
	  			<div class="col-md-6">
	  				<label><input type="checkbox" value="absent" name="absent">absent</label>
	  			</div>

	  		</div>
	  		<div class="row">
	  			<div class="col-md-2">
	  				<label><input type="checkbox" value="cola" name="payroll_cola">COLA</label>
	  			</div>
	  			<div class="col-md-2">
	  				<label><input type="checkbox" value="regular_holiday" name="regular_holiday"> Regular Holiday</label>
	  			</div>
	  			<div class="col-md-8">
	  				<label><input type="checkbox" value="late" name="late">late</label>
	  			</div>
	  		</div>
	  	</div>
	  	<div class="form-group">
	  		
	  	</div>
	</div>
	<div class="modal-footer">
		<button type="button" class="btn btn-def-white btn-custom-white" data-dismiss="modal">Close</button>
		<button class="btn btn-primary btn-custom-primary" type="Submit">Submit</button>
	</div>
</form>