@extends('member.layout')
@section('content')
<div class="panel panel-default panel-block panel-title-block">
    <input type="hidden" name="_token" id="_token" value="{{csrf_token()}}"/>
    <div class="panel-heading">
        <div>
            <i class="fa fa-calendar"></i>
            <h1>
            <span class="page-title">Commission Report</span>
            <small>
            Insert Description Here
            </small>
            </h1>
            <div class="dropdown pull-right">
                <button onclick="window.open('/member/merchant/commission_report/export')" class="btn btn-def-white btn-custom-green-white"><i class="fa fa-file-excel-o"></i> Export to Excel</button>
                <button onclick="action_load_link_to_modal('/member/merchant/commission_report/import', 'md')" class="btn btn-custom-white"><i class="fa fa-upload"></i> Import Excel File</button>
            </div>
        </div>
    </div>
</div>
    {!! csrf_field() !!}
<div class="panel panel-default panel-block panel-title-block panel-gray col-md-6">
    <div class="tab-content">
        <div id="all-orders" class="tab-pane fade in active">
            <form method="post">
                <div class="panel-heading">
                <center>Commission Settings</center>
                <div class="col-md-12">
                    <label>Commission Percentage</label>
                    <input type="number" min="0" value="{{$percentage}}" class="form-control commission-percentage" name="merchant_commission_percentage">
                    <hr>
                    <button onclick="return false" class="btn btn-primary popup pull-right" size='md' link="/member/merchant/commission-report-pass"><i class="fa fa-floppy-o"></i> Save</button>
                </div>
                </div>
            </form>
        </div>   
    </div>
</div>
<!-- <div class="panel panel-default panel-block panel-title-block panel-gray col-md-6">
    <div class="tab-content">
        <div id="all-orders" class="tab-pane fade in active">
            <form method="post">
                <div class="panel-heading">
                    <center>Import Excel File</center>
                    <div class="col-md-12">
                        <label>Excel File</label>
                        <input id="basic-input" type="file"  class="form-control" name="excel_file">
                        <hr>
                        <button onclick="return false" class="btn btn-primary popup pull-right" size='md' link="/member/merchant/commission-report-pass"><i class="fa fa-upload"></i> Import File</button>
                    </div>
                </div>
            </form>
        </div>   
    </div>
</div> -->
<div class="panel panel-default panel-block panel-title-block panel-gray col-md-12"  style="margin-bottom: -10px;">
    <div class="search-filter-box">
        <div class="col-md-3" style="padding: 10px">
            <!-- <select class="form-control">
                <option value="0">All</option>
                <option value="1">Current Warehouse</option>
            </select> -->
        </div>
        <div class="col-md-3" style="padding: 10px">
            <!-- <select class="form-control">
                <option value="0">Filter Sample 002</option>
            </select> -->
        </div>
        <div class="col-md-2" style="padding: 10px">
        </div>
        <div class="col-md-4" style="padding: 10px">
            <!-- <div class="input-group">
                <span style="background-color: #fff; cursor: pointer;" class="input-group-addon" id="basic-addon1"><i class="fa fa-search"></i></span>
                <input type="text" class="form-control search-employee-name" placeholder="Search by employee name / number" aria-describedby="basic-addon1">
            </div> -->
        </div>
    </div>
    <div class="tab-content codes_container" style="min-height: 300px;">
        <div id="all" class="tab-pane fade in active">
            <div class="form-group order-tags"></div>
            <div class="clearfix">
                <div class="col-md-12">
                    <div class="table-responsive load-commission-table-here">
                        
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
</div>
@endsection
@section('script')
<script type="text/javascript" src="/assets/js/commission_report.js?v=11"></script>
<script type="text/javascript">
    @if(Session::get("response")=='success')
    toastr.success("Importation Complete");
    @elseif(Session::get("response")=='no_data')
    toastr.error("Importation Failed (No Data Found)");
    @elseif(Session::get("response")=='error')
    toastr.error("Please select a file");
    @endif
</script>
{{-- table pagination --}}
<script type="text/javascript">
    $(window).on('hashchange', function() {
        if (window.location.hash) {
            var page = window.location.hash.replace('#', '');
            if (page == Number.NaN || page <= 0) {
                return false;
            } else {
                getPosts(page);
            }
        }
    });
    $(document).ready(function() {
        getPosts(1);
        $(document).on('click', '.pagination a', function (e) {
            getPosts($(this).attr('href').split('page=')[1]);
            e.preventDefault();
        });
    });
    function getPosts(page) {
        $.ajax(
        {
            url : '/member/merchant/commission_report/table?page=' + page,
            type: 'get',
        }).done(function (data) 
        {
            $('.load-commission-table-here').html(data);
            location.hash = page;
        }).fail(function () 
        {
            alert('Posts could not be loaded.');
        });
    }
</script>
@endsection
<script type="text/javascript" src="/assets/themes/js/jquery.min.js?v=1"></script>
<script type="text/javascript">
    function success(data)
    {
        toastr.success('Setting saved');
        commission_report.action_load_table();
    }
</script>

