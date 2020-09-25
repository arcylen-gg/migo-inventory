<form class="global-submit" role="form" action="/member/payroll/payroll_admin_dashboard/save_edit_group_approver" method="post">
	<div class="modal-header">
		<input type="hidden" name="_token" value="{{csrf_token()}}">
		<input type="hidden" id="approver_group_id" name="approver_group_id" value="{{ $approver_group_info->payroll_approver_group_id }}">
		
		<h4 class="modal-title">Edit Group Employee Approver</h4>
	</div>
	<div class="modal-body clearfix">
		<div class="form-group">
		  <label for="approver_group_name">Approver Group Name:</label>
		  <input type="text" class="form-control" id="approver_group_name" placeholder="Enter Group Name" name="approver_group_name" value="{{$approver_group_info->payroll_approver_group_name}}" required>
		</div>

		<div class="form-group row">
		  <div class="col-md-6">
		    <label for="approver_type">Select Approver Type:</label>
		    <select id="approver_type" class="form-control approver_type" name="approver_type" required>
		        <option value="overtime" {{$approver_group_info->payroll_approver_group_type == 'overtime' ? 'selected':''}}> Overtime Request </option>
		        <option value="rfp" {{$approver_group_info->payroll_approver_group_type == 'rfp' ? 'selected':''}}> Request For Payment </option>
		        <option value="leave" {{$approver_group_info->payroll_approver_group_type == 'leave' ? 'selected':''}}> Leave Requesst </option>
		    </select>
		  </div>
		  <div class="col-md-6">
		    <label for="approver_level_count">Select Up to Level:</label>
		    <select id="approver_level_count" class="form-control approver_level_count" name="approver_level_count" required>
		        <option value="1" {{$approver_group_info->payroll_approver_group_level == '1' ? 'selected':''}}> 1 </option>
		        <option value="2" {{$approver_group_info->payroll_approver_group_level == '2' ? 'selected':''}}> 2 </option>
		        <option value="3" {{$approver_group_info->payroll_approver_group_level == '3' ? 'selected':''}}> 3 </option>
		        <option value="4" {{$approver_group_info->payroll_approver_group_level == '4' ? 'selected':''}}> 4 </option>
		        <option value="5" {{$approver_group_info->payroll_approver_group_level == '5' ? 'selected':''}}> 5 </option>
		    </select>
		  </div>
		</div>
		<div class="approver-container">
		  
		</div>
	</div>
	<div class="modal-footer">
		<button type="button" class="btn btn-def-white btn-custom-white" data-dismiss="modal">Close</button>
		<button class="btn btn-primary btn-custom-primary" type="Submit">Submit</button>
	</div>
</form>

<script type="text/javascript" src="/assets/js/modal_payroll_group_approver.js"></script>
