<form class="import-form" role="form" action="/member/merchant/commission_report/import" method="post" enctype="multipart/form-data">
	<div class="modal-header">
		 {{ csrf_field() }}
		<button type="button" class="close" data-dismiss="modal">×</button>
		<h4 class="modal-title">{{$page}}</h4>
	</div>
	<div class="modal-body clearfix">
		
		<div class="form-group">
	        <div class="col-md-12">
	            <input id="basic-input" type="file"  class="form-control excel_file" name="excel_file" required="">
	        </div>
	    </div>

	</div>
	<div class="modal-footer">
		<button type="button" class="btn btn-def-white btn-custom-white" data-dismiss="modal"><i class="fa fa-close"></i> Cancel</button>
		<button class="btn btn-primary btn-custom-primary btn-submit" type="submit"><i class="fa fa-upload"></i> Import</button>
	</div>
</form>
<script type="text/javascript">
	$(document).ready(function()
	{
		$('.btn-submit').on('click',function(e)
		{
			if($('.excel_file').val() != '')
			{
				$(".modal-loader").removeClass("hidden");
				console.log('submit');
			}
			
		});
	});
</script>
<script type="text/javascript">
	function success(data)
	{
		toastr.success('success');
		data.element.modal('hide');
	}
	function error()
	{
		toastr.error('no file');
	}
</script>