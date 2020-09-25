<div class="report-container">
	<div class="panel panel-default panel-block panel-title-block panel-report load-data">
		<div class="panel-heading load-content">
			@include('member.reports.report_header')
			<div class="table-reponsive">
				<table class="table table-condensed collaptable">
					<thead style="text-transform: uppercase">
						<tr>
							<th>EQP ID</th>
							<th>EQP Name</th>
							<th>Proposal Number</th>
							<th>Issued to</th>
							<th>Issued Date</th>
							<th>Status</th>
						</tr>
					</thead>
					<tbody>
						@if(count($_equipment) > 0)
							@foreach($_equipment as $eqp)
							<tr>
								<td>{{$eqp->record_log_id}}</td>
								<td>{{$eqp->item_name}}</td>
								<td>{{$eqp->number}}</td>
								<td>{{$eqp->issued_to}}</td>
								<td>{{$eqp->issued_date}}</td>
								<td>{{$eqp->status}}</td>
							</tr>
							@endforeach
						@else
						<tr><td colspan="6" class="text-center"> NO TRANSACTION YET</td></tr>
						@endif
					</tbody>
				</table>
			</div>
		</div>
	</div>
</div>