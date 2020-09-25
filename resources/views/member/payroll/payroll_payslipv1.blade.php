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
            vertical-align: top; -
            top: 0; 
            background-color: #fff; 
            margin: 5px;
            /*float: left;*/
          }
          .col-md-6
          {
            padding: 0;
          }

          /*div.breakNow { page-break-inside:avoid; page-break-after:always; }*/
        </style>
    </head>
    <body>

    <div style="vertical-align: top; text-align: center;">
      <div class="clearfix">
     
        
          @foreach($_employee as $key => $employee)
          <div class="col-md-6">
              <div class="payslip-wrapper page">
                <div class="main-content-holder">
                  <div class="row" >
                    <div class="col-md-12 text-center" style="font-weight: bold; font-size: 16px;">{{ strtoupper($company->payroll_company_name) }}</div>
                    <div class="col-md-12" style="margin-top: 10px;">
                        <div>{{ $employee->payroll_employee_last_name }}, {{ $employee->payroll_employee_first_name }} {{ $employee->payroll_employee_middle_name }}</div>
                        <div>{{ $show_period_start }} - {{ $show_period_end }}</div>
                        @if($show_release_date != 'not specified')
                        <div>Release Date: {{ $show_release_date }}</div>
                        @endif
                    </div>
                  </div>

                  <div class="row" style="margin-top: 20px; text-align: left;">
                      <div class="col-md-12">
                          <table style="width: 100%;" class="table table-bordered">
                              <tbody>
                                  <tr>
                                      <td width="40%" style="font-weight: bold;">BASIC PAY</td>
                                      <td width="30%" style="font-weight: bold;" class="text-right">{{ payroll_currency($employee->net_basic_pay) }}</td>
                                      <td width="30%" style="font-weight: bold;"></td>
                                  </tr>

                                  <!-- ADDITION TO GET GROSS -->
                                  @foreach($employee->cutoff_breakdown->_gross_pay_breakdown as $breakdown)
                                  <tr>
                                      <td>{{ strtoupper($breakdown["label"]) }}</td>
                                      <td class="text-right">{{ payroll_currency($breakdown["amount"]) }}</td>
                                      <td></td>
                                  </tr>
                                  @endforeach

                                  <tr style="font-weight: bold;">
                                      <td style="font-weight: bold;">GROSS SALARY</td>
                                      <td style="font-weight: bold;"></td>
                                      <td style="font-weight: bold;" class="text-right">{{ payroll_currency($employee->gross_pay) }}</td>
                                  </tr>

                                  @foreach($employee->cutoff_breakdown->_taxable_salary_breakdown as $breakdown)
                                    @if($breakdown["add.gross_pay"] == true && $breakdown["deduct.taxable_salary"] == true && $breakdown["add.net_pay"] == true)
                                    @else
                                      <tr>
                                          <td>{{ strtoupper($breakdown["label"]) }}</td>
                                          <td class="text-right">{{ payroll_currency($breakdown["amount"]) }}</td>
                                          <td></td>
                                      </tr>
                                    @endif
                                  @endforeach



                                  @foreach($employee->cutoff_breakdown->_net_pay_breakdown as $breakdown)
                                    @if($breakdown["add.gross_pay"] == true && $breakdown["deduct.taxable_salary"] == true && $breakdown["add.net_pay"] == true)
                                    @else
                                      <tr>
                                          <td>{{ strtoupper($breakdown["label"]) }}</td>
                                          <td class="text-right">{{ payroll_currency($breakdown["amount"]) }}</td>
                                          <td></td>
                                      </tr>
                                    @endif
                                  @endforeach


                                  <tr>
                                      <td>TOTAL DEDUCTION</td>
                                      <td></td>
                                      <td class="text-right">{{ payroll_currency($employee->total_deduction) }}</td>
                                  </tr>

                                  <tr style="font-weight: bold;">
                                      <td style="font-weight: bold;">TAKE HOME PAY</td>
                                      <td style="font-weight: bold;"></td>
                                      <td style="font-weight: bold;" class="text-right">{{ payroll_currency($employee->net_pay) }}</td>
                                  </tr>

                                  <div>
                                  </div>
                              </tbody>
                          </table>
                      </div>
                  <div class="row" >
                    <div class="col-md-12 text-center" style="font-weight: bold; font-size: 12px;">PERFORMANCE SUMMARY</div>
                  </div>
                      <div class="col-md-12">
                        <table style="width: 100%;" class="table table-bordered">
                              <tbody>
                                  @foreach($employee->cutoff_breakdown->_time_breakdown as $key => $time_breakdown)
                                      <tr>
                                          <td style="text-transform:uppercase">{{ str_replace("_"," ",$key) }}</td>
                                          <td class="text-right">{{ $time_breakdown["time"] }}</td>
                                      </tr>
                                  @endforeach
                              </tbody>
                          </table>

                                        @if(isset($option[0]['per_page']))
                                          @if($option[0]['per_page'] == 0)
                                               <span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;____________&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;____________</span>
                                              <h5 style="font-size: 10px">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Prepared by&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Received by</h5>
                                          @else
                                             <span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;____________&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;____________</span>
                                              <h5 style="font-size: 10px">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Prepared by&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Received by</h5>
                                          @endif
                                        @endif
                
                      </div>
                  </div>
                </div>
              </div>
          </div>
          @endforeach
      </div>
    </div>
    </body>
</html>

