<ol class="breadcrumb">
    <li class="breadcrumb-item">
      <a href="/employee">Dashboard</a>
    </li>
    <li class="breadcrumb-item active">{{ $page }}</li>
</ol>
	<div class="modal-body form-horizontal">
		<div class="row">
	    	<div class="col-md-6">
				<small>Name of Employee</small>
				<select class="form-control" required>
					<option value="">Select Employee</option>
				</select>
			</div>
			<div class="col-sm-6">
				<small>Department</small>
				<select class="form-control" required>
					<option value="">Select Department</option>
				</select>
			</div>
	    </div>
		<div class="row">
			<div class="col-md-6">
				<small>Date</small>
				<input class="form-control" type="date" class="form-control" required>
			</div>
			<div class="col-md-3">
				<small>Time in</small>
				<input class="form-control" type="time" class="form-control">
			</div>
			<div class="col-md-3">
				<small>Time out</small>
				<input class="form-control" type="time" class="form-control">
			</div>
		</div>
		<div class="row">
			<div class="col-md-6">
				<small>Client Name</small>
				<select class="form-control" name="select_client" required>
					<option value="">Select Client</option>
				</select>
			</div>
			<div class="col-md-6">
				<small>Branch</small>
				<select class="form-control" name="select_branch" required>
					<option value="">Select Branch</option>
				</select>
			</div>
		</div>
        <div class="row">
            <div class="col-sm-6">
            <small>Contact Person</small>
            	<input type="text" class="form-control">
            </div>
			<div class="col-md-6">
				<small>Contact No.</small>
				<input class="form-control" type="text" class="form-control">
			</div>
        </div>
		<div class="row">
            <div class="col-sm-12">
            <small>Adress</small>
            	<input type="text" class="form-control">
            </div>
        </div>
		<div class="row">
            <div class="col-sm-12">
            <small>Remarks</small>
            	<textarea class="form-control" name="other_info" rows="4" required></textarea>
            </div>
        </div>
	</div>
	<div class="modal-footer">
		<button type="button"  class="btn btn-primary btn-md">Cancel</button>
		<button type="submit"  class="btn btn-primary btn-md">Submit</button>
	</div>
</form>
