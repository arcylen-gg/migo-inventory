<form class="global-submit" role="form" action="/member/payroll/timesheet/time_sheet_comment_save" method="post">
	<div class="modal-header">
		<button type="button" class="close" data-dismiss="modal">×</button>
		<h4 class="modal-title">{{date('M d, Y', strtotime($date_record->payroll_time_date)).' ( '.date('h:i a', strtotime($time_sheet->payroll_time_sheet_in)).' - '.date('h:i a', strtotime($time_sheet->payroll_time_sheet_out)).' )'}}</h4>
		<input type="hidden" name="_token" value="{{csrf_token()}}">
		<input type="hidden" name="payroll_time" value="{{$time_sheet->payroll_time_sheet_record_id}}">
	</div>
	<div class="modal-body form-horizontal">
		<div class="form-group">
			<div class="col-md-12">
				<small>Remarks</small>
				<textarea class="form-control" placeholder="type your comment here..." rows="5" name="time_sheet_record_remarks">{{$time_sheet->time_sheet_record_remarks}}</textarea>
			</div>
		</div>
	</div>
	<div class="modal-footer">
		<button type="button" class="btn btn-def-white btn-custom-white" data-dismiss="modal">Close</button>
		<button class="btn btn-primary btn-custom-primary" type="submit"">Save</button>
	</div>
</form>