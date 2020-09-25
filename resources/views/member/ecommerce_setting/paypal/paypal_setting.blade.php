@extends('member.layout')
@section('css')
@endsection

@section('content')
<form class="form-horizontal global-submit" action="/member/ecommerce/settings/paypalsetting/updatepaypal" method="POST">
    <input type="hidden" value="{{csrf_token()}}" name="_token">
    <div class="form-group">
        <div class="panel panel-default panel-block panel-title-block">
            <div class="panel-heading">
                <div>
                    <i class="fa fa-cogs"></i>
                    <h1>
                        <span class="page-title"><a class="color-gray" href="/member/ecommerce/settings">Settings</a>/Paypal</span>
                        <small>
                        Manage your paypal settings
                        </small>
                    </h1>
                   	<button class="btn btn-custom-primary pull-right btn-save-draft margin-left-10" type="submit">Update</button>
                   	<a href="/member/ecommerce/settings" class="btn btn-custom-white pull-right">Back</a>
                </div>
            </div>
        </div>
    </div>
    <div class="form-group">
        <div class=" panel panel-default panel-block background-white">
            <div class="panel-body">
                <div class="form-group margin-bottom-0">
                    <ul class="display-inline-block">
                       <li class="display-inline-block">
                           <div class="radio">
                                <label for="">ENABLED<input type="radio" name="enable" {{$paypal->ecommerce_setting_enable == 1 ? 'checked':''}} value="1"/></label>
                            </div>
                       </li> 
                       <li class="display-inline-block margin-left-30">
                           <div class="radio">
                                <label for="">DISABLED<input type="radio" name="enable" value="0" {{$paypal->ecommerce_setting_enable == 0 ? 'checked':''}}/></label>
                            </div>
                       </li> 
                    </ul>
                </div>
                <hr class=" margin-top-0">
               <div class="col-md-12">
                   <div class="form-group">
                        <div class="col-md-12">
                            <label for="">Client ID</label>
                            <input type="text" value="{{isset($credentials->paypal_clientid) ? $credentials->paypal_clientid: ''}}" class="form-control" name="client_id" placeholder="Client ID" required>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-md-12">
                            <label for="">Secret</label>
                            <input type="text" class="form-control" value="{{isset($credentials->paypal_secret) ? $credentials->paypal_secret : ''}}" name="secret" placeholder="Secret" required>
                        </div>
                    </div>
               </div>
                
            </div>
        </div>
    </div>
</form>
@endsection

@section('script')
<script type="text/javascript" src="/assets/member/js/e_commerce/setting.js"></script>
@endsection