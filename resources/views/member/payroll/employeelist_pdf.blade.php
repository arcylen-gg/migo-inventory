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


</head>
<body>
<div class="tab-content tab-pane-div padding-top-10">
  <div id="active-employee-tab" class="tab-pane fade in active">
    <div class="form-horizontal">

      <div class="form-group">
        <div class="col-md-12" id="active-employee">
          <div class="load-data" target="value-id-1">
            <div id="value-id-1">
              <table class="table table-condensed table-bordered">
                <thead>
                  <tr>
                    <th>Employee No</th>
                    <th>Employee Name</th>
                    <th>Employee Company</th>
                    <th>Department</th>
                    <th>Position</th>
                  </tr>
                </thead>
                @foreach($_active as $active)
                <tr>
                  <td>
                    {{$active->payroll_employee_number}}
                  </td>
                  <td>
                    {{$active->payroll_employee_last_name}}, {{$active->payroll_employee_first_name}} {{ substr($active->payroll_employee_middle_name, 0, -(strlen($active->payroll_employee_middle_name))+1) }}.
                  </td>
                  <td>
                    {{$active->payroll_company_name}}
                  </td>
                  <td>
                    {{$active->payroll_department_name}}
                  </td>
                  <td>
                    {{$active->payroll_jobtitle_name}}
                  </td>
                </tr>
                @endforeach
              </table>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
</body>
</html>