<center>Bank Option</center>
<div class="col-md-12">	
<form class="global-submit" metho="post" action="/member/mlm/encashment/view/type/bank/edit/name">
	{!! csrf_field() !!}

	<label>Bank Account Name Editable</label>
	<select class="form-control" name="enchasment_settings_bank_edit">
		<option value="0" {{$encashment_settings->enchasment_settings_bank_edit == 0 ? 'selected' : ''}}>Disable</option>
		<option value="1" {{$encashment_settings->enchasment_settings_bank_edit == 1 ? 'selected' : ''}}>Enable</opttion>
	</select>
	<hr>
	<button class="btn btn-primary pull-right">Save</button>
</div>
</form>
<div class="col-md-12">
	<hr>
</div>
<div class="col-md-12">
	<div class="col-md-6">Bank Name</div>
	<div class="col-md-6"></div>
</div>
<div class="col-md-12">
	<hr>
</div>
@foreach($bank_option as $key => $value)
<form class="global-submit" method="post" action="/member/mlm/encashment/view/type/bank/archive">
{!! csrf_field() !!}
<input type="hidden" name="encashment_bank_deposit_id" value="{{$value->encashment_bank_deposit_id}}">
<div class="col-md-12">
	<div class="col-md-6">
		<input type="text"  class="form-control" value="{{$value->encashment_bank_deposit_name}}" readonly>
	</div>
	<div class="col-md-6">
		<button class="btn btn-warning col-md-12" >Archive</button>
	</div>
</div>
</form>
<div class="col-md-12">
	<hr>
</div>
@endforeach

<form class="global-submit" method="post" action="/member/mlm/encashment/view/type/bank/add">
	{!! csrf_field() !!}

<div class="col-md-12">
	<div class="col-md-6"><input type="text" class="form-control" name="encashment_bank_deposit_name" required="required"></div>
	<div class="col-md-6"><button class="btn btn-primary col-md-12">New</button></div>
</div>
</form>