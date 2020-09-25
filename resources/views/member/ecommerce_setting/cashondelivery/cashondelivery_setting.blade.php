@extends('member.layout')
@section('css')
@endsection

@section('content')
<form class="form-horizontal global-submit" method="POST" action="/member/ecommerce/settings/banksetting/settingupdate">
    <input type="hidden" value="{{csrf_token()}}" name="_token">
    <input type="hidden" name="trigger" value="CASH ON DELIVERY"/>
    <div class="form-group">
        <div class="panel panel-default panel-block panel-title-block">
            <div class="panel-heading">
                <div>
                    <i class="fa fa-cogs"></i>
                    <h1>
                        <span class="page-title"><a class="color-gray" href="/member/ecommerce/settings">Settings</a>/Cash On Delivery</span>
                        <small>
                        Manage your paypal settings
                        </small>
                    </h1>
                   	<button class="btn btn-custom-primary pull-right btn-save-draft margin-left-10">Update</button>
                   	<a href="/member/ecommerce/settings" class="btn btn-custom-white pull-right">Back</a>
                </div>
            </div>
        </div>
    </div>
    <div class="form-group">
        <div class="panel panel-default panel-block background-white">
            <div class="panel-body">
                <div class="form-group margin-bottom-0">
                    <ul class="display-inline-block">
                       <li class="display-inline-block">
                           <div class="radio">
                                <label for="">ENABLED<input type="radio" name="enable" {{$cod->ecommerce_setting_enable == 1 ? 'checked':''}} value="1"/></label>
                            </div>
                       </li> 
                       <li class="display-inline-block margin-left-30">
                           <div class="radio">
                                <label for="">DISABLED<input type="radio" name="enable" {{$cod->ecommerce_setting_enable == 0 ? 'checked':''}} value="0"/></label>
                            </div>
                       </li> 
                    </ul>
                </div>
                <hr class="margin-bottom-0 margin-top-0">
            </div>
        </div>
    </div>
</form>
@endsection

@section('script')
<script type="text/javascript" src="/assets/member/js/e_commerce/setting.js"></script>
@endsection