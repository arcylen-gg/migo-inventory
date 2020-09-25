@extends('member.layout')
@section('content')
<div class="panel panel-default panel-block panel-title-block" id="top">
    <div class="panel-heading">
        <div>
            <i class="fa fa-tags"></i>
            <h1>
                <span class="page-title">Marketing Plan - Brown Rank System</span>
                <small>
                    You can set the computation of your Brown Ranking.
                </small>
            </h1>
            <a href="javascript:" class="panel-buttons btn btn-custom-blue pull-right" onClick="update_basic_settings()">Update</a>
            <a href="/member/mlm/plan" class="panel-buttons btn btn-custom-white pull-right"> < Back</a>
        </div>
    </div>
</div>

<div class="bsettings">
    {!! $basic_settings !!}  
</div>

<div class="panel panel-default panel-block panel-title-block panel-gray ">
    <div class="search-filter-box">
        <div class="col-md-8" style="padding: 10px">
        </div>
        <div class="col-md-4" style="padding: 10px; text-align: right;">
            <button class="btn btn-custom-white" onclick="action_load_link_to_modal('/member/mlm/plan/brown_rank/add_rank')"><i class="fa fa-plus"> </i> Add Rank</button>
        </div>
    </div>
    <div class="tab-content codes_container" style="min-height: 300px;">
        <div id="all" class="tab-pane fade in active">
            <div class="form-group order-tags"></div>
            <div class="clearfix">
                <div class="col-md-12">
                    <div class="table-responsive load-rank-list">
                       
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
@section('script')
<script type="text/javascript" src="/assets/member/js/brown_rank/brown_rank.js"></script>
@endsection