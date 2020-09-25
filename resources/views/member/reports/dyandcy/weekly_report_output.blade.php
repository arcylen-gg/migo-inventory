<div class="report-container">
	<div class="panel panel-default panel-block panel-title-block panel-report load-data">
		<div class="panel-heading load-content">
			@include('member.reports.report_header')
			<div class="table-reponsive">
				<table class="table table-condensed collaptable">
					<thead style="text-transform: uppercase">
						<tr>
							<th class="text-center">DATE</th>
							<th class="text-center">NET GROSS</th>
							<th class="text-center">TOTAL NET INCOME</th>
						</tr>
					</thead>
					<tbody class="{{$totalgross = 0}} {{$totalnetincome = 0}}">
						@if(count($_weeklysales) > 0)
							@foreach($_weeklysales as $item)
							<tr>
								<td class="text-center">{{date('F d, Y',strtotime($item['salesdate']))}}</td>
								<td class="text-right" {{$totalgross += $item['sales_gross']}}>{{currency('',$item['sales_gross'])}}</td>
								<td class="text-right" {{$totalnetincome += $item['sales_netincome']}}>{{currency('',$item['sales_netincome'])}}</td>
							</tr>
							@endforeach
							<tr>
								<td class="text-center">TOTAL</td>
								<td class="text-right">{{currency('', $totalgross)}}</td>
								<td class="text-right">{{currency('', $totalnetincome)}}</td>
							</tr>
						@else
						<tr><td colspan="6" class="text-center"> NO TRANSACTION YET</td></tr>
						@endif
					</tbody>
				</table>
			</div>
		</div>
	</div>
</div>