<form class="global-submit" role="form" action="/member/payroll/leave/modal_save_leave_temp" method="POST">
	<div class="modal-header">
		<button type="button" class="close" data-dismiss="modal">&times;</button>
		<h4 class="modal-title">Create Leave</h4>
		<input type="hidden" name="_token" value="{{csrf_token()}}">
	</div>
	<div class="modal-body form-horizontal">
		<div class="form-group">
			<div class="col-md-12">
				<small>Leave Name</small>
				<input type="text" name="payroll_leave_temp_name" class="form-control" required>
			</div>
			
		</div>
		<div class="form-group">
			<div class="col-md-6">
				<small>No. of Hours</small>
				<input type="number" name="payroll_leave_temp_days_cap" class="form-control text-right"  step="0.01" required>
			</div>
		</div>

		<div class="form-group">
			<div class="col-md-6" hidden>
				<small>With Pay?</small>
				<div class="form-control padding-b-37">
					<div class="col-md-6">
						<div class="radio">
							<label><input type="radio" class="payroll_leave_temp_with_pay" name="payroll_leave_temp_with_pay" value="1" checked >Yes</label>
						</div>
					</div>
					<div class="col-md-6">
						<div class="radio">
							<label><input type="radio" class="payroll_leave_temp_with_pay" name="payroll_leave_temp_with_pay" value="0" >No</label>
						</div>
					</div>					
				</div>		
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
				<span><b>Affected Employee<a href="#" class="btn btn-custom-primary pull-right popup" link="/member/payroll/leave/modal_leave_tag_employee/0'">Tag Employee</a></b></span>

			</div>
		</div>
		<div class="form-group">
			<div class="col-md-12">
				<table class="table table-bordered table-condensed">
					<thead>
						<tr>
							<th>Employee Name</th>
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
<script type="text/javascript" src="/assets/member/js/payroll/modal_create_leave_temp.js"></script>