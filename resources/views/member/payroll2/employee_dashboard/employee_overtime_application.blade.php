<style type="text/css">
.row
{
	margin-top: 10px;
}
</style>
<form class="global-submit" action="/employee_request_overtime_save" action="post" >
	<div class="modal-header">
	    <h5 class="modal-title">{{ $page }}</h5>
	    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
	    <span aria-hidden="true">&times;</span>
	    </button>
	    <input type="hidden" name="_token" id="_token" value="{{ csrf_token() }}">

	</div>
	<div class="modal-body form-horizontal">
		<!-- <div class="row">
			<div class="col-md-6" class="text-center">
				<a href="#" class="popup" link="/employee_overtime_view_shift" size="lg"><button class="btn btn-link">View Shift Schedule</button></a>
			</div>
		</div> -->
		<div class="row">
			<div class="col-md-6">
				<small>Overtime Date</small>
				<input class="form-control" type="date" class="date_picker" name="overtime_date" required>
			</div>
			<div class="col-md-6">
				<small>Overtime Type</small>
				<select class="form-control" name="overtime_type" required>
					<option value=""> Select Type </option>
					<option value="Regular Overtime"> Regular Overtime </option>
					<option value="Rest Day Overtime"> Rest Day Overtime </option>
				</select>
			</div>
		</div>
		<div class="row">
				<div class="col-md-3">
					<small>Regular Time In</small>
					<input class="form-control" type="time" name="regular_time_in" required>
				</div>
				<div class="col-md-3">
					<small>Regular Time Out</small>
					<input class="form-control" type="time" name="regular_time_out" required>
				</div>
				<div class="col-md-3">
					<small>Over Time In</small>
					<input class="form-control" type="time" name="overtime_time_in" required>
				</div>
				<div class="col-md-3">
					<small>Over Time Out</small>
					<input class="form-control" type="time" name="overtime_time_out" required>
				</div>
		</div>
		<div class="row">
	        <div class="col-sm-12">
	        <small>Remarks</small>
	        	<textarea class="form-control" name="remark" rows="4" name="remark" placeholder="Type Remarks" required></textarea>
	        </div>
	    </div>
	    <div class="row">
	    	<div class="col-sm-12">
	    		<small for="approver_group">Select Group Approver</small>
		    	<select class="form-control approver_group" id="approver_group" name="approver_group" required>
		    		<option value=""> Select Group Approver </option>
		    		@foreach($_group_approver as $group_approver)
		    		<option value="{{ $group_approver->payroll_approver_group_id }}"> {{ $group_approver->payroll_approver_group_name }} </option>
		    		@endforeach
		    	</select>
	    	</div>
	    </div> 
	    
	    <div class="row">
	    	<div class="col-sm-12 approver_group_list">
	    		
	    	</div>
	    </div>   
	</div>
	<div class="modal-footer">
		<button type="button"  class="btn btn-primary btn-md" data-dismiss="modal">Cancel</button>
		<button type="submit"  class="btn btn-primary btn-md">Submit</button>
	</div>
</form>

<script type="text/javascript" src="/assets/employee/js/employee_overtime_application.js"></script>

