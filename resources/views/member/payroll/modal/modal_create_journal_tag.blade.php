<form class="global-submit " role="form" action="/member/payroll/payroll_jouarnal/create_journal_tag" method="post">
	<div class="modal-header">
		<button type="button" class="close" data-dismiss="modal">×</button>
		<h4 class="modal-title">Create Journal Tags</h4>
		<input type="hidden" name="_token" value="{{csrf_token()}}">
	</div>
	<div class="modal-body form-horizontal">
		<div class="form-group">
			<div class="col-md-12">
				<small>Accounts Name</small>
				<select class="form-control select-account" name="account_id">
					<option value="">Select Account</option>
					@foreach($_expense as $expense)
					<option value="{{$expense->account_id}}">{{$expense->account_number.' . '.$expense->account_name}}</option>
					@endforeach
				</select>
			</div>
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
							  		<li>
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
<script type="text/javascript" src="/assets/member/js/payroll/modal_create_journal_tag.js"></script>