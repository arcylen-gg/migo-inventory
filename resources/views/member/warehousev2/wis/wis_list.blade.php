@extends('member.layout')
@section('content')

<link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
<div class="panel panel-default panel-block panel-title-block" id="top">
    <div class="panel-heading">
        <div>
            <i class="fa fa-archive"></i>
            <h1>
                <span class="page-title">Warehouse Transfer</span>
            </h1>
            <div class="text-right">
                <a class="btn btn-primary panel-buttons" href="/member/transaction/warehouse_transfer/create"><i class="fa fa-icon fa-star"></i> Warehouse Transfer</a>
            </div>
        </div>
    </div>
</div>
<div class="panel panel-default panel-block panel-title-block">
    <div class="panel-body form-horizontal">
        <div class="form-group">
            <div class="col-md-6">
                <ul class="nav nav-tabs">
                  <li id="all-list" class="active"><a data-toggle="tab" onClick="change_status('pending');"><i class="fa fa-star" aria-hidden="true"></i>&nbsp;Pending</a></li>
                  <li id="archived-list"><a data-toggle="tab" onClick="change_status('confirm');"><i class="fa fa-check" aria-hidden="true"></i>&nbsp;Confirmed Warehouse Transfer</a></li>
                  <li id="archived-list"><a data-toggle="tab" onClick="change_status('received');"><i class="fa fa-hand-grab-o" aria-hidden="true"></i>&nbsp;Received</a></li>
                  <li><a data-toggle="tab" onClick="change_status('all');"><i class="fa fa-list" aria-hidden="true"></i>&nbsp; All</a></li>
                </ul>
            </div>
        </div>
            @include('member.warehousev2.wis.load_wis_table')
        </div>        
    </div>
</div>
@endsection

@section('script')
<script type="text/javascript" src="/assets/member/js/warehouse/wis.js"></script>
@endsection
