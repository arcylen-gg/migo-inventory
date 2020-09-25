@extends('member.layout')
@section('content')

<div class="panel panel-default panel-block panel-title-block">
    <div class="panel-heading">
        <div>
            <i class="fa fa-user-secret"></i>
            <h1>
                <span class="page-title">Utilities &raquo; User position</span>
                <small>
                Add a position
                </small>
            </h1>
            <button type="button" class="panel-buttons btn btn-custom-primary pull-right popup" link="/member/utilities/modal-position" size="sm">Create Position</button>
        </div>
    </div>
</div>

<div class="panel panel-default panel-block panel-title-block">
    
    <ul class="nav nav-tabs">
        <li class="active cursor-pointer"><a class="cursor-pointer" data-toggle="tab" href="#actives"><i class="fa fa-star"></i> Active Position</a></li>
        <li class="cursor-pointer"><a class="cursor-pointer" data-toggle="tab" href="#inactives"><i class="fa fa-trash"></i> Inactive Position</a></li>
    </ul>
    
    <div class="search-filter-box">
        <div class="col-md-4 col-md-offset-8" style="padding: 10px">
            <div class="input-group">
                <span style="background-color: #fff; cursor: pointer;" class="input-group-addon" id="basic-addon1"><i class="fa fa-search"></i></span>
                <input type="text" class="form-control" placeholder="Search by Vendor Name" aria-describedby="basic-addon1">
            </div>
        </div>  
    </div>
    
    <div class="tab-content">
        <div id="actives" class="tab-pane fade in active">
            <div class="panel-body position-container">
                <table style="table-layout: fixed;" class="table table-hover table-condensed table-bordered table-sale-month">
                    <thead>
                        <tr>
                            <th class="text-left">Id</th>
                            @if($is_developer)
                                <th class="text-left">Shop Id</th>
                                <th class="text-left">Shop Name</th>
                            @endif
                            <th class="text-left">Name</th>
                            <th class="text-left">Rank</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($_position as $position)
                        <tr class="cursor-pointer">
                            <td class="text-left">{{$position->position_id}}</td>
                            @if($is_developer)
                                <td class="text-left">{{$position->shop_id}}</td>
                                <td class="text-left">{{$position->shop_key}}</td>
                                <!-- <td class="text-left">{{$position->shop_status}}</td> -->
                            @endif
                            <td class="text-left">{{$position->position_name}}</td>
                            <td class="text-left">{{$position->position_rank}}</td>
                            <td class="text-center">
                                <div class="btn-group">
                                    <a class="btn btn-primary btn-grp-primary" href="/member/utilities/access?id={{$position->position_id}}" size="sm">Edit</a>
                                    <a class="btn btn-primary btn-grp-primary popup" href="javascript:" link="/member/utilities/modal-archive-position?position_id={{$position->position_id}}" size="sm"><span class="fa fa-trash"></span></a>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
         <div id="inactives" class="tab-pane fade in">
            <div class="panel-body position-container">
                <table style="table-layout: fixed;" class="table table-hover table-condensed table-bordered table-sale-month">
                    <thead>
                        <tr>
                            <th class="text-left">Id</th>
                            @if($is_developer)
                                <th class="text-left">Shop Id</th>
                                <th class="text-left">Shop Name</th>
                            @endif
                            <th class="text-left">Name</th>
                            <th class="text-left">Rank</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($_position_archived as $position)
                        <tr class="cursor-pointer">
                            <td class="text-left">{{$position->position_id}}</td>
                            @if($is_developer)
                                <td class="text-left">{{$position->shop_id}}</td>
                                <td class="text-left">{{$position->shop_key}}</td>
                                <!-- <td class="text-left">{{$position->shop_status}}</td> -->
                            @endif
                            <td class="text-left">{{$position->position_name}}</td>
                            <td class="text-left">{{$position->position_rank}}</td>
                            <td class="text-center">
                                <div class="btn-group">
                                    <a class="btn btn-primary btn-grp-primary popup" hrefe="javascript:" link="/member/utilities/modal-restore-position?position_id={{$position->position_id}}" size="sm">Restore</a>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
@section('script')
<script type="text/javascript">
    function submit_done(data)
    {
        if(data.response_status == "success")
        {
            $(".position-container").load("/member/utilities/position .position-container");
            toastr.success('Success');
            $("#global_modal").modal("toggle");
        }
        else if(data.response_status == "success-archived")
        {
            data.element.modal("toggle");
            $("#inactives").load('/member/utilities/position #inactives .position-container')
            $("#actives").load('/member/utilities/position #actives .position-container', function()
            {
                toastr.success(data.message);
            })
        }
        else if(data.response_status == "success-restored")
        {
            data.element.modal("toggle");
            $("#actives").load('/member/utilities/position #actives .position-container');
            $("#inactives").load('/member/utilities/position #inactives .position-container', function()
            {
                toastr.success(data.message);
            })
        }

    }
    
</script>
@endsection