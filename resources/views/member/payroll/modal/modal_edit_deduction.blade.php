<form class="global-submit" role="form" action="/member/payroll/deduction/modal_update_deduction" method="POST">
	<div class="modal-header">
		<button type="button" class="close" data-dismiss="modal">&times;</button>
		<h4 class="modal-title">Create Deduction</h4>
		<input type="hidden" name="_token" value="{{csrf_token()}}">
		<input type="hidden" name="payroll_deduction_id" id="payroll_deduction_id" value="{{$deduction->payroll_deduction_id}}">
	</div>
	<div class="modal-body form-horizontal">
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
			<div class="col-md-6">
				<small>Date End</small>
				<input type="text" name="payroll_deduction_date_end" class="form-control datepicker" required value="{{$deduction->payroll_deduction_date_end != '0000-00-00' ? date('m/d/Y', strtotime($deduction->payroll_deduction_date_end)) : ''}}">
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
									<label><input type="radio" name="payroll_deduction_period" value="Second Period" {{$deduction->payroll_deduction_period == 'Second Period' ? 'checked':''}}>Second Period</label>
								</div>
							</div>
							<div class="col-md-6">
								<div class="radio">
									<label><input type="radio" name="payroll_deduction_period" value="Last Period" {{$deduction->payroll_deduction_period == 'Last Period' ? 'checked':''}}>Last Period</label>
								</div>
							</div>
							<div class="col-md-6">
								<div class="radio">
									<label><input type="radio" name="payroll_deduction_period" checked value="Every Period" {{$deduction->payroll_deduction_period == 'Every Period' ? 'checked':''}}>Every Period</label>
								</div>
							</div>
						</div>
					</div>
				</div>
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
			{{-- <div class="col-md-6">
				<small>Type</small>
				<div class="input-group">
					<select class="form-control select-deduction-type" name="payroll_deduction_type" required>
						<option value="">Select Type</option>
						@foreach($_type as $type)
						<option value="{{$type->payroll_deduction_type_id}}" {{$deduction->payroll_deduction_type == $type->payroll_deduction_type_id ? 'selected="selected"':''}}>{{$type->payroll_deduction_type_name}}</option>
						@endforeach
					</select>
					<span class="input-group-btn">
						<a href="#" type="button" class="btn btn-custom-primary btn-add-type" link=""><i class="fa fa-plus"></i></a>
					</span>
				</div>
				
			</div> --}}
			{{-- <div class="col-md-6">
				<small>Type</small>
				<div class="input-group">
					<select class="form-control select-deduction-type" name="payroll_deduction_type_id" required>
						<option value="">Select Type</option>
						<option value="Taxable" {{ ($deduction->payroll_deduction_type_2 == 'Taxable') ? 'selected' : '' }} >Taxable</option>
						<option value="Non-Taxable" {{ ($deduction->payroll_deduction_type_2 == 'Non-Taxable') ? 'selected' : '' }}>Non-Taxable</option>
						<option value="Hidden" {{ ($deduction->payroll_deduction_type_2 == 'Hidden') ? 'selected' : '' }}>Hidden</option>
					</select>
				</div>
				
			</div>  --}}
		</div>
		<div class="form-group">
			<div class="col-md-12">
				<small>Remarks</small>
				<textarea class="form-control textarea-expand" name="payroll_deduction_remarks">{{$deduction->payroll_deduction_remarks}}</textarea>
			</div>
		</div>

		<div class="col-md-6">
            <label>Expense Account *</label>
            <select name="expense_account_id" class="drop-down-coa form-control expense_account_id" id="expense_account_id" required>             
        		@include("member.load_ajax_data.load_chart_account", ['add_search' => "", '_account' => $_expense, 'account_id' => $deduction->expense_account_id ])
            </select>
        </div>

		<hr>
		<div class="form-group">
			<div class="col-md-12">
				<span><b>Affected Employee</b><button class="btn btn-custom-primary pull-right popup" type="button" link="/member/payroll/deduction/modal_deduction_tag_employee/{{$deduction->payroll_deduction_id}}">Tag Employee</button></span>
			</div>
		</div>
		<div class="affected-employee">
			<ul class="nav nav-tabs">
				<li class="active"><a data-toggle="tab" href="#active-employee">Active</a></li>
				<li><a data-toggle="tab" href="#zero-employee">Zero Balance</a></li>
				<li><a data-toggle="tab" href="#canceled-employee">Canceled</a></li>
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
								<td class="text-center">
									<a href="#" class="popup" link="/member/payroll/deduction/deduction_employee_tag/1/{{$active['deduction']->payroll_deduction_employee_id}}" size="sm" ><i class="fa fa-times"></i></a>
								</td>
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
				<div id="canceled-employee" class="tab-pane fade">
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
									<a href="#" class="popup" link="/member/payroll/deduction/deduction_employee_tag/0/{{$cancel['deduction']->payroll_deduction_employee_id}}" size="sm"><i class="fa fa-refresh"></i></a>
								</td>
							</tr>
							@endforeach
						</tbody>
					</table>
				</div>
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