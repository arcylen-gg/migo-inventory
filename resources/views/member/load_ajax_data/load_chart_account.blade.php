@foreach($_account as $key=>$account)
	<option value="{{$account['account_id']}}" indent="{{$account['account_sublevel']}}" acct-desc="{{$account['account_description']}}" add-search="{{$add_search}}" reference="{{$account['account_type'] == 'Accounts Receivable' ? 'customer' : ($account['account_type'] == 'Accounts Payable' ? 'vendor' : '')}}"
	{{ isset($account_id) ?  ($account_id == $account['account_id'] ? 'selected' : '') : '' }}>{{$account['account_number']}} • {{$account['account_name']}}</option>
	@if($account['sub_account'] != null)
		@include('member.load_ajax_data.load_chart_account', ['_account' => $account['sub_account'], 'add_search' => $account['account_name']."|".$add_search])
	@endif
	@if(sizeOf($_account)-1 == $key)
		<option class="hidden" value="" />
	@endif
@endforeach