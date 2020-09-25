<div class="modal-header">
	<button type="button" class="close" data-dismiss="modal">×</button>
	<h4 class="modal-title">Settings</h4>
	<input type="hidden" name="selected_settings" value="{{$selected_settings}}" class="selected_settings">
</div>

<div class="modal-body clearfix">
<div class="alert alert-warning">
  <strong>Warning!</strong>You have an unset setting/s.
</div>
	{!! $settings_setup !!}
</div>

<div class="modal-footer">
	<button type="button" class="btn btn-def-white btn-custom-white" data-dismiss="modal">Close</button>
	<button class="btn btn-primary btn-custom-primary" type="button" onClick="edit_settings()">Save Settings</button>
</div>

<form class="global-submit" method="post" action="/member/settings/verify/add" id="submit_form_settings">
	{!! csrf_field() !!}
	<input type="hidden" value="" name="settings_key" id="settings_key_form">
	<input type="hidden" value="" name="settings_value" id="settings_value_form">
	<input type="hidden" value="add" name="update_type" id="update_type">
</form>
<script>
@if(isset($settings_active))
	@if(count($settings_active) != 0)
		@foreach($settings_active as $key => $value)
			$('#{{$key}}').find("[name='settings_value']").val('{{$value["settings_value"]}}');
			@if($value['settings_setup_done'] == 0)
				$('#{{$key}}').addClass('alert alert-warning');
			@endif
		@endforeach
	@endif
@endif
</script>
<script type="text/javascript" src="/assets/member/settings/setting_single.js"></script>