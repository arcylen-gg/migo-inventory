<div class="modal-header">
    <button type="button" class="close" data-dismiss="modal">×</button>
    <h4 class="modal-title">{{$page}}</h4>
</div>
<div class="modal-body clearfix">
    <div class="dropdown pull-right"><button onclick="action_load_link_to_modal('/member/item/token/add-token', 'md')" class="btn btn-primary"><i class="fa fa-plus-circle"></i> Add Tokens</button>
    </div>
    <div class="panel panel-default panel-block panel-title-block panel-gray "  style="margin-bottom: -10px;">
        <ul class="nav nav-tabs">
            <li class="active change_tab pending-tab cursor-pointer" mode="0"><a class="cursor-pointer"><i class="fa fa-check"></i> Active</a></li>
            <li class="cursor-pointer change_tab approve-tab" mode="1"><a class="cursor-pointer"><i class="fa fa-trash"></i> Archived</a></li>
        </ul>
        {{-- <div class="search-filter-box">
            <div class="col-md-3" style="padding: 10px">
                <select class="form-control">
                    <option value="0">Filter Sample 001</option>
                </select>
            </div>
            <div class="col-md-3" style="padding: 10px">
                <select class="form-control">
                    <option value="0">Filter Sample 002</option>
                </select>
            </div>
            <div class="col-md-2" style="padding: 10px">
            </div>
            <div class="col-md-4" style="padding: 10px">
                <div class="input-group">
                    <span style="background-color: #fff; cursor: pointer;" class="input-group-addon" id="basic-addon1"><i class="fa fa-search"></i></span>
                    <input type="text" class="form-control search-employee-name" placeholder="Search by employee name / number" aria-describedby="basic-addon1">
                </div>
            </div>
        </div> --}}
        <div class="tab-content codes_container">
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
<div class="modal-footer">
    <button type="button" class="btn btn-def-white btn-custom-white" data-dismiss="modal">Close</button>
    {{-- <button class="btn btn-primary btn-custom-primary" type="button">Submit</button> --}}
</div>
<script type="text/javascript" src="/assets/member/js/item/new_token.js"></script>