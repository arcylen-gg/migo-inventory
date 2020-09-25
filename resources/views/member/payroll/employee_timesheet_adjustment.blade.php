<form class="form-horizontal over-time-form" method="post">
    <input type="hidden" class="field-hidden-date" value="{{ $timesheet_info->payroll_time_date }}">
    <input type="hidden" class="field-hidden-employee-id" value="{{ $timesheet_info->payroll_employee_id }}">

    <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">×</button>
        <h4 class="modal-title">TIME RECORD APPROVAL</h4>
    </div>
    <div class="modal-body clearfix">

        <!-- TABLE TIME IN AND OUT -->
            <input class="ot-token" type="hidden" name="_token" value="{{ csrf_token() }}">
            <h4>TIME RECORD</h4>
            <table class="table table-condensed table-bordered">
                <thead>
                    <tr>
                        <th>TIME IN</th>
                        <th>TIME OUT</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($_time_record as $key => $time_record)
                    <tr>
                        <td><input name="time_in[{{ $time_record->payroll_time_sheet_record_id }}]" placeholder="NO TIME" class="over-time-entry form-control" time_max="11:59 PM" time_min="12:01:00 AM" {{ $time_record->time_in_enabled==true ? ''  : '' }}  type="text" value="{{ $time_record->time_in }}"></td>
                        <td><input name="time_out[{{ $time_record->payroll_time_sheet_record_id }}]" placeholder="NO TIME" class="over-time-entry form-control" time_max="11:59 PM" time_min="12:01:00 AM" {{ $time_record->time_out_enabled==true ? ''  : '' }}  type="text"  value="{{ $time_record->time_out }}"></td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </form>

        <!-- TABLE TIME IN AND OUT -->
        <h4>SUMMARY HOURS</h4>
        <table class="table table-condensed table-bordered">
            <thead>
                <tr>
                    <th></th>
                    <th class="text-center">ACTUAL</th>
                    <th class="text-center">APPROVED</th>
                    <th></th>
                    <th class="text-center">ACTUAL</th>
                    <th class="text-center">APRROVED</th>
                </tr>
            </thead>
            <tbody>

                <tr>
                    <td class="text-right">REGULAR</td>
                    <td width="60px" s="p1" class="text-center old_regular_hours time-summary-adjustment old">00:00</td>
                    <td width="60px" s="p1" class="text-center new_regular_hours time-summary-adjustment new">00:00</td>
                    <td class="text-right">NIGHT DIFF</td>
                    <td width="60px" s="p2" class="text-center old_night_differential time-summary-adjustment old">00:00</td>
                    <td width="60px" s="p2" class="text-center new_night_differential time-summary-adjustment new">00:00</td>
                </tr>
                <tr>
                    <td class="text-right">EARLY OT</td>
                    <td width="60px" s="p3" class="text-center old_early_overtime time-summary-adjustment old">00:00</td>
                    <td width="60px" s="p3" class="text-center new_early_overtime time-summary-adjustment new">00:00</td>
                    <td class="text-right">REGULAR OT</td>
                    <td width="60px" s="p4" class="text-center old_late_overtime time-summary-adjustment old">00:00</td>
                    <td width="60px" s="p4" class="text-center new_late_overtime time-summary-adjustment new">00:00</td>
                </tr>
                <tr>
                    <td class="text-right">EXTRA DAY</td>
                    <td width="60px" s="p5" class="text-center old_extra_day time-summary-adjustment old">00:00</td>
                    <td width="60px" s="p5" class="text-center new_extra_day time-summary-adjustment new">00:00</td>
                    <td class="text-right">REST DAY</td>
                    <td width="60px" s="p6" class="text-center old_rest_day time-summary-adjustment old">00:00</td>
                    <td width="60px" s="p6" class="text-center new_rest_day time-summary-adjustment new">00:00</td>
                </tr>
                <tr>
            </tbody>
        </table>
    </div>
    <div class="modal-footer">
        <button type="button" class="btn btn-def-white btn-custom-white" data-dismiss="modal">Close</button>
        <button type="button" class="btn btn-def-white btn-custom-white cancel-approve">
            <span class="cancel-approve-span"><i class="fa fa-times color-red" aria-hidden="true"></i></span>
            <span>Cancel Approve</span>
        </button>
        <button class="btn btn-primary btn-custom-primary submit-overtime-form" type="submit">
            
            <span class="approve-span"><i class="adjust-form-icon fa fa-check"></i></span>
            &nbsp; Confirm Approve Time
        </button>
    </div>
</form>