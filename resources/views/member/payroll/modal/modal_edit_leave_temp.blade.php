<form class="global-submit" role="form" action="/member/payroll/leave/update_leave_temp" method="POST">
	<div class="modal-header">
		<button type="button" class="close" data-dismiss="modal">&times;</button>
		<h4 class="modal-title">Leave Template Details</h4>
		<input type="hidden" name="_token" value="{{csrf_token()}}">
		<input type="hidden" name="payroll_leave_temp_id" id="payroll_leave_temp_id" value="{{$leave_temp->payroll_leave_temp_id}}">
	</div>
	<div class="modal-body form-horizontal">


		<div class="form-group">
			<div class="col-md-12">
				<small>Leave Template Name</small>
				<input type="text" name="payroll_leave_temp_name" class="form-control" value="{{$leave_temp->payroll_leave_temp_name}}" required>
			</div>
			
		</div>
		<div class="form-group">
			<div class="col-md-6">
				<small>No. of Hours</small>
				<input type="number" name="payroll_leave_temp_days_cap" class="form-control text-right"
				value="{{$leave_temp->payroll_leave_temp_days_cap}}" step="0.01" required>
			</div>
		</div>

		<div class="form-group">
			<div class="col-md-6" hidden>
				<small>With Pay?</small>
				<div class="form-control padding-b-37">
					<div class="col-md-6">
						<div class="radio">
							<label><input type="radio" class="payroll_leave_temp_with_pay" name="payroll_leave_temp_with_pay" value="1" {{$leave_temp->payroll_leave_temp_with_pay == '1' ? 'checked' : ''}} >Yes</label>
						</div>
					</div>
					<div class="col-md-6">
						<div class="radio">
							<label><input type="radio" class="payroll_leave_temp_with_pay" name="payroll_leave_temp_with_pay" value="0" {{$leave_temp->payroll_leave_temp_with_pay == '0' ? 'checked' : ''}} >No</label>
						</div>
					</div>					
				</div>		
			</div>
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
				<span><b>Affected Employee<a href="#" class="btn btn-custom-primary pull-right popup" link="/member/payroll/leave/modal_leave_tag_employee/{{$leave_temp->payroll_leave_temp_id}}'">Tag Employee</a></b></span>
			</div>
		</div>
		<div class="leave-employee">
			<ul class="nav nav-tabs">
				<li class="active"><a data-toggle="tab" href="#active-emp"><i class="fa fa-star"></i>&nbsp;Active</a></li>
				<li><a data-toggle="tab" href="#archived-emp"><i class="fa fa-trash-o"></i>Archived</a></li>
			</ul>
			<div class="tab-content padding-top-10">
				<div id="active-emp" class="tab-pane fade in active">
					<table class="table table-bordered table-condensed">
						<thead>
							<tr>
								<th>Employee Name</th>
								<th></th>
							</tr>
						</thead>
						<tbody>
							@foreach($_active as $active)
							<tr>
								<td>
									{{$active->payroll_employee_title_name.' '.$active->payroll_employee_first_name.' '.$active->payroll_employee_middle_name.' '.$active->payroll_employee_last_name.' '.$active->payroll_employee_suffix_name}}
								</td>
								<td class="text-center">
									<a href="#" class="popup" size="sm" link="/member/payroll/leave/modal_archived_leave_employee/1/{{$active->payroll_leave_employee_id}}"><i class="fa fa-times"></i></a>
								</td>
							</tr>
							@endforeach
						</tbody>
					</table>
				</div>
				<div id="archived-emp" class="tab-pane fade">
					<table class="table table-bordered table-condensed">
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
									<a href="#" class="popup" size="sm" link="/member/payroll/leave/modal_archived_leave_employee/0/{{$archived->payroll_leave_employee_id}}"><i class="fa fa-refresh"></i></a>
								</td>
							</tr>
							@endforeach
						</tbody>
					</table>
				</div>
				
			</div>
		</div>

	<div class="modal-footer">
		<button type="button" class="btn btn-custom-white" data-dismiss="modal">Close</button>
		<button class="btn btn-custom-primary btn-submit" type="submit">Update</button>
	</div>
</form>
<script type="text/javascript" src="/assets/member/js/payroll/modal_create_leave_temp.js"></script>