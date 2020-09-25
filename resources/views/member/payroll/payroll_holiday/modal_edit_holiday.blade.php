<form class="global-submit" role="form" action="/member/payroll/holiday/modal_update_holiday/v2" method="POST">
	<div class="modal-header">
		<button type="button" class="close" data-dismiss="modal">&times;</button>
		<h4 class="modal-title">Create Holiday</h4>
		<input type="hidden" name="_token" value="{{csrf_token()}}">
		<input type="hidden" name="payroll_holiday_id" value="{{$holiday->payroll_holiday_id}}">
	</div>
	<div class="modal-body form-horizontal">
		<div class="form-group">
			<div class="col-md-12">
				<small>Holiday Name</small>
				<input type="text" name="payroll_holiday_name" class="form-control" value="{{$holiday->payroll_holiday_name}}">
			</div>
		</div>
		<div class="form-group">
			<div class="col-md-6">
				<small>Date</small>
				<input type="text" name="payroll_holiday_date" class="form-control datepicker" value="{{date('m/d/Y',strtotime($holiday->payroll_holiday_date))}}">
			</div>
			<div class="col-md-6">
				<small>Category</small>
				<select class="form-control" required name="payroll_holiday_category">
					<option value="">Select Category</option>
					<option value="Regular" {{$holiday->payroll_holiday_category == 'Regular' ? 'selected="selected"':''}}>Regular</option>
					<option value="Special" {{$holiday->payroll_holiday_category == 'Special' ? 'selected="selected"':''}}>Special</option>
				</select>
			</div>
		</div>
		<div class="form-group">
			<div class="col-md-12">
				<small>Affected Company</small>
				<ul class="list-group">
				  <li class="list-group-item padding-3-10">
				  	<div class="checkbox">
				  		<label><input type="checkbox" name="" class="check-all">Check All</label>
				  	</div>
				  </li>
				  @foreach($_company as $company)
				  <li class="list-group-item padding-3-10">
				  	<div class="checkbox">
				  		<label><input type="checkbox" name="company[]" class="company-check check-{{$company['payroll_company_id']}}" value="{{$company['payroll_company_id']}}" {{$company['status']}}>
				  		</label>
				  		<label>
				  			<a style="color: #000" class="popup" size="md" link="/member/payroll/holiday/modal_tag_employee/{{$company['payroll_company_id']}}?id={{$holiday_id or 0}}" >{{$company['payroll_company_name']}} 
			  				</a>
			  			</label>
				  	</div>
				  </li>
				  @endforeach
				</ul>
			</div>
		</div>
	</div>
	<div class="modal-footer">
		<button type="button" class="btn btn-custom-white" data-dismiss="modal">Close</button>
		<button class="btn btn-custom-primary btn-submit" type="submit">Update</button>		
	</div>
</form>
<script type="text/javascript">
	$(".datepicker").datepicker();
	$(".check-all").unbind("change");
	$(".check-all").bind("change", function()
	{
		if($(this).is(':checked'))
		{
			$(".company-check").prop("checked", true);
		}	
		else
		{
			$(".company-check").prop("checked", false);
		}
	});
</script>