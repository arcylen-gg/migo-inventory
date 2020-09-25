<div class="report-container">
  <div class="panel panel-default panel-block panel-title-block panel-report load-data">
      <div class="panel-heading load-content">
         @include('member.reports.report_header');
         <div class="table-reponsive">
         		<table class="table table-condensed collaptable">
         		<tr>
              <th class="text-center" class="text-center">Customer</th>
              @if($migo_customization)
              <th class="text-center">Customer Category</th>
              <th class="text-center">Customer Type</th>
              @endif
         			<th class="text-center">Date</th>
         			<th class="text-center">Type</th>
         			<th class="text-center">Num</th>
         			<th class="text-center">Account</th>
         			<th class="text-center">Amount</th>
         			<th class="text-center">Balance</th>
         		</tr>
         		<tbody>
         			
     				@foreach($_customer as $key=>$customer)
     				<tr data-id="customer-{{$key}}" data-parent="">
         				<td><b>{{$customer->company != "" ? $customer->company : $customer->first_name." ".$customer->last_name}}</b></td>
                @if($migo_customization)
                <td class="text-center">{{$customer->customer_category}}</td>
                <td class="text-center">{{$customer->customer_category_type}}</td>
                @endif
                <td colspan="5"></td>
                <td class="text-right"><text class="total-report">{{currency('PHP', $customer->balance)}}</text></td>
         			</tr>
         				<?php $balance = 0;?>
     					@foreach($customer->customer_journal as $key2=>$journal)
  						<tr data-id="customer2-{{$key}}" data-parent="customer-{{$key}}">
                <td nowrap colspan="3"></td>
  							<td nowrap>{{$journal->je_entry_date}}</td>
  							<td nowrap>{{$journal->je_reference_module}}</td>
  							<td nowrap>{{$journal->je_reference_id}}</td>
  							<td nowrap>{{$journal->account_name}}</td>
  							<td class="text-right" nowrap>{{currency('PHP', $journal->amount)}}</td>
  							<?php $balance += $journal->amount; ?>
  							<td class="text-right" nowrap>{{currency('PHP', $balance)}}</td>
  						</tr>
    					@endforeach
      				<tr  data-id="customer2-{{$key}}" data-parent="customer-{{$key}}">
      					<td colspan="6"><b>Total {{$customer->company != "" ? $customer->company : $customer->first_name." ".$customer->last_name}}</b></td>
      					<td class="text-right">{{currency('PHP', $customer->balance)}}</td>
      				</tr>	
     				@endforeach
         		</tbody>
         		</table>
         	</div>
          <h5 class="text-center">---- {{$now or ''}} ----</h5>
      </div>
  </div>
</div>