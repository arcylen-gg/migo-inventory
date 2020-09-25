<form class="global-submit form-horizontal" role="form" action="/member/columns/{{ Request::segment(3) }}" method="post">
<input type="hidden" name="_token" value="{{ csrf_token() }}">
	<div class="modal-header">
		<button type="button" class="close" data-dismiss="modal">×</button>
		<h4 class="modal-title">Item Columns</h4>
	</div>
	<div class="modal-body clearfix">
		<div class="row">
			@foreach($_column as $key => $column)
			<div class="col-md-6">
				<div class="checkbox">
					<input type="hidden" name="column[{{ $key }}][value]" value="{{ $column["value"] }}">
					<input type="hidden" name="column[{{ $key }}][array]" value="{{ $column["array"] }}">
					<input type="hidden" name="column[{{ $key }}][checked]" value="no">
				  	<label><input name="column[{{ $key }}][checked]" type="checkbox" {{ $column["checked"] ? "checked" : "" }} value="yes">{{ $column["value"] }}</label>
				</div>
			</div>
			@endforeach
		</div>
	</div>
	<div class="modal-footer">
		<button type="button" class="btn btn-def-white btn-custom-white" data-dismiss="modal">Close</button>
		<button class="btn btn-primary btn-custom-primary" type="button" onClick="location.href='/member/columns/reset/{{ Request::segment(3) }}'">Reset</button>
		<button class="btn btn-primary btn-custom-primary" type="submit">Submit</button>
	</div>
</form>
<script type="text/javascript">
function columns_submit_done(data)
{
	if (data.response_status == "success") 
	{
		toastr.success(data.message, 'Success');
	}
	else
	{
		toastr.warning(data.message);
	}

	data.element.modal("hide");
	window.location.reload();
}
</script>