<form class="global-submit form-horizontal" role="form" action="/member/payroll/company_timesheet_custom_shift_update" method="post">
    {{ csrf_field() }}
    <input type="hidden" name="timesheet_id" value="{{ $timesheet_id }}">
    <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">×</button>
        <h4 class="modal-title">CUSTOM SHIFT FOR {{ date("F d, Y", strtotime($date)) }} OF {{ $employee_info->payroll_employee_first_name }} {{ $employee_info->payroll_employee_last_name }}</h4>
    </div>
    <div class="modal-body form-horizontal">
        <div class="form-group">
            <div class="col-md-12">
                <table class="table table-condensed table-bordered timesheet">
                    <thead>
                        <tr>
                            <th rowspan="2" valign="center" class="text-center">Day</th>
                            <th rowspan="2" valign="center" class="text-center">Working Hours</th>
                            
                            <th colspan="2" class="text-center">Work Schedule</th>
                            <th class="text-center"><i class="fa fa-clock"></i></th>
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
                        @foreach($_day as $key => $day)
                            <tr class="editable main-con main-time" day="{{$day->shift_day}}">
                                <td>
                                    {{ $day->shift_day }}
                                    <input type="hidden" name="day[{{$day->shift_day}}]" value="{{$day->shift_day}}">
                                </td>
                                <td>
                                    <input type="number" name="target_hours[{{$day->shift_day}}]" class="form-control text-center" step="any" value="{{ $day->shift_target_hours }}">
                                </td>
                                <td class="editable">
                                    <input type="text" placeholder="NO TIME" value="{{ (isset($day->time_shift[0]) == '' ? '' : $day->time_shift[0]->shift_work_start) }}" name="work_start[{{$day->shift_day}}][]" class="text-table time-entry in" >
                                </td>
                                <td class="editable">
                                    <input type="text" placeholder="NO TIME" value="{{ (isset($day->time_shift[0]) == '' ? '' : $day->time_shift[0]->shift_work_end) }}" name="work_end[{{$day->shift_day}}][]" class="text-table time-entry out" >
                                </td>
                                <td>
                                    <button type="button" class="btn btn-default add-sub-time"><i class="fa fa-plus"></i></button>
                                </td>
                                <td class="text-center">
                                    <input type="checkbox" name="flexitime_day_{{$day->shift_day}}" class="flexitime-check" value="1" {{ $day->shift_flexi_time != 0 ? "checked" :  ""}}>
                                </td>
                                <td>
                                    <input type="number" name="break_hours[{{$day->shift_day}}]" class="form-control text-center break_hours hidden" step="any" value="{{ $day->shift_break_hours }}">
                                </td>
                                <td class="text-center">
                                    <input type="checkbox" name="rest_day_{{$day->shift_day}}" class="restday-check" value="1" {{ $day->shift_rest_day != 0 ? "checked" :  ""}}>
                                </td>
                                <td class="text-center">
                                    <input type="checkbox" name="extra_day_{{$day->shift_day}}" class="extraday-check" value="1" {{ $day->shift_extra_day != 0 ? "checked" :  ""}}>
                                </td>
                            </tr>
                            @if($day->time_shift)
                                @foreach($day->time_shift as $x => $timeshift)
                                    @if($x != 0)
                                        <tr class="editable main-con sub-time" day="{{$day->shift_day}}">
                                            <td></td>
                                            <td></td>
                                            <td class="editable">
                                                <input type="text" placeholder="NO TIME" value="{{ $timeshift->shift_work_start }}" name="work_start[{{$day->shift_day}}][]" class="text-table time-entry in" >
                                            </td>
                                            <td class="editable">
                                                <input type="text" placeholder="NO TIME" value="{{ $timeshift->shift_work_end }}" name="work_end[{{$day->shift_day}}][]" class="text-table time-entry out" >
                                            </td>
                                            <td>
                                                <button type="button" class="btn btn-default remove-time-entry"><i class="fa fa-close"></i></button>
                                            </td>
                                            <td class="text-center"></td>
                                            <td class="text-center"></td>
                                            <td class="text-center"></td>
                                        </tr>
                                    @endif
                                @endforeach
                            @endif
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <div class="modal-footer">
        <button type="button" class="btn btn-def-white btn-custom-white" data-dismiss="modal">Close</button>
        <button class="btn btn-primary btn-custom-primary" type="submit"">Submit</button>
    </div>
</form>

<script type="text/javascript" src="/assets/member/payroll/js/shift-template.js"></script>
