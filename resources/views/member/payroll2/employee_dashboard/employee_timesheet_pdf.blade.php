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
        text-align: center;
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
      .timesheet-title
      {
        text-transform:uppercase;
        font-size: 18px;
        font-weight: bold;
        padding-left: 20px;
      }
      .timesheet-record
    {
        text-transform: uppercase;
        text-align: left;
        font-weight: bold;
    }

      /*div.breakNow { page-break-inside:avoid; page-break-after:always; }*/
    </style>
</head>
<body>
    <div style="vertical-align: top; text-align: center;">
        <div class="row">
            <div class="timesheet-title">{{ $period_record->payroll_employee_first_name }} {{ $period_record->payroll_employee_last_name }} - TIME RECORD</div>
        </div>
        <div class="clearfix">   
            <div class="col-md-12">
                 <table class="table table-bordered table-condensed">
                    <thead class="timesheet-thead">
                        <tr>
                            <th colspan="2">Day</th>
                            <th>Time In</th>
                            <th>Time Out</th>
                            <th>Remark / Activity</th>
                            <th>Shift</i></th>
                            <th>Source</th>
                            <th>Branch</th>
                            <th>{{ $access_salary_rates == 1 ? 'Rates':'Time Spent'}}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($_timesheet as $timesheet)
                        <tr>
                            <td style="font-weight: bold;">{{ $timesheet->day_number}}</td>
                            <td style="font-weight: bold;">{{ $timesheet->day_word}}</td>
                            <td>
                            @if($timesheet->record)
                                @foreach($timesheet->record as $key => $record)
                                <label >{{ $record->time_sheet_in }}</label>
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

                            <td>
                            @if($timesheet->record)
                                @foreach($timesheet->record as $key => $record)
                                    <label>{{ ($record->time_sheet_activity == '' ? $timesheet->default_remarks : $record->time_sheet_activity) }}</label>
                                @endforeach
                            @else 
                                <label>{{ $timesheet->default_remarks }}</label>
                            @endif
                            </td style="font-weight: bold;">
                           
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
            <div class="col-md-7 pull-right" >
                <table style="width: 100%;" class="table table-bordered">
                    <tbody>
                    @foreach($period_record->cutoff_breakdown->_time_breakdown as $key => $time_breakdown)
                      <tr>
                          <td class="timesheet-record">{{ str_replace("_"," ",$key) }}</td>
                          <td class="text-right" style="font-weight: bold;">{{ $time_breakdown["time"] }}</td>
                      </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</body>
</html>