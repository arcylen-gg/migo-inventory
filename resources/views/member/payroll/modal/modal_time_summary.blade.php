<div class="form-horizontal">
	<div class="modal-header">
		<button type="button" class="close" data-dismiss="modal">×</button>
		<h4 class="modal-title">{{$header}}</h4>
	</div>
	<div class="modal-body">
		<div class="form-group">
			<div class="col-md-12">
				<span>{{$period}}</span>
			</div>
		</div>
		<div class="form-group">
			<div class="col-md-12 no-more-tables over-flow height-30-em">
				<table class="table table-bordered table-condensed c">
					<thead class="cf">
						<tr>
							<th width="50">Employee Name</th>
							@foreach($_date as $date)
							<th class="text-center">
								<p>{{date('D', strtotime($date))}}</p>
								<p class="color-gray">{{date('d', strtotime($date))}}</p>
							</th>
							@endforeach
							<th valign="middle">Total</th>
						</tr>
					</thead>
					<tbody>
						@foreach($_employee as $employee)
						<tr>
							<td data-title="	" >
								{{$employee['employee']->payroll_employee_display_name}}
							</td>
							@foreach($employee['time'] as $key=> $time)
							<td class="{{$time == '00:00' ? 'color-gray':''}}" data-title="{{date('D', strtotime($_date[$key]))}}">
								{{$time}}
							</td>
							@endforeach
							<td>
								{{$employee['total_time']}}
							</td>
						</tr>
						@endforeach
					</tbody>
				</table>
			</div>
		</div>
	</div>
	<div class="modal-footer">
		<button type="button" class="btn btn-def-white btn-custom-white" data-dismiss="modal">Close</button>
		<button class="btn btn-primary btn-custom-primary" type="button"">Submit</button>
	</div>
</div>