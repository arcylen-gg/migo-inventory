<div class="modal-header">
	<button type="button" class="close" data-dismiss="modal">×</button>
	<h4 class="modal-title">Holiday for {{date('F d, Y', strtotime($_holiday[0]->payroll_holiday_date))}}</h4>
</div>
<div class="modal-body clearfix">
	<table class="table table-bordered table-condensed">
		<thead>
			<tr>
				<th>Holiday Name</th>
				<th>Category</th>
			</tr>
		</thead>
		<tbody>
			@foreach($_holiday as $holiday)
			<tr>
				<td>
					{{$holiday->payroll_holiday_name}}
				</td>
				<td>
					{{ucfirst($holiday->payroll_holiday_category)}}
				</td>
			</tr>
			@endforeach
		</tbody>
	</table>
</div>
<div class="modal-footer">
	<button type="button" class="btn btn-def-white btn-custom-white" data-dismiss="modal">Close</button>
</div>