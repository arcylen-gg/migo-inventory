@if(count($_account) > 0)
	@foreach($_account as $key => $account)
		<tr data-id="account-{{$account['account_id']}}" data-parent="{{$account['account_parent_id'] == '' ? '' : 'account-'.$account['account_parent_id']}}" >
			
		    <td style="font-weight: bold; {{$account['account_sublevel'] > 0  ? 'padding-left: ' .$account['account_sublevel']*20 .'px' : '' }}">
		    	<span >
		    	@if($account['is_sub_count'] == 1)
		    	@endif
		    		{{$account['account_number']}} • {{$account['account_name']}}
		    	</span>
		    </td>
		    <td>{{$account['account_type']}}</td>
		    <td>{{currency('PHP', $account['account_new_balance'])}}</td>
		    <td class="text-center">
		    	<!-- ACTION BUTTON -->
		    	<div class="btn-group">
	                <a class="btn btn-primary btn-grp-primary popup btn-edit-account" href="javascript:" link="/member/accounting/chart_of_account/popup/update/{{$account['account_id']}}">Edit</a>
	                <a class="btn btn-primary btn-grp-primary" href="/member/accounting/journal/all-entry-by-account/{{$account['account_id']}}">Quick Report</a>
	            </div>
		    </td>
		</tr>
		@if($account['sub_account'] != null)
			@include('member.accounting.load_chart_account', ['_account' => $account['sub_account']])
		@endif
		@if(sizeOf($_account)-1 == $key)
			<option class="hidden" value="" />
		@endif
	@endforeach
@else
<tr><td colspan="4">No Result Found!</td></tr>
@endif