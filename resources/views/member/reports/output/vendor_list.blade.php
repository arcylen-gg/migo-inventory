<div class="report-container">
  <div class="panel panel-default panel-block panel-title-block panel-report load-data">
      <div class="panel-heading load-content">
         @include('member.reports.report_header')
         <div class="table-reponsive">
         		<table class="table table-condensed collaptable">
         		<tr>
              <th class="text-center">Vendor</th>
         			<th class="text-center">Date</th>
         			<th class="text-center">Type</th>
         			<th class="text-center">Num</th>
         			<th class="text-center">Account</th>
         			<th class="text-center">Amount</th>
         			<th class="text-center">Balance</th>
         		</tr>
         		<tbody>
         			
     				@foreach($_vendor as $key=>$vendor)
     				<tr data-id="vendor-{{$key}}" data-parent="">
         				<td><b>{{ $vendor->vendor_company != '' ? $vendor->vendor_company : $vendor->vendor_first_name." ".$vendor->vendor_last_name }}</b> <br>
                  &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<small>{{$vendor->vendor_address}}</small>
                </td>
                <td colspan="5"></td>
                <td class="text-right"><text class="total-report">{{currency('PHP', $vendor->balance)}}</text></td>
         			</tr>
         				<?php $balance = 0;?>
     					@foreach($vendor->vendor_journal as $key2=>$journal)
  						<tr data-id="vendor2-{{$key}}" data-parent="vendor-{{$key}}">
                <td nowrap></td>
  							<td nowrap>{{$journal->je_entry_date}}</td>
  							<td nowrap>{{$journal->je_reference_module}}</td>
  							<td nowrap>{{$journal->je_reference_id}}</td>
  							<td nowrap>{{$journal->account_name}}</td>
  							<td class="text-right" nowrap>{{currency('PHP', $journal->amount)}}</td>
  							<?php $balance += $journal->amount; ?>
  							<td class="text-right" nowrap>{{currency('PHP', $balance)}}</td>
  						</tr>
  					  @endforeach
      				<tr data-id="vendor2-{{$key}}" data-parent="vendor-{{$key}}">
      					<td colspan="6"><b>Total {{$vendor->first_name." ".$vendor->last_name}}</b></td>
      					<td class="text-right">{{currency('PHP', $vendor->balance)}}</td>
      				</tr>	
     				@endforeach
         		</tbody>
         		</table>
         	</div>
          <h5 class="text-center">---- {{$now or ''}} ----</h5>
      </div>
  </div>
</div>