@extends('member.layout')

@section('css')
@endsection


@section('content')
<div class="form-horizontal">
    <div class="form-group">
        <div class="panel panel-default panel-block panel-title-block" id="top">
            <div class="panel-heading">
                <div>
                    <i class="fa fa-cogs"></i>
                    <h1>
                        <span class="page-title">Settings</span>
                        <small>
                        Manage your page settings
                        </small>
                    </h1>
                   	<!--<button class="btn btn-primary pull-right btn-save-draft">Save draft</button>-->
                </div>
            </div>
        </div>
    </div>
    <div class="form-group">
        <div class="col-md-12 panel">
            <div class="panel-body">
                <table class="table table-bordered table-condensed table-hover">
                    <tr>
                        <thead>
                            <th class="text-center">PAYMENT CODE</th>
                            <th class="text-center">ENABLED</th>
                            <th></th>
                        </thead>
                    </tr>
                    @foreach($_setting_code as $setting)
                    <tr>
                        <td>
                            {{$setting->ecommerce_setting_code}}
                        </td>
                        <td class="text-center">
                            {!!$setting->ecommerce_setting_enable == 1? '<span class="color-green">ENABLED</span>':'<span class="color-red">DISABLED</span>'!!}
                        </td>
                        <td class="text-center">
                            <a href="{{$setting->ecommerce_setting_url}}">CONFIGURE</a>
                        </td>
                    </tr>
                    @endforeach
                </table>
            </div>
        </div>
    </div>
  
</div>
@endsection

@section('script')
<script type="text/javascript" src="/assets/member/js/e_commerce/setting.js"></script>
@endsection