<form class="global-submit" role="form" action="/member/payroll/payroll_process/process_payroll" method="post">
	<div class="modal-header">
		<button type="button" class="close" data-dismiss="modal">×</button>
		<h4 class="modal-title">Create Payroll Process</h4>
		<input type="hidden" name="_token" value="{{csrf_token()}}">
	</div>
	<div class="modal-body clearfix form-horizontal">
		<div class="form-group">
			<div class="col-md-6">
				<small>Tax Period</small>
				<select class="form-control tax-period-change">
					<option value="">Select Period</option>
					@foreach($_period as $period)
					<option value="{{$period->payroll_tax_period}}">{{$period->payroll_tax_period}}</option>
					@endforeach
				</select>
			</div>
			<div class="col-md-6">
				<small>Payroll Period</small>
				<select class="form-control payroll-period">
					<option value="">Select Period</option>
				</select>
			</div>
		</div>
		<div class="form-group">
			<div class="col-md-12 company-list">
				
			</div>
		</div>
	</div>
	<div class="modal-footer">
		<button type="button" class="btn btn-def-white btn-custom-white" data-dismiss="modal">Close</button>
		<button class="btn btn-primary btn-custom-primary" type="submit"">Process</button>
	</div>
</form>
<script type="text/javascript" src="/assets/member/js/payroll/modal_create_payroll_process.js"></script>