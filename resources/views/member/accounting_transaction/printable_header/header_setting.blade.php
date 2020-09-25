<form class="global-submit" method="post" action="/member/settings/verify/add">
<div class="row clearfix">
	{!! csrf_field() !!}
	<div class="form-group">
		<input type="hidden" class="form-control" name="settings_setup_done[]" value="1">
		<input type="hidden" name="settings_key[]" value="printable_header">
		<div class="col-md-12">
			<textarea class="form-control input-sm tinymce" name="settings_value[]">{!! isset($_settings['printable_header']) ? $_settings['printable_header'] : '' !!}</textarea>
			<br>
			<br>
			<button type="submit" class="pull-right btn btn-primary btn-custom-primary"> SAVE </button>
		</div>
	</div>
</div>
</form>