@extends('member.layout')
@section('content')
<div class="panel panel-default panel-block panel-title-block">
    <input type="hidden" name="_token" id="_token" value="{{csrf_token()}}"/>
    <div class="panel-heading">
        <div>
            <i class="fa fa-file-o"></i>
            <h1>
            <span class="page-title">{{$page or ''}}</span>
            <small>
            Insert Description Here
            </small>
            </h1>
            <div class="dropdown pull-right">
               <!--  <button onclick="location.href=''" class="btn btn-def-white btn-custom-white"><i class="fa fa-check"></i> Secondary Command</button> -->
                <a href="/member/transaction/purchase_requisition/create" class="btn btn-primary"><i class="fa fa-star"></i> Create Purchase Requisition</a>
            </div>
        </div>
    </div>
</div>
<div class="panel panel-default panel-block panel-title-block panel-gray "  style="margin-bottom: -10px;">
    <!-- <ul class="nav nav-tabs">
        <li id="all-list" class="active"><a data-toggle="tab" onClick="change_status('open');"><i class="fa fa-check" aria-hidden="true"></i>&nbsp;Open</a></li>
        <li id="archived-list"><a data-toggle="tab" onClick="change_status('closed');"><i class="fa fa-trash" aria-hidden="true"></i>&nbsp;Closed</a></li>
    </ul> -->
    <ul class="nav nav-tabs">
        <li class="active change-tab cursor-pointer open-tab" mode="open"><a class="cursor-pointer"><i class="fa fa-folder-open-o"></i> Open</a></li>
        <li class="cursor-pointer change-tab closed-tab" mode="closed"><a class="cursor-pointer"><i class="fa fa-folder-o"></i> Close</a></li>
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
                <input type="text" class="form-control search-keyword" placeholder="Search..." aria-describedby="basic-addon1">
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
<script type="text/javascript" src="/assets/member/js/accounting_transaction/vendor/requisition_slip.js"></script>
@endsection
