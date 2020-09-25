<form class="global-submit " role="form" action="/member/payroll/payroll_reports/save_custom_reports" method="post">
	<div class="modal-header">
		<button type="button" class="close" data-dismiss="modal">×</button>
		<h4 class="modal-title">Creat Report</h4>
		<input type="hidden" name="_token" value="{{csrf_token()}}">
	</div>
	<div class="modal-body form-horizontal">
		<div class="form-group">
			<div class="col-md-12">
				<small>Report Name</small>
				<input type="text" name="payroll_reports_name" class="form-control" placeholder="Report Name" required>
			</div>
		</div>
		<div class="checkbox">
			<label><input type="checkbox" name="is_by_company" value="1">Select by Company</label>
		</div>
		<div class="checkbox">
			<label><input type="checkbox" name="is_by_department" value="1">Select by Department</label>
		</div>
		<div class="checkbox">
			<label><input type="checkbox" name="is_by_employee" value="1">Select by Employee</label>
		</div>
		@foreach($_entity as $key => $entity_data)
		<div class="form-group">
			<div class="col-md-12">
				<div class="panel panel-primary">
					<div class="panel-heading">{{ucfirst($key)}}</div>
				<div class="panel-body padding-0">
						<ul class="no-list-style margin-nl-19">
						  @foreach($entity_data as $entity)
						  <li>
						  	<div class="checkbox">
						  		<label><input type="checkbox" name="entity[]" value="{{$entity['payroll_entity_id']}}}">{{$entity['entity_name']}}</label>
						  	</div>
						  	@if(!empty($entity['sub']))
						  	<li >
							  	<ul class="no-list-style">

							  		@foreach($entity['sub'] as $sub)
							  		<li >
							  			<div class="checkbox">
							  				<label><input type="checkbox" name="sub_entity[]" value="{{$sub['payroll_deduction_type_id']}}}">{{$sub['payroll_deduction_type_name']}}</label>
							  			</div>
							  		</li>
							  		@endforeach
							  	</ul>
						  	</li>
						  	@endif
						  </li>
						  @endforeach
						</ul>
					</div>
				</div>
			</div>
		</div>
		@endforeach
	</div>
	<div class="modal-footer">
		<button type="button" class="btn btn-def-white btn-custom-white" data-dismiss="modal">Close</button>
		<button class="btn btn-primary btn-custom-primary" type="submit"">Submit</button>
	</div>
</form>