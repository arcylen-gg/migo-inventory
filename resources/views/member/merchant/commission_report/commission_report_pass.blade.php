<form class="global-submit form-horizontal" role="form" action="/member/merchant/commission-report-pass" method="post">
	{{csrf_field()}}
	<div class="modal-header">
		<button type="button" class="close" data-dismiss="modal">×</button>
		<h4 class="modal-title">{{$page}}</h4>
	</div>
	<div class="modal-body clearfix">
		
		<div class="form-group">
            <div class="col-md-12">
                <label for="basic-input">Password</label>
                <input id="basic-input" type="password"  class="form-control" name="password">
                <input type="hidden" class="hidden-percentage" name="percentage">
            </div>
        </div>

	</div>
	<div class="modal-footer">
		<button type="button" class="btn btn-def-white btn-custom-white" data-dismiss="modal">Close</button>
		<button class="btn btn-primary btn-custom-primary" type="submit">Submit</button>
	</div>
</form>

<script type="text/javascript">
	function success(data)
	{
		toastr.success("Setting saved");
		data.element.modal("hide");
   		commission_report.action_load_table();
	}
	function invalid_password()
	{
		toastr.error("Invalid Password");
	}
</script>

<script type="text/javascript">
	$(document).ready(function()
	{
		$('.hidden-percentage').val($('.commission-percentage').val());
	});
</script>