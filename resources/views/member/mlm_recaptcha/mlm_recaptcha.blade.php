@extends('member.layout')
@section('content')
<div class="panel panel-default panel-block panel-title-block">
    <input type="hidden" name="_token" id="_token" value="{{csrf_token()}}"/>
    <div class="panel-heading">
        <div>
            <i class="fa fa-recycle"></i>
            <h1>
            <span class="page-title">{{$page}}</span>
            <small>
            Recaptcha setting and logs
            </small>
            </h1>
            <div class="dropdown pull-right">
                <button onclick="action_load_link_to_modal('/member/mlm/recaptcha/recaptcha_setting', 'md')" class="btn btn-def-white btn-custom-white"><i class="fa fa-cog"></i> Recaptcha Setting</button>
                <button onclick="action_load_link_to_modal('/member/mlm/recaptcha/add_pool', 'md')" class="btn btn-primary"><i class="fa fa-plus"></i> Add Amount on Pool</button>
            </div>
        </div>
    </div>
</div>
<div class="panel panel-default panel-block panel-title-block panel-gray "  style="margin-bottom: -10px;">
    {{-- <ul class="nav nav-tabs">
        <li class="active change-tab pending-tab cursor-pointer" mode="pending"><a class="cursor-pointer"><i class="fa fa-check"></i> Active</a></li>
        <li class="cursor-pointer change-tab approve-tab" mode="approved"><a class="cursor-pointer"><i class="fa fa-trash"></i> Archived</a></li>
    </ul> --}}
    <div class="search-filter-box">
        <div class="col-md-3" style="padding: 10px">
            {{-- <select class="form-control">
                <option value="0">Filter Sample 001</option>
            </select> --}}
              <div class="pool-amount"><label>Total Pool Amount <b>:</b> </label><font color="red"> PHP 5000.00</font></div>
        </div>
        <div class="col-md-3" style="padding: 10px">
            {{-- <select class="form-control">
                <option value="0">Filter Sample 002</option>
            </select> --}}
              <div class="points-per-submit"><label>Acquired points per submit <b>:</b> </label><font color="red"> PHP 0.50</font></div>
        </div>
        <div class="col-md-2" style="padding: 10px">
        </div>
        <div class="col-md-4" style="padding: 10px">
            {{-- <div class="input-group">
                <span style="background-color: #fff; cursor: pointer;" class="input-group-addon" id="basic-addon1"><i class="fa fa-search"></i></span>
                <input type="text" class="form-control search-employee-name" placeholder="Search by employee name / number" aria-describedby="basic-addon1">
            </div> --}}
        </div>
    </div>
    <div class="tab-content codes_container" style="min-height: 300px;">
        <div id="all" class="tab-pane fade in active">
            <div class="form-group order-tags"></div>
            <div class="clearfix">
                <div class="col-md-12">
                    <div class="table-responsive load-table-here">
                        
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
</div>
@endsection
@section('script')
<script type="text/javascript" src="/assets/member/js/mlm/recaptcha.js"></script>
<script>
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
        url : '/member/mlm/recaptcha/recaptcha_table?page=' + page,
        type: 'get',
    }).done(function (data) 
    {
        $('.load-table-here').html(data);
        location.hash = page;
    }).fail(function () 
    {
        alert('Posts could not be loaded.');
    });
}
</script>
@endsection