@foreach($approver_by_level as $key => $_approver)
	<div class="form-group">
	  <label for="level-{{$key}}">Select Level {{$key}} Approver:</label><br>
	  <select id="level-{{$key}}" class="multiple-select-2 form-control" name="approver_level[{{$key}}][]" multiple="multiple" required>
	    @foreach($_approver as $key2 => $approver)
	    	<option value="{{$approver->payroll_approver_employee_id}}"> {{$approver->payroll_employee_first_name}} {{$approver->payroll_employee_last_name}}</option>
	    @endforeach
	  </select>
	</div>
@endforeach

<script type="text/javascript">
	$('.multiple-select-2').select2(
				{placeholder: 'Select an option', closeOnSelect : false,  allowClear: true});

	@if($_approver_group_by_level != null)
		@foreach($_approver_group_by_level as $key => $approver_group)
			var employee = [];
			@foreach($approver_group as $key2 => $approver_employee_id)
				employee.push({{$approver_employee_id}});
			@endforeach
			$('#level-{{$key}}').val(employee).trigger('change');
		@endforeach
	@endif
	
</script>




