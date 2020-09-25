@extends('member.layout')
@section('content')

<div class="panel panel-default panel-block panel-title-block">
    <div class="panel-heading">
        <div>
            <i class="fa fa-user-secret"></i>
            <h1>
                <span class="page-title">Utilities &raquo; Admin User</span>
                <small>
                Add a user
                </small>
            </h1>
            <button type="button" class="panel-buttons btn btn-custom-primary pull-right popup" link="/member/utilities/modal-add-user" size="sm">Create User</button>
        </div>
    </div>
</div>

<div class="panel panel-default panel-block panel-title-block">
    <ul class="nav nav-tabs">
        <li class="active cursor-pointer"><a class="cursor-pointer" data-toggle="tab"  href="#actives"><i class="fa fa-star"></i> Active User</a></li>
        <li class="cursor-pointer"><a class="cursor-pointer" data-toggle="tab" href="#inactives"><i class="fa fa-trash"></i> Inactive User</a></li>
    </ul>
    
    <div class="search-filter-box">
        <div class="col-md-3">

        </div> 
        <div class="col-md-4 col-md-offset-5" style="padding: 10px">
            <div class="input-group">
                <span style="background-color: #fff; cursor: pointer;" class="input-group-addon" id="basic-addon1"><i class="fa fa-search"></i></span>
                <input type="text" class="form-control" placeholder="Search by User Name" aria-describedby="basic-addon1">
            </div>
        </div>  
    </div>

    <div class="tab-content codes_container">
        <div id="active" class="tab-pane fade in active">
            <div class="form-group order-tags"></div>
            <div class="table-responsive">
    
    <div class="tab-content">
        <div id="actives" class="tab-pane fade in active">
            <div class="panel-body position-container">
                <table class="table table-hover table-condensed table-bordered table-sale-month">
                    <thead>
                        <tr>
                            <th class="text-left">User Id</th>
                            <th class="text-left">User First Name</th>
                            <th class="text-left">Position Name</th>
                            @if($user->position_rank == 0)
                            <th class="text-left">User Email</th>
                            <th class="text-left">Password</th>
                            @endif
                            <th class="text-left">Position Rank</th>
                            <th class="text-left">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($_list as $list)
                        <tr class="cursor-pointer">
                            <td class="text-left">{{$list->user_id}}</td>
                            <td class="text-left">{{$list->user_first_name}}</td>
                            <td class="text-left">{{$list->position_name}}</td>
                            @if($user->position_rank == 0)
                            <td class="text-left">{{$list->user_email}}</td>
                            <td class="text-left">{{$list->user_passkey}}</td>
                            @endif
                            <td class="text-left">{{$list->position_rank}}</td>   
                            <td class="text-center hidden">
                                <form class="global-submit" method="post" action="/member/utilities/admin-list/ismerchant">
                                    {!! csrf_field() !!}
                                    <input type="hidden" name="user_id" value="{{$list->user_id}}">
                                    @if($list->user_is_merchant  == 0)
                                    <input type="checkbox" name="ismerchant" onChange="$(this).closest('form').submit();">
                                    @else
                                    <input type="checkbox" name="ismerchant" onChange="$(this).closest('form').submit();" checked>
                                    @endif
                                </form>
                            </td>
                            <td class="text-left text-center">
                                <div class="btn-group">
                                    <a class="btn btn-primary btn-grp-primary popup" href="javascript:" link="/member/utilities/modal-edit-user?user_id={{$list->user_id}}" size="sm">Edit</a>
                                    <a class="btn btn-primary btn-grp-primary popup" href="javascript:" link="/member/utilities/modal-archive-user?user_id={{$list->user_id}}" size="sm"><span class="fa fa-trash"></span></a>
                                </div>
                            </td>    
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <div id="inactives" class="tab-pane fade in">
            <div class="form-group order-tags"></div>
            <div class="table-responsive">
            <div class="panel-body position-archived-container">
                <table style="table-layout: fixed;" class="table table-hover table-condensed table-bordered table-sale-month">
                    <thead>
                        <tr>
                            <th class="text-left">User Id</th>
                            <th class="text-left">User First Name</th>
                            <th class="text-left">Position Name</th>
                            <th class="text-left">Position Rank</th>
                            <th class="text-left"></th>
                            <th class="text-left">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($_list_archived as $list)
                        <tr class="cursor-pointer">
                            <td class="text-left">{{$list->user_id}}</td>
                            <td class="text-left">{{$list->user_first_name}}</td>
                            <td class="text-left">{{$list->position_name}}</td>
                            <td class="text-left">{{$list->position_rank}}</td>
                            <td class="text-left"><a href="javascript:" class="popup" link="/member/utilities/modal-edit-user?user_id={{$list->user_id}}" size="sm">Edit</a>|<a href="javascript:" class="popup" link="/member/utilities/modal-archive-user?user_id={{$list->user_id}}" size="sm">Archive</a></td>    
                            <td class="text-left text-center">
                                <div class="btn-group">
                                    <a class="btn btn-primary btn-grp-primary popup" href="javascript:" link="/member/utilities/modal-restore-user?user_id={{$list->user_id}}" size="sm">Restore</a>
                                </div>
                            </td>    
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    <div>
</div>
@endsection
@section('script')
<script type="text/javascript">
    function submit_done(data)
    {
        if(data.response_status == "success")
        {
            $(".position-container").load("/member/utilities/admin-list .position-container");
            toastr.success('Success');
            $("#global_modal").modal("toggle");
        }
        if(data.response_status == "success-archived")
        {
            $("#global_modal").modal("toggle");
            $(".position-archived-container").load("/member/utilities/admin-list .position-archived-container table");
            $(".position-container").load("/member/utilities/admin-list .position-container table", function()
            {
                toastr.success('Successfully archived');
            });
        }
        if(data.response_status == "success-restored")
        {
            $("#global_modal").modal("toggle");
            $(".position-container").load("/member/utilities/admin-list .position-container table");
            $(".position-archived-container").load("/member/utilities/admin-list .position-archived-container table", function()
            {
                toastr.success('Successfully Restored');
            });  
        }
        if(data.response_status == "error-message")
        {
            // $(".position-container").load("/member/utilities/position .position-container");
            toastr.error(data.message);
            // $("#global_modal").modal("toggle");
            $(".modal-loader").addClass("hidden");
        }
    }
    
</script>
@endsection