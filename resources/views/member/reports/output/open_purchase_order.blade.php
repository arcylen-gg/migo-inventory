<div class="report-container">
  <div class="panel panel-default panel-block panel-title-block panel-report load-data">
      <div class="panel-heading load-content">
         @include('member.reports.report_header')
         <div class="table-reponsive">
         		<table class="table table-condensed collaptable">
         		<tr>
              <th >Purchase Order</th>
         			<th >Vendor Name</th>
         			<th >Date</th>
         			<th class="text-center">Item Name</th>
         			<th class="text-center">Ordered Qty</th>
         			<th class="text-center">Received Qty</th>
         			<th class="text-center">Backordered Qty</th>
              <th class="text-center">Rate</th>
         			<th class="text-center">Amount</th>
         			<th class="text-center">Open Balance</th>
         		</tr>
         		<tbody>
     				@foreach($_po as $key => $po)
     				<tr data-id="customer-{{$key}}" data-parent="" style="">
         				<td><b>{{$po->transaction_refnum != "" ? $po->transaction_refnum : $po->po_id." - "}}</b></td>
         				<td>{{$po->vendor_company == '' ? ucfirst($po->vendor_title_name." ".$po->vendor_first_name." ".$po->vendor_middle_name." ".$po->vendor_last_name." ".$po->vendor_suffix_name) : $po->vendor_company}}</td>
         				<td>{{date('F d, Y', strtotime($po->po_date))}}</td>
		                <td colspan="6"></td>
		                <td class="text-right"><text class="total-report"><b>{{currency('PHP', $po->balance)}}</b></text></td>
		         	</tr>
		         	@if($po->monitoring_qty)
			         	@foreach($po->monitoring_qty as $value)
		         		<tr data-id="customer2-{{$key}}" data-parent="customer-{{$key}}">
		         			<td nowrap></td>
		         			<td nowrap></td>
		         			<td nowrap></td>
							<td class="text-center" nowrap>{{$value['item_name']}}</td>
							<td class="text-center" nowrap>{{number_format($value['orig_qty'])}}</td>
							<td class="text-center" nowrap>{{number_format($value['received'])}}</td>
							<td class="text-center" nowrap>{{number_format($value['backorder'])}}</td>
              <td class="text-right" nowrap>{{currency('PHP', $value['rate'])}}</td>
							<td class="text-right" nowrap>{{currency('PHP', $value['amount'])}}</td>
							<td class="text-right" nowrap>{{currency('PHP', $value['total'])}}</td>
		         		</tr>
		         		@endforeach
	         		@endif
	         		<tr data-id="customer2-{{$key}}" data-parent="customer-{{$key}}" bgcolor="#c2bcbc">
      					<td colspan="9"><b>Total Amount</b></td>
      					<td class="text-right">{{currency('PHP', $po->balance)}}</td>
      				</tr>
		         	@endforeach
     				
         		</tbody>
         		</table>
         	</div>
          <h5 class="text-center">---- {{$now or ''}} ----</h5>
      </div>
  </div>
</div>