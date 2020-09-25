@extends('member.layout')
@section('content')
<div class="panel panel-default panel-block panel-title-block" id="top">
    <div class="panel-heading">
        <div>
            <i class="fa fa-tags"></i>
            <h1>
                <span class="page-title">Payroll Reports &raquo; Loan Summary</span>
                <small>
                Select employee to view loan summary
                </small>
            </h1>
        </div>
    </div>
</div>

<div class="panel panel-default panel-block panel-title-block">
    <div class="panel-body form-horizontal">
         <div>
            <select class="select-deduction-type pull-right form-control" name="deduction_type" style="width: 300px">    
                <option value="novalue">Select Deduction Type</option>
                <option value="SSS_Loan">SSS Loan</option>
                <option value="HDMF_Loan">HDMF Loan</option>
                <option value="Cash_Bond">Cash Bond</option>
                <option value="Others">Others...</option>
            </select>
        </div>

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

        <div>
            <a href="/member/payroll/reports/loan_summary/loan_summary_report_excel/0/noval" class="excel_tag"><button type="button" class="btn btn-success pull-right" style="margin-right:20px;"><i class="fa fa-file-excel-o" ></i> &nbsp;EXPORT TO EXCEL</button></a>
        </div>
        <div class="form-group tab-content panel-body employee-container">
            <div id="all" class="tab-pane fade in active">
                <div class="form-group order-tags"></div>
                <div class="table-responsive loan-summary-table-load">
                    
                </div>
            </div>
        </div> 
    </div>
</div>
@endsection
@section('script')
<script type="text/javascript" src="/assets/member/payroll/js/loan_summary.js"></script>
<script>
    $(".select-company-name").unbind("change");
    $(".select-company-name").bind("change", function()
    {

        var link = "/member/payroll/reports/loan_summary/loan_summary_report_excel/" + $(this).val() +"/noval";
         $(".excel_tag").attr('href',link);
    });

    $('select[name=deduction_type]').change(function() 
    {
        var company = $('.select-company-name').val();
        var link = "/member/payroll/reports/loan_summary/loan_summary_report_excel/"+ company +"/" + $(this).val();
         $(".excel_tag").attr('href',link);
    });

    </script>
@endsection