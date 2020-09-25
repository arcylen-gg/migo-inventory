<form class="global-submit" role="form" action="/member/payroll/company_list/update_company" method="POST">
	<div class="modal-header">
		<button type="button" class="close" data-dismiss="modal">&times;</button>
		<h4 class="modal-title">{{ucfirst($action)}} Company</h4>
		<input type="hidden" value="{{$company->payroll_company_id}}" name="payroll_company_id">
		<input type="hidden" name="_token" value="{{csrf_token()}}">
		<input type="hidden" name="" class="blade-action" value="{{$action}}">
	</div>
	<div class="modal-body form-horizontal">
		@if(isset($company->payroll_parent_company_id))
			@if($company->payroll_parent_company_id != 0)
				<div class="form-group">
					<div class="col-md-12">
						<small>Is Sub-Company of:</small>
						<select class="form-control sub-company-drop-down" required name="payroll_parent_company_id">
							@foreach($_company as $company_list)
								<option {{$company->payroll_parent_company_id == $company_list->payroll_company_id ? 'selected' : ''}} value="{{$company_list->payroll_company_id}}">{{$company_list->payroll_company_name}}</option>
							@endforeach
						</select>
					</div>
				</div>
			@endif
		@endif
		<div class="form-group">
			<div class="col-md-12">
				<small>Company Name</small>
				<input type="text" name="payroll_company_name" placeholder="Company Name" class="form-control view-form" required value="{{$company->payroll_company_name}}" {{$action == 'view' ? 'disabled':''}}>
			</div>
		</div>		
		<div class="form-group">
			<div class="col-md-6">
				<small>Company Code</small>
				<input type="text" name="payroll_company_code" placeholder="Company Code" class="form-control view-form" required value="{{$company->payroll_company_code}}" {{$action == 'view' ? 'disabled':''}}>
			</div>
			<div class="col-md-6">
				<small>Company RDO</small>
				<select class="form-control view-form" required name="payroll_company_rdo" {{$action == 'view' ? 'disabled':''}}>
					<option value="">Select</option>
					@foreach($_rdo as $rdo)
					<option value="{{$rdo->payroll_rdo_id}}" {{$company->payroll_company_rdo == $rdo->payroll_rdo_id ? 'selected="selected"':''}}>{{$rdo->rdo_code.' - '.$rdo->rdo_location}}</option>
					@endforeach
				</select>
			</div>
		</div>
		<div class="form-group">
			<div class="col-md-12">
				<small>Company Address</small>
				<input type="text" name="payroll_company_address" placeholder="Company Address" class="form-control view-form" required value="{{$company->payroll_company_address}}" {{$action == 'view' ? 'disabled':''}}>
			</div>
		</div>
		<div class="form-group">
			<div class="col-md-6">
				<small>Company Contact</small>
				<input type="text" name="payroll_company_contact" placeholder="Company Contact" class="form-control view-form" value="{{$company->payroll_company_contact}}" {{$action == 'view' ? 'disabled':''}}>
			</div>
			<div class="col-md-6">
				<small>Company Email</small>
				<input type="email" name="payroll_company_email" placeholder="Company Email" class="form-control view-form" value="{{$company->payroll_company_email}}" {{$action == 'view' ? 'disabled':''}}>
			</div>
		</div>
		<div class="form-group">
			<div class="col-md-6">
				<small>Nature of Business</small>
				<input type="text" name="payroll_company_nature_of_business" placeholder="Nature of Business" class="form-control view-form" value="{{$company->payroll_company_nature_of_business}}" {{$action == 'view' ? 'disabled':''}}>
			</div>
			<div class="col-md-6">
				<small>Company Date Started</small>
				<input type="text" name="payroll_company_date_started" placeholder="Company Date Started" class="form-control datepicker view-form" value="{{$company->payroll_company_date_started != '0000-00-00' ? date('m/d/Y',strtotime($company->payroll_company_date_started)):''}}" {{$action == 'view' ? 'disabled':''}}>
			</div>
		</div>
		<div class="form-group">
			<div class="col-md-6">
				<small>Bank</small>
				<select class="form-control view-form" name="payroll_company_bank" {{$action == 'view' ? 'disabled':''}}>
					<option value="">Select Bank</option>
					@foreach($_bank as $bank)
					<option value="{{$bank->payroll_bank_convertion_id}}" {{$company->payroll_company_bank == $bank->payroll_bank_convertion_id ? 'selected="selected"':''}} >{{$bank->bank_name}}</option>
					@endforeach
				</select>
			</div>
			<div class="col-md-6">
				<small>Bank Account Number</small>
				<input type="text" name="payroll_company_account_no" placeholder="Bank Account Number" class="form-control" value="{{$company->payroll_company_account_no}}" {{$action == 'view' ? 'disabled':''}}>
			</div>
		</div>
		<div class="form-group">
			<div class="col-md-6">
				<small>Company TIN</small>
				<input type="text" name="payroll_company_tin" placeholder="Company TIN" class="form-control view-form" value="{{$company->payroll_company_tin}}" {{$action == 'view' ? 'disabled':''}}>
			</div>
			<div class="col-md-6">
				<small>Company SSS</small>
				<input type="text" name="payroll_company_sss" placeholder="Company SSS" class="form-control view-form" value="{{$company->payroll_company_sss}}" {{$action == 'view' ? 'disabled':''}}>
			</div>
		</div>
		<div class="form-group">
			<div class="col-md-6">
				<small>Company Philhealth</small>
				<input type="text" name="payroll_company_philhealth" placeholder="Company Philhealth" class="form-control view-form" value="{{$company->payroll_company_philhealth}}" {{$action == 'view' ? 'disabled':''}}>
			</div>
			<div class="col-md-6">
				<small>Company PAGIBIG</small>
				<input type="text" name="payroll_company_pagibig" placeholder="Company PAGIBIG" class="form-control view-form" value="{{$company->payroll_company_pagibig}}" {{$action == 'view' ? 'disabled':''}}>
			</div>
		</div>
		<div class="form-group">
			<div class="col-md-12 text-center">
				<img src="{{$company->payroll_company_logo}}" class="company_logo img-200-200">
			</div>
		</div>
		<div class="form-group">
			<div class="col-md-12 text-center">
				<div class="width-200px margin-auto">
					<div class="custom-progress-container margin-auto display-none">
						<div class="custom-progress"></div>
					</div>
				</div>
			</div>
		</div>
		<div class="form-group">
			<div class="col-md-12 text-center">
				<label class="btn btn-success view-form" {{$action == 'view' ? 'disabled':''}}><input type="file" name="" class="hide" id="files-update" accept=".jpg, .JPG, .jpeg, .png, .gif, .bmp">Upload Logo</label>
			</div>
		</div>
	</div>
	<div class="modal-footer">
		<button type="button" class="btn btn-custom-white" data-dismiss="modal">Close</button>
		<button class="btn btn-custom-primary btn-submit {{$action == 'view' ? 'display-none':''}}" type="submit" >Update</button>
		<button class="btn btn-danger btn-edit {{$action == 'edit' ? 'display-none':''}}" type="button">Edit</button>
	</div>
</form>
<script type="text/javascript" src="/assets/member/js/payroll/modal_create_company.js"></script>