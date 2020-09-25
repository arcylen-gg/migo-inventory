<!doctype html>
<!--[if lt IE 7]>      <html class="no-js lt-ie9 lt-ie8 lt-ie7" lang=""> <![endif]-->
<!--[if IE 7]>         <html class="no-js lt-ie9 lt-ie8" lang=""> <![endif]-->
<!--[if IE 8]>         <html class="no-js lt-ie9" lang=""> <![endif]-->
<!--[if gt IE 8]><!--> <html class="no-js" lang=""> <!--<![endif]-->
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
        <base href="{{ URL::to('/') }}">
        <title>Digima House</title>
        <meta name="description" content="">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <!-- <link rel="apple-touch-icon" href="apple-touch-icon.png"> -->
        <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,400,600,700,800" rel="stylesheet">
        <link rel="stylesheet" href="/assets/initializr/css/bootstrap.min.css">
        <link rel="stylesheet" href="/assets/initializr/css/bootstrap-theme.min.css">
        <!--<link rel="stylesheet" href="/assets/initializr/css/main.css">-->
        {{-- <link rel="stylesheet" href="/assets/front/css/global.css"> --}}
        <style type="text/css">
          td
          {
            padding: 5px !important;
            font-size: 10px;
            line-height: 11px;
          }
          .payslip-wrapper
          {
            page-break-inside: avoid; 
            /*width: 48.5%; */
            padding: 10px; 
            border: 1px solid #bbb; 
            /*display: inline-block; */
            vertical-align: top; 
            top: 0; 
            background-color: #fff; 
            margin: 5px;
            /*float: left;*/
          }
          .col-md-6
          {
            padding: 0;
          }
          td
          {
            text-align: center;
          }
          tr
          {
            font-size: 12px;
          }

          /*div.breakNow { page-break-inside:avoid; page-break-after:always; }*/
        </style>
    </head>
    <body>

    <div style="vertical-align: top;">
        <div class="clearfix">  
            <div class="col-md-12"><b>TIME SHEET</b> &raquo; 
                {{ $employee_info->payroll_employee_first_name }} {{ $employee_info->payroll_employee_last_name }} (Employee No. {{ $employee_info->payroll_employee_number == "" ? "00" : $employee_info->payroll_employee_number }})</h4>
            </div>
            <div class="col-md-12">
            {{ $show_period_start }} - {{  $show_period_end }}
            </div>
        </div>
        <br>
        <div class="clearfix">
            <div class="col-md-12">
                <div class="table-responsive">
                    <table class="table table-bordered table-condensed timesheet table-timesheet timesheet-of-employee">
                        <thead style="text-transform: uppercase">
                            <tr>
                                <th class="text-center" colspan="2">Day</th>
                                <th class="text-center" width="100px">Time In</th>
                                <th class="text-center" width="100px">Time Out</th>
                                <th class="text-center">Remark / Activity</th>
                                <th class="text-center" width="80px;">Shift</i></th>
                                <th class="text-center" width="150px;">Source</th>
                                <th class="text-center" width="150px;">Branch</th>
                                <th width="150px" class="text-center">{{ $access_salary_rates == 1 ? 'Rates':'Time Spent'}}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($_timesheet as $timesheet)
                                @if($timesheet->record)
                                    @foreach($timesheet->record as $x => $record)
                                        <?php $random_integer[$x] = rand (10000000, 999999999); ?>
                                    @endforeach
                                @endif
                                
                                <?php $random_integer_for_blank = rand (10000000, 999999999); ?>

                                <tr class="tr-parent" date="{{ $timesheet->date }}" timesheet_id="{{ $timesheet->payroll_time_sheet_id }}">
                                    {{ csrf_field() }}
                                    <input type="hidden" name="date" value="{{ $timesheet->date }}"/>
                                    <td class="text-center" width="50px" style="font-weight: bold;">{{ $timesheet->day_number }}</td>
                                    <td class="text-center" width="50px" style="font-weight: bold;">{{ $timesheet->day_word }}</td>
                                    <td>
                                    @if($timesheet->record)
                                        @foreach($timesheet->record as $key => $record)
                                        <label>{{ $record->time_sheet_in }}</label>
                                        @endforeach
                                    @else
                                        <label>No Time</label>
                                    @endif
                                    </td>

                                    <td>
                                    @if($timesheet->record)
                                        @foreach($timesheet->record as $key => $record)
                                        <label>{{ $record->time_sheet_out }}</label>
                                        @endforeach
                                    @else
                                        <label>No Time</label>
                                    @endif
                                    </td>

                                    <td style="text-transform: uppercase;">
                                    @if($timesheet->record)
                                        @foreach($timesheet->record as $key => $record)
                                            <label>{{ ($record->time_sheet_activity == '' ? $timesheet->default_remarks : $record->time_sheet_activity) }}</label>
                                        @endforeach
                                    @else 
                                        <label>{{ $timesheet->default_remarks }}</label>
                                    @endif
                                    </td>
                                   
                                    @if($timesheet->custom_shift == 1)
                                        <td style="font-weight: bold;">Custom</td>
                                    @else
                                        <td style="font-weight: bold;">Default</td>
                                    @endif
                                    
                                    <td>
                                    @if($timesheet->record)
                                        @foreach($timesheet->record as $key => $record)
                                            <label>{{ $record->source }}</label>
                                        @endforeach
                                    @else 
                                        <label>Manually Encoded</label>
                                    @endif
                                    </td>

                                    <td>
                                    @if($timesheet->record)
                                        @foreach($timesheet->record as $key => $record)
                                            <label>{{ $record->branch }}</label>
                                        @endforeach
                                    @else 
                                        <label>New</label>
                                    @endif
                                    </td>

                                    <td>{!! $timesheet->daily_info->value_html !!}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>   


    <div class="modal-body clearfix">
        <div class="text-center text-bold" style="font-size: 20px; color: #1682ba">PERFORMANCE SUMMARY</div>
        <div class="col-md-12" style="text-align: left; font-weight: normal; margin-bottom: 10px; font-size: 16px;"></div>
        <div class="clearfix">
            <div class="col-md-12">
                <div class="table-responsive">
                    <table class="table table-condensed timesheet table-timesheet">
                        <thead style="text-transform: uppercase">
                            <tr>
                                <th class="text-right"></th>
                                <th class="text-center" width="100px">TOTAL TIME</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($compute_cutoff["cutoff_breakdown"]->_time_breakdown as $key => $time)
                            <tr>
                                <td class="text-right text-bold">{{ code_to_word($key) }}</td>
                                <td class="text-center ">{{ $time["time"] }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    </body>
</html>

