@extends('member.payroll2.employee_dashboard.layout')
@section('content')
<ol class="breadcrumb">
    <li class="breadcrumb-item">
      <a href="/employee">Dashboard</a>
    </li>
    <li class="breadcrumb-item active">{{ $page }}</li>
  </ol>
<div class="panel panel-default">
	<div class="modal-body form-horizontal">
		<div class="row">
			<div class="col-md-6">
				<small>Approver Group Name</small>
				<input class="form-control"  placeholder="" required>
			</div>
			<div class="col-md-6">
			</div>
	    </div>
	    <br>
	    <div class="row">
			<div class="col-md-3">
				<small>Level of Approver</small>
				<input class="form-control"  value="Level 1" disabled>
			</div>
			<div class="col-md-9">
				<small>Name of Employee</small>
				<select class="form-control" required>
					<option value=""></option>
				</select>
			</div>
	    </div>
		<br>
	    <div class="row">
			<div class="col-md-3">
				<input class="form-control"  value="Level 2" disabled>
			</div>
			<div class="col-md-9">
				<select class="form-control" required>
					<option value=""></option>
				</select>
			</div>
	    </div>
		<br>
	    <div class="row">
			<div class="col-md-3">
				<input class="form-control"  value="Level 3" disabled>
			</div>
			<div class="col-md-9">
				<select class="form-control" required>
					<option value=""></option>
				</select>
			</div>
	    </div>
	</div>
</div>
<div class="modal-footer">
	<button type="button"  class="btn btn-primary btn-md">Cancel</button>
	<button type="submit"  class="btn btn-primary btn-md">Submit</button>
</div>
</form>
@endsection