<div class="leave_whole">
<form class="global-submit" role="form" action="/member/payroll/leave/v2/update_leave_tempv2" method="POST">
	<div class="modal-header">
		<button type="button" class="close" data-dismiss="modal">&times;</button>
		<h4 class="modal-title">Leave Template Details</h4>
		<input type="hidden" name="_token" value="{{csrf_token()}}">
		<input type="hidden" name="payroll_leave_temp_id" id="payroll_leave_temp_id" value="{{$leave_temp->payroll_leave_temp_id}}">
	</div>
	<div class="modal-body form-horizontal">


		<div class="form-group">
			<div class="col-md-12">
				<small>Leave Name</small>
				<input type="text" name="payroll_leave_temp_name" class="form-control" value="{{$leave_temp->payroll_leave_temp_name}}" required>
			</div>
			
		</div>

		<div class="form-group">
			<div class="col-md-6">
				<small>Commulative?</small>
				<div class="form-control padding-b-37">
					<div class="col-md-6">
						<div class="radio">
							<label><input type="radio" name="payroll_leave_temp_is_cummulative" value="1" {{$leave_temp->payroll_leave_temp_is_cummulative == '1' ? 'checked' : ''}} >Yes</label>
						</div>
					</div>
					<div class="col-md-6">
						<div class="radio">
							<label><input type="radio" name="payroll_leave_temp_is_cummulative" value="0" {{$leave_temp->payroll_leave_temp_is_cummulative == '0' ? 'checked' : ''}}>No</label>
						</div>
					</div>					
				</div>		
			</div>
		</div>		
	</div>

	<div class="form-group">
			<div class="col-md-12">
				<span><b>Affected Employee<a href="#" class="btn btn-custom-primary pull-right popup" link="/member/payroll/leave/v2/modal_leave_tag_employeev2/{{$leave_temp->payroll_leave_temp_id}}'">Tag Employee</a></b></span>
			</div>
		</div>

	<ul class="nav nav-tabs">
	  <li class="active"><a data-toggle="tab" href="#active-leave_temps"><i class="fa fa-star"></i>&nbsp;Active</a></li>
	  <li><a data-toggle="tab" href="#archived-leave_temps"><i class="fa fa-trash-o"></i>&nbsp;Archived</a></li>
	</ul>
	<div class="tab-content padding-top-10">
		  <div id="active-leave_temps" class="tab-pane fade in active">
		  	
		  	<div class="load-data" target="value-id-1">
				<div id="value-id-1">
				
				  	<table class="table table-condensed table-bordered">
				  		<thead>
						 	<tr>
								<th>Employee Name</th>
								<th>Leave Hours</th>
								<th></th>
							</tr>
				  		</thead>
				  		<tbody>
							@foreach($_active as $active)
								<tr>
									<td>
										{{$active->payroll_employee_title_name.' '.$active->payroll_employee_first_name.' '.$active->payroll_employee_middle_name.' '.$active->payroll_employee_last_name.' '.$active->payroll_employee_suffix_name}}
									</td>
									<input type="hidden" name="employee_tag[]" value="{{$active->payroll_employee_id}}">
									<input type="hidden" name="payroll_leave_employee_id[]" value="{{$active->payroll_leave_employee_id}}">
									<td class="text-center edit-data zerotogray" width="25%">
										<input type="text" name="leave_hours_{{$active->payroll_employee_id}}" placeholder="00:00" class="text-center form-control break time-entry time-target time-entry-24 is-timeEntry" value="{{$active->payroll_leave_temp_hours}}">
									</td>

									<td class="text-center">
										<a href="#" class="popup" size="sm" link="/member/payroll/leave/v2/modal_leave_action/{{$active->payroll_leave_employee_id}}/archived_temp/0"><i class="fa fa-times"></i></a>
									</td>
								</tr>
							@endforeach
				  		</tbody>
				  	</table>
				  	
		  		</div> 
			</div>

		  </div> 

		  <div id="archived-leave_temps" class="tab-pane fade">
		  	
		  	<div class="load-data" target="value-id-2">
				<div id="value-id-2">
				
				  	<table class="table table-condensed table-bordered">
				  		<thead>
				  			<tr>
								<th>Employee Name</th>
								<th></th>
							</tr>
				  		</thead>
				  		<tbody>
				  			@foreach($_archived as $archived)
							<tr>
								<td>
									{{$archived->payroll_employee_title_name.' '.$archived->payroll_employee_first_name.' '.$archived->payroll_employee_middle_name.' '.$archived->payroll_employee_last_name.' '.$archived->payroll_employee_suffix_name}}
								</td>
								<td class="text-center">
										<a href="#" class="popup" size="sm" link="/member/payroll/leave/v2/modal_leave_action/{{$archived->payroll_leave_employee_id}}/restore_temp/0"><i class="fa fa-refresh"></i></a>
								</td>
							</tr>
							@endforeach
				  		</tbody>
				  	</table>

		  		</div> 
			</div>

		  </div> 

  </div>

	<div class="modal-footer">
		<button type="button" class="btn btn-custom-white" data-dismiss="modal">Close</button>
		<button class="btn btn-custom-primary btn-submit" type="submit">Update</button>
	</div>
</form>
</div>
<script type="text/javascript" src="/assets/member/js/payroll/modal_create_leave_tempv2.js"></script>