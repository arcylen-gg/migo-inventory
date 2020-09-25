@extends('member.layout')
@section('content')
 <input type="hidden" class="_token" value="{{ csrf_token() }}" />
<div class="panel panel-default panel-block panel-title-block" id="top">
    <div class="panel-heading">
        <div>
            <i class="fa fa-tags"></i>
            <h1>
                <span class="page-title">Payroll Reports &raquo; Man Power Report </span>
                <small>
                </small>
            </h1>
        </div>
    </div>
</div>

<div class="panel panel-default panel-block panel-title-block">
    <div class="panel-body form-horizontal">
        
         <div>
            <select class="select-company-name pull-right form-control" style="width: 300px">    
                <option value="0">All Company</option>
                  @foreach($_company as $company)
                  <option value="{{$company['company']->payroll_company_id}}">{{$company['company']->payroll_company_name}}</option> 
                    @foreach($company['branch'] as $branch)
                        <option value="{{$branch->payroll_company_id}}">&nbsp;&nbsp;• {{$branch->payroll_company_name}}</option>
                    @endforeach
                  @endforeach
            </select>
        </div>

        <div class="form-group">
            <div class="col-md-3 pull-right">
                      <a href="/member/payroll/reports/manpower_report_export_excel/0" class="excel_tag"><button type="button" class="btn btn-success pull-right" style="margin-right:20px;"><i class="fa fa-file-excel-o" ></i> &nbsp;EXPORT TO EXCEL</button></a>
            </div>
        </div>

    </div>
</div>
 <div class="text-center" id="spinningLoaders" style="display:none;">
    <img src="/assets/images/loader.gif">
</div>
<div class="load-filter-datas">
<div class="panel panel-default panel-block panel-title-block">
    <div class="panel-body form-horizontal">
        <div class="form-group tab-content panel-body employee-container">
            <div id="all" class="tab-pane fade in active">
                <div class="form-group order-tags"></div>
                <div class="table-responsive">
                    <table class="table table-bordered table-condensed">
                        <thead>
                            <tr>
                                <th>Date Hired</th>
                                <th>Date End</th>
                                <th>Employee Name</th>
                                <th>Position</th>
                                <th>Department</th>
                                <th>Employment Status</th>
                            </tr>
                        </thead>
                          <tbody>
                             @foreach($manpower_info as $manpower)
                            <tr>
                                <td class="text-center">{{$manpower->payroll_employee_contract_date_hired}}</td>
                                <td class="text-center">{{$manpower->payroll_employee_contract_date_end}}</td>
                                <td class="text-center">{{$manpower->payroll_employee_display_name}}</td>
                                <td class="text-center">{{$manpower->payroll_jobtitle_name}}</td>
                                <td class="text-center">{{$manpower->payroll_department_name}}</td>
                                <td class="text-center">{{$manpower->employment_status}}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div> 
    </div>
</div>
</div>
<script>
    var ajaxdata = {};
        $(".select-company-name").on("change", function(e) 
        {
            var href = "/member/payroll/reports/manpower_report_export_excel/"+ $(this).val();
            $(".excel_tag").attr('href',href);

            var company         = $(this).val();
            ajaxdata.company_id    = company;
            ajaxdata._token     = $("._token").val();
            $('#spinningLoaders').show();
            $(".load-filter-datas").hide();
            setTimeout(function(e){
            $.ajax(
            {
                url:"/member/payroll/reports/manpower_report_filter",
                type:"post",
                data: ajaxdata,
                
                success: function(data)
                {
                    $('#spinningLoaders').hide();
                    $(".load-filter-datas").show();
                    $(".load-filter-datas").html(data);
                }
            });
            }, 700);
        });
    
</script>

@endsection
