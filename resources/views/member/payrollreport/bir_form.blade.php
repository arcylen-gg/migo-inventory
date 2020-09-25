@extends('member.layout')
@section('content')
 <input type="hidden" class="_token" value="{{ csrf_token() }}" />
<div class="panel panel-default panel-block panel-title-block" id="top">
    <div class="panel-heading">
        <div>
            <i class="fa fa-tags"></i>
            <h1>
                <span class="page-title">Payroll Reports &raquo; BIR Forms</span>
                <small>
                Filter different forms for BIR
                </small>
            </h1>
        </div>
    </div>
</div>
<div class="panel panel-default panel-block panel-title-block">
    <div class="panel-body form-horizontal">
        <div class="form-group">
            <div class="col-md-3">
                <select class="form-control contribution-month">
                    @foreach($_year_period as $year)
                        <option value="{{$year["year_contribution"]}}" {{$year["year_contribution"] == $year_today ? 'selected': 'unselected'}}>{{$year["year_contribution"]}}</option>
                    @endforeach
                </select>
            </div>

            <div class="col-md-3">
                        <select class="select-company-name form-control" style="width: 300px">    
                            <option value="0">All Company</option>
                              @foreach($_company as $company)
                              <option value="{{$company['company']->payroll_company_id}}">{{$company['company']->payroll_company_name}}</option> 
                              @endforeach
                        </select>
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
                                <th>Month</th>
                                <th width="100px">Action</th>
                            </tr>
                        </thead>
                        <tbody class="table-warehouse">
                            @foreach($_month as $key => $month)
                            <tr>
                                <td>{{ $month["month_name"] }}</td>
                                                        <td class="text-center"> 
                                    <div class="dropdown">
                                        <button class="btn btn-custom-white dropdown-toggle btn-xs" type="button" data-toggle="dropdown">Action
                                        <span class="caret"></span></button>
                                        <ul class="dropdown-menu dropdown-menu-custom">
                                            <li>
                                                <a href="javascript: action_load_link_to_modal('/member/payroll/reports/view_bir_forms/{{ $year_today }}/{{ $month["month_value"] }}/0','lg')"><i class="fa fa-search"></i>&nbsp;View</a>
                                            </li>
                                        </ul>
                                    </div>
                                </td>
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
    $(document).ready(function()
    {
        load_bir_forms_filter();

    })
   
    function load_bir_forms_filter()
    {    
        $(".contribution-month").on("change", function(e)
        {
            var year            = $(this).val();
            var company         = $(".select-company-name").val();
            ajaxdata.year       = year;
            ajaxdata.company_id    = company;
            ajaxdata._token     = $("._token").val();
            $('#spinningLoaders').show();
            $(".load-filter-datas").hide();
            setTimeout(function(e){
            $.ajax(
            {
                url:"/member/payroll/reports/bir_forms_filter",
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

        $(".select-company-name").on("change", function(e)
        {
            var year            = $(".contribution-month").val();
            var company         = $(this).val();
            ajaxdata.year       = year;
            ajaxdata.company_id    = company;
            ajaxdata._token     = $("._token").val();
            $('#spinningLoaders').show();
            $(".load-filter-datas").hide();
            setTimeout(function(e){
            $.ajax(
            {
                url:"/member/payroll/reports/bir_forms_filter",
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
    }
</script>
@endsection