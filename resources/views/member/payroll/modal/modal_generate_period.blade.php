<form class="global-submit form-horizontal form-period-list" role="form" action="/member/payroll/time_keeping/generate_period" method="post">
	<div class="modal-header">
		<button type="button" class="close" data-dismiss="modal">×</button>
		<input type="hidden" name="_token" value="{{csrf_token()}}">
		<h4 class="modal-title">Generate Period</h4>
	</div>
	<div class="period-list">
		<div class="modal-body ">
			<ul class="list-group">
			  @foreach($_period as $period)
			  <li class="list-group-item">
			  	<div class="checkbox">
			  		<label><input type="checkbox" name="payroll_period_id[]" value="{{$period->payroll_period_id}}">{{date('M d, Y',strtotime($period->payroll_period_start)).' - '.date('M d, Y',strtotime($period->payroll_period_end))}}</label>
			  		<span class="pull-right color-gray">{{$period->payroll_period_category}}</span>
			  	</div>
			  </li>
			  @endforeach
			</ul>
		</div>
	</div>
	<div class="modal-footer">
		<button type="button" class="btn btn-def-white btn-custom-white" data-dismiss="modal">Close</button>
		<button class="btn btn-danger popup" type="button" link="/member/payroll/payroll_period_list/modal_create_payroll_period"><i class="fa fa-plus"></i>&nbsp;New period</button>
		<button class="btn btn-primary btn-custom-primary" type="submit"">Submit</button>

	</div>
</form>