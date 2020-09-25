@extends('member.layout')
@section('content')
<div class="panel panel-default panel-block panel-title-block">
    <input type="hidden" name="_token" id="_token" value="{{csrf_token()}}"/>
    <div class="panel-heading">
        <div>
            <i class="fa fa-users"></i>
            <h1>
            <span class="page-title">Sales Agent</span>
            <small>
            
            </small>
            </h1>
            <div class="dropdown pull-right">
                <button onclick="location.href='/member/cashier/sales_agent/import'" class="btn btn-def-white btn-custom-white"><i class="fa fa-check"></i> Import Agent</button>
                <button onclick="action_load_link_to_modal('/member/cashier/sales_agent/add','md')" class="btn btn-primary"><i class="fa fa-star"></i> Add Agent</button>
            </div>
        </div>
    </div>
</div>
<div class="panel panel-default panel-block panel-title-block panel-gray "  style="margin-bottom: -10px;">
    <ul class="nav nav-tabs">
        <li class="active change-tab pending-tab cursor-pointer" mode="pending"><a class="cursor-pointer"><i class="fa fa-check"></i> Active</a></li>
        <li class="cursor-pointer change-tab approve-tab" mode="approved"><a class="cursor-pointer"><i class="fa fa-trash"></i> Archived</a></li>
    </ul>
    <div class="search-filter-box">
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
    </div>
    <div class="tab-content codes_container" style="min-height: 300px;">
        <div id="all" class="tab-pane fade in active">
            <div class="form-group order-tags"></div>
            <div class="clearfix">
                <div class="col-md-12">
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped table-condensed">
                            <thead style="text-transform: uppercase">
                                <tr>
                                    <th class="text-center" width="10px">#</th>
                                    <th class="text-center" width="300px">Name</th>
                                    <th class="text-center" >OVERALL COMMISSION</th>
                                    <th class="text-center" >TOTAL RELEASED COMMISSION</th>
                                    <th class="text-center" >FOR RELEASING</th>
                                    <th class="text-center">PENDING COMMISSION</th>
                                    <th class="text-center"></th>
                                </tr>
                            </thead>
                            <tbody>
                                @if(count($_list) > 0)
                                    @foreach($_list as $key => $list)
                                        <tr>
                                            <td class="text-center">{{$key+1}}</td>
                                            <td class="text-center">{{ucwords($list->agent_code.'-'.$list->first_name.' '.$list->middle_name.' '.$list->last_name)}}</td>
                                            <td class="text-center">{{currency('P ',$list->orverall_comm,2)}}</td>
                                            <td class="text-center">{{currency('P ',$list->released_comm,2)}}</td>
                                            <td class="text-center">{{currency('P ',$list->for_releasing_comm,2)}}</td>
                                            <td class="text-center">{{currency('P ',$list->pending_comm,2)}}</td>
                                            <td class="text-center">
                                                <a href="javascript:" class="popup" link="/member/cashier/sales_agent/view-transaction/{{$list->employee_id}}" size="lg">View Transaction</a>
                                            </td>
                                        </tr>
                                    @endforeach
                                @else
                                <tr><td colspan="7" class="text-center">NO AGENT YET</td></tr>
                                @endif
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
</div>
@endsection
@section('script')
<script type="text/javascript">
    
    function success_agent(data)
    {
        if(data.status == 'success')
        {
            toastr.success("Success");
            location.reload();
        }
    }
</script>
@endsection