<form class="global-submit form-horizontal" role="form" action="/member/payroll/timesheet/choose_company_save" method="post">
	<div class="modal-header">
		<button type="button" class="close" data-dismiss="modal">×</button>
		<h4 class="modal-title">Choose Company</h4>
		<input type="hidden" value="{{csrf_token()}}" name="_token">
		<input type="hidden" name="payroll_time_sheet_record_id" value="{{$record->payroll_time_sheet_record_id}}">
	</div>
	<div class="modal-body clearfix">
		<div class="form-group">
			<div class="col-md-12">
				<small>Company Name</small>
				<select class="form-control payroll_company_id" name="payroll_company_id">
					<option value="">Select Company</option>
					@foreach($_company as $company)
						<option value="{{$company['company']->payroll_company_id}}" {{$record->payroll_company_id == $company['company']->payroll_company_id ? 'selected="selected"':''}}>{{$company['company']->payroll_company_name}}</option> 
						@foreach($company['branch'] as $branch)
						<option value="{{$branch->payroll_company_id}}" {{$record->payroll_company_id == $branch->payroll_company_id ? 'selected="selected"':''}}>&nbsp;&nbsp;• {{$branch->payroll_company_name}}</option>
						@endforeach
					@endforeach
				</select>
			</div>
		</div>
	</div>
	<div class="modal-footer">
		<button type="button" class="btn btn-def-white btn-custom-white" data-dismiss="modal">Close</button>
		<button class="btn btn-primary btn-custom-primary" type="submit"">Submit</button>
	</div>
</form>