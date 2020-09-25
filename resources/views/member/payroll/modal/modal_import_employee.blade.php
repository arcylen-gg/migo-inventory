<div class="modal-header">
	<button type="button" class="close" data-dismiss="modal">×</button>
	<h4 class="modal-title"><i class="fa fa-file-excel-o color-green" aria-hidden="true"></i>&nbsp;Import Employee</h4>
</div>
<div class="modal-body clearfix form-horizontal">
	<form class="form-group" action="/member/payroll/employee_list/modal_import_employee/get_201_template" method="GET">
		<div class="col-md-6">
			<input type="hidden" name="_token" value="{{csrf_token()}}">
			<small>Download Template Here</small>
			<button class="btn btn-custom-primary">Download Template</button>
		</div>
		<div class="col-md-6">
			<small>Number of Employees</small>
			<input type="number" name="number_of_rows" class="form-control text-right" required>
		</div>
	</form>
	<hr>
	<div class="form-group">
		<div class="col-md-4">
			<label class="btn btm-custom-green"><input type="file" id="file-201" name="" class="hide" accept="application/vnd.ms-excel, application/vnd.openxmlformats-officedocument.spreadsheetml.sheet"><i class="fa fa-folder-open-o" aria-hidden="true"></i>&nbsp;Choose Excel File</label>
		</div>
		<div class="col-md-8 padding-lr-1" style="padding-top:15px">
			<i><span class="file_name"></span></i>
		</div>
		<div class="col-md-12">
			
		</div>
	</div>
	<div class="form-group">
		<div class="col-md-6">
			<button class="btn btn-custom-primary btn-import"><i class="fa fa-upload"></i>&nbsp;Import Excel File</button>
		</div>
	</div>
	<div class="form-group">
		<div class="col-md-12 import-status">
			
		</div>
	</div>
</div>
<div class="modal-footer">
	<button type="button" class="btn btn-def-white btn-custom-white" data-dismiss="modal">Close</button>
</div>
<script type="text/javascript" src="/assets/member/js/payroll/modal_import_employee.js"></script>