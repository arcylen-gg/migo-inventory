<!doctype html>
<!--[if lt IE 7]>      <html class="no-js lt-ie9 lt-ie8 lt-ie7" lang=""> <![endif]-->
<!--[if IE 7]>         <html class="no-js lt-ie9 lt-ie8" lang=""> <![endif]-->
<!--[if IE 8]>         <html class="no-js lt-ie9" lang=""> <![endif]-->
<!--[if gt IE 8]><!--> <html class="no-js" lang=""> <!--<![endif]-->
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <base href="{{ URL::to('/') }}">
    <meta name="description" content="">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- <link rel="apple-touch-icon" href="apple-touch-icon.png"> -->
    <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,400,600,700,800" rel="stylesheet">
      <link href="/assets/employee/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <!--<link rel="stylesheet" href="/assets/initializr/css/main.css">-->
    <link rel="stylesheet" href="/assets/front/css/global.css">

    <style type="text/css">
      td
      {
        padding: 5px !important;
        font-size: 10px;
        line-height: 11px;
      }


      /*div.breakNow { page-break-inside:avoid; page-break-after:always; }*/
    </style>
</head>
<body>
<div>
    <h6 style="font-weight: bold;text-align: center;font-size: 14px;">PHILIPPINE ARCHIPELAGO PORTS AND TERMINAL SERVICES, INC.<br>OVERTIME AUTHORIZATION</h6>
    <br>
    <br>
<h6 style="font-size: 12px;">Name:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<span style="font-weight: bold">{{$request_info['payroll_employee_display_name']}}</span></h6>
<h6 style="font-size: 12px;margin-left:400px;margin-top: -100px;" class="pull-right">Area:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<span style="font-weight: bold">{{$employee['payroll_department_name']}}</span></h6>
<br>
<h6 style="font-size: 12px;margin-top: -35px;">Position:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<span style="font-weight: bold">{{$employee['payroll_jobtitle_name']}}</span></h6>
<h6 style="font-size: 12px;margin-left:400px;" class="pull-right">Cut-off Date: </h6>
<br>
<h6 style="font-size: 12px;margin-top: -35px;" >Purpose of Overtime:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<span style="font-weight: bold">{{ $request_info['payroll_request_overtime_remark'] }}</span></h6>
<br>
<table class="table table-bordered" style="font-size:12px;">
    <thead>
        <tr>
            <th style="text-align: center;font-weight: bold;border-bottom-color: white !important;">DATE OF OVERTIME</th>
            <th style="text-align: center;font-weight: bold;border-bottom-color: white !important;">REASON OF OVERTIME</th>
            <th colspan="2" style="text-align: center;font-weight: bold;">REGULAR TIME</th>
            <th colspan="2" style="text-align: center;font-weight: bold;">OVERTIME</th>
            <th style="text-align: center;font-weight: bold;border-bottom-color: white !important;">TYPE OF OVERTIME</th>
            <th style="text-align: center;font-weight: bold;border-bottom-color: white !important;">TOTAL HOURS OF OVERTIME</th>
        </tr>
        <tr>    
            <th style="border-top-color: white !important;"></th>
            <th style="border-top-color: white !important;"></th>
            <th style="text-align: center;font-weight: bold;">IN</th>
            <th style="text-align: center;font-weight: bold;">OUT</th>
            <th style="text-align: center;font-weight: bold;">IN</th>
            <th>OUT</th>
            <th style="border-top-color: white !important;"></th>
            <th style="border-top-color: white !important;"></th>
        </tr>
    </thead>
    <tbody>
                  <tr>
                    <td style="text-align: center;font-weight: bold;">{{ date('F d, Y',strtotime($request_info['payroll_request_overtime_date'])) }}</td>
                    <td  style="text-align: center;font-weight: bold;">{{ $request_info['payroll_request_overtime_remark'] }}</td>
                    <td  style="text-align: center;font-weight: bold;">{{ date('h:i A',strtotime($request_info['payroll_request_regular_time_in'])) }}</td>
                    <td  style="text-align: center;font-weight: bold;">{{ date('h:i A',strtotime($request_info['payroll_request_regular_time_out'])) }}</td>
                    <td  style="text-align: center;font-weight: bold;">{{ date('h:i A',strtotime($request_info['payroll_request_overtime_time_in'])) }}</td>
                    <td  style="text-align: center;font-weight: bold;">{{ date('h:i A',strtotime($request_info['payroll_request_overtime_time_out'])) }}</td>
                    <td  style="text-align: center;font-weight: bold;">{{ $request_info['payroll_request_overtime_type']}}</td>
                    <td  style="text-align: center;font-weight: bold;">{{ date('H:i',strtotime($request_info['payroll_request_overtime_total_hours'])) }}</td>
                </tr>
                <tr>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                </tr>
                     <tr>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                </tr>
                <tr>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                </tr>
                <tr>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                </tr>
                 <tr>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                </tr>
                <tr>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                </tr>
                <tr>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                </tr>
                 <tr>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                </tr>
                <tr>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                </tr>
                <tr>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                </tr>
                     <tr>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                </tr>
                <tr>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                </tr>
                <tr>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                </tr>
                <tr>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                </tr>
    </tbody>
</table>
<br>
<br>
@php
$count = 1;
$count1 = "Checked by:";
$count2 = "Verified by:";
$count3 = "Noted by:";
$count4 = "Approved by:";
@endphp
        @foreach($approver_info as $approver)
            
            <div style="float-left;display: inline;">
            <h5 style="font-size: 10px;margin-bottom: 10px;">@if($count == 1){{$count1}}@elseif($count == 2){{$count2}}@elseif($count == 3){{$count3}}@elseif($count == 4){{$count4}}@endif</h5>
            <h5 style="font-size: 10px;float-left;display: inline;">_______________________________________</h5>
            <h5 style="font-size:10px;float-left;display: inline;">{{$approver['payroll_employee_display_name']}}<br>{{$approver['payroll_jobtitle_name']}}</h5>
            </div>
            <br>
            @php
            $count++
            @endphp
        @endforeach
</div>

</div>
</body>
</html>