<form class="global-submit " role="form" action="/member/payroll/custom_payslip/modal_update_payslip" method="post">
	<div class="modal-header">
		<button type="button" class="close" data-dismiss="modal">×</button>
		<h4 class="modal-title">Custom Payslip</h4>
		<input type="hidden" name="_token" value="{{csrf_token()}}">
		<input type="hidden" name="payroll_payslip_id" value="{{$payslip->payroll_payslip_id}}">
	</div>
	<div class="modal-body form-horizontal">
		<div class="form-group">
			<div class="col-md-4">
				<div class="panel panel-default background-white">
					<div class="panel-body ">
						<div class="form-group">
							<div class="col-md-12">
								<small>Payslip Code</small>
								<input type="text" name="payslip_code" class="form-control" required value="{{$payslip->payslip_code}}">
							</div>
						</div>
						<div class="form-group">
							<div class="col-md-12">
								<small>Paper Size</small>
								<select class="form-control drop-down-paper-size" name="payroll_paper_sizes_id">
									@foreach($_paper as $paper)
									<option value="{{$paper->payroll_paper_sizes_id}}" {{$payslip->payroll_paper_sizes_id == $paper->payroll_paper_sizes_id ? 'selected="selected"' :''}}>{{$paper->paper_size_name.' ( '.$paper->paper_size_width.' cm x '. $paper->paper_size_width. ' cm )'}}</option>
									@endforeach
								</select>
							</div>
						</div>
						
						<div class="form-group">
							<div class="col-md-12">
								<small>Payslip width (%)</small>
								{{-- <input type="number" name="payslip_width" class="form-control text-right payslip-width" placeholder="0" min="1" max="100" value="{{$payslip->payslip_width}}" step="any" required> --}}

								<select class="form-control text-right payslip-width" name="payslip_width">
									<option value="100" {{ ($payslip->payslip_width=='100' ? 'selected' : '' ) }} >100%</option>
									<option value="50"  {{ ($payslip->payslip_width=='50'  ? 'selected' : '' ) }}>50%</option>
									<option value="25"  {{ ($payslip->payslip_width=='25'  ? 'selected' : '' ) }}>25%</option>
								</select>


							</div>
						</div>
						<!-- <div class="form-group">
							<div class="col-md-12">
								<small>Copy per page</small>
								<input type="hidden" name="payslip_copy" value="1" min="1" class="form-control text-right copy-per-page" placeholder="0" required>
							</div>
						</div> -->
						<input type="hidden" name="payslip_copy" value="1" min="1" class="form-control text-right copy-per-page" placeholder="0" required>
						<div class="form-group">
							<div class="col-md-12">
								
								<div class="checkbox">
									<label><input type="checkbox" name="include_company_logo" class="company-name-logo" value="1" {{$payslip->include_company_logo == 1 ? 'checked="checked"' : ''}}>Include Company Name and Logo</label>
								</div>
								<div class="checkbox">
									<label><input type="checkbox" name="include_department" class="include-header" data-target=".p-department" value="1" {{$payslip->include_department == 1 ? 'checked="checked"':''}}>Include Department</label>
								</div>
								<div class="checkbox">
									<label><input type="checkbox" name="include_job_title" class="include-header" data-target=".p-job-title" value="1" {{$payslip->include_job_title == 1 ? 'checked="checked"':''}}>Include Job Title</label>
								</div>
								<div class="checkbox">
									<label><input type="checkbox" name="include_time_summary" class="time-summary" value="1" {{$payslip->include_time_summary == 1 ? 'checked="checked"' : ''}}>Include Time Summary</label>
								</div>
							</div>
						</div>
						<div class="form-group include-company-logo">
							
							<div class="col-md-12">
								<small>Company Name and Logo Template</small>
								<div class="list-group">
									<input type="hidden" id="company-position" name="company_position" value="{{$payslip->company_position}}" name="company_position">
								  <a href="#" class="list-group-item company-position {{$payslip->company_position == '.company-logo-left' ? 'active':''}}" data-target=".company-logo-left" >
								  	<img src="/assets/images/noimage.png" class="img30x30">&nbsp;<span class="margin-top-10px  pos-absolute">Company Name</span>
								  </a>
								  <a href="#" class="list-group-item company-position text-right {{$payslip->company_position == '.company-logo-right' ? 'active':''}}" data-target=".company-logo-right">
								  	<span class="margin-top-10px  pos-absolute" style="right: 49px">Company Name</span>&nbsp;<img src="/assets/images/noimage.png" class="img30x30">
								  </a>
								  <a href="#" class="list-group-item company-position text-center {{$payslip->company_position == '.company-logo-center' ? 'active':''}}" data-target=".company-logo-center" >
								  	<img src="/assets/images/noimage.png" class="img30x30"><br>
								  	Company Name
								  </a>
								  <a href="#" class="list-group-item company-position {{$payslip->company_position == '.company-left' ? 'active':''}}" data-target=".company-left">
								  	Company Name
								  </a>
								  <a href="#" class="list-group-item text-right company-position {{$payslip->company_position == '.company-right' ? 'active':''}}" data-target=".company-right">
								  	Company Name
								  </a>
								  <a href="#" class="list-group-item text-center company-position {{$payslip->company_position == '.company-center' ? 'active':''}}" data-target=".company-center">
								  	Company Name
								  </a>
								</div>
							</div>
						</div>
						
					</div>
				</div>
			</div>
			<div class="col-md-8">
				<div class="panel panel-default background-white">
					<div class="panel-heading">Payslip Preview</div>
					<div class="panel-body">
						<div class="payslip-div paper-portrait">
							<div class="payslip-gray-dotted width-25-n payslip-container" style="height:auto;display: inline-block;">
								<div class="col-md-12 padding-5">
									<div class="payslip-div padding-5 company-logo-container">
										<div class="company-logo company-logo-left">
											<img src="/assets/images/noimage.png" class="img20x20">&nbsp;<span class="margin-top-10px f-9 pos-absolute">Company Name</span>
										</div>
										<div class="company-logo company-logo-right text-right display-none">
											<img src="/assets/images/noimage.png" class="img20x20">&nbsp;<span class="margin-top-10px f-9 pos-absolute" style="right: 49px">Company Name</span>
										</div>
										<div class="company-logo company-logo-center text-center display-none">
											<img src="/assets/images/noimage.png" class="img20x20">&nbsp;<p class="f-9">Company Name</p>
										</div>
										<div class="company-logo company-left display-none">
											<p class="f-9">Company Name</p>
										</div>
										<div class="company-logo company-center text-center display-none">
											<p class="f-9">Company Name</p>
										</div>
										<div class="company-logo company-right text-right display-none">
											<p class="f-9">Company Name</p>
										</div>
									</div>
								</div>
								<div class="col-md-12 padding-5">
									<div class="payslip-div padding-5">
										<p class="f-9 margin-bottom-1">Employee Name here</p>
										<p class="f-9 margin-bottom-1 p-department">Department</p>
										<p class="f-9 margin-bottom-1 p-job-title">Job Title</p>
									</div>
								</div>
								<div class="col-md-6 padding-5 computaion-field" style="height:10em">
									<div class="payslip-div text-center">
										<p class="f-9">Payroll Computation here</p>
									</div>
								</div>
								<div class="col-md-6 padding-5 time-summary-field" style="height:10em">
									<div class="payslip-div text-center">
										<p class="f-9">Time summary here</p>
									</div>
								</div>
								<div class="col-md-6 padding-5 text-left">
									<br><div class="payslip-div"></div>
									<span class="f-9">Date Received</span>
								</div>
								<div class="col-md-6 padding-5 text-right">
									<br><div class="payslip-div"></div>
									<span class="f-9">Employee Signature</span>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
	<div class="modal-footer">
		<button type="button" class="btn btn-def-white btn-custom-white" data-dismiss="modal">Close</button>
		<button class="btn btn-primary btn-custom-primary" type="submit"">Update</button>
	</div>
</form>
<script type="text/javascript" src="/assets/member/js/payroll/modal_create_payslip.js"></script>