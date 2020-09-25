@extends('member.layout')
@section('content')
<form method="post">
<input type="hidden" name="_token" value="{{ csrf_token() }}">
    <div class="panel panel-default panel-block panel-title-block" id="top">
        <div class="panel-heading">
            <div>
                <i class="fa fa-tags"></i>
                <h1>
                    <span class="page-title">Mail <i class="fa fa-angle-double-right"></i> Setting</span>
                    <small>
                    This is only for developers.
                    </small>
                </h1>
                <button type="submit" class="panel-buttons btn btn-custom-primary pull-right">Submit</button>
            </div>
        </div>
    </div>
    <!-- NO PRODUCT YET -->
    <div class="panel panel-default panel-block panel-title-block panel-gray">
        <div style="padding: 15px;">
            <div class="row clearfix">
                <div class="col-md-12">
                    <div class="form-group">
                        <label>Developer Password * (Verification)</label>
                        <input class="form-control" type="password" name="developer_password">
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label>Mail Driver</label>
                        <input value="{{ isset($setting['mail_driver']) ? $setting['mail_driver']->settings_value : '' }}" class="form-control" type="text" name="mail_driver">
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label>Mail Host</label>
                        <input value="{{ isset($setting['mail_host']) ? $setting['mail_host']->settings_value : '' }}" class="form-control" type="text" name="mail_host">
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label>Mail Port</label>
                        <input value="{{ isset($setting['mail_port']) ? $setting['mail_port']->settings_value : '' }}" class="form-control" type="text" name="mail_port">
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label>Mail Username</label>
                        <input value="{{ isset($setting['mail_username']) ? $setting['mail_username']->settings_value : '' }}" class="form-control" type="text" name="mail_username">
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label>Mail Password</label>
                        <input value="{{ isset($setting['mail_password']) ? $setting['mail_password']->settings_value : '' }}" class="form-control" type="password" name="mail_password">
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label>Mail Encryption</label>
                        <input value="{{ isset($setting['mail_encryption']) ? $setting['mail_encryption']->settings_value : '' }}" class="form-control" type="text" name="mail_encryption">
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label>Mail Name</label>
                        <input value="{{ isset($setting['mail_name']) ? $setting['mail_name']->settings_value : '' }}" class="form-control" type="text" name="mail_name">
                    </div>
                </div>
            </div>
        </div>
    </div>
</form>
@endsection