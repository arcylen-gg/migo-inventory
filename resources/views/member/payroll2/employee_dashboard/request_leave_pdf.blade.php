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
   

      /*div.breakNow { page-break-inside:avoid; page-break-after:always; }*/
    </style>
</head>
<body>
<div>

 <table class="table table-bordered" style="font-size:12px;border:2px solid black !important;">
            <thead>
              <tr>
                <th colspan="5" style="text-align: center;">PHILIPPINE ARCHIPELAGO PORTS AND TERMINAL SERVICES, INC.</th>
              </tr>
              <tr>
                 <th colspan="5" style="text-align: center;">Request For Leave Form</th>
              </tr>
            </thead>
            <tbody class="tbl-tags">
              <tr>
                    <td colspan="3">Name of employee:&nbsp;&nbsp;&nbsp;<span style="font-weight: bold;">{{$leave_info[0]->payroll_employee_display_name}}</span></td>
                    <td colspan="2">Date filed:&nbsp;&nbsp;&nbsp;<span style="font-weight: bold;">{{$leave_info[0]->payroll_request_leave_date_filed}}</span></td>
              </tr>
               <tr>
                    <td colspan="3">Department:&nbsp;&nbsp;&nbsp;<span style="font-weight: bold;">{{$department}}</span></td>
                    <td colspan="2">Date received by HR:</td>
              </tr>
              <tr>
                 <td ></td>
                 <td><strong style="font-weight: bold;">Type of leave: </strong></td>
                 <td><strong style="font-weight: bold;">Start date:  </strong></td>
                 <td><strong style="font-weight: bold;">End date:  </strong></td>
                 <td rowspan="6"><span style="position: absolute !important;top: 250px !important;">Reason:&nbsp;&nbsp;&nbsp;<span style="font-weight: bold;">{{$leave_info[0]->payroll_request_leave_remark}}</span></span></td>
              </tr>      
              <tr>
                 <td></td>
                 <td>Vacation Leave (VL)</td>
                 @if($leave_info[0]->payroll_request_leave_type == 'Vacation Leave')
                 <td><span style="font-weight: bold;">{{$leave_info[0]->payroll_request_leave_date}}</span></td>
                 <td><span style="font-weight: bold;">{{$leave_info[0]->payroll_request_leave_date}}</span></td>
                 @else
                 <td></td>
                 <td></td>
                @endif
              </tr>  
               <tr>
                 <td></td>
                 <td>Sick Leave (SL)</td>
                 @if($leave_info[0]->payroll_request_leave_type == 'Sick Leave')
                 <td><span style="font-weight: bold;">{{$leave_info[0]->payroll_request_leave_date}}</span></td>
                 <td><span style="font-weight: bold;">{{$leave_info[0]->payroll_request_leave_date}}</span></td>
                 @else
                 <td></td>
                 <td></td>
                @endif
              </tr> 
               <tr>
                 <td></td>
                 <td>Off-set</td>
                 <td></td>
                 <td></td>
              </tr>  
               <tr>
                 <td></td>
                 <td>Absent without pay</td>
                 <td></td>
                 <td></td>
              </tr>
               <tr>
                 <td></td>
                 <td>Bereavement</td>
                 <td></td>
                 <td></td>
              </tr>
               <tr>
                 <td></td>
                 <td>Maternity</td>
                 @if($leave_info[0]->payroll_request_leave_type == 'Maternity Leave')
                 <td><span style="font-weight: bold;">{{$leave_info[0]->payroll_request_leave_date}}</span></td>
                 <td><span style="font-weight: bold;">{{$leave_info[0]->payroll_request_leave_date}}</span></td>
                 <td rowspan="4"><span style="padding-bottom: 150px !important;">Reliever:&nbsp;&nbsp;&nbsp;<span style="font-weight: bold;">{{$leave_reliever[0]->payroll_employee_display_name}}</span></span></td>
                 @else
                 <td></td>
                 <td></td>
                  <td rowspan="4"><span style="padding-bottom: 50px;">Reliever:&nbsp;&nbsp;&nbsp;<span style="font-weight: bold;">{{$leave_reliever[0]->payroll_employee_display_name}}</span></span></td>
                @endif
              </tr> 
               <tr>
                 <td></td>
                 <td>Paternity</td>
                 @if($leave_info[0]->payroll_request_leave_type == 'Paternity Leave')
                 <td><span style="font-weight: bold;">{{$leave_info[0]->payroll_request_leave_date}}</span></td>
                 <td><span style="font-weight: bold;">{{$leave_info[0]->payroll_request_leave_date}}</span></td>
                 @else
                 <td></td>
                 <td></td>
                @endif
              </tr>  
              <tr>
                 <td></td>
                 <td>Solo-parent leave</td>
                 <td></td>
                 <td></td>
              </tr>
               <tr>
                 <td></td>
                 <td>Others (pls. specify)</td>
                 <td></td>
                 <td></td>
              </tr>
                <tr>
                 <td colspan="5" style="padding-top: 50px;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Signature of employee<span style="margin-left: 450px;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Signature of immediate supervisor</span></td>
              </tr>
                <tr>
                 <td colspan="5" style="text-align: center;font-weight: bold;"><strong><i>Please do not write anything below this line. To be filled out only by the HR and approving Department</i></strong></td>
              </tr>
               <tr>
                 <td colspan="5" style="font-weight: bold;"><strong>Status of leaves:</strong></td>
              </tr>
                <tr>
                 <td>Available SL:</td>
                 <td>&nbsp;&nbsp;&nbsp;<span style="font-weight: bold;">{{$sickleave[0]->payroll_leave_temp_hours}}</span></td>
                 <td>Available VL:</td>
                  <td>&nbsp;&nbsp;&nbsp;<span style="font-weight: bold;">{{$vacationleave[0]->payroll_leave_temp_hours}}</span></td>
                 <td rowspan="2"></td>
              </tr>
                <tr>
                 <td>Less SL applied for:</td>
                 <td>&nbsp;&nbsp;&nbsp;<span style="font-weight: bold;">@if($leave_info[0]->payroll_request_leave_type == 'Sick Leave'){{$leave_info[0]->consume}}@endif</span></td>
                 <td>Less VL applied for:</td>
                 <td>&nbsp;&nbsp;&nbsp;<span style="font-weight: bold;">@if($leave_info[0]->payroll_request_leave_type == 'Vacation Leave'){{$leave_info[0]->consume}}@endif</span></td>
              </tr>
                <tr>
                 <td>Remaing SL:</td>
                 <td>&nbsp;&nbsp;&nbsp;<span style="font-weight: bold;">@if($sickleave[0]->total_leave_consume == null){{$sickleave[0]->payroll_leave_temp_hours}}@else{{$sickleave[0]->remaining_leave}}@endif</span></td>
                 <td>Remaining VL:</td>
                 <td>&nbsp;&nbsp;&nbsp;<span style="font-weight: bold;">@if($vacationleave[0]->total_leave_consume == null){{$vacationleave[0]->payroll_leave_temp_hours}}@else{{$vacationleave[0]->remaining_leave}}@endif</span></td>
                 <td style="text-align: center;"><strong>HR Assistant</strong></td>
              </tr>
                    <tr>
                 <td colspan="5" style="padding-top: 50px;text-align: center;">Approved by/Date</td>
              </tr>


            </tbody>
    </table>
</div>
</body>
</html>