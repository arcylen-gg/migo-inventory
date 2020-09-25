<table class="table table-condensed table-bordered timesheet">
	<thead>
		<tr>
			<th rowspan="2" valign="center" class="text-center">Day</th>
			<th rowspan="2" valign="center" class="text-center">Working Hours</th>
			<th rowspan="2" valign="center" class="text-center">Break Hours</th>
			<th colspan="2" class="text-center">Work Schedule</th>
			<th rowspan="2" class="text-center">Flexitime</th>
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
		@foreach($_day as $key => $day)
		    
			<tr class="editable main-con main-time" day="{{$day->shift_day}}">
				<td class="text-center">
					{{ $day->shift_day }}
				</td>
				<td class="text-center">
					{{ $day->shift_target_hours }}
				</td>
				<td class="text-center">
					{{ $day->shift_break_hours }}
				</td>
				<td class="text-center">
					{{ (isset($day->time_shift[0]) == '' ? 'NO TIME' : date('h:i a', strtotime($day->time_shift[0]->shift_work_start))) }}
				</td>
				<td class="text-center">
					{{ (isset($day->time_shift[0]) == '' ? 'NO TIME' : date('h:i a', strtotime($day->time_shift[0]->shift_work_end))) }}
				</td>
				<td class="text-center">
				    {!!$day->shift_flexi_time == 1 ? '<i class="fa fa-check"></i>':''!!}
				</td>
				<td class="text-center">
				    {!!$day->shift_rest_day == 1 ? '<i class="fa fa-check"></i>':''!!}
				</td>
				<td class="text-center">
				     {!!$day->shift_extra_day == 1 ? '<i class="fa fa-check"></i>':''!!}
					
				</td>
			</tr>
			@if($day->time_shift)
				@foreach($day->time_shift as $x => $timeshift)
					@if($x != 0)
						<tr class="editable main-con sub-time" day="{{$day->shift_day}}">
							<td></td>
							<td></td>
							<td></td>
							<td class="text-center">
								{{ date('h:i a', strtotime($timeshift->shift_work_start)) }}
							</td>
							<td class="text-center">
								{{ date('h:i a', strtotime($timeshift->shift_work_end)) }}
							</td>
							<td class="text-center"></td>
							<td class="text-center"></td>
						</tr>
					@endif
				@endforeach
			@endif
		@endforeach
	</tbody>
</table>