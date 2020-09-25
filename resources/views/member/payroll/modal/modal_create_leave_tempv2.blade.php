<link rel="stylesheet" type="text/css" href="/assets/member/payroll/css/timesheet.css">
<link rel="stylesheet" type="text/css" href="/assets/external/jquery.timeentry.package-2.0.1/jquery.timeentry.css">

<!-- Change action, name, some links and functions-->
<form class="global-submit" role="form" action="/member/payroll/leave/v2/modal_save_leave_temp_v2" method="POST">
	<div class="modal-header">
		<button type="button" class="close" data-dismiss="modal">&times;</button>
		<h4 class="modal-title">Create Leave</h4>
		<input type="hidden" name="_token" value="{{csrf_token()}}">
	</div>
	<div class="modal-body form-horizontal">
		<div class="form-group">
			<div class="col-md-6">
				<small>Type of Leave</small>
				<select class="form-control user-error" id="payroll_leave_type_id" required name="payroll_leave_temp_name" aria-invalid="true">
					<option value="">Select Leave Type</option>
					<option value="Sick Leave">Sick Leave</option>
					<option value="Vacation Leave">Vacation Leave</option>
					<option value="Emergency Leave">Emergency Leave</option>
					<option value="Maternity Leave">Maternity Leave</option>
					<option value="Paternity Leave">Paternity Leave</option>
					<option value="Parental Leave">Parental Leave</option>
					<option value="Leave For Victims of VAWC">Leave For Victims of VAWC</option>
					<option value="Special Leave For Women">Special Leave For Women</option>
				</select>
			</div>

			<div class="col-md-6">
				<small>Commulative?</small>
				<div class="form-control padding-b-37">
					<div class="col-md-6">
						<div class="radio">
							<label><input type="radio" name="payroll_leave_temp_is_cummulative" value="1" checked>Yes</label>
						</div>
					</div>
					<div class="col-md-6">
						<div class="radio">
							<label><input type="radio" name="payroll_leave_temp_is_cummulative" value="0">No</label>
						</div>
					</div>					
				</div>		
			</div>

		</div>



		<div class="form-group">
			<div class="col-md-12">
				<span><b>Affected Employee<a href="#" class="btn btn-custom-primary pull-right popup" link="/member/payroll/leave/v2/modal_leave_tag_employeev2/0'">Tag Employee</a></b></span>
			</div>
		</div>
		<div class="form-group">
			<div class="col-md-12">
				<table class="table table-bordered table-condensed">
					<thead>
						<tr>
							<th>Employee Name</th>
							<th class="text-center">Leave Hours</th>
							<th width="5%"></th>
						</tr>
					</thead>
					<tbody class="tbl-tag">
						
					</tbody>
				</table>
			</div>
		</div>
		

	</div>	
	<div class="modal-footer">
		<button type="button" class="btn btn-custom-white" data-dismiss="modal">Close</button>
		<button class="btn btn-custom-primary btn-submit" type="submit">Save</button>
	</div>
</form>
<script type="text/javascript" src="/assets/external/jquery.timeentry.package-2.0.1/jquery.plugin.min.js"></script>
<script type="text/javascript" src="/assets/external/jquery.timeentry.package-2.0.1/jquery.timeentry.min.js"></script>

<script type="text/javascript" src="/assets/member/js/payroll/modal_create_leave_tempv2.js"></script>
