@extends('member.layout')
@section('content')
<div class="panel panel-default panel-block panel-title-block">
    <input type="hidden" name="_token" id="_token" value="{{csrf_token()}}"/>
    <div class="panel-heading">
        <div>
            <i class="fa fa-calendar"></i>
            <h1>
            <span class="page-title">Reward Slots</span>
            <small style="font-size: 14px; color: gray;">
                List of <b>REWARD SLOTS</b>
            </small>
            </h1>
            <div class="dropdown-pull-right" style="text-align: right;">
            <!-- TESTING MENU -->
                <div class="btn-group">
                   
                    <button type="button" class="btn btn-custom-white dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    <i class="fa fa-wrench"></i> TESTING <span class="caret"></span>
                    </button>
                    <ul class="dropdown-menu dropdown-menu-custom">
                        <li>
                            <a onclick="action_load_link_to_modal('/member/mlm/developer/create_slot')">
                                <div style="display: inline-block; width: 17px; text-align: center;"><i class="fa fa-plus"></i> &nbsp;</div>
                                CREATE TEST SLOT
                            </a>
                        </li>
                        <li>
                            <a onclick="action_load_link_to_modal('/member/mlm/developer/repurchase')">
                                <div style="display: inline-block; width: 17px; text-align: center;"><i class="fa fa-cart-plus"></i> &nbsp;</div>
                                TEST REPURCHASE
                            </a>
                        </li>
                        <li>
                            <a href="/member/mlm/developer/reset" onclick="return (prompt('WARNING! All MLM SLOT and CUSTOMER related to MLM will be deleted. Please write RESET if you are sure.') == 'RESET' ? true : false)">
                                <div style="display: inline-block; width: 17px; text-align: center;"><i class="fa fa-warning"></i> &nbsp;</div>
                                RESET MLM DATA
                            </a>
                        </li>

                        <li>
                            <a href="javascript:" onclick="action_load_link_to_modal('/member/mlm/developer/recompute')">
                                <div style="display: inline-block; width: 17px; text-align: center;"><i class="fa fa-warning"></i> &nbsp;</div>
                                RECOMPUTE REWARDS
                            </a>
                        </li>

                        <li>
                            <a href="javascript:" onclick="action_load_link_to_modal('/member/mlm/developer/redistribute')">
                                <div style="display: inline-block; width: 17px; text-align: center;"><i class="fa fa-warning"></i> &nbsp;</div>
                                SLOT RE-DISTRIBUTE REWARD
                            </a>
                        </li>
                        
                    </ul>
                </div>
                <button class="btn btn-def-white btn-custom-white" onclick="action_load_link_to_modal('/member/columns/slot_module', 'md')"><i class="fa fa-gear"></i> COLUMNS</button>
                <button class="btn btn-def-white btn-custom-white" onclick="action_load_link_to_modal('/member/mlm/developer/import', 'lg')"><i class="fa fa-arrow-circle-up"></i> IMPORT SLOTS</button>
                <a href="/downloadables/mlm-template.xlsx"><button class="btn btn-def-white btn-custom-white"><i class="fa fa-arrow-circle-down"></i> TEMPLATE</button></a>
           

            </div>
        </div>
    </div>
</div>

<div class="panel panel-default panel-block panel-title-block" style="margin-bottom: -10px;">

    <div class="form-group col-md-12" style="margin-top: 15px;">
        <div class="pull-left">
            <select class="form-control change-filter-membership" style="width: 250px;">
                <option value="0">All Membership</option>
                @foreach($_membership as $membership)
                    <option value="{{ $membership->membership_id }}">{{ $membership->membership_name }}</option>
                @endforeach
            </select>
        </div>
        <div class="pull-left">
            <select class="form-control change-filter-type" style="width: 250px; margin-left: 15px;">
                <option value="NA">All Type</option>
                <option value="PS">PS</option>
                <option value="FS">FS</option>
                <option value="CD">CD</option>
            </select>
        </div>
        <div class="input-group pull-right" style="width: 300px;">
            <span style="background-color: #fff; cursor: pointer;" class="input-group-addon" id="basic-addon1"><i class="fa fa-search"></i></span>
            <input type="text" class="form-control search-employee-name" placeholder="Search customer or slot number" aria-describedby="basic-addon1">
        </div>
    </div>
    <div class="form-group panel-body employee-container">
        <div id="all" class="tab-pane fade in active">
            <div class="form-group order-tags"></div>
            <div class="table-responsive load-test-slots">
            </div>
        </div>
    </div> 
</div>

<script type="text/javascript" src="/assets/member/js/mlm/mlm_developer.js?v=3.0"></script>
<script type="text/javascript" src="/assets/member/js/column.js"></script>
<style type="text/css">
    .paginat .pagination
    {
        margin-bottom: 0;
    }
</style>
@endsection