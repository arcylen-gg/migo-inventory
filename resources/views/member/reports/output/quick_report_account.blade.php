<div class="report-container">
	<div class="panel panel-default panel-block panel-title-block panel-report load-data">
		<div class="panel-heading load-content">
			@include('member.reports.report_header')
			<div class="table-responsive load-data">
				<table class="table table-striped table-condensed table-hovered">
					<thead>
						<tr>
							<th>Date</th>
							<th>Transaction Type</th>
							<th>Num</th> 
							<th>Memo/Description</th>
							<th>Account Name</th>
							<th>Amount</th>
							<th>Balance</th>
						</tr>
					</thead>
					<tbody class="{{ $balance = 0 }}">
						@if(count($_journal) > 0)
						@foreach($_journal as $key=>$entry)
						<tr>
							<td>{{dateFormat($entry->je_entry_date)}}</td>
							<td>{{strtoupper(str_replace("-"," ",$entry->je_reference_module))}}</td>
							<td>
								<a href="{{$entry->txn_link}}">
									{{$entry->transaction_refnumber != null || $entry->transaction_refnumber != "" ? $entry->transaction_refnumber : $entry->je_reference_id}}
								</a>
							</td>
							<td>{{$entry->jline_description}}</td>
							<td>{{$entry->account_name}}</td>
							<td class="text-right">{{strToUpper($entry->normal_balance) == strToUpper($entry->jline_type) ? currency('PHP', $entry->jline_amount) : currency('PHP', -$entry->jline_amount)}}</td>
						
							<td class="text-right {{ $balance += strToUpper($entry->normal_balance) == strToUpper($entry->jline_type) ? $entry->jline_amount : -$entry->jline_amount }}">{{currency('PHP', $balance)}}</td>
						</tr>
						@endforeach
						@else
						<tr><td colspan="7" class="text-center">NO TRANSACTION</td></tr>
						@endif
						<tr>
							<td>TOTAL {{$account->account_name}}</td>
							<td colspan="5"></td>
							<td><b>{{currency('PHP', $balance)}}</b></td>
						</tr>
					</tbody>
				</table>
			</div>
		</div>
	</div>
</div>