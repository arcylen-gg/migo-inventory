@extends('member.layout')
@section('content')

<link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
<div class="panel panel-default panel-block panel-title-block" id="top">
    <div class="panel-heading">
        <div>
            <i class="fa fa-archive"></i>
            <h1>
                <span class="page-title">Warehouse Issuance Slip</span>
            </h1>
            <div class="text-right">
                <a class="btn btn-primary panel-buttons" href="/member/transaction/wis/create"><i class="fa fa-star"></i> Create WIS</a>
            </div>
        </div>
    </div>
</div>
<div class="panel panel-default panel-block panel-title-block">
    <div class="panel-body form-horizontal">
        <div class="form-group">
            <div class="col-md-6">
                <ul class="nav nav-tabs">
                    <li id="all-list" class="active change-tab pending-tab" mode="pending">
                        <a data-toggle="tab" onClick="change_status('pending');"><i class="fa fa-star" aria-hidden="true"></i>&nbsp;Pending</a>
                    </li>
                    <li id="archived-list" class="change-tab confirm-tab" mode="confirm">
                        <a data-toggle="tab" onClick="change_status('confirm');"><i class="fa fa-truck" aria-hidden="true"></i>&nbsp;Confirm</a>
                    </li>
                    <li id="archived-list" class="change-tab delivered-tab" mode="delivered">
                        <a data-toggle="tab" onClick="change_status('delivered');"><i class="fa fa-hand-grab-o" aria-hidden="true"></i>&nbsp;Delivered</a>
                    </li>
                    <li class="change-tab all-tab" mode="all">
                        <a data-toggle="tab" onClick="change_status('all');"><i class="fa fa-list" aria-hidden="true"></i>&nbsp;All</a>
                    </li>
                </ul>
            </div>

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
        </div>
        <div class="tab-content codes_container" style="min-height: 300px;">
            <div id="all" class="tab-pane fade in active">
                <div class="form-group order-tags"></div>
                <div class="clearfix">
                    <div class="col-md-12">
                        <div class="table-responsive load-item-table">
                          <div class="text-center">LOADING TRANSACTION...</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>        
    </div>
</div>
@endsection

@section('script')
<script type="text/javascript" src="/assets/member/js/warehouse/customer_wis.js"></script>
@endsection
