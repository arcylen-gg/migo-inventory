<form class="global-submit" method="post" action="/mlm/profile/edit/encashment">
  {!! csrf_field() !!}
  @if(isset($encashment_settings->enchasment_settings_type))
    <input type="hidden" name="enchasment_settings_type" value="{{$encashment_settings->enchasment_settings_type}}">
    @if($encashment_settings->enchasment_settings_type == 0)
    <label>Bank</label>
    <select class="form-control" name="encashment_bank_deposit_id">
      @foreach($bank as $key => $value)
      <option value='{{$value->encashment_bank_deposit_id}}' @if($customer_payout->encashment_bank_deposit_id == $value->encashment_bank_deposit_id) selected @endif >{{$value->encashment_bank_deposit_name}}</option>
      @endforeach
    </select>
    <label>Bank Branch</label>
    <input type="text" class="form-control" name="customer_payout_bank_branch" required value="{{$customer_payout->customer_payout_bank_branch}}">
    <label>Bank Account Name</label>
    <input type="text" class="form-control" name="customer_payout_bank_account_name" required value="{{$customer_payout->customer_payout_bank_account_name}}" @if($encashment_settings->enchasment_settings_bank_edit == 0) readonly @endif>
    <label>Bank Account Number</label>
    <input type="text" class="form-control" name="customer_payout_bank_account_number" required value="{{$customer_payout->customer_payout_bank_account_number}}">
    @elseif($encashment_settings->enchasment_settings_type == 1)
    <label>Name on Cheque</label>
    <input type="text" class="form-control" name="customer_payout_name_on_cheque" value="{{$customer_payout->customer_payout_name_on_cheque}}" @if($encashment_settings->enchasment_settings_cheque_edit == 0) readonly @endif>
    @else

    @endif
  @else

  @endif
  <hr>
  <button class="btn btn-primary col-md-12">Update</button>
</form>