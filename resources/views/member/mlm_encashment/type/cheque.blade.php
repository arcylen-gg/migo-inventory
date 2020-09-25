<center>Cheque</center>
<form class="global-submit" metho="post" action="/member/mlm/encashment/view/type/cheque/edit">
	{!! csrf_field() 	!!}

<div class="col-md-12">
	<label>Name on Cheque Editable</label>
	<select class="form-control" name="enchasment_settings_cheque_edit">
		<option value="0" {{$encashment_settings->enchasment_settings_cheque_edit == 0 ? 'selected' : ''}}>Disable</option>
		<option value="1" {{$encashment_settings->enchasment_settings_cheque_edit == 1 ? 'selected' : ''}}>Enable</opttion>
	</select>
</div>
<div  class="col-md-12">
	<hr>
</div>
<div class="col-md-12">
	<button class="btn btn-primary pull-right">Save</button>
</div>
</form>