<div class="report-container">
	<div class="panel panel-default panel-block panel-title-block panel-report load-data">
		<div class="panel-heading load-content">
			@include('member.reports.report_header')
			<h4 class="text-center">{{$payment_method or 'All Payment Type'}}</h4>
			<div class="table-reponsive">
				<table class="table table-condensed collaptable">
					<thead style="text-transform: uppercase">
						<tr>
							<th class="text-center">Payment Gateway</th>
							<th class="text-center">Order ID</th>
							<th class="text-center">Date</th>
							<th class="text-center">Month</th>
							<th class="text-center">Customer Name</th>
							<th class="text-center">Email</th>
							<th class="text-center">Contact Number</th>
							<th class="text-center">TIN</th>
							<th class="text-center">Slot ID</th>
							<th class="text-center">Slot No</th>
							<th class="text-center">Upline Code / Referror</th>
							<th class="text-center">Invoice Date</th>
							<th class="text-center">Invoice No.</th>
							<th class="text-center">Product</th>
							<th class="text-center">Quantity</th>
							<th class="text-center">Street</th>
							<th class="text-center">Zip</th>
							<th class="text-center">City</th>
							<th class="text-center">State</th>
							<th class="text-center">Checkout ID / TXN ID</th>
							<th class="text-center">Payment Gateway Response</th>
							<th class="text-center">Investigation</th>
						</tr>
					</thead>
					<tbody>
						@if(count($_report) > 0)
							@foreach($_report as $key => $report)
							<tr>
								<td class="text-center">{{ucfirst($report->payment_method)}}</td>
								<td class="text-center">{{$report->transaction_id}}</td>
								<td class="text-center">{{date('m/d/y',strtotime($report->transaction_date_created))}}</td>
								<td class="text-center">{{date('F',strtotime($report->transaction_date_created))}}</td>
								<td class="text-center">{{ucwords($report->first_name.' '.$report->middle_name.' '.$report->last_name)}}</td>
								<td class="text-center">{{$report->email}}</td>
								<td class="text-center">{{$report->customer_mobile or $report->contact}}</td>
								<td class="text-center">{{$report->tin_number or '-'}}</td>
								<td class="text-center">{{strtoupper($report->slot_id)}}</td>
								<td class="text-center">{{strtoupper($report->slot_no)}}</td>
								<td class="text-center">{{strtoupper($report->slot_upline_no)}}</td>
								<td class="text-center">{{date('m/d/y',strtotime($report->transaction_date_created))}}</td>
								<td class="text-center">{{$report->transaction_number}}</td>
								<td class="text-center">{{$report->quantity}}</td>
								<td class="text-center">{{$report->item_name}}</td>
								<td class="text-center">{{$report->customer_street}}</td>
								<td class="text-center">{{$report->customer_zipcode}}</td>
								<td class="text-center">{{$report->customer_city}}</td>
								<td class="text-center">{{$report->customer_state}}</td>

								<td class="text-center">{{$report->checkout_id}}</td>
								@if($report->payment_method == 'paymaya')
								<td class="text-center">{{$report->paymaya_response}}</td>
								<td class="text-center">{{$report->paymaya_status}}</td>

								@elseif($report->payment_method == 'dragonpay')
								<td class="text-center">{{$report->dragonpay_response}}</td>
								@endif
							</tr>
							@endforeach
						@else
						<tr><td colspan="22"><h3 class="text-center">No Transaction</h3></td></tr>
						@endif							
					</tbody>
				</table>
			</div>
			<h5 class="text-center">---- {{$now or ''}} ----</h5>
		</div>
	</div>
</div>

<style type="text/css">
	tr { page-break-inside: avoid; }
</style>