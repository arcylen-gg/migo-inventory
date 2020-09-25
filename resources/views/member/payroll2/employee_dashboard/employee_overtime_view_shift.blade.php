<div class="modal-header">
    <h5 class="modal-title">{{ $page }}</h5>
    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
    <span aria-hidden="true">&times;</span>
    </button>
</div>
<div class="modal-body form-horizontal">
	<div class="form-group">
		<div class="col-md-12">
			<table class="table table-condensed table-bordered">
				<thead>
					<tr>
						<th rowspan="2" valign="center" class="text-center">Day</th>
						<th rowspan="2" valign="center" class="text-center">Working Hours</th>
						<th colspan="2" class="text-center">Work Schedule</th>
						<th class="text-center"></th>
						<th rowspan="2" class="text-center">Flexi Time</th>
						<th rowspan="2" valign="center" class="text-center">Break Hours</th>
						<th rowspan="2" class="text-center">Rest Day</th>
						<th rowspan="2" class="text-center">Extra Day</th>
					</tr>
					<tr>
						<th class="text-center">Start</th>
						<th class="text-center">End</th>
						<th></th>
					</tr>
				</thead>
				<tbody>
					@foreach($shift as $day)
						<tr>
							<td>
								{{ $day->shift_day }}
							</td>
							<td>
								{{ $day->shift_target_hours }}
							</td>
						</tr>
					@endforeach

					@foreach($shift_time as $time)
						<tr>
							<td>
								{{ $time->shift_work_start }}
							</td>
							<td>
								{{ $time->shift_work_end }}
							</td>
						</tr>
					@endforeach

				</tbody>
			</table>
		</div>
	</div>
</div>
