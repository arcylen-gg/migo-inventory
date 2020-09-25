@extends('member.layout')

@section('content')
<div class="panel panel-default panel-block panel-title-block">
    <input type="hidden" name="_token" id="_token" value="{{csrf_token()}}"/>
    <div class="panel-heading">
        <div>
            <i class="fa fa-calendar"></i>
            <h1>
            <span class="page-title">Project List</span>
            <small>
            	List of Projects
            </small>
            </h1>
            <div class="dropdown pull-right">
                <button onclick="location.href=''" class="btn btn-def-white btn-custom-white something-was-clicke"><i class="fa fa-check"></i> Secondary Command</button>
                <button  class="btn btn-primary popup" link="/member/project/project_list/addtask" size="md"><i class="fa fa-star"></i> Add Task</button>
            </div>
        </div>
    </div>
</div>
<div class="panel panel-default panel-block panel-title-block panel-gray "  style="margin-bottom: -10px;">
    <!-- TAB -->
    <!-- <ul class="nav nav-tabs">
        <li class="active change-tab pending-tab cursor-pointer" mode="active"><a class="cursor-pointer"><i class="fa fa-check"></i> Active</a></li>
        <li class="cursor-pointer change-tab approve-tab" mode="archived"><a class="cursor-pointer"><i class="fa fa-trash"></i> Archived</a></li>
    </ul> -->
    <div class="search-filter-box">
        <div class="col-md-4" style="padding: 10px">
            <select class="form-control">
                <option value="0">All Project Type</option>
            </select>
        </div>
        <div class="col-md-4" style="padding: 10px">
        </div>
        <div class="col-md-4" style="padding: 10px">
            <div class="input-group">
                <span style="background-color: #fff; cursor: pointer;" class="input-group-addon" id="basic-addon1"><i class="fa fa-search"></i></span>
                <input type="text" class="form-control search-project" placeholder="Search Project Name" aria-describedby="basic-addon1">
            </div>
        </div>
    </div>
    <div class="tab-content codes_container" style="min-height: 300px;">
        <div id="all" class="tab-pane fade in active">
            <div class="form-group order-tags"></div>
            <div class="clearfix">
                <div class="col-md-12">
                    <div class="table-responsive table-of-task">

                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('script')
<script type="text/javascript" src="/assets/member/js/project/project-list.js?v=2"></script>
@endsection