
<div class="modal-header">
	<button type="button" class="close" data-dismiss="modal">×</button>
	<h4 class="modal-title"><i class="fa fa-file-excel-o color-green" aria-hidden="true"></i>&nbsp;Import Time Sheet</h4>
</div>

<div class="modal-body clearfix form-horizontal">
	<div class="form-group">
		<div class="col-md-6">
			<button class="btn btn-custom-primary btn-import-biometric"><i class="fa fa-calendar" aria-hidden="true"></i> &nbsp; Import From Biometric</button>
		</div>
	</div>
	<form class="form-group" action="/member/payroll/import_bio/template_global" method="GET">
		<input type="hidden" value="{{csrf_token()}}" class="_token" name="_token">
		<div class="col-md-6">
			<button class="btn btn-custom-primary">Download Template</button>
		</div>
		<div class="col-md-6">
			<select class="form-control" id="biometric_name" name="biometric_name">
				
				<option value="Digital Persona">Digital Persona</option>
				<option value="Manual Template">Manual Template</option>
				<option value="Mustard Seed">Mustard Seed</option>
				<option value="ZKTeco TX628">ZKTeco TX628</option>
				<option value="ZKTime 5.0">ZKTime 5.0</option>
				<option value="ZKTeco- YH 803A (UPS)">ZKTeco- YH 803A (UPS)</option>
				<option value="Touchlink V1">Touchlink BT3TFT</option>
				<option value="ANVIZ Biometrics EP Series">ANVIZ Biometrics EP Series</option>
				
			</select>
		</div>

		
		 
	</form>

	<div class="form-group">
		<div class="col-md-6">
			<label class="btn btm-custom-green"><input type="file" name="" id="bio-file" class="hide" accept="application/vnd.ms-excel, application/vnd.openxmlformats-officedocument.spreadsheetml.sheet, text/plain, .dat">Choose File</label>
		</div>
		<div class="col-md-6">
			<select class="form-control" id="company" name="company">
				<option value="">Select Company</option>
				@foreach($_company as $company)
	              <option value="{{$company['company']->payroll_company_id}}">{{$company['company']->payroll_company_name}}</option> 
	                @foreach($company['branch'] as $branch)
	                <option value="{{$branch->payroll_company_id}}">&nbsp;&nbsp;• {{$branch->payroll_company_name}}</option>
	                @endforeach
	             @endforeach
			</select>
		</div>
		<div class="col-md-12">
			<i><span class="file-name"></span></i>
		</div>
	</div>
	<div class="form-group">
		<div class="col-md-6">
			<button class="btn btn-custom-primary btn-import">Import File</button>
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
<script type="text/javascript" src="/assets/member/js/payroll/modal_biometrics.js"></script>
