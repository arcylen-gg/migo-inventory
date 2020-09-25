<form class="global-submit" role="form" action="/member/payroll/payroll_period_list/modal_update_period" method="post">
	<div class="modal-header">
		<button type="button" class="close" data-dismiss="modal">×</button>
		<h4 class="modal-title">Create Payroll Period</h4>
		<input type="hidden" name="_token" value="{{csrf_token()}}">
		<input type="hidden" name="payroll_period_id" value="{{$period->payroll_period_id}}">
	</div>
	<div class="modal-body clearfix form-horizontal">
		<div class="form-group">
			<div class="col-md-6">
				<small>Payroll Tax Period</small>
				<select class="form-control" name="payroll_period_category" required>
					@foreach($_tax as $tax)
					<option value="{{$tax->payroll_tax_period}}" {{$period->payroll_period_category == $tax->payroll_tax_period ? 'selected="selected"' :''}}>{{$tax->payroll_tax_period}}</option>
					@endforeach
				</select>
			</div>
			<div class="col-md-6">
				<small>Period Count</small>
				<select class="form-control" required name="period_count">
					<option value="first_period" {{$period->period_count == 'first_period' ? 'selected="selected"' :''}}>First Period</option>
					<option value="middle_period" {{$period->period_count == 'middle_period' ? 'selected="selected"' :''}}>Middle Period</option>
					<option value="last_period" {{$period->period_count == 'last_period' ? 'selected="selected"' :''}}>Last Period</option>
				</select>
			</div>
		</div>
		<div class="form-group">
			<div class="col-md-6">
				<small>Contribution Month</small>
				<select class="form-control" required name="month_contribution">
					@foreach($_month as $month)
					<option value="{{$month}}" {{$period->month_contribution == $month ? 'selected="selected"' : ''}}>{{$month}}</option>
					@endforeach
				</select>
			</div>
			<div class="col-md-6">
				<small>Contribution Year</small>
				<input type="text" class="form-control text-center" required value="{{$period->year_contribution != '' ? $period->year_contribution : date('Y')}}" name="year_contribution">
			</div>
		</div>
		<div class="form-group">
			<div class="col-md-6">
				<small>Period Start</small>
				<input type="text" name="payroll_period_start" class="datepicker form-control" required value="{{date('m/d/Y', strtotime($period->payroll_period_start))}}">
			</div>
			<div class="col-md-6">
				<small>Period End</small>
				<input type="text" name="payroll_period_end" class="datepicker form-control" required value="{{date('m/d/Y', strtotime($period->payroll_period_end))}}">
			</div>
		</div>
		<div class="form-group">
			<div class="col-md-6">
				<small>Release Date</small>
				<input type="text" name="payroll_release_date" class="datepicker form-control" required value="{{date('m/d/Y', strtotime($period->payroll_release_date))}}">
			</div>
		</div>
	</div>
	<div class="modal-footer">
		<button type="button" class="btn btn-def-white btn-custom-white" data-dismiss="modal">Close</button>
		<button class="btn btn-primary btn-custom-primary" type="submit">Update</button>
	</div>
</form>
<script type="text/javascript">
	$(".datepicker").datepicker();
</script>