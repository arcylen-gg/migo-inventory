<div class="form-horizontal">
	<div class="form-group">
		<div class="col-md-12">
			<!-- <button class="btn btn-custom-primary pull-right">In use</button> -->
			<!-- <label class="switch">
				<input type="checkbox" name="" >
				<div class="slider round"></div>
			</label> -->
			<div class="checkbox">
				<label><input type="checkbox" value="{{$payslip->payroll_payslip_id}}" class="checkbox-use-payslip" {{$payslip->payslip_is_use == 1 ? 'checked="checked"':''}}>Use as template</label>
			</div>
			<div class="dropdown pull-right" style="margin-top:-28px">
				<button class="btn btn-custom-white dropdown-toggle btn-xs" type="button" data-toggle="dropdown">Action
				<span class="caret"></span></button>
				<ul class="dropdown-menu dropdown-menu-custom">
					<li>
						<a href="#" class="popup" link="/member/payroll/custom_payslip/modal_edit_payslip/{{$payslip->payroll_payslip_id}}" size="lg"></i><i class="fa fa-pencil"></i>&nbsp;Edit</a>
					</li>
					<li>
						<a href="#" class="popup" link="/member/payroll/custom_payslip/modal_archive_payslip/1/{{$payslip->payroll_payslip_id}}" size="sm"><i class="fa fa-trash-o"></i>&nbsp;Archived</a>
					</li>
				</ul>
			</div>
		</div>
	</div>
	<div class="form-group">
		<div class="col-md-12">
			<div class="payslip-div paper-portrait">
				<div class="payslip-gray-dotted" style="height:auto;display: inline-block;width:{{$payslip->payslip_width}}%;">
					<div class="col-md-12 padding-5">
						@if($payslip->include_company_logo == 1)
						<div class="payslip-div padding-5 company-logo-container">
							@if($payslip->company_position == '.company-logo-left')
							<div class="company-logo company-logo-left">
								<img src="/assets/images/noimage.png" class="img20x20">&nbsp;<span class="margin-top-10px f-9 pos-absolute">Company Name</span>
							</div>
							@elseif($payslip->company_position == '.company-logo-right')
							<div class="company-logo company-logo-right text-right">
								<img src="/assets/images/noimage.png" class="img20x20">&nbsp;<span class="margin-top-10px f-9 pos-absolute" style="right: 49px">Company Name</span>
							</div>
							@elseif($payslip->company_position == '.company-logo-center')
							<div class="company-logo company-logo-center text-center">
								<img src="/assets/images/noimage.png" class="img20x20">&nbsp;<p class="f-9">Company Name</p>
							</div>
							@elseif($payslip->company_position == '.company-left')
							<div class="company-logo company-left">
								<p class="f-9">Company Name</p>
							</div>
							@elseif($payslip->company_position == '.company-right')
							<div class="company-logo company-center text-center">
								<p class="f-9">Company Name</p>
							</div>
							@elseif($payslip->company_position == '.company-center')
							<div class="company-logo company-right text-right">
								<p class="f-9">Company Name</p>
							</div>
							@endif
						</div>
						@endif
					</div>
					<div class="col-md-12 padding-5">
						<div class="payslip-div padding-5">
							<p class="f-9 margin-bottom-1">Employee Name here</p>
							<p class="f-9 margin-bottom-1 p-department {{$payslip->include_department == 1 ? '':'display-none'}}">Department</p>
							<p class="f-9 margin-bottom-1 p-job-title {{$payslip->include_job_title == 1 ? '':'display-none'}}">Job Title</p>
						</div>
					</div>
					<div class="{{$payslip->include_time_summary == 1 ? 'col-md-6':'col-md-12'}} padding-5 computaion-field" style="height:10em">
						<div class="payslip-div text-center">
							<p class="f-9">Payroll Computation here</p>
						</div>
					</div>
					<div class="col-md-6 padding-5 time-summary-field {{$payslip->include_time_summary == 1 ? '' : 'display-none'}}" style="height:10em">
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

<script type="text/javascript">
	$(".checkbox-use-payslip").unbind("change");
	$(".checkbox-use-payslip").bind("change", function()
	{
		var is_checked = 1;
		if(!$(this).is(':checked'))
		{
			is_checked = 0;
		}
		var id = $(this).val();

		$.ajax({
			url 	: 	'/member/payroll/custom_payslip/payslip_use_change',
			type 	: 	'POST',
			data 	: 	{
				_token:$("#_token").val(),
				is_checked:is_checked,
				id:id
			},
			success	: 	function(result)
			{
				toastr.success('Payslip template has changed usage.');
			},
			error 	: 	function(err)
			{
				toastr.error("Error, something went wrong.");
			}
		});
	});
</script>