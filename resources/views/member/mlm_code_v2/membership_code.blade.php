@extends('member.layout')
@section('content')
<div class="panel panel-default panel-block panel-title-block">
    <input type="hidden" name="_token" id="_token" value="{{csrf_token()}}"/>
    <div class="panel-heading">
        <div>
            <i class="fa fa-calendar"></i>
            <h1>
            <span class="page-title">Membership Codes</span>
            <small>
            You can see membership codes from here
            </small>
            </h1>
            <div class="dropdown pull-right">
                <a class="btn btn-def-white btn-custom-white" href="/member/mlm/report_codes"><i class="fa fa-table"></i> View Codes Report</a>
                <button class="btn btn-def-white btn-custom-white popup" link="/member/mlm/print_codes?type=membership_code" size='md'><i class="fa fa-print"></i> Print Membership Codes</button>
                <button onclick="action_load_link_to_modal('/member/mlm/code2/disassemble', 'md')"  class="btn btn-def-white btn-custom-white"><i class="fa fa-yelp"></i> Disassemble Kit</button>
                <button onclick="action_load_link_to_modal('/member/mlm/code2/assemble', 'md')" class="btn btn-primary"><i class="fa fa-qrcode"></i> Assemble Membership Kit</button>
            </div>
        </div>
    </div>
</div>
<div class="panel panel-default panel-block panel-title-block panel-gray ">
    <ul class="nav nav-tabs">
        <li class="active change-tab unused-tab cursor-pointer" onClick="click_status('unused');" mode="pending"><a class="cursor-pointer"><i class="fa fa-check"></i> Unused</a></li>
        <li class="cursor-pointer change-tab reserved-tab" onClick="click_status('reserved');" mode="approved"><a class="cursor-pointer"><i class="fa fa-anchor"></i> Reserved</a></li>
        <li class="cursor-pointer change-tab sold-tab" onClick="click_status('sold');" mode="approved"><a class="cursor-pointer"><i class="fa fa-money"></i> Sold</a></li>
        <li class="cursor-pointer change-tab used-tab" onClick="click_status('used');" mode="approved"><a class="cursor-pointer"><i class="fa fa-star"></i> Used</a></li>
    	<li class="cursor-pointer change-tab block-tab" onClick="click_status('block');" mode="approved"><a class="cursor-pointer"><i class="fa fa-warning"></i> Blocked</a></li>
    </ul>
    <div class="search-filter-box">
        <div class="col-md-3" style="padding: 10px">
            <select class="form-control filter-membership">
                <option value="0">All Membership</option>
                @foreach($_membership as $membership)
                    <option value="{{ $membership->membership_id }}">{{ $membership->membership_name }}</option>
                @endforeach
            </select>
        </div>
        <div class="col-md-3" style="padding: 10px">
            <select class="form-control filter-item-kit">
                <option value="0">All Item Kit</option>
                @foreach($_item_kit as $kit)
                    <option value="{{ $kit->item_id }}">{{ $kit->item_name }}</option>
                @endforeach
            </select>
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
                       
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
</div>

@endsection
@section('script')
<script type="text/javascript" src="/assets/member/js/membership_code/membership_code.js"></script>
@endsection