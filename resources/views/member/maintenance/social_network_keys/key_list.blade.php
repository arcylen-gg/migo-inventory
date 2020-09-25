@extends('member.layout')
@section('content')
<link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
<div class="panel panel-default panel-block panel-title-block" id="top">
    <div class="panel-heading">
        <div>
            <i class="fa fa-key"></i>
            <h1>
                <span class="page-title">Social Networking Keys</span>
                <small>
                    List of Application Keys.
                </small>
            </h1>
            <div class="text-right">
            <a class="btn btn-primary panel-buttons popup" link="/member/maintenance/app_keys/add-appkey" size="md" >Add App Keys</a>
            </div>
        </div>
    </div>
</div>
<div class="panel panel-default panel-block panel-title-block">
    <div class="panel-body form-horizontal">
        <div class="form-group">
            <div class="col-md-6">
                <ul class="nav nav-tabs">
                  <li id="all-list" class="active"><a data-toggle="tab" href="#all"><i class="fa fa-qrcode" aria-hidden="true"></i>&nbsp;All Keys</a></li>
                </ul>
            </div>
            <div class="col-md-4 pull-right">
            </div>
        </div>

        <div class="form-group tab-content panel-body">
            <div id="all" class="tab-pane fade in active">
                <div class="form-group order-tags"></div>
                <div class="table-responsive">
                    <table class="table table-bordered table-condensed">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>App Name</th>
                                <th class="text-center">Action</th>
                            </tr>
                        </thead>
                        <tbody class="table-warehouse">
                            @if(count($_app_list) > 0)
                            @foreach($_app_list as $app)
                            <tr>
                                <td class="text-center">{{$app->keys_id}}</td>
                                <td class="text-center">{{$app->social_network_name}}</td>
                                <td class="text-center">
                                    <a class="popup"  link="/member/maintenance/app_keys/add-appkey?id={{$app->keys_id}}" size="md" >EDIT</a>
                                </td>
                            </tr>
                            @endforeach
                            @else
                            <tr>
                                <td colspan="3" class="text-center">NO APP KEYS YET</td>
                            </tr>
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
    
function success_key(data)
{    
    if(data.status == "success")
    {
        toastr.success(data.message);
        data.element.modal("hide");
        setInterval(function()
        {
            location.reload();
        },2000)
    }
}
</script>
@endsection