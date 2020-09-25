<form class="" role="form" action="/member/payroll/generate_bank" method="post">
	<div class="modal-header">
		<button type="button" class="close" data-dismiss="modal">×</button>
		<h4 class="modal-title">Generate Bank</h4>
		<input type="hidden" name="company_period_id" value="{{$id}}">
		<input type="hidden" name="_token" value="{{csrf_token()}}">
	</div>
	<div class="modal-body form-horizontal">
		<div class="form-group">
			<div class="col-md-6">
				<small>Company Code</small>
				<input type="text" name="company_code" class="form-control" required value="{{$company->payroll_company_code}}">
			</div>
			<div class="col-md-6">
				<small>Bank</small>
				<select class="form-control" required name="bank_name">
					<option value="">Select Bank</option>
					@foreach($_bank as $bank)
					<option value="{{$bank->payroll_bank_convertion_id}}" {{$company->payroll_company_bank == $bank->payroll_bank_convertion_id ? 'selected="selected"':''}}>{{$bank->bank_name}}</option>
					@endforeach
				</select>
			</div>
		</div>
		<div class="form-group">
			<div class="col-md-6">
				<small>Upload Date</small>
				<input type="text" name="upload_date" value="{{date('m/d/Y')}}" class="form-control datepicker" required>
			</div>
			<div class="col-md-6">
				<small>Batch No.</small>
				<input type="number" name="batch_no" class="form-control" required>
			</div>
		</div>
	</div>
	<div class="modal-footer">
		<button type="button" class="btn btn-def-white btn-custom-white" data-dismiss="modal">Close</button>
		<button class="btn btn-primary btn-custom-primary" type="submit"">Submit</button>
	</div>
</form>

<script type="text/javascript">
	$(".datepicker").datepicker();
</script>