@extends('member.layout')
@section('content')
<link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
<div class="panel panel-default panel-block panel-title-block" id="top">
    <div class="panel-heading">
        <div>
            <i class="fa fa-envelope"></i>
            <h1>
                <span class="page-title">SMS Content</span>
                <small>
                    List of Sms Content.
                </small>
            </h1>
            <h4 class="pull-right {{isset($sms_balance->balance) ? $sms_balance->balance > 50 ? 'green' : 'red' : 'red'}}"> BALANCE : {{$sms_balance->currency or ''}} {{$sms_balance->balance or '0'}} </h4>
            <div class="col-md-4 pull-right">
                <a class="btn btn-custom-white pull-right" href="/member/maintenance/sms/logs">SMS Logs</a>
                @if($user->user_level == 1)
                <form class="global-submit" action="/member/maintenance/sms/authorization-key">
                    <input type="hidden" name="_token" value="{{csrf_token()}}">
                    <div class="input-group">
                        <input type="text" class="form-control" name="sms_authorization_key" placeholder="Authorization Key" value="{{$sms_key->sms_authorization_key or ''}}">
                        <span class="input-group-btn">
                            <button class="btn btn-custom-primary pane" type="submit" size="md">Save SMS Key</button>
                        </span>
                    </div>  
                </form>
                @endif
            </div>                            
            </div>
        </div>
    </div>
</div>
<div class="panel panel-default panel-block panel-title-block">
    <div class="panel-body form-horizontal load-data">
        @if(isset($sms_key->sms_authorization_key))
        <div class="form-group tab-content panel-body sms-content-container">
            <div class="table-responsive">
                <table class="table table-bordered table-condensed">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>SMS Content Key</th>
                            <th width="50%">SMS Content</th>
                            <th class="text-center">Action</th>
                        </tr>
                    </thead>
                    <tbody class="table-warehouse">
                        @foreach($_sms as $key=>$sms)
                        <tr>
                            <td>{{$key+1}}</td>
                            <td>{{$sms->sms_default_key}}</td>
                            <td>{{$sms->sms_temp_content or ''}}</td>
                            <td class="text-center">
                                <div class="btn-group">
                                    <a class="btn btn-primary btn-grp-primary popup" href="javascript:" link="/member/maintenance/sms/sms-modal/{{$sms->sms_default_key}}" size="md">Edit</a>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        @else
        <div class="col-md-12">
            <div class="error-template">
                <h2 class="message">Create Authorization Key First</h2>
                <div class="error-details">
                    Sorry, please contact your administrator.
                </div>
            </div>
        </div>
        @endif
    </div>
</div>
@endsection
@section("script")
<script type="text/javascript">
    function submit_done(data)
    {
        if(data.status == "success")
        {
            if(data.type == "sms")
            {
                data.element.modal("toggle");
                $(".load-data").load("/member/maintenance/sms .sms-content-container", function()
                {
                    toastr.success(data.message);
                })   
            }
            else if(data.type == "authorization_key")
            {
                toastr.success(data.message);
            }
        }
        else
        {
            toastr.error(data.message);
        }
    }

</script>
@endsection