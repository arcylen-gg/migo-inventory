@extends('member.layout')
@section('content')
<div class="panel panel-default panel-block panel-title-block">
    <input type="hidden" name="_token" id="_token" value="{{csrf_token()}}"/>
    <div class="panel-heading">
        <div>
            <i class="fa fa-calendar"></i>
            <h1>
            <span class="page-title">{{$page or ''}}</span>
            <small>
            Insert Description Here
            </small>
            </h1>
            <div class="dropdown pull-right">
                <button class="btn btn-primary popup" link="/member/utilities/create-sales-rep/"><i class="fa fa-plus"></i> New Sales Representative</button>
            </div>
        </div>
    </div>
</div>
<div class="panel panel-default panel-block panel-title-block panel-gray "  style="margin-bottom: -10px;">
    <ul class="nav nav-tabs">
        <li class="active change-tab cursor-pointer open-tab" mode="open"><a class="cursor-pointer"><i class="fa fa-folder-open-o"></i> Active</a></li>
        <li class="cursor-pointer change-tab closed-tab" mode="closed"><a class="cursor-pointer"><i class="fa fa-folder-o"></i> Archived</a></li>
        <li class="cursor-pointer change-tab all-tab" mode="all"><a class="cursor-pointer"><i class="fa fa-list"></i> All</a></li>
    </ul>
    <div class="search-filter-box">
        <div class="col-md-3" style="padding: 10px">
        </div>
        <div class="col-md-3" style="padding: 10px">
        </div>
        <div class="col-md-2" style="padding: 10px">
        </div>
        <div class="col-md-4" style="padding: 10px">
            <div class="input-group">
                <span style="background-color: #fff; cursor: pointer;" class="input-group-addon" id="basic-addon1"><i class="fa fa-search"></i></span>
                <input type="text" class="form-control search-keyword" placeholder="Search ..." aria-describedby="basic-addon1">
            </div>
        </div>
    </div>
    <div class="tab-content codes_container" style="min-height: 300px;">
        <div id="all" class="tab-pane fade in active">
            <div class="form-group order-tags"></div>
            <div class="clearfix">
                <div class="col-md-12">
                    <div class="table-responsive load-item-table">
                      <div class="text-center">LOADING SALES REPRESENTATIVE...</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
</div>
@endsection
@section('script')
<script type="text/javascript" src="/assets/member/js/sales_representative/sales_representative.js"></script>
@endsection