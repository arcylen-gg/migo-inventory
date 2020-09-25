@foreach($_account as $key=>$account)
	<tr data-id="account-{{$account['account_id']}}" data-parent="{{$account['account_parent_id'] ? 'account-' . $account['account_parent_id'] : ''}}">
		<td ><b>{{$account['account_name']	}}</b></td>
		<td >{{$account['account_type']}}</td>
		<td class="text-right"><a href="/member/accounting/journal/all-entry-by-account/{{$account['account_id']}}" target="_blank"><text class="">{{is_numeric($account['account_new_balance']) ? '' .currency('PHP', $account['account_new_balance']) : ''}}</text></a></td>
	</tr>
	@if(isset($account['sub_account']))
		@include('member.reports.output.account_list_sub', ['_account' => $account['sub_account']])
	@endif
@endforeach