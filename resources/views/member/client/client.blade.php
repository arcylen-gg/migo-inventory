@extends('member.layout')
@section('content')
<link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
<div class="panel panel-default panel-block panel-title-block" id="top">
    <div class="panel-heading">
        <div>
            <i class="fa fa-users"></i>
            <h1>
                <span class="page-title">Owner's Information Settings</span>
            </h1>
        </div>
    </div>
</div>
<div class="panel panel-default panel-block panel-title-block">
    <div class="panel-body form-horizontal">
        <div class="form-group">
           <!--  <div class="col-md-6">
                <ul class="nav nav-tabs">
                  <li id="all-list" class="active"><a data-toggle="tab" href="#all"><i class="fa fa-star" aria-hidden="true"></i>&nbsp;Active Truck</a></li>
                  <li id="archived-list"><a data-toggle="tab" href="#archived"><i class="fa fa-trash" aria-hidden="true"></i>&nbsp;Archived Truck</a></li>
                </ul>
            </div> -->
           <!--  <div class="col-md-4 pull-right">
                <div class="input-group">
                    <span style="background-color: #fff; cursor: pointer;" class="input-group-addon" id="basic-addon1"><i class="fa fa-search"></i></span>
                    <input type="search" name="" class="form-control srch-txt" placeholder="Start typing client name">
                </div>
            </div> -->
        </div>

        <div class="form-group tab-content panel-body client-container">
            <div id="all" class="tab-pane fade in active">
                <div class="form-group order-tags"></div>
                <div class="table-responsive">
                    <table class="table table-bordered table-condensed">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th class="text-center">Shop Nme</th>
                                <th class="text-center">Client Name</th>
                                <th class="text-center">Action</th>
                            </tr>
                        </thead>
                        <tbody class="table-client">
                            @if($_shop_info)
                                @foreach($_shop_info as $shop)
                                    <tr>
                                        <td>{{$shop->shop_id}}</td>
                                        <td class="text-center">{{$shop->shop_key}}</td>
                                        <td class="text-center">{{$shop->user_first_name." ".$shop->user_last_name}}</td>
                                        <td class="text-center"><a link="/member/utilities/client/update/{{$shop->user_id}}" size="md" class="popup">Update</a></td>
                                    </tr>
                                @endforeach
                            @endif
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        
    </div>
</div>
@endsection
@section("script")
<script type="text/javascript">
    function submit_done(data)
    {
        if(data.status == "success")
        {
            toastr.success("Success");
            $("client-container").load("/member/utilities/client_list .client-container"); 
            $(data.target).html(data.view);
            $('#global_modal').modal('toggle');
        }
        else if(data.status == "error")
        {
            toastr.warning(data.status_message);
            $(data.target).html(data.view);
        }
    }
</script>
@endsection