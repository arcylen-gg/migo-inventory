<div class="form-horizontal">
	<div class="form-group">
		<div class="col-md-12">
			<h4>Tax Period</h4>
		</div>
	</div>
	<div class="form-group">
		<div class="col-md-12">
			<ul class="list-group">
			@foreach($_period as $period)
			  <li class="list-group-item">
			  	<div class="checkbox">
			  		<label><input type="checkbox" name="" class="tax-period-check" value="{{$period->payroll_tax_period_id}}" {{$period->is_use == 1 ? 'checked' : ''}}>{{$period->payroll_tax_period}}</label>
			  	</div>
			  </li>
			@endforeach
			</ul>
		</div>
	</div>
</div>
<script type="text/javascript">
	$(".tax-period-check").unbind("change");
	$(".tax-period-check").bind("change", function()
	{
		var is_use = 1;
		if(!$(this).is(':checked'))
		{
			is_use = 0;
		}
		var form_data = {
			_token:$("#_token").val(),
			is_use:is_use,
			payroll_tax_period_id:$(this).val()
		};
		var action = "/member/payroll/tax_period/taxt_perid_change";
		var method = "POST";
		var target = "";
		var toaster = 'period has been updated.';
		console.log(toaster);
		payrollconfiguration.load_configuration(action, method, target, form_data, toaster);
	});
</script>