<div class="report-container">
  <div class="panel panel-default panel-block panel-title-block panel-report load-data">
      <div class="panel-heading load-content">
         @include('member.reports.report_header')
         <div class="table-reponsive">
         		<table class="table table-condensed collaptable">
         		<tr>
         			<th >Vendor Name</th>
              <th >Reference Number</th>
              <th class="text-center">Date</th>
              <th class="text-center">Remarks</th>
         			<th class="text-center">Status</th>
              <th class="text-center">Total Amount</th>
              <th class="text-center">Remaining Balance</th>
         		</tr>
         		<tbody>
     				@foreach($_vendor as $key => $vendor)
     				<tr data-id="customer-{{$key}}" data-parent="" style="">
         				  <td>{{$vendor->vendor_company == '' ? ucfirst($vendor->vendor_title_name." ".$vendor->vendor_first_name." ".$vendor->vendor_middle_name." ".$vendor->vendor_last_name." ".$vendor->vendor_suffix_name) : $vendor->vendor_company}}</td>
	                <td colspan="4"></td>
                  <td class="text-right"><text class="total-report"><b>{{currency('PHP', $vendor->total)}}</b></text></td>
	                <td class="text-right"><text class="total-report"><b>{{currency('PHP', $vendor->balance)}}</b></text></td>
		         	</tr>
		         	@if($vendor->purchase_order)
			         	@foreach($vendor->purchase_order as $value)
		         		<tr data-id="customer2-{{$key}}" data-parent="customer-{{$key}}">
		         			<td nowrap></td>
		         			<td class="text-center" nowrap>{{$value->transaction_refnum}}</td>
                  <td class="text-center" nowrap>{{date('F d, Y',strtotime($value->po_date))}}</td>
                  <td class="text-center" nowrap>{{$value->po_message}}</td>
                  @if($value->po_is_billed != 0)
                  <td class="text-center" nowrap>FULLY RECEIVED</td>
                  @elseif($value->rem_balance < $value->total_po && $value->rem_balance > 0)
                  <td class="text-center" nowrap>PARTIALLY RECEIVED</td>
                  @else
                  <td class="text-center" nowrap>OPEN</td>
                  @endif
                  <td class="text-right" nowrap>{{currency('PHP', $value->total_po)}}</td>
                  <td class="text-right" nowrap>{{currency('PHP', $value->rem_balance)}}</td>
		         		</tr>
		         		@endforeach
	         		@endif
	         		<tr data-id="customer2-{{$key}}" data-parent="customer-{{$key}}" bgcolor="#c2bcbc">
      					<td colspan="5"><b>Total Amount</b></td>
      					<td class="text-right">{{currency('PHP', $vendor->total)}}</td>
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