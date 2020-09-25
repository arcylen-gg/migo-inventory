<form class="global-submit " role="form" action="/member/payroll/custom_payslip/save_payslip_options" method="post">
	<div class="modal-header">
		<button type="button" class="close" data-dismiss="modal">×</button>
		<h4 class="modal-title">Payslip Option</h4>
		<input type="hidden" name="_token" value="{{csrf_token()}}">
	</div>
	<div class="modal-body form-horizontal">
		<div class="form-group">
				
				@if(isset($option))
                <div class="checkbox">
				<div class="radio">
					<label><input type="radio" name="per_page" value="0" {{$option[0]['per_page'] == 0 ? 'checked' : 'unchecked'}} style="margin-left: 20px;" >View by Default</label>
				</div>
				<div class="radio">
					<label><input type="radio" name="per_page" value="1" {{$option[0]['per_page'] == 1 ? 'checked' : 'unchecked'}} style="margin-left: 20px;" >View per page</label>
				</div>
                @endif
		</div>
	</div>
	<div class="modal-footer">
		<button type="button" class="btn btn-def-white btn-custom-white" data-dismiss="modal">Close</button>
		<button class="btn btn-primary btn-custom-primary" type="submit"">Submit</button>
	</div>
</form>
