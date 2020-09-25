<form class="global-submit" role="form" action="/member/payroll/deduction/v2/modal_update_deduction" method="POST">
	<div class="modal-header">
		<button type="button" class="close" data-dismiss="modal">&times;</button>
		<h4 class="modal-title">Edit Deduction</h4>
		<input type="hidden" name="_token" value="{{csrf_token()}}">
		<input type="hidden" name="payroll_deduction_id" id="payroll_deduction_id" value="{{$deduction->payroll_deduction_id}}">
	</div>



	<div class="modal-body form-horizontal">


		<div class="form-group">
			<div class="col-md-12">
				<small>Deduction Type</small>
				<select class="form-control deduction-category-change" name="payroll_deduction_type" required>
					<option value="">Select Category</option>
					<option value="SSS Loan" {{ ($deduction->payroll_deduction_type == 'SSS Loan') ? 'selected' : '' }} >SSS Loan</option>
					<option value="HDMF Loan" {{ ($deduction->payroll_deduction_type == 'HDMF Loan') ? 'selected' : '' }} >HDMF Loan</option>
					<option value="Cash Bond" {{ ($deduction->payroll_deduction_type == 'Cash Bond') ? 'selected' : '' }} >Cash Bond</option>
					<option value="Cash Advance" {{ ($deduction->payroll_deduction_type == 'Cash Advance') ? 'selected' : '' }} >Cash Advance</option>
					<option value="Others" {{ ($deduction->payroll_deduction_type == 'Others') ? 'selected' : '' }} >Others...</option>
				</select>
			</div>
		</div>

		<div class="form-group">
			<div class="col-md-12">
				<small>Deduction Name</small>
				<input type="text" name="payroll_deduction_name" class="form-control" value="{{$deduction->payroll_deduction_name}}" required>
			</div>
		</div>
		<div class="form-group">
			<div class="col-md-12">
				<small>Deduction Amount</small>
				<input type="number" name="payroll_deduction_amount" class="form-control text-right" step="any" required value="{{$deduction->payroll_deduction_amount}}">
			</div>
		</div>
		<div class="form-group">
			<div class="col-md-6">
				<small>Monthly Amortization</small>
				<input type="number" name="payroll_monthly_amortization" class="form-control text-right" step="any" required value="{{$deduction->payroll_monthly_amortization}}">
			</div>
			<div class="col-md-6">
				<small>Periodical Deduction</small>
				<input type="number" name="payroll_periodal_deduction" class="form-control text-right" step="any" required value="{{$deduction->payroll_periodal_deduction}}">
			</div>
		</div>
		<div class="form-group">
			<div class="col-md-6">
				<small>Date filed</small>
				<input type="text" name="payroll_deduction_date_filed" class="form-control datepicker" required value="{{date('m/d/Y',strtotime($deduction->payroll_deduction_date_filed))}}">
			</div>
			<div class="col-md-6">
				<small>Date start</small>
				<input type="text" name="payroll_deduction_date_start" class="form-control datepicker" required value="{{date('m/d/Y',strtotime($deduction->payroll_deduction_date_start))}}">
			</div>
		</div>
		<div class="form-group">
			<div class="col-md-12">
				<small>Deduct Every</small>
				<div class="panel panel-default">
					<div class="panel-body">
						<div class="row">

							<div class="col-md-6">
								<div class="radio">
									<label><input type="radio" name="payroll_deduction_period" value="First Period" {{$deduction->payroll_deduction_period == 'First Period' ? 'checked':''}}>First Period</label>
								</div>
							</div>
							<div class="col-md-6">
								<div class="radio">
									<label><input type="radio" name="payroll_deduction_period" value="Middle Period" {{$deduction->payroll_deduction_period == 'Second Period' ? 'checked':''}}>Middle Period</label>
								</div>
							</div>
							<div class="col-md-6">
								<div class="radio">
									<label><input type="radio" name="payroll_deduction_period" value="Last Period" {{$deduction->payroll_deduction_period == 'Last Period' ? 'checked':''}}>Last Period</label>
								</div>
							</div>
							<div class="col-md-6">
								<div class="radio">
									<label><input type="radio" name="payroll_deduction_period" value="Every Period" {{$deduction->payroll_deduction_period == 'Every Period' ? 'checked':''}}>Every Period</label>
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
				<input type="number" name="payroll_deduction_terms" class="form-control" required value="{{$deduction->payroll_deduction_terms}}">
			</div>
			<div class="col-md-6">
				<small># of payments</small>
				<input type="number" name="payroll_deduction_number_of_payments" class="form-control" required value="{{$deduction->payroll_deduction_number_of_payments}}" >
			</div>
		</div>

	
		<div class="form-group">
			<div class="col-md-6">
				<small>Category</small>
				<select class="form-control deduction-category-change" name="payroll_deduction_category" required>
					<option value="">Select Category</option>
					<option value="Taxable" {{ ($deduction->payroll_deduction_category == 'Taxable') ? 'selected' : '' }} >Taxable</option>
					<option value="Non-Taxable" {{ ($deduction->payroll_deduction_category == 'Non-Taxable') ? 'selected' : '' }}>Non-Taxable</option>
					<option value="Hidden" {{ ($deduction->payroll_deduction_category == 'Hidden') ? 'selected' : '' }}>Hidden</option>
				</select>
			</div>
	
		</div>

		<hr>
		<div class="form-group">
			<div class="col-md-12">
				<span><b>Affected Employee</b><button class="btn btn-custom-primary pull-right popup" type="button" link="/member/payroll/deduction/v2/modal_deduction_tag_employee/{{$deduction->payroll_deduction_id}}">Tag Employee</button></span>
			</div>
		</div>
		<div class="affected-employee">
			<ul class="nav nav-tabs">
				<li class="active"><a data-toggle="tab" href="#active-employee">Active</a></li>
				<li><a data-toggle="tab" href="#zero-employee">Zero Balance</a></li>
				<!-- <li><a data-toggle="tab" href="#canceled-employee">Canceled</a></li> -->
			</ul>
			<div class="tab-content padding-top-10">
				<div id="active-employee" class="tab-pane fade in active">
					<table class="table table-condensed table-bordered">
						<thead>
							<tr>
								<th>Employee Name</th>
								<th>Balance</th>
								<th width="5%"></th>
							</tr>
						</thead>
						<tbody>
							@foreach($emp['active'] as $active)
							<tr>
								<td>
									{{$active['deduction']->payroll_employee_title_name.' '.$active['deduction']->payroll_employee_first_name.' '.$active['deduction']->payroll_employee_middle_name.' '.$active['deduction']->payroll_employee_last_name.' '.$active['deduction']->payroll_employee_suffix_name}}
								</td>
								<td class="text-right">
									{{number_format($active['balance'], 2)}}
								</td>
								<!-- <td class="text-center">
									<a href="#" class="popup" link="/member/payroll/deduction/v2/deduction_employee_tag/1/{{$active['deduction']->payroll_deduction_employee_id}}" size="sm" ><i class="fa fa-times"></i></a>
								</td> -->
							</tr>
							@endforeach
						</tbody>
					</table>
				</div>
				<div id="zero-employee" class="tab-pane fade">
					<table class="table table-condensed table-bordered">
						<thead>
							<tr>
								<th>Employee Name</th>
								<th>Balance</th>
								<th width="5%"></th>
							</tr>
						</thead>
						<tbody>
							@foreach($emp['zero'] as $zero)
							<tr>
								<td>
									{{$zero['deduction']->payroll_employee_title_name.' '.$zero['deduction']->payroll_employee_first_name.' '.$zero['deduction']->payroll_employee_middle_name.' '.$zero['deduction']->payroll_employee_last_name.' '.$zero['deduction']->payroll_employee_suffix_name}}
								</td>
								<td class="text-right">
									{{number_format($zero['balance'], 2)}}
								</td>
								<td class="text-center">
									<!-- <a href="#" payroll_deduction_employee_id><i class="fa fa-times"></i></a> -->
								</td>
							</tr>
							@endforeach
						</tbody>
					</table>
				</div>
				<!-- <div id="canceled-employee" class="tab-pane fade">
					<table class="table table-condensed table-bordered">
						<thead>
							<tr>
								<th>Employee Name</th>
								<th>Balance</th>
								<th width="5%"></th>
							</tr>
						</thead>
						<tbody>
							@foreach($emp['cancel'] as $cancel)
							<tr>
								<td>
									{{$cancel['deduction']->payroll_employee_title_name.' '.$cancel['deduction']->payroll_employee_first_name.' '.$cancel['deduction']->payroll_employee_middle_name.' '.$cancel['deduction']->payroll_employee_last_name.' '.$cancel['deduction']->payroll_employee_suffix_name}}
								</td>
								<td class="text-right">
									{{number_format($cancel['balance'], 2)}}
								</td>
								<td class="text-center">
									<a href="#" class="popup" link="/member/payroll/deduction/v2/deduction_employee_tag/0/{{$cancel['deduction']->payroll_deduction_employee_id}}" size="sm"><i class="fa fa-refresh"></i></a>
								</td>
							</tr>
							@endforeach
						</tbody>
					</table>
				</div> -->
			</div>
		</div>
		
		
	</div>
	<div class="modal-footer">
		<button type="button" class="btn btn-custom-white" data-dismiss="modal">Close</button>
		<button class="btn btn-custom-primary btn-submit" type="submit">Update</button>
	</div>
</form>
<script type="text/javascript" src="/assets/member/js/payroll/modal_create_deduction.js"></script>
<script type="text/javascript" src="/assets/member/js/textExpand.js"></script>