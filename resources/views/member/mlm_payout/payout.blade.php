@extends('member.layout')
@section('content')
<input type="hidden" value="{{ csrf_token() }}" class="payout-token">

<div class="panel panel-default panel-block panel-title-block">
    <input type="hidden" name="_token" id="_token" value="{{csrf_token()}}"/>
    <div class="panel-heading">
        <div>
            <i class="fa fa-calendar"></i>
            <h1>
            <span class="page-title">Payout Processing</span>
            <small>
                This modules allows the admin to process payout.
            </small>
            </h1>
            <div class="dropdown pull-right">
                <button onclick="action_load_link_to_modal('/member/mlm/payout/config', 'lg')" class="btn btn-def-white btn-custom-white"><i class="fa fa-gear""></i> PAYOUT CONFIGURATION</button>
                <button onclick="location.href='/downloadables/payout-import.xlsx'" class="btn btn-def-white btn-custom-white"><i class="fa fa-arrow-circle-down""></i> TEMPLATE</button>
                <button onclick="action_load_link_to_modal('/member/mlm/payout/import', 'lg')" class="btn btn-def-white btn-custom-white"><i class="fa fa-arrow-circle-up""></i> IMPORT</button>
                <button class="btn btn-def-white btn-custom-white reset-payout"><i class="fa fa-file-excel-o"></i> RESET IMPORT</button>
                <button onclick="action_load_link_to_modal('/member/mlm/payout/process')" class="btn btn-primary"><i class="fa fa-star"></i> PROCESS PAYOUT</button>
            </div>
        </div>
    </div>
</div>
<div class="panel panel-default panel-block panel-title-block panel-gray" style="border-bottom: 0; margin-bottom: -10px;">
    <ul class="nav nav-tabs">
        <li class="active change-tab pending-tab cursor-pointer" mode="pending"><a class="cursor-pointer"><i class="fa fa-money"></i> Pending</a></li>
        <li class="cursor-pointer change-tab approve-tab" mode="done"><a class="cursor-pointer"><i class="fa fa-check"></i> Processed</a></li>
        {{-- @if($shop_id == 55 && $count_reject == 0) --}}
        @if($shop_id == 55)
        <li class="pull-right">
            <button class="btn btn-primary popup" link="/member/mlm/payout/reject-encashment" size="md" style="margin: 5px;">REJECT ENCASHMENT</button>
        </li>
        @endif
    </ul>
    <div class="search-filter-box">
        <div class="col-md-3" style="padding: 10px">
            <select class="form-control">
                <option value="0">All Method</option>
            </select>
        </div>
        <div class="col-md-3" style="padding: 10px">
            <input type="text" class="form-control" placeholder="Filter Date Range" name="">
        </div>
        <div class="col-md-2" style="padding: 10px">
        </div>
        <div class="col-md-4" style="padding: 10px">
            <div class="input-group">
                <span style="background-color: #fff; cursor: pointer;" class="input-group-addon" id="basic-addon1"><i class="fa fa-search"></i></span>
                <input type="text" class="form-control search-employee-name" placeholder="Search by employee name / slot number" aria-describedby="basic-addon1">
            </div>
        </div>
    </div>
    <div class="tab-content codes_container" style="min-height: 300px;">
        <div id="all" class="tab-pane fade in active">
            <div class="form-group order-tags"></div>
            <div class="clearfix">
                <div class="col-md-12">
                    <div class="table-responsive table-container">
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
</div>
@endsection

@section('script')
<script type="text/javascript" src="/assets/member/js/mlm/payout.js"></script>
@endsection